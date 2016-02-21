<?php

namespace Org\Util;
use Org\Util\PasswordHash;

/**
 * Class UAuth
 * @package Org\Util
 *
 * 配置说明
 *
 * UAUTH_MAXLOGIN 最大允许同时登录 //TODO
 *
 * UAUTH_USER 用户表
 * 字段名-说明
 * uid 用户识别码，唯一
 * username 用户名，唯一
 * password 加密的密码域，使用PasswordHash生成
 * level 用户等级
 * createtime 用户创建时间
 *
 * UAUTH_AUTH_KEY 登录有效授权表
 * uid 用户识别码
 * key 授权码，唯一
 * checktime 上次检索时间
 * ip 登录IP
 * CREATE TABLE IF NOT EXISTS `auth_key` (
 * `uid` varchar(10) NOT NULL,
 * `key` varchar(100) NOT NULL,
 * `sessionid` varchar(1000) NOT NULL,
 * `ip` varchar(100) NOT NULL,
 * `checktime` datetime NOT NULL
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8
 *
 */

class UAuth {
    static public function Auth_login($username,$password) {
//        session(null);
        session('[start]');
        $User = M(C('UAUTH_USER'));
        $verifyPSW=new PasswordHash();
        $verifyPSW->setPassword($password);
        $condition['username']=$username;
        $verifyPSW->setHash($User->where($condition)->getField('password'));
        if($User && $verifyPSW::authPassword()){
            $uid=$User->where($condition)->getField('uid');
            $level=$User->where($condition)->getField('level');
            //清除同一IP，同一用户的授权认证
            $clearAuth['ip']=get_client_ip();
            $clearAuth['uid']=$uid;
            self::Clear_USER_AUTH_KEY($clearAuth);
            //添加授权认证信息
            $auth_key=self::Add_USER_AUTH_KEY($uid);
            // set SESSION
            session('uid',$uid);
            session('username',$username);
            session('key',$auth_key);
            session('level',$level);
            return true;
        }else
        {
            return false;
        }
    }

    static public function Auth_Logout(){
        $condition['sessionid']=session_id();
        $User=M(C('UAUTH_AUTH_KEY'));
        $User->where($condition)->delete();
    }

    //检查登录合法性
    static public function Check_LoginValid(){
//        if(!isset($_SESSION['uid'])){
//            return false;
//        }
        session('[start]');
        $sessionid=session_id();
        $condition['uid']=session('uid');
        $condition['key']=session('key');
        $condition['ip']=get_client_ip();
        $condition['sessionid']=$sessionid;

        $Check=M(C('UAUTH_AUTH_KEY'));
        $result = $Check->where($condition)->find();

        //return ($result==$sessionid);
//        return $result;
        if($result==null){
            return false;
        }else
        {
            self::Update_USER_AUTH_KEY_TIME();
            return true;
        }
        return !($result==null);
//        return session('key');
    }

    public function random_str($length)
    {
        //生成一个包含 大写英文字母, 小写英文字母, 数字 的数组
        $arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));

        $str = '';
        $arr_len = count($arr);
        for ($i = 0; $i < $length; $i++)
        {
            $rand = mt_rand(0, $arr_len-1);
            $str.=$arr[$rand];
        }

        return $str;
    }
    private function Add_USER_AUTH_KEY($uid){
        $AuthKey=M(C('UAUTH_AUTH_KEY'));
        $data['uid']=$uid;
        $data['key']=self::random_str(32);
        $data['ip']=get_client_ip();
        $data['sessionid']=session_id();
        $data['checktime']=date('Y-m-d H:i:s');
        $AuthKey->data($data)->add();
        return $data['key'];
    }

    private function Clear_USER_AUTH_KEY($condition){
        if($condition!=null){
            $AuthKey=M(C('UAUTH_AUTH_KEY'));
            $AuthKey->where($condition)->delete();
        }

    }

    //更新授权时间
    private function Update_USER_AUTH_KEY_TIME(){
        $User=M(C('UAUTH_AUTH_KEY'));
        $User->checktime = date('Y-m-d H:i:s');
        $condition['uid']=session('uid');
        $condition['sessionid']=session_id();
        $condition['key']=session('key');
        $User->where($condition)->save();
    }

}

?>
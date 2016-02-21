<?php
namespace Dashboard\Controller;
use Think\Controller;
use Org\Util\PasswordHash;
class CommonController extends Controller {
    public function index($username,$password) {
        $User = M('auth_user');
        $verifyPSW=new PasswordHash();
        $verifyPSW->setPassword($password);
        $condition['username']=$username;
        $verifyPSW->setHash($User->where($condition)->select('password'));
        if($verifyPSW::authPassword()){
            // set SESSION
            session( array('uid'=>$User->where($condition)->select('uid'),'username'=>$username,'level'=>$User->where($condition)->select('uid')));
        }else
        {
            $this->error("Wrong Username or Password");

        }
    }
}
?>
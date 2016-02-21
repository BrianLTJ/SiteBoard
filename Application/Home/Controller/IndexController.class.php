<?php
namespace Home\Controller;
use Think\Controller;
use Org\Util\UAuth;
class IndexController extends Controller {
    public function index(){
        layout('common_layout');
        $this->display();
    }

    public function login(){
        if(UAuth::Auth_Login($_POST['username'],$_POST['password'])){
            $this->redirect('/Dashboard/Index/index', array() , 2, '登录成功，页面跳转中...');
        }else{
            $this->error('Username or Password Incorrect,please try again',U('Home/Index/index'));
        }
    }
}
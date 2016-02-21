<?php
namespace Dashboard\Controller;
use Think\Controller;
use Org\Util\UAuth;
//use Dashboard\CommonController;
class IndexController extends Controller {
    public function index(){
        if(UAuth::Check_LoginValid()){
            layout('common_layout');
            $Projects=M('siteboard_project');
            $condition['uid']=session('uid');
            $list=$Projects->where($condition)->order('createtime')->select();
            $this->value=$list;
            $this->display();

        }else{
            $this->error('Login state changed,please try again',U('Home/Index/index'));
        }
    }
    public function newproject(){
        if(UAuth::Check_LoginValid()){
            layout('common_layout');

            $this->display();

        }else{
            $this->error('Login state changed,please try again',U('Home/Index/index'));
        }
    }
}
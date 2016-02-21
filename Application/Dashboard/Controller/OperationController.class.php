<?php

namespace Dashboard\Controller;
use Think\Controller;
use Org\Util\UAuth;
use Org\Util\RunCMD;

class OperationController extends Controller {
    public function index(){

        $CheckBelonging=M('siteboard_project');
        $condition=array('pid'=>$_POST['pid'],'uid'=>session('uid'));
        $result=$CheckBelonging->where($condition)->find();
        if($result==null){
            $this->error('Internal Error',U('Home/Index/index'));
        }
        //获得本地文件夹
        session('folder',$result['folder']);


        session('pid',$_POST['pid']);
        layout('common_layout');
        $this->assign('data',$result);

        //SetBranchList in sync

        $condition=array('pid'=>session('pid'),'uid'=>session('uid'));
        $branches=$CheckBelonging->where($condition)->getField('folder');
        $str=RunCMD::fetchLocalBranches($branches);
        $output=array();
        for($i=0;$i<count($str);$i++){
            switch(substr($str[$i],0,1)){
                case " ":
                    array_push($output,substr($str[$i],2));
                    break;
                case "*":
                    array_unshift($output,substr($str[$i],2));
                    break;
            }
        }

        $this->assign('branches',$output);

        $this->display();
    }

    public function addNewProject(){
        $addProj=M('siteboard_project');
        $data['uid']=session('uid');
        $data['pid']=UAuth::random_str(50);
        $data['name']=I('post.name');
        $data['type']=I('post.type');
        $data['giturl']=I('post.url');
        $data['folder']=I('post.folder');
        $data['description']=I('post.description');
//        $data['']=I('post.');

//        array_unshift($data,array(I('post.')));
        if($addProj->data($data)->add()){
            $this->success("Add Successfully",U('Dashboard/Index/index'));
        }else{
            $this->error("Fail");
        }

    }



    public function fetchLocalBranch(){

        $str=RunCMD::fetchLocalBranches(session('folder'));
        $output="";
        for($i=0;$i<count($str);$i++){
            switch(substr($str[$i],0,1)){
                case " ":
                    $output=$output.substr($str[$i],2)."<br>";
                    break;
                case "*":
                    $output='(Current)'.substr($str[$i],2)."<br>".$output;
                    break;
            }
        }
        $this->ajaxReturn($output);

    }

    public function runSync(){
        $str=RunCMD::runSync(session('folder'),$_POST['sync_target_folder']);
        $output="";
        for($i=0;$i<count($str);$i++){
            $output.=$str[$i];
        }
        $this->ajaxReturn($output);
    }

    public function runCheckout(){
        $str=RunCMD::runCheckout(session('folder'),$_POST['sync_target_folder']);
        $output="";
        for($i=0;$i<count($str);$i++){
            $output.=$str[$i];
        }
        $this->ajaxReturn($output);
    }

    public function cleanFolder() {
        $str=RunCMD::clearFolder(session('folder'));
        $output="";
        for($i=0;$i<count($str);$i++){
            $output.=$str[$i];
        }
        $this->ajaxReturn($output);
    }

    public function cloneRepo() {
        $getURL=M('siteboard_project');
        $condition=array('pid'=>$_SESSION['pid'],'uid'=>session('uid'));
        $url=$getURL->where($condition)->getField('giturl');
        $str=RunCMD::cloneRepo($url,session('folder'));
        $output="";
        for($i=0;$i<count($str);$i++){
            $output.=$str[$i];
        }
        $this->ajaxReturn($output);
    }





}
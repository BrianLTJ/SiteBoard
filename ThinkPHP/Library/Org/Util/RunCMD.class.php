<?php

namespace Org\Util;

/**
 * Class RunCMD
 * @package Org\Util
 * CONFIG:
 * RUN_SYSTEM:系统信息，Windows、Linux
 * RUN_WWWROOT:HTTP文件夹地址
 */



class RunCMD {

    static function fetchLocalBranches($docroot){
        $str=self::RunShell($docroot,'git branch');

        return $str;
    }

    static function runSync($docroot,$targetBranch){
        $str=self::RunShell($docroot,'git pull origin '.$targetBranch);

        return $str;
    }

    static function runCheckout($docroot,$targetBranch){
        $str=self::RunShell($docroot,'git checkout '.$targetBranch);
        return $str;
    }

    static function clearFolder($docroot){
        $command="rd /S /Q \"".C('RUN_WWWROOT')."$docroot"."\"";
        exec($command,$rc);
        return $rc;
    }

    static function cloneRepo($url,$foldername){
        //clone
        $EnterFolder="cd /d ".C('RUN_WWWROOT');
        $command=$EnterFolder ." & git clone ".$url;
        exec($command,$rc);
//        $orgname=substr($url,strrpos($url,"/"),strrpos($url,".git"));
//        $command=$EnterFolder ." & ren ".$orgname." ".$foldername;
        return $rc;

    }

    protected function RunShell($docroot,$CMD){
        if(!file_exists(C('RUN_WWWROOT')."$docroot")){
            $rc=array("Error:Folder doesn't exist,please clone first!");
            return $rc;

        }

        $EnterFolder="cd /d ".C('RUN_WWWROOT')."$docroot";

        $command=$EnterFolder ." & ".$CMD;
        exec($command,$rc);
        return $rc;
//        return $command;
    }

}

?>
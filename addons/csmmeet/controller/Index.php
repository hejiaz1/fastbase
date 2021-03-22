<?php
// +----------------------------------------------------------------------
// Csmmeet [ CSMMeet会议室预约系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-26
// +----------------------------------------------------------------------
namespace addons\csmmeet\controller;

use addons\csmmeet\library\QRcode;
use addons\csmmeet\library\CsmBase;

class Index extends CsmBase
{

    // protected $layout = 'default';
    protected $noNeedLogin = [
        '*'
    ];

    protected $noNeedRight = [
        '*'
    ];

 

    public function index()
    {
        $pcurl = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . config('view_replace_str.__PUBLIC__') . "index/csmmeet.frame1/index.html";
        $mobileUrl = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . config('view_replace_str.__PUBLIC__') . "assets/addons/csmmeet/mo";
        
        $this->view->assign("pcUrl", $pcurl);
        $this->view->assign("mobileUrl", $mobileUrl);
        return $this->view->fetch('/index');
    }

    public function getMobileUrl()
    {
        $mobileUrl = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . config('view_replace_str.__PUBLIC__') . "assets/addons/csmmeet/mo";
        QRcode::png($mobileUrl, false, "L", 12);
    }
}

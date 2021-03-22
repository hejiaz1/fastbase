<?php
// +----------------------------------------------------------------------
// Csmmeet [ CSMSignin活动签到系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-26
// +----------------------------------------------------------------------
namespace addons\csmsignin\controller;

 

use addons\csmsignin\library\CsmBase;

class Index extends CsmBase
{

    public function index()
    {
        $this->error("欢迎使用CsmSignin系统");
    }
    

}

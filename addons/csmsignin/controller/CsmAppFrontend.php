<?php
// +----------------------------------------------------------------------
// Csmmeet [ CSMSignin签到和活动 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-29
// +----------------------------------------------------------------------
namespace addons\csmsignin\controller;

use addons\csmsignin\library\CsmFrontend;

/**
 * 前台控制器基类
 */
class CsmAppFrontend extends CsmFrontend
{

    /**
     * 封装了从header获取token，完成登录，如果没有登录则报错
     */
    public function csmappassertlogin()
    {
        $openid = $this->request->header("csmlogintoken");
        return $openid;
    }

    /**
     * 获取当前帐号在微信中的信息
     */
    public function getwxuserinfo()
    {
        $openid = $this->csmappassertlogin();
        $config = get_addon_config('csmsignin');
        $wxAppID = $config['wxAppID'];

        $weixinuserdao = new \app\admin\model\csmsignin\Weixinuser();
        $weixinuser = $weixinuserdao->where("wxappid", "=", $wxAppID)
            ->where("openid", "=", $openid)
            ->find();
        return $weixinuser;
    }
}
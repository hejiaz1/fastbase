<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-14 15:15:28
 * @FilePath       : \application\api\controller\login\Login.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-11-26 16:04:29
 * @Description    : 微信小程序授权控制器
 */
namespace app\api\controller\login;
use app\common\controller\Api;
use think\Db;
use think\Hook;
use think\Cookie;

use app\common\model\User;
use fast\Random; // 随机类

/**
 * 微信小程序授权
 */
class Login extends Api
{
    // 不需登录方法
    protected $noNeedLogin = ['login'];
    protected $noNeedRight = '*';

    // 第三方平台标识
    private $platform = 'wxapplet';

    public function _initialize()
    {
        parent::_initialize();

        // 登录成功
        Hook::add('user_login_successed', function ($user) use ($auth) {
            $expire = input('post.keeplogin') ? 30 * 86400 : 0;
            Cookie::set('uid', $user->id, $expire);
            Cookie::set('token', $auth->getToken(), $expire);
        });

        // 注册成功
        Hook::add('user_register_successed', function ($user) use ($auth) {
            Cookie::set('uid', $user->id);
            Cookie::set('token', $auth->getToken());
        });

        // 退出登录
        Hook::add('user_logout_successed', function ($user) use ($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
        });

    }

    /** 退出登录
     * @Author: hejiaz
     * @Date: 2020-11-26 15:52:27
     */
    public function logout()
    {
        $this->auth->logout();
        $this->success(__('Logout successful'), url('user/index'));
    }





}

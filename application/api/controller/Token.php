<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-30 14:02:03
 * @FilePath       : \application\api\controller\Token.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-05-08 10:29:52
 * @Description    : token相关控制器
 */

namespace app\api\controller;

use app\common\controller\Api;
use fast\Random;

/**
 * Token接口
 */
class Token extends Api
{
    protected $noNeedLogin = [];
    protected $noNeedRight = '*';

    /** 退出登录
     * @Author: hejiaz
     * @Date: 2021-03-25 15:37:38
     */
    public function logout()
    {
        $this->auth->logout();
        $this->success();
    }

    /**
     * 检测Token是否过期
     *
     */
    public function check()
    {
        $token = $this->auth->getToken();
        $tokenInfo = \app\common\library\Token::get($token);
        $this->success('', ['token' => $tokenInfo['token'], 'expires_in' => $tokenInfo['expires_in']]);
    }

    /**
     * 刷新Token
     */
    public function refresh()
    {
        //删除源Token
        $token = $this->auth->getToken();
        \app\common\library\Token::delete($token);

        //创建新Token
        $token = Random::uuid();

        \app\common\library\Token::set($token, $this->auth->id, 2592000);
        $tokenInfo = \app\common\library\Token::get($token);
        $this->success('', ['token' => $tokenInfo['token'], 'expires_in' => $tokenInfo['expires_in']]);
    }
}

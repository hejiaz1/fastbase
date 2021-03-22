<?php

namespace app\common\library\wxapplet;

use think\facade\Cache;

/**
 * 微信api基类
 * Class wechat
 * @package app\library
 */
class WxBase
{
    protected $appid;
    protected $appSecret;
    protected $error;

    public $request_url = [
        'get_access_token' => 'https://api.weixin.qq.com/sns/oauth2/access_token',
        'get_userinfo'     => 'https://api.weixin.qq.com/sns/userinfo',
        'code2session'     => 'https://api.weixin.qq.com/sns/jscode2session',
    ];

    /**
     * error code 说明.
     * <ul>
     *    <li>-41001: encodingAesKey 非法</li>
     *    <li>-41003: aes 解密失败</li>
     *    <li>-41004: 解密后得到的buffer非法</li>
     *    <li>-41005: base64加密失败</li>
     *    <li>-41016: base64解密失败</li>
     * </ul>
     */
    public $errorCode = [
        'IllegalAesKey'     => -41001,
        'IllegalIv'         => -41002,
        'IllegalBuffer'     => -41003,
        'DecodeBase64Error' => -41004,
    ];

    /**
     * 构造函数
     * WxBase constructor.
     * @param $appid
     * @param $appSecret
     */
    public function __construct($appid, $appSecret)
    {
        $this->appid     = $appid;
        $this->appSecret = $appSecret;
    }

    /** code获取access_token令牌
     * @Author: hejiaz
     * @Date: 2020-08-31 10:59:06
     * @Param: $code
     * @Return: mixed
     */
    public function get_access_token($code)
    {
        $cacheKey = $this->appid . '@access_token';

        // if (!Cache::get($cacheKey)) {

            $url = $this->request_url['get_access_token'];

            $param = [
                'appid'      => $this->appid,
                'secret'     => $this->appSecret,
                'grant_type' => 'authorization_code',
                'code'       => $code
            ];

            // 拼接URL
            $request_url = combineURL($url, $param);
            $result      = json_decode($this->get($request_url), true);
            // dump($result);

            // 记录日志
            log_write([
                'describe' => '获取access_token',
                'appId'    => $this->appid,
                'result'   => $result
            ]);

            $cachedata = [
                'access_token' => $result['access_token'],
                'openid'       => $result['openid'],
            ];
            // 写入缓存
            Cache::set($cacheKey, $cachedata, 6000);
        // }

        return Cache::get($cacheKey);
    }

    /**
     * 获取access_token
     * @return string access_token
     */
    protected function getAccessToken()
    {


        $cacheKey = $this->appid . '@access_token';
        if (!Cache::get($cacheKey)) {

            // 请求API获取 access_token
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->appSecret}";
            $result = $this->get($url);
            $data = json_decode($result, true);
            // 记录日志
            log_write([
                'describe' => '获取access_token',
                'appId'    => $this->appid,
                'result'   => $result
            ]);
            // 写入缓存
            Cache::set($cacheKey, $data['access_token'], 6000);    // 7000
        }
        return Cache::get($cacheKey);
    }


    /**
     * 返回错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /** 写入日志记录
     * @param $values
     * @return bool|int
     */
    protected function doLogs($values)
    {
        return write_log($values, __DIR__);
    }


}
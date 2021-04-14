<?php
/*
 * @Author         : hejiaz
 * @Date           : 2021-04-13 16:49:09
 * @FilePath       : \application\common\library\wxapplet\WxBase.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-14 17:46:57
 * @Description    : 微信api基类
 */

namespace app\common\library\wxapplet;

use think\facade\Cache;

class WxBase
{
    protected $appid;
    protected $appSecret;
    protected $error;

    public $request_url = [
        'get_access_token' => 'https://api.weixin.qq.com/cgi-bin/token',
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

    /**
     * 获取access_token
     * @return string access_token
     */
    protected function getAccessToken()
    {
        $cacheKey = $this->appid . '@access_token';

        if (!Cache::get($cacheKey)) {
            // 请求API获取 access_token
            $url = $this->request_url['get_access_token'];
            $param = [
                'grant_type' => 'client_credential',
                'appid'      => $this->appid,
                'secret'     => $this->appSecret,
            ];

            // 拼接URL
            $request_url = combineURL($url, $param);
            // 请求接口解析
            $result = json_decode(curlGet($request_url), true);

            // 记录日志
            Log::write($this->appid . ' 获取AccessToken 返回值:' . $result);

            // 写入缓存
            Cache::set($cacheKey, $result['access_token'], 7000);    // 令牌有效期为7200
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


}
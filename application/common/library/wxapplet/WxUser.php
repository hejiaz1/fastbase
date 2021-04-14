<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-06-02 10:53:49
 * @FilePath       : \application\common\library\wxapplet\WxUser.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-14 17:45:39
 * @Description    : 微信用户管理类
 */

namespace app\common\library\wxapplet;

class WxUser extends WxBase
{

    /** 获取session_key/openid
     * @param $code
     * @return array|mixed
     */
    public function get_sessionKey($code)
    {
        /**
         * code 换取 session_key
         * ​这是一个 HTTPS 接口，开发者服务器使用登录凭证 code 获取 session_key 和 openid。
         * 其中 session_key 是对用户数据进行加密签名的密钥。为了自身应用安全，session_key 不应该在网络上传输。
         */

        $url = $this->request_url['code2session'];
        $param = [
            'appid'      => $this->appid,
            'secret'     => $this->appSecret,
            'grant_type' => 'authorization_code',
            'js_code'    => $code
        ];

        // 拼接URL
        $request_url = combineURL($url, $param);
        // 请求接口解析
        $result = json_decode(curlGet($request_url), true);
        if($result['errcode']){
            return $result['errcode'];
        }
        return $result;
    }

    /** 获取会员信息 微信公众号好使 小程序不好使
     * @Author: hejiaz
     * @Date: 2021-04-14 10:54:49
     */
    public function get_userInfo($openid){
        // 获取令牌
        $access_token = $this->getAccessToken();

        $url = $this->request_url['get_userinfo'];
        $param = [
            'access_token' => $access_token,
            'openid'       => $openid,
            'lang'         => 'zh_CN',
        ];

        // 拼接URL
        $request_url = combineURL($url, $param);
        // 请求接口解析
        $result = json_decode(curlGet($request_url), true);
        if($result['errcode']){
            return $result['errcode'];
        }

        return $result;
    }

    /** 静默授权 code 获取令牌和openid
     * @Author: hejiaz
     * @Date: 2020-08-31 11:48:56
     * @Param: $code
     * @Return: mixed array json boole
     */
    public function silent_code2openid($code){

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
        dump($result);die;

        // 记录日志
        log_write([
            'describe' => '静默授权 code获取令牌和openid',
            'appId'    => $this->appid,
            'result'   => $result
        ]);

        return $result['openid'];
    }


    /**
	 * 检验数据的真实性，并且获取解密后的明文.
	 * @param $sessionKey string 用户在小程序登录后获取的会话密钥
	 * @param $encryptedData string 加密的用户数据
	 * @param $iv string 与用户数据一同返回的初始向量
	 * @return array state=1为成功，其他返回对应的错误码
	 */
	public function decryptData($sessionKey, $encryptedData, $iv )
	{
        if (strlen($sessionKey) != 24) {
            return ['state' => $this->errorCode['IllegalAesKey']];
		}
		if (strlen($iv) != 24) {
            return ['state' => $this->errorCode['IllegalIv']];
        }

		$aesKey    = base64_decode($sessionKey);
		$aesIV     = base64_decode($iv);
		$aesCipher = base64_decode($encryptedData);
		$result    = openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataObj   = json_decode( $result );

		if( $dataObj  == NULL ){
            return ['state' => $this->errorCode['IllegalBuffer']];
		}
		if( $dataObj->watermark->appid != $this->appid ){
            return ['state' => $this->errorCode['IllegalBuffer']];
		}

        // 解密成功
		return ['state' => 1, 'data' => json_decode($result, true)];
    }

}
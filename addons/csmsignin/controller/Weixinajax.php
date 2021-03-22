<?php
// +----------------------------------------------------------------------
// Csmmeet [ CSMSignin活动签到系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-27
// +----------------------------------------------------------------------
namespace addons\csmsignin\controller;

use think\Session;
use addons\csmsignin\library\WXBizDataCrypt;
use addons\csmsignin\library\CsmFrontend;

class Weixinajax extends CsmFrontend
{

    protected $layout = 'default';

    protected $noNeedLogin = [
        '*'
    ];

    protected $noNeedRight = [
        '*'
    ];

    // 初始化
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->error("index page");
    }

    /**
     * 微信登录：根据code获取openid、session_key
     */
    public function loginbyweixincode()
    {
        $code = $this->csmreq("code", true);
        $avatarUrl = $this->csmreq("avatarUrl", false);
        $city = $this->csmreq("city", false);
        $country = $this->csmreq("country", false);
        $gender = $this->csmreq("gender", false);
        $language = $this->csmreq("language", false);
        $nickName = $this->csmreq("nickName", false);
        $province = $this->csmreq("province", false);

        $config = get_addon_config('csmsignin');
        $appid = $config['wxAppID'];
        $appsecret = $config['wxAppSecret'];

        $weixinurl = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$appsecret}&js_code={$code}&grant_type=authorization_code";
        $weixinsr = file_get_contents($weixinurl);

        $weixinsrjson = json_decode($weixinsr, true);
        trace("weixinsrjson=");
        trace($weixinsrjson);

        if ($weixinsrjson == null) {
            $weixinsrjson = [];
        }
        if (array_key_exists('errcode', $weixinsrjson) && $weixinsrjson['errcode'] != "0") {
            return $this->csmerrorjson($weixinsrjson['errmsg']);
        } else {
            // 微信信息保存DB，sessionkey保存session
            $session_key = $weixinsrjson['session_key'];
            $openid = $weixinsrjson['openid'];
            Session::set("csmsignin_weixin_session_key", $session_key);

            $param = [
                "wxappid" => $appid,
                "currentsessionkey" => $weixinsrjson['session_key'],
                "avatarUrl" => $avatarUrl,
                "city" => $city,
                "country" => $country,
                "gender" => $gender,
                "language" => $language,
                "nickName" => $nickName,
                "province" => $province,
                "updatetime" => time()
            ];
            trace("param=");
            trace($param);
            $weixinuserdao = new \app\admin\model\csmsignin\Weixinuser();
            $weixinuser = $weixinuserdao->where("wxappid", "=", $appid)
                ->where("openid", "=", $openid)
                ->find();
            if ($weixinuser != null) {
                $param["updatetime"] = time();
                $weixinuserdao->where("id", "=", $weixinuser->id)->update($param);
            } else {
                $param["openid"] = $openid;
                $param["createtime"] = time();
                $weixinuserdao->create($param);
            }

            return $this->csmjson(array(
                "openid" => $openid
            ));
        }
    }

    /**
     * 获取微信的手机信息（解密）
     */
    public function logintogetmobile()
    {
        $openid = $this->request->header("csmlogintoken");

        $encryptedData = $this->csmreq("encryptedData", true);
        $iv = $this->csmreq("iv", true);

        $config = get_addon_config('csmsignin');
        $appid = $config['wxAppID'];

        $weixinuserdao = new \app\admin\model\csmsignin\Weixinuser();
        $weixinuser = $weixinuserdao->where("wxappid", "=", $appid)
            ->where("openid", "=", $openid)
            ->find();
        $this->assertNotNull($weixinuser, "");

        $data = [];
        $pc = new WXBizDataCrypt($appid, $weixinuser->currentsessionkey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        $dataobject = json_decode($data, false);
        trace($dataobject);
        if ($errCode == 0) {
            $param = [
                "phoneNumber" => $dataobject->phoneNumber,
                "purePhoneNumber" => $dataobject->purePhoneNumber,
                "countryCode" => $dataobject->countryCode,
                "updatetime" => time()
            ];

            $weixinuserdao->where("wxappid", "=", $appid)
                ->where("openid", "=", $openid)
                ->update($param);
            // $weixinuserdao->where("id", "=", $weixinuser)->update($param);
            return $this->csmjson(array());
        } else {
            return $this->csmerrorjson(array(), $errCode);
        }
    }
}

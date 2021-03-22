<?php
// +----------------------------------------------------------------------
// Csmmeet [ CSMSignin活动签到系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-26
// +----------------------------------------------------------------------
namespace addons\csmsignin\controller;

class Indexajax extends CsmAppFrontend
{

    protected $layout = 'default';

    protected $noNeedLogin = [
        '*'
    ];

    protected $noNeedRight = [
        '*'
    ];

    public function index()
    {
        $this->error("index page");
    }

    /**
     * 获取完整的会议信息
     *
     * @return \think\response\Json
     */
    public function getFullConference()
    {
        $id = $this->csmreq("id", true);

        $confdao = new \app\admin\model\csmsignin\Conf();
        $conf = $confdao->alias("t")
            ->where("t.id", "=", $id)
            ->find();
        // $this->tracedao($confdao);
        $confinfodao = new \app\admin\model\csmsignin\Confinfo();
        $confinfos = $confinfodao->alias("t")
            ->where("t.csmsignin_conf_id", "=", $id)
            ->order("weigh", "desc")
            ->order("status", "'normal'")
            ->select();
        $baseurl = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . config('view_replace_str.__PUBLIC__');
        // 逗号分隔的图片分解为数组
        if ($conf != null && $conf->images != null && $conf->images != "") {
            $conf->imagearray = explode(",", $conf->images);
        }

        $result = array(
            "baseurl" => $baseurl,
            "conf" => $conf,
            "confinfos" => $confinfos
        );
        return $this->csmjson($result);
    }

    /**
     * 获取当前用户的签到信息
     *
     * @return \think\response\Json
     */
    public function getSignininfo()
    {
        $wxuser = $this->getwxuserinfo();
        $confid = $this->csmreq("id", true);

        $confuserdao = new \app\admin\model\csmsignin\Confuser();
        $confuser = $confuserdao->where("csmsignin_conf_id", "=", $confid)
            ->where("status", "=", "normal")
            ->where("weixinuser_id", "=", $wxuser->id)
            ->find();

        $result = array(
            "confuser" => $confuser
        );
        return $this->csmjson($result);
    }

    /**
     * 提交签到
     *
     * @return void|\think\response\Json
     */
    public function submitsigin()
    {
        $wxuser = $this->getwxuserinfo();
        $confid = $this->csmreq("id", true);
        $confdao = new \app\admin\model\csmsignin\Conf();
        $conf = $confdao->where("id", "=", $confid)->find();
        $this->assertNotNull($conf, "ID{$confid}对应的活动不存在");

        $now = time();
        if ($now < $conf->begintime) {
            return $this->csmerrorjson([], "签到从" . date('Y-m-s h:i', $conf->begintime) . "开始，目前无法签到!");
        }
        if ($now > $conf->endtime) {
            return $this->csmerrorjson([], "签到时间已过，无法签到!");
        }

        $confuserdao = new \app\admin\model\csmsignin\Confuser();
        $confuser = $confuserdao->where("csmsignin_conf_id", "=", $confid)
            ->where("status", "=", "normal")
            ->where("usermobile", "=", $wxuser->purePhoneNumber)
            ->find();

        if ($conf->canoutusersignin == "N" && $confuser == null) {
            return $this->csmerrorjson([], "您不在参会名单中，无法签到!");
        } else {
            $cnt = $confuserdao->where("csmsignin_conf_id", "=", $confid)
                ->where("status", "=", "normal")
                ->where("signinstatus", "=", "Y")
                ->where("usermobile", "<>", $wxuser->purePhoneNumber)
                ->count();
            $signordernum = $cnt + 1;
            if ($confuser == null) {
                $param = [
                    "csmsignin_conf_id" => $confid,
                    "username" => $wxuser->nickName,
                    "usermobile" => $wxuser->purePhoneNumber,
                    "signinstatus" => "Y",
                    "signintime" => time(),
                    "signordernum" => $signordernum,
                    "weixinuser_id" => $wxuser->id,
                    "createtime" => time()
                ];
                $confuserdao->create($param);
            } else {
                $param = [
                    "signinstatus" => "Y",
                    "signintime" => time(),
                    "signordernum" => $signordernum,
                    "weixinuser_id" => $wxuser->id,
                    "updatetime" => time()
                ];
                $confuserdao->where("id", "=", $confuser->id)->update($param);
            }
            return $this->csmjson([
                "signordernum" => $signordernum
            ]);
        }
    }
}


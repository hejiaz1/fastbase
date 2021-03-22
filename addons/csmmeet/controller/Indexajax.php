<?php
// +----------------------------------------------------------------------
// Csmmeet [ CSMMeet会议室预约系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-26
// +----------------------------------------------------------------------
namespace addons\csmmeet\controller;

use addons\csmmeet\library\CsmFrontend;
use think\Session;
use app\common\model\User;
use app\common\library\Ems;

class Indexajax extends CsmFrontend
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
     * 使用token登录
     */
    public function loginByToken()
    {
        $token = $this->csmreq("logintoken", true);
        $islogin = $this->auth->init($token);
        $result = array();
        if ($islogin == true) {
            $result["islogin"] = "1";
        } else {
            $result["islogin"] = "0";
        }

        return $this->csmjson($result);
    }

    public function getFrame3()
    {
        $this->csmassertlogin();

        $sessionuser = $this->auth->getUser();
        $result = [
            "sessionuser" => $sessionuser,
            "version" => "1.1.2"
        ];
        return $this->csmjson($result);
    }

    public function cancelApply()
    {
        $this->csmassertlogin();
        $id = $this->csmreq("id", true);
        $applymodel = new \app\admin\model\csmmeet\Apply();
        $param = [
            "auditstatus" => "-2",
            "updatetime" => time()
        ];
        $applymodel->where('id', $id)->update($param);
        return $this->csmjson(array());
    }

    /**
     * 获取当前登录人的会议申请
     *
     * @return \think\response\Json
     */
    public function getMyApplyList()
    {
        $this->csmassertlogin();

        $sessionuser = $this->auth->getUser();
        $applymodel = new \app\admin\model\csmmeet\Apply(); 
        $applylist = $applymodel->alias("t")
            ->field('t.*,t1.name meetname')
            ->where('t.user_id', '=', $sessionuser->id)
            ->join('csmmeet_room t1', 't1.id=t.csmmeet_room_id')
            ->order('createtime desc')
            ->limit(20)
            ->select();
        $result = array(
            "applylist" => $applylist
        );
        return $this->csmjson($result);
    }

    /**
     * 通过账号名密码登录
     */
    public function loginpassword()
    {
        session_start();
        $result = array();
        $email = strtolower($this->csmreq("login_user_email", true));
        $password = $this->csmreq("login_user_psd", true);

        $this->auth->keeptime(0);
        $ff = $this->auth->login($email, $password);
        if ($ff === true) {
            $result["loginsuccess"] = "1";
            $result["logintoken"] = $this->auth->getToken();
        } else {
            // 验证码错误
            $result["loginsuccess"] = "0";
            $result["failuremsg"] = "登录失败";
        }

        return $this->csmjson($result);
    }

    /**
     * 通过邮箱验证码登录
     * 注册并登录，并把登陆信息提交到fa的user表中
     * 如果邮箱或者手机存在，则更新fa_user
     */
    public function login()
    {
        session_start();
        $result = array();
        $email = strtolower($this->csmreq("user_email", true));
        $name = $this->csmreq("user_name", true);
        $nickname = $this->csmreq("nick_name", true);
        
        $mobile = $this->csmreq("user_mobile", false);
        $loginccode = $this->csmreq("loginccode", true);
        $user_password = $this->csmreq("user_password", true);

        if (true) {
            // 邮箱后缀的问题待处理
            $ff = $this->_matchEmail($email);
            if ($ff === false) {
                $config = get_addon_config('csmmeet');
                return $this->csmerrorjson(array(), "此邮箱无法登录，仅支持如下后缀邮箱: " . $config['emailsuffix']);
            }
        }
        if ($loginccode == session('loginccode')) {
            $user = User::getByEmail($email);
            if (! $user) {
                $user = User::getByMobile($mobile);
            }
            if (! $user) {
                // 如果重名，则使用邮箱前缀
                if (User::getByUsername($name)) {
                    $tt = substr($email, 0, strpos($email, "@"));
                    $name = $name . $tt;
                }
                $ff = $this->auth->register($name, $user_password, $email, $mobile);
                if ($ff === false) {
                    return $this->csmerrorjson(array(), $this->auth->getError());
                }

                $user = User::getByEmail($email);
                trace("nickname=".$nickname);
                $user->save(["nickname"=>$nickname]);
         
            } else {
                $usermodel = new User();
                $param = [
                    "username" => $name,
                    "email" => $email,
                    "mobile" => $mobile,
                    "updatetime" => time()
                ];
                $usermodel->where('id', $user->id)->update($param);
            }
            $this->auth->keeptime(0);
            $this->auth->direct($user->id);
            $result["loginsuccess"] = "1";
            $result["logintoken"] = $this->auth->getToken();
        } else {
            // 验证码错误
            $result["loginsuccess"] = "0";
            $result["failuremsg"] = "验证码错误";
        }
        return $this->csmjson($result);
    }

    // 根据配置是否匹配到邮箱后缀
    private function _matchEmail($email)
    {
        $emaill = strtolower($email);
        $config = get_addon_config('csmmeet');

        $emailsuffix = $config['emailsuffix'];
        // 如果没有配置此项，则认为所有邮箱都支持
        if ($emailsuffix == null || $emailsuffix == "") {
            return true;
        }

        $emailss = explode(",", $emailsuffix);
        foreach ($emailss as $value) {
            $pos1 = stripos($emaill, $value);
            if ($pos1 === false) {} else {
                return true;
            }
        }
        return false;
    }

    /**
     * 根据Email回去人员姓名
     */
    public function getUsernameByEmail()
    {
        $email = strtolower($this->csmreq("user_email", true));
        $usermodel = new \app\admin\model\User();
        $user = $usermodel->alias("t")
            ->where("t.email", "=", $email)
            ->find();
        $username = null;
        if ($user) {
            $username = $user->name;
        }
        return $this->csmjson($username);
    }

    /**
     * 给邮箱发送登录验证码邮件
     * 如果邮箱存在，则返回姓名和手机号码
     */
    public function sendLoginCcodeByEmail()
    {
        session_start();
        $email = strtolower($this->csmreq("user_email", true));
        if (strpos($email, "@") === false) {
            return $this->csmerrorjson(array(), "邮箱格式不对，请重新输入");
        }

        if (true) {
            // 邮箱后缀的问题待处理
            $ff = $this->_matchEmail($email);
            if ($ff === false) {
                $config = get_addon_config('csmmeet');
                return $this->csmerrorjson(array(), "此邮箱无法登录，仅支持如下后缀邮箱: " . $config['emailsuffix']);
            }
        }

        $obj2 = \app\common\library\Email::instance();
        if ($obj2->options['mail_smtp_host'] == null || $obj2->options['mail_smtp_host'] == "") {
            return $this->csmerrorjson(array(), "发送邮件配置不正确，请联系管理到后台配置管理-系统配置-邮件配置中配置！");
        }

        $rand = session('loginccode');
        if ($rand == null || $rand == "") {
            $rand = mt_rand(1000, 9999);
        }
        $event = "default";
        $last = Ems::get($email, $event);
        if ($last && time() - $last['createtime'] < 60) {
            return $this->csmerrorjson(array(), "邮件发送频繁");
        }
        \think\Hook::add('ems_send', function ($params) {
            $obj = \app\common\library\Email::instance();
            $result = $obj->to($params->email)
                ->subject('验证码')
                ->message("你的验证码是：" . $params->code)
                ->send();
            return $result;
        });
        $ff = Ems::send($email, $rand, $event);
        if ($ff === true) {
            trace("sendLoginCcodeByEmail loginccode=" + $rand);
            Session::set("loginccode", $rand);

            $user = User::getByEmail($email);
            return $this->csmjson(array(
                "user" => $user
            ));
        } else {
            return $this->csmerrorjson(array(), "邮件发送失败");
        }
    }

    /**
     * 会议室申请提交
     *
     * @return \think\response\Json
     */
    public function submitRoomApply()
    {
        $this->csmassertlogin();

        $applydate = $this->csmreq("apply_applaydate", true);
        $beginhour = $this->csmreq("apply_beginhour", true);
        $beginmin = $this->csmreq("apply_beginmin", true);
        $endhour = $this->csmreq("apply_endhour", true);
        $endmin = $this->csmreq("apply_endmin", true);
        $title = $this->csmreq("apply_meettitle", true);

        $sessionuser = $this->auth->getUser();

        // 如果会议室配置免审批，则自动审核通过
        $room_id = $this->csmreq("apply_csmmeet_room_id", true);
        $meetmodel = new \app\admin\model\csmmeet\Room();
        $meet = $meetmodel->where("id", '=', $room_id)->find();
        $auditstatus = '0';
        if ($meet->needaudit == 'N') {
            $auditstatus = '1';
        }

        $applymodel = new \app\admin\model\csmmeet\Apply();
        $data = [
            "csmmeet_room_id" => $room_id,
            "applydate" => $applydate,
            "beginhour" => $beginhour,
            "beginmin" => $beginmin,
            "endhour" => $endhour,
            "endmin" => $endmin,
            "title" => $title,
            "user_id" => $sessionuser->id,
            "username" => $sessionuser->username,
            "auditstatus" => $auditstatus
        ];
        $apply = $applymodel->create($data);

        $applyhourmodel = new \app\admin\model\csmmeet\Applyhour();
        while ($beginhour < $endhour + 1) {
            $data2 = [
                "csmmeet_apply_id" => $apply->id,
                "applydate" => $applydate,
                "applyhour" => $beginhour
            ];
            $applyhourmodel->create($data2);
            $beginhour ++;
        }

        $result = array();
        return $this->csmjson($result);
    }

    /**
     * 显示会议室预约的table数据：
     *
     * @return \think\response\Json
     */
    public function getRoomApplyInfo()
    {
        $this->csmassertlogin();

        $applydate = $this->request->request('applaydate');
        $roommodel = new \app\admin\model\csmmeet\Room();
        $roomlist = $roommodel->alias("t")
            ->where("t1.status", "=", "normal")
            ->join('csmmeet_building t1', "t1.id=t.csmmeet_building_id and t1.status='normal'")
            ->field('t.*,t1.name buildingname')
            ->order("weigh", "desc")
            ->select();
        /* 查询当天applay信息 */
        $applymodel = new \app\admin\model\csmmeet\Apply();
        $applylist = $applymodel->alias("t")
            ->
        // ->where("auditstatus","=","1")
        where("applydate", "=", $applydate)
            ->field('t.*,t1.name meetname')
            ->join('csmmeet_room t1', 't1.id=t.csmmeet_room_id')
            ->select();

        /* 计算每个会议室当天的使用情况 */
        foreach ($roomlist as $item) {
            $item["h0"] = 0;
            $item["h1"] = 0;
            $item["h2"] = 0;
            $item["h3"] = 0;
            $item["h4"] = 0;
            $item["h5"] = 0;
            $item["h6"] = 0;
            $item["h7"] = 0;
            $item["h8"] = 0;
            $item["h9"] = 0;
            $item["h10"] = 0;
            $item["h11"] = 0;
            $item["h12"] = 0;
            $item["h13"] = 0;
            $item["h14"] = 0;
            $item["h15"] = 0;
            $item["h16"] = 0;
            $item["h17"] = 0;
            $item["h18"] = 0;
            $item["h19"] = 0;
            $item["h20"] = 0;
            $item["h21"] = 0;
            $item["h22"] = 0;
            $item["h23"] = 0;
        }

        $applyhourmodel = new \app\admin\model\csmmeet\Applyhour();
        $applyhourlist = $applyhourmodel->alias("t")
            ->field('t1.csmmeet_room_id,t.applyhour,count(*) applycount')
            ->join('csmmeet_apply t1', 't1.id=t.csmmeet_apply_id')
            ->where("t.applydate", "=", $applydate)
            ->where("auditstatus", "in", [
            '0',
            '1'
        ])
            ->group('t1.csmmeet_room_id,t.applyhour')
            ->select();
        // $this->tracedao($applyhourmodel);

        $applyhourlistsorted = [];
        foreach ($applyhourlist as $item) {
            if (! array_key_exists("R" . $item["csmmeet_room_id"], $applyhourlistsorted)) {
                $applyhourlistsorted["R" . $item["csmmeet_room_id"]] = [];
            }
            $applyhourlistsorted["R" . $item["csmmeet_room_id"]][] = $item;
        }
        // return $this->csmjson($applyhourlistsorted);

        foreach ($roomlist as $item) {
            if (array_key_exists("R" . $item["id"], $applyhourlistsorted)) {
                $houritems = $applyhourlistsorted["R" . $item["id"]];
                foreach ($houritems as $houritem) {
                    $item["h" . $houritem["applyhour"]] = $houritem["applycount"];
                }
            }
        }

        /* 会议室按照大楼来分组，供前段分开显示 */
        $buildings = [];
        foreach ($roomlist as $item) {
            $k = $item['buildingname'];
            if (! array_key_exists($k, $buildings)) {
                $buildings[$k] = array();
            }
            $buildings[$k][] = $item;
        }
        $result = array(/*"roomlist" => $roomlist,*/"buildings"=>$buildings,"applylist"=>$applylist);
        return $this->csmjson($result);
    }
}


<?php
// +----------------------------------------------------------------------
// Csmmeet [ CSMMeet会议室预约系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-26
// +----------------------------------------------------------------------
namespace addons\csmmeet\controller;

use addons\csmmeet\library\CsmFrontend;

class Screenajax extends CsmFrontend
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
    public function querybuildingapply()
    {
        $building_id = $this->csmreq("building_id", true);
        $applydate = $this->csmreq("applydate", true);
        $currenthour = $this->csmreq("currenthour", true);
        $applymodel = new \app\admin\model\csmmeet\Apply();
        $applylist = $applymodel->alias("t")
            ->field('t.title,t.beginhour,t.beginmin,t.endhour,t.endmin,t.username,t1.name meetname')
            ->join('fa_csmmeet_room t1', "t.csmmeet_room_id=t1.id and t1.status='normal'")
            ->join('fa_csmmeet_building t2', "t2.status='normal'")
            ->where('t.applydate', '=', $applydate)
            ->where('t2.id', '=', $building_id)
            ->where('t.auditstatus', '=', '1')
            ->where('t.endhour', '>=', $currenthour)
            ->where('t1.csmmeet_building_id=t2.id')
            ->order('beginhour desc,endmin desc')
            ->select();
        return $this->csmjson(array(
            "applylist" => $applylist
        ));
    }
}

<?php
// +----------------------------------------------------------------------
// Csmmeet [ CSMMeet会议室预约系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-26
// +----------------------------------------------------------------------
namespace app\admin\controller\csmmeet;

use app\common\controller\Backend;

/**
 * 会议室申请
 *
 * @icon fa fa-circle-o
 */
class Apply extends Backend
{

    /**
     * Apply模型对象
     *
     * @var \app\admin\model\csmmeet\Apply
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\csmmeet\Apply();
        $this->view->assign("auditstatusList", $this->model->getAuditstatusList());
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * 查看
     */
    public function index()
    {
        // 设置过滤方法
        $this->request->filter([
            'strip_tags'
        ]);
        if ($this->request->isAjax()) {
            // 如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list ($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model->alias("t")
                ->field("t.*,t1.name roomname,t2.name buildingname,concat(t.applydate,' ',t.beginhour,':',t.beginmin,'-',t.endhour,':',t.endmin) applydatetime")
                ->where($where)
                ->join('csmmeet_room t1', 't1.id=t.csmmeet_room_id')
                ->join('csmmeet_building t2', 't2.id=t1.csmmeet_building_id')
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();
            $result = array(
                "total" => $total,
                "rows" => $list
            );

            return json($result);
        }
        return $this->view->fetch();
    }
    /**
     * 审核/退回预约申请
     */
    public function submitAuditAjax()
    {
        $id = $this->request->request('id');
        $auditstatus = $this->request->request('auditstatus');

        $sessionuser = $this->auth;
        $param = [
            "auditstatus" => $auditstatus,
            "audituser_id" => $sessionuser->id,
            "audituser" => $sessionuser->nickname,
            "updatetime" => time()
        ];
        $this->model->where('id', $id)->update($param);
        $this->success();
    }
}

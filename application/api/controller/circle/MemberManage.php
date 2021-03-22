<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-14 15:15:28
 * @FilePath       : \application\api\controller\circle\MemberManage.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-11 14:52:15
 * @Description    : 圈子成员管理控制器
 */
namespace app\api\controller\circle;
use app\common\controller\Api;
use think\Db;

use app\common\model\circle\Index as Circle;    // 圈子内容

/**
 * 圈子成员管理
 */
class MemberManage extends Api
{
    protected $noNeedLogin = [];
    protected $noNeedRight = [];

    // 可访问身份
    private $pass_status = ['master','admin'];

    // 成员信息参数
    private $memberConfig = [
        'field' => 'id,user_id,group_id,nickname,avatar,status,jointype,notes_num,days_num,createtime,lastissuetime',
        'order' => 'createtime desc,id desc',
    ];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\circle\Member();

        // 圈子信息
        $this->circle_id = $this->request->request('circle_id/d', 0, 'intval');
        if($this->circle_id == 0){
            $this->error(__('Parameter error'));
        }

        $this->circle = (new Circle)
            ->where([
                'id'     => $this->circle_id,
                'status' => 'normal',
            ])
            ->field('master_id,jointype')
            ->find();
        // 圈子不存在
        if(!$this->circle){
            $this->error(__('Circle don\'t exist'));
        }

        // dump($this->circle);

        // TODO 超管身份

        // 成员身份
        $this->member = $this->model->member($this->auth->id, $this->circle_id);

        if(!$this->member){
            $this->error(__('No access'));
        }else{
            // 判断身份
            if(!in_array($this->member['status'], $this->pass_status)){
                $this->error(__('You have no permission'));
            }
        }

    }

    /** 圈子成员列表
     * @Author: hejiaz
     * @Date: 2020-12-10 10:17:02
     */
    public function list(){
        $type   = $this->request->request('type/d', 1, 'intval');
        $kwords = $this->request->request('kwords/s', '', 'trim');

        $where = [
            'circle_id' => $this->circle_id,
        ];

        switch ($type) {
            case 1:
            case 2:
            case 3:
                $where['isaudit'] = $type;
                break;
            default:
                $this->error(__('error'));
                break;
        }

        if($kwords){
            // 搜索
            $where['nickname'] = ['like', '%'.$kwords.'%'];
        }

        // 排序
        switch ($this->publicParams['order']) {
            case 'join_asc':
                // 最早加入
                $order = 'createtime asc,id asc';
                break;
            case 'join_desc':
                // 最新加入
                $order = 'createtime desc,id desc';
                break;
            case 'days_num_asc':
                // 打卡天数少到多
                $order = 'days_num asc,id asc';
                break;
            case 'days_num_desc':
                $order = 'days_num desc,id desc';
                break;
            case 'last_issue':
                // 最后活跃时间
                $order = 'lastissuetime desc,id desc';
                break;

            default:
                $order = 'createtime desc,id desc';
                break;
        }


        $data = $this->model
            ->where($where)
            ->with('user')
            ->field($this->memberConfig['field'])
            ->order($order)
            ->paginate($this->publicParams['shownum'])->toarray();

        $this->success('', $data);

    }

    /** 更改成员昵称
     * @Author: hejiaz
     * @Date: 2020-12-10 14:43:23
     */
    public function editnickname(){
        $user_id = $this->request->request('user_id/d', 0, 'intval');
        if($user_id == 0){
            $this->error(__(''));
        }

        $nickname = $this->request->request('nickname/s', '', 'trim');
        if($nickname == ''){
            $this->success();
        }

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            $deal = $this->model->where([
                'user_id'   => $user_id,
                'circle_id' => $this->circle_id,
            ])->update([
                'nickname'   => $nickname,
                'updatetime' => time(),
            ]);

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
            $this->success(__('Operation completed'), $id);
        }

    }

    /** 删除成员
     * @Author: hejiaz
     * @Date: 2020-12-10 10:37:11
     */
    public function remove(){
        $user_id = $this->request->request('user_id/d', 0, 'intval');
        if($user_id == 0){
            $this->error(__(''));
        }

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            $deal = $this->model->where([
                'user_id'   => $user_id,
                'circle_id' => $this->circle_id,
            ])->update([
                'isaudit'    => 9,
                'updatetime' => time(),
            ]);
            (new Circle)->where('id', $this->circle_id)->setDec('member_num');

            // TODO 加管理员记录
            // TODO 发消息

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
            $this->success(__('Operation completed'), $id);
        }

    }

    /** 通过审核
     * @Author: hejiaz
     * @Date: 2020-12-11 11:24:50
     */
    public function agree(){
        $user_id = $this->request->request('user_id/d', 0, 'intval');
        if($user_id == 0){
            $this->error();
        }

        // TODO 发消息
        // TODO 通过记录

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            $deal = $this->model->where([
                'user_id'   => $user_id,
                'circle_id' => $this->circle_id,
            ])->update([
                'isaudit'    => 1,
                'updatetime' => time(),
            ]);

            // 增加圈子成员数
            (new Circle)->where('id', $this->circle_id)->setInc('member_num');

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
            $this->success(__('Operation completed'), $id);
        }

    }

    /** 拒绝审核
     * @Author: hejiaz
     * @Date: 2020-12-11 11:24:50
     */
    public function refuse(){
        $user_id = $this->request->request('user_id/d', 0, 'intval');
        if($user_id == 0){
            $this->error();
        }

        // TODO 给拒绝用户发消息
        // TODO 踢出记录

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            $deal = $this->model->where([
                'user_id'   => $user_id,
                'circle_id' => $this->circle_id,
            ])->update([
                'isaudit'    => 3,
                'updatetime' => time(),
            ]);
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
            $this->success(__('Operation completed'), $id);
        }

    }
}

<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-14 15:15:28
 * @FilePath       : \application\api\controller\note\Manage.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-10 14:12:45
 * @Description    : 笔记信息管理
 */
namespace app\api\controller\note;
use app\common\controller\Api;
use think\Db;

use app\common\model\circle\Index as circleModel;   // 圈子主体
use app\common\model\circle\Member;             // 圈子成员

use app\common\model\note\Annex;        // 笔记附件模型
use app\common\model\like\Note as likeNote;     // 笔记点赞
use app\common\model\user\Like as userLike;     // 会员点赞记录

/**
 * 笔记管理编辑
 */
class Manage extends Api
{
    protected $noNeedLogin = [];
    protected $noNeedRight = [];

    // 展示状态类型
    protected $show_status_type = [1, 2, 8, 9];

    // 笔记主体参数
    private $mianConfig = [
        'where' => [
            'status' => '1',
        ],
        // 'field' => 'id,circle_id,topic_id,user_id,days_num,content,group_id,pv_num,like_num,comment_num,share_num,location',
        'outField' => 'status,updatetime,deletetime',
        'order' => 'createtime desc,weigh desc,id desc',
    ];

    // 初始化
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\note\Index();

        $this->circle_id = $this->request->request('circle_id/d',0,'intval');
        // TODO 验证是否加入该圈子
        $this->member = (new Member)->member($this->auth->id, $this->circle_id);
        if($this->circle_id){
            if(!$this->member || $this->member['isaudit'] != 1){
                dump($member['isaudit']);
                die;
                $this->error(__('Not to join'));
            }
        }

    }

    /** 发布笔记
     * @Author: hejiaz
     * @Date: 2020-10-30 17:54:31
     */
    public function issue(){

        $user_id = $this->auth->id;

        // 获取参数
        $main_params = [
            'circle_id' => $this->circle_id,
            'member_id' => $this->member['id']? :0, // 默认为0
            'topic_id'  => $this->request->request('topic_id/d', 0, 'intval'),
            'user_id'   => $user_id,
            'group_id'  => $this->request->request('group_id/d', 0, 'intval'),
            'content'   => $this->request->request('content/s', '', 'trim'),
            'status'    => $this->request->request('status/d', 1, 'intval')
        ];

        // 获取附件参数
        $annex_params = get_annex_params($this->request);
        if($annex_params == ''&& $main_params['content'] == ''){
            $this->error(__('The note is empty'));
        }

        // 过滤内容
        mask_words($main_params['content']);

        // 获取地址信息
        $location = $this->request->request('location/a',[]);
        if($location){
            if($location['name'] == '' && $location['coord_x'] == '' && $location['coord_y'] == ''){
                // 所有地址信息都为空 则不存地址信息
                $main_params['location'] = '';
            }else{
                if($location['name'] == '' || $location['coord_x'] == '' || $location['coord_y'] == ''){
                    $this->error(__('Incorrect address information'));
                }else{
                    $main_params['location'] = json_encode($location);
                }
            }
        }

        if($this->circle_id){

            // 获取圈子累计天数
            $main_params['days_num'] = (new Member)->getDaysNum($user_id, $this->circle_id);

            // 默认不累计天数
            $amass = false;

            // 获取本圈子所发布的上一条信息
            $last_note = $this->model->getLastNote($user_id, $this->circle_id);
            if(!$last_note){
                $amass = true; // 累计天数
                $main_params['days_num']++;
            }else{
                // 判断是否是今天
                $last_date = date('Y-m-d', $last_note['createtime']);
                if($last_date !== date('Y-m-d')){
                    $amass = true; // 累计天数
                    $main_params['days_num']++;
                }
            }

            // TODO 圈子活力值

        }


        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            $deal = $this->model->save($main_params);

            // 附件处理 未上传附件不添加
            if($annex_params){
                $annex_deal = (new Annex)->save(array_merge($annex_params,['note_id'=>$this->model->id]));
            }

            if($this->circle_id){
                // 圈子笔记数量累计
                (new circleModel)->where('id',$this->circle_id)->setInc('notes_num');

                // 成员信息笔记数量累计
                (new Member)->where(['user_id' => $user_id,'circle_id' => $this->circle_id])->setInc('notes_num');
                // 更新最后发布时间
                (new Member)->where(['user_id' => $user_id,'circle_id' => $this->circle_id])->update(['lastissuetime'=>time()]);
                // 成员打卡天数累计
                if($amass){
                    (new Member)->where(['user_id' => $user_id,'circle_id' => $this->circle_id])->setInc('days_num');
                }
            }


            // TODO 会员笔记数量累计


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

    /** 删除笔记
     * @Author: hejiaz
     * @Date: 2020-11-03 17:01:14
     * @Param: $id
     * @Return: mixed array json boole
     */
    public function delete(){
        $id = $this->request->request('id/d', 0, 'intval');
        if($id == 0){
            $this->error(__('Parameter error'));
        }

        $data = $this->model->where($this->mianConfig['where'])->find($id);
        if(!$data){
            $this->error(__('Notes don\'t exist'));
        }

        // TODO 判断是否是超级管理员

        // 判断是否是自己的笔记
        if($data['user_id'] != $this->auth->id){
            if($data['circle_id'] == 0){
                // 没有圈子则仅有会员自己可删除
                $this->error(__('You have no right to operate'));
            }else{
                $member = (new Member)->member($this->auth->id, $data['circle_id']);
                // 判断用户是否有权限
                if(!in_array($member['status'],$this->memberStatus)){
                    $this->error(__('You have no right to operate'));
                }
            }
        }

        $deal = false;
        Db::startTrans();
        try {
            // 删除数据 软删除
            $deal = $data->delete();
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
            // TODO 给会员发信息 做记录

            $this->success(__('Operation completed'), $id);
        }
    }

    /** 设置置顶/取消置顶
     * @Author: hejiaz
     * @Date: 2020-11-04 14:20:50
     */
    public function set_top(){
        $id = $this->request->request('id/d', 0, 'intval');
        if($id == 0){
            $this->error(__('Parameter error'));
        }

        $data = $this->model->where($this->mianConfig['where'])->find($id);
        if(!$data){
            $this->error(__('Notes don\'t exist'));
        }

        // TODO 判断是否是超级管理员

        // 判断是否是自己的笔记
        if($data['user_id'] != $this->auth->id){
            if($data['circle_id'] == 0){
                // 没有圈子则仅有会员自己可删除
                $this->error(__('You have no right to operate'));
            }else{
                $member = (new Member)->member($this->auth->id, $data['circle_id']);
                // 判断用户是否有权限
                if(!in_array($member['status'],$this->memberStatus)){
                    $this->error(__('You have no right to operate'));
                }
            }
        }

        // 置顶状态
        $params = [
            'is_top' => $data['is_top']==1?0:1,
        ];

        Db::startTrans();
        try {
            // 更新数据
            $deal = $this->model->save($params, ['id'=> $id]);
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

    /** 设置笔记展示状态
     * @Author: hejiaz
     * @Date: 2020-11-04 14:58:05
     */
    public function set_show_status(){
        $id = $this->request->request('id/d', 0, 'intval');
        if($id == 0){
            $this->error(__('Parameter error'));
        }

        $data = $this->model->where($this->mianConfig['where'])->find($id);
        if(!$data){
            $this->error(__('Notes don\'t exist'));
        }

        $show_status = $this->request->request('show_status/d', 1, 'intval');
        if($show_status == $data['show_status']){
            $this->error(__('Parameter error'));
        }

        if(!in_array($show_status, $this->show_status_type)){
            $this->error(__('Type error'));
        }

        // TODO 判断是否是超级管理员
        if($show_status == 9){
            $this->error('非超级管理员');
        }

        // 判断是否是自己的笔记
        if($data['user_id'] != $this->auth->id){
            if($data['circle_id'] == 0){
                // 没有圈子则仅有会员自己可删除
                $this->error(__('You have no right to operate'));
            }else{
                $member = (new Member)->member($this->auth->id, $data['circle_id']);
                // 判断用户是否有权限
                if(!in_array($member['status'],$this->memberStatus)){
                    $this->error(__('You have no right to operate'));
                }
            }
        }

        // 置顶状态
        $params = [
            'show_status' => $show_status,
        ];

        Db::startTrans();
        try {
            // 更新数据
            $deal = $this->model->save($params, ['id'=> $id]);
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

    /** 点赞/取消点赞
     * @Author: hejiaz
     * @Date: 2020-11-04 15:54:05
     */
    public function dispose_like(){
        $id = $this->request->request('id/d', 0, 'intval');
        if($id == 0){
            $this->error(__('Parameter error'));
        }
        $note = $this->model->where($this->mianConfig['where'])->find($id);
        if(!$note){
            $this->error(__('Notes don\'t exist'));
        }

        if($note['circle_id']){
            $member = (new Member)->member($this->auth->id, $note['circle_id']);
        }

        // 验证笔记状态
        switch ($note['show_status']) {
            case 2:
                // 判断是否是圈子 圈主或管理员或自己
                if($note['user_id'] != $this->auth->id){
                    if($data['circle_id'] == 0){
                        $this->error(__('You have no right to operate'));
                    }else{
                        // 判断用户是否有权限
                        if(!in_array($member['status'],$memberStatus)){
                            $this->error(__('You have no right to operate'));
                        }
                    }
                }
                break;
            case 3:
                // 仅仅自己可以点赞
                if($note['user_id'] != $this->auth->id){
                    $this->error(__('You have no right to operate'));
                }
                break;

            default:
                # code...
                break;
        }

        $islike = 0;    // 默认取消点赞
        $data = (new likeNote)->get($id);
        if($data){
            $like_uids = json_decode($data['like_uids'], true);
            if(in_array($this->auth->id, $like_uids)){
                // 定位 去除键名
                $k = array_search($this->auth->id, $like_uids);
                unset($like_uids[$k]);

                $like_uids = array_values($like_uids);
            }else{
                $islike = 1;     // 点赞
                $like_uids[] = $this->auth->id;
            }
            // 更新参数
            $params = [
                'total_num' => count($like_uids),
                'like_uids' => json_encode($like_uids),
            ];
        }else{
            // 新增数据
            $params = [
                'circle_id' => $note['circle_id'],
                'note_id'   => $id,
                'user_id'   => $note['user_id'],
                'total_num' => 1,
                'like_uids' => json_encode([$this->auth->id]),
            ];

            $islike = 1;
        }

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            if($data){
                $deal = $data->save($params);
            }else{
                $deal = (new likeNote)->save($params);
            }

            if($islike){
                // 点赞
                $this->model->where(['id'=>$id])->setInc('like_num');
            }else{
                // 取消点赞
                $this->model->where(['id'=>$id])->setDec('like_num');
            }

            // 会员个人点赞记录
            $user_like = (new userLike)->like('note', $this->auth->id, $id);
            if ($user_like == 'add') {
                // 保存点赞信息
                (new userLike)->save([
                    'user_id' => $this->auth->id,
                    'note_ids' => json_encode([$id]),
                ]);
            } else {
                (new userLike)->where(['user_id' => $this->auth->id])->update(['note_ids' => $user_like,]);
            }

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
            if($islike){
                // TODO 给发布者发点赞消息

            }

            $return['islike']  = $islike;
            $return['user_id'] = $this->auth->id;
            $return['user']    = (new \app\common\model\info\User)->user($this->auth->id);
            $return['member']  = (new \app\common\model\info\Member)->member($this->auth->id, $this->circle_id);


            $this->success(__('Operation completed'), $return);
        }
    }


}

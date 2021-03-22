<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-14 15:15:28
 * @FilePath       : \application\api\controller\note\Index.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-14 17:52:33
 * @Description    : 笔记信息 展示
 */
namespace app\api\controller\note;
use app\common\controller\Api;

use app\common\model\circle\Index as circleModel;
use app\common\model\comment\Note as commentNote; // 笔记评论模型
use app\common\model\like\Note as likeNote; // 笔记点赞模型

/**
 * 笔记信息
 */
class Index extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    // 笔记主体参数
    private $mianConfig = [
        'where' => [
            'status' => '1',
        ],
        // 'field' => 'id,circle_id,topic_id,user_id,days_num,content,group_id,pv_num,like_num,comment_num,share_num,location',
        'outField' => 'status,updatetime,deletetime',
        'order' => 'createtime desc,weigh desc,id desc',
    ];


    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\note\Index();
    }

    /** 圈子笔记列表
     * @Author: hejiaz
     * @Date: 2020-11-03 14:05:39
     */
    public function circle_list(){

        // 展示状态 默认公开
        $show_status = $this->request->request("show_status/d", 1);

        $circle_id = $this->request->request("circle_id/d", 0);
        if($circle_id == 0){
            $this->error(__('Parameter error'));
        }

        // 圈子信息
        $circle = (new circleModel)->getBaseInfo($circle_id);
        if(!$circle){
            $this->error(__('The circle does not exist'));
        }

        // TODO 判断圈子是否可不登录查看信息



        // TODO 话题信息 topic_id
        // 判断是否是自己的笔记

        $data = $this->model
            ->where($this->mianConfig['where'])
            ->where('circle_id', $circle_id)
            ->where('show_status', $show_status)
            ->order('is_top desc')
            ->order($this->mianConfig['order'])
            ->field($this->mianConfig['outField'], true)
            ->with('annex,user,member,comment')
            ->paginate($this->publicParams['shownum'])->toarray();


        // 赋值点赞信息
        (new likeNote)->assign_like_user($data['data']);

        // dump($data);die;

        $this->success($circle, $data);
    }

    /** 会员点赞列表
     * @Author: hejiaz
     * @Date: 2020-12-08 10:13:20
     */
    public function userlike(){
        $user_id = $this->request->request('user_id/d', 0, 'intval');
        if($user_id == 0){
            $this->error(__(''));

        }
        // 获取笔记ID
        $note_ids = (new \app\common\model\user\Like)->where(['user_id'=>$user_id])->value('note_ids');
        $note_ids = json_decode($note_ids, true);
        if(!$note_ids){
            $this->success(__('Data is empty'));
        }

        $page    = $this->publicParams['page'];
        $shownum = $this->publicParams['shownum'];
        $count   = count($note_ids);

        foreach($note_ids as $k=>$id){
            // 判断分页
            if($page == 1){
                if($shownum-1 < $k){
                    break;
                }
            }else{
                if($shownum > $k){
                    continue;
                }
                if(($shownum*$page)-1 < $k){
                    break;
                }
            }
            $note = $this->model
                ->where($this->mianConfig['where'])
                ->where('id', $id)
                ->where('show_status', 1)
                ->field($this->mianConfig['outField'], true)
                ->with('annex,circle,user,member,comment')
                ->find()->toarray();
            // 赋值点赞信息
            (new likeNote)->assign_like_user($note, 2);
            $list[] = $note;
        }

        $data = [
            'total'        => $count,
            'per_page'     => $shownum,
            'current_page' => $page,
            'last_page'    => $count<$shownum? 1 : ceil($count/$shownum),
            'data'         => $list,
        ];
        // dump($count);
        // dump($data);die;

        $this->success($user_id, $data);
    }


    /** 笔记主页 详情
     * @Author: hejiaz
     * @Date: 2020-11-03 16:52:24
     */
    public function index(){
        $id = $this->request->request("id/d", 0);
        if ($id == 0) {
            $this->error(__('Parameter error'));
        }

        $data = $this->model
            ->with('annex,user,member')
            ->where($this->mianConfig['where'])
            ->field('weigh,updatetime,deletetime,status', true)
            ->find($id);

        toarray($data, ['location', 'annex.images']);

        if(!$data){
            $this->error(__('Notes don\'t exist'));
        }

        if($data['circle_id']){
            $data['circle'] = (new circleModel)->getBaseInfo($data['circle_id']);
            if(!$data['circle']){
                $this->error(__('The circle does not exist'));
            }
        }

        // 通过用户判断获取笔记展示类型 show_status
        switch ($data['show_status']) {
            case 1:
                // 公开
                break;

            case 2:
                // 圈主/管理员可见
                if($data['circle']){
                    // 验证登录
                    $this->checklogin();
                    $user_member = (new \app\common\model\info\Member)->member($this->auth->id, $data['circle_id']);

                    if($user_member){
                        // easy 用户为成员无法查看
                        if($user_member['status'] == 'member'){
                            $this->error('No access');
                        }
                    }else{
                        $this->error(__('Not to join'));
                    }
                }else{
                    // 没有圈子 仅自己可见
                    if ($this->auth->id != $data['user_id']) {
                        $this->error(__('The note cannot be viewed'));
                    }
                }
                break;

            case 8:
                // 仅自己可见
                if($this->auth->id != $data['user_id']){
                    $this->error(__('The note cannot be viewed'));
                }
                break;
            case 9:
                // TODO 被超管删除

                break;
            default:
                # code...
                break;
        }

        // 赋值点赞信息
        (new likeNote)->assign_like_user($data, 2);

        $this->success('', $data);

    }


    /** 增加笔记访问量
     * @Author: hejiaz
     * @Date: 2020-11-03 16:28:06
     */
    public function setinc_pv_num(){
        $id = $this->request->request("id/d", 0);
        if ($id == 0) {
            $this->error(__('Parameter error'));
        }
        // TODO 判断调取接口的IP地址 会员信息 是否登录 判断时间间隔 以保证浏览量准确度
        $this->model->where('id', $id)->setInc('pv_num');
        $this->success();
    }

    /** 笔记评论信息
     * @Author: hejiaz
     * @Date: 2020-11-03 17:23:30
     */
    public function comment_list(){

    }



}

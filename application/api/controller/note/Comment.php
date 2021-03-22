<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-14 15:15:28
 * @FilePath       : \application\api\controller\note\Comment.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-07 16:57:02
 * @Description    : 笔记评论管理
 */
namespace app\api\controller\note;
use app\common\controller\Api;
use think\Db;

use app\common\model\comment\NoteAnnex as Annex; // 评论附件

use app\common\model\note\Index as noteModel;   // 笔记模型
use app\common\model\circle\Member;             // 圈子成员模型

use app\common\model\like\NoteComment as noteCommentLike;      // 笔记评论点赞
use app\common\model\user\Like as userLike; // 会员点赞记录

// use app\common\model\circle\Index as circleModel;   // 圈子主体
use fast\Tree; // 通用树形类

/**
 * 笔记评论管理
 */
class Comment extends Api
{
    // 无需登录方法
    protected $noNeedLogin = ['list'];

    // 无需鉴权方法
    protected $noNeedRight = [];

    // 评论参数
    private $mainConfig = [
        'where' => [
            'status' => '1',
        ],
    ];

    // 初始化
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\comment\Note();

        // 评论笔记 全部要基于笔记
        $note_id = $this->request->request('note_id/d',0,'intval');
        if($note_id == 0){
            $this->error(__('Parameter error'));
        }
        // 判断笔记是否存在及展示状态
        $note = (new noteModel)->where(['status' => 1])->find($note_id);
        if(!$note){
            $this->error(__('Notes don\'t exist'));
        }

        $checkJoin = false;
        $checkAuth = false;
        switch ($note['show_status']) {
            case 1:
                // 公开 判断是否加入圈子
                $checkJoin = true;
                break;
            case 2:
                // 仅圈主/管理员可见
                $checkJoin = true;
                $checkAuth = true;
                break;
            case 3:
                // 尽自己可见 除自己外没人能评论
                if($this->auth->id != $note['user_id']){
                    $this->error(__('You have no right to operate'));
                }
                break;
            default:
                $this->error(__('Data is empty'));
                break;
        }

        // 判断笔记展示状态
        if($note['circle_id'] && $checkJoin){
            $member = (new Member)->member($this->auth->id, $note['circle_id']);

            // dump($member);
            // die;
            if($member){
                if($checkAuth){
                    // 验证权限
                }
            }else{
                // 未加入圈子可评论
            }
        }

        $this->note_id = $note_id;
        $this->note    = $note;
        $this->member  = $member;
    }

    /** 评论列表
     * @Author: hejiaz
     * @Date: 2020-11-05 15:00:16
     */
    public function list(){

        // 获取原始数据
        $rawList = $this->model
            ->where(['note_id'=>$this->note_id])
            ->where($this->mainConfig['where'])
            ->field('circle_id,note_id,deletetime,status', true)
            ->with('annex,user,member,revertUser,revertMember')
            ->order('is_hot desc,createtime asc,id asc')
            ->select();

        if(!$rawList){
            $this->success();
        }

        // 初始化树状模型
        $tree = Tree::instance();
        $tree->init(collection($rawList)->toArray(), 'pid');
        $list = $tree->getTreeArray(0);

        $this->success('', $list);
    }

    /** 弹幕列表 笔记下所有有内容的评论文字
     * @Author: hejiaz
     * @Date: 2020-12-03 10:17:08
     */
    public function barrage(){
        // TODO
    }

    /** 发布评论
     * @Author: hejiaz
     * @Date: 2020-11-05 10:51:47
     */
    public function comment(){

        $content = $this->request->request('content/s', '', 'trim');

        // 获取附件参数
        $annex_params = get_annex_params($this->request);
        if($annex_params == ''){
            // 判断是否有内容
            if($content == ''){
                $this->error(__('The comment is empty'));
            }
        }

        // 过滤文字
        mask_words($content);

        $params = [
            'circle_id' => $this->note['circle_id'],
            'note_id'   => $this->note_id,
            'user_id'   => $this->auth->id,
            'member_id' => $this->member['id'],
            'content'   => $content,
        ];
        // dump($params); die;

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            $deal = $this->model->save($params);

            if($annex_params){
                $annex_deal = (new Annex)->save(array_merge($annex_params,['comment_id'=>$this->model->id]));
            }

            // 评论数量增加
            (new noteModel)->where(['id'=>$this->note_id])->setInc('comment_num');

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
            // TODO 通知笔记创作者收到评论

            $comment = $this->model
                ->where(['id'=>$this->model->id])
                ->where($this->mainConfig['where'])
                ->field('circle_id,note_id,deletetime,status', true)
                ->with('annex,user,member,revertUser,revertMember')
                ->find();
            // toarray($comment);

            $this->success(__('Operation completed'), $comment);
        }
    }

    /** 回复评论
     * @Author: hejiaz
     * @Date: 2020-11-05 14:46:22
     */
    public function revert(){
        $pid = $this->request->request('pid/d', 0, 'intval');
        if($pid == 0){
            $this->error(__('Parameter error'));
        }
        // 判断回复评论信息
        $p_comment = $this->model->where($this->mainConfig['where'])->where(['id' => $pid])->find();
        if($p_comment){
            if($p_comment['pid'] > 0){
                $this->error(__('The comment message is incorrect'));
            }
            if($p_comment['note_id'] != $this->note_id){
                $this->error(__('Parameter error'));
            }
        }else{
            $this->error(__('The comment message does not exist'));
        }

        // 回复ID
        $revert_id        = $this->request->request('revert_id/d', 0, 'intval');
        $revert_user_id   = 0;
        $revert_member_id = 0;
        if($revert_id){
            // 判断回复信息
            $revert = $this->model->where($this->mainConfig['where'])->where(['id' => $revert_id])->find();

            if($revert){
                if($revert['note_id'] != $this->note_id){
                    $this->error(__('Parameter error'));
                }
                if($revert['pid'] == 0){
                    $this->error(__('Reply comment is incorrect'));
                }
                if($revert['pid'] != $pid){
                    // 不能跨服聊天
                    $this->error(__('Reply comment is incorrect'));
                }
            }else{
                $this->error(__('Reply comment does not exist'));
            }

            $revert_user_id   = $revert['user_id'];
            $revert_member_id = $revert['member_id'];
        }

        $content = $this->request->request('content/s', '', 'trim');

        // 获取附件参数
        $annex_params = get_annex_params($this->request);
        if($annex_params == ''){
            // 判断是否有内容
            if($content == ''){
                $this->error(__('The comment is empty'));
            }
        }

        // 过滤文字
        mask_words($content);

        // TODO 判断是否要把父级设为热评


        $params = [
            'pid'              => $pid,
            'revert_id'        => $revert_id,
            'revert_user_id'   => $revert_user_id,
            'revert_member_id' => $revert_member_id,
            'circle_id'        => $this->note['circle_id'],
            'note_id'          => $this->note_id,
            'user_id'          => $this->auth->id,
            'member_id'        => $this->member['id'],
            'content'          => $content,
        ];

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            $deal = $this->model->save($params);

            if($annex_params){
                $annex_deal = (new Annex)->save(array_merge($annex_params,['comment_id'=>$this->model->id]));
            }

            // 评论数量增加
            (new noteModel)->where(['id'=>$this->note_id])->setInc('comment_num');

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
             $comment = $this->model
                ->where(['id'=>$this->model->id])
                ->where($this->mainConfig['where'])
                ->field('circle_id,note_id,deletetime,status', true)
                ->with('annex,user,member,revertUser,revertMember')
                ->find();
            // toarray($comment);

            $this->success(__('Operation completed'), $comment);
        }
    }

    /** 删除评论
     * @Author: hejiaz
     * @Date: 2020-11-06 11:41:49
     */
    public function delete(){
        $id = $this->request->request('id/d', 0, 'intval');
        if($id == 0){
            $this->error(__('Parameter error'));
        }

        $data = $this->model->get($id);
        if($data){
            if($data['note_id'] != $this->note_id){
                $this->error(__('Parameter error'));
            }
        }else{
            $this->error(__('The comment message does not exist'));
        }

        // TODO 验证超管身份

        if($data['user_id'] != $this->auth->id){
            $this->error(__('You have no right to operate'));
        }

        $childnum = 0;

        $deal = false;
        Db::startTrans();
        try {
            // 删除数据 软删除
            $deal = $data->delete();

            if($data['pid'] == 0){
                // 判断是否是一级评论 删除子级
                $this->model->where(['pid' => $id])->update(['deletetime'=>time()]);
            }

            (new noteModel)->where(['id' => $this->note_id])->setDec('comment_num', $childnum+1);

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
            // 获取笔记当前评论数
            $comment_num = (new noteModel)->where(['id' => $this->note_id])->value('comment_num');
            $this->success(__('Operation completed'), ['comment_num'=>$comment_num]);
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

        $comment_data = $this->model->where($this->mainConfig['where'])->find($id);
        if($comment_data){
            if($comment_data['note_id'] != $this->note_id){
                $this->error(__('Parameter error'));
            }
        }else{
            $this->error(__('Data is empty'));
        }

        $islike = 0;    // 默认取消点赞
        $data = (new noteCommentLike)->get($id);
        if($data){
            $like_uids = json_decode($data['like_uids'], true);
            if(in_array($this->auth->id, $like_uids)){
                $k = array_search($this->auth->id, $like_uids);
                unset($like_uids[$k]);
                // 去除键名
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
                'circle_id'  => $this->note['circle_id'],
                'note_id'    => $this->note_id,
                'comment_id' => $id,
                'user_id'    => $comment_data['user_id'],
                'total_num'  => 1,
                'like_uids'  => json_encode([$this->auth->id]),
            ];
            $islike = 1;
        }

        // dump($params);die;
        // TODO 当前会员点赞记录

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            if($data){
                $deal = $data->save($params);
            }else{
                $deal = (new noteCommentLike)->save($params);
            }

            if($islike){
                // 评论点赞数量
                $this->model->where(['id'=>$id])->setInc('like_num');

                // TODO 会员个人点赞数
                // TODO 笔记点赞数量
            }else{
                // 取消点赞
                $this->model->where(['id'=>$id])->setDec('like_num');

            }

            // 会员个人点赞记录
            $user_like = (new userLike)->like('comment_note', $this->auth->id, $id);
            if ($user_like == 'add') {
                // 保存点赞信息
                (new userLike)->save([
                    'user_id' => $this->auth->id,
                    'comment_note_ids' => json_encode([$id]),
                ]);
            } else {
                (new userLike)->where(['user_id' => $this->auth->id])->update(['comment_note_ids' => $user_like,]);
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
                // TODO 给评论者发点赞消息
            }

            $this->success(__('Operation completed'), $islike);
        }
    }


}

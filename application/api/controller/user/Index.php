<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-14 15:15:28
 * @FilePath       : \application\api\controller\user\Index.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-16 17:33:12
 * @Description    : 会员信息展示控制器
 */
namespace app\api\controller\user;
use app\common\controller\Api;
use think\Db;

use app\common\model\circle\Member;

/**
 * 会员信息展示控制器
 */
class Index extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    // // 展示状态类型
    // protected $show_status_type = [1, 2, 8, 9];

    // // 笔记主体参数
    // private $mianConfig = [
    //     'where' => [
    //         'status' => '1',
    //     ],
    //     // 'field' => 'id,circle_id,topic_id,user_id,days_num,content,group_id,pv_num,like_num,comment_num,share_num,location',
    //     'outField' => 'status,updatetime,deletetime',
    //     'order' => 'createtime desc,weigh desc,id desc',
    // ];

    // 初始化
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\User();

        $this->user_id = $this->request->request('user_id/d', 0, 'intval');
        if($this->user_id == 0){
            $this->error(__(''));
        }

    }

    /** 会员信息
     * @Author: hejiaz
     * @Date: 2020-12-16 14:22:14
     */
    public function info(){

        $userinfo = $this->model
            ->field('id,uuid_code,nickname,mobile,avatar,gender,birthday,wechat_code,bio,follow_num,fans_num,note_num,note_like_num,note_top_num,circle_showtype')
            ->where([
                'id'     => $this->user_id,
                'status' => 'normal',
            ])
            ->find();

        $circle_id = $this->request->request('circle_id/d', 0, 'intval');
        if($circle_id){
            // 获取会员 圈子成员信息
            $member = (new Member)->member($this->user_id, $circle_id);
        }

        $this->success(__(''), [
            'userinfo' => $userinfo,
            'member' => $member,
        ]);
    }

    /** 会员圈子列表
     * @Author: hejiaz
     * @Date: 2020-12-08 16:55:51
     */
    public function circle_list(){

        $where = [
            'user_id'    => $this->user_id,
            'isaudit'    => 1,
            'deletetime' => null,
        ];

        $circle_showtype = db('user')->where(['id'=>$this->user_id])->value('circle_showtype');
        if($circle_showtype == 2){
            // 仅获取设置展示圈子
            $where['showtype'] = 1;
        }

        // $mainModel = new mainModel();
        $data = (new Member)
            ->where($where)
            ->field('circle_id,status,showtype,notes_num,days_num')
            ->with('circle')
            ->order('updatetime desc, id desc')
            ->paginate($this->publicParams['shownum'])->toarray();
        $this->success('', $data);
    }

}

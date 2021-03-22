<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-14 15:15:28
 * @FilePath       : \application\api\controller\circle\Member.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-11 11:22:40
 * @Description    : 圈子成员相关控制器
 */
namespace app\api\controller\circle;
use app\common\controller\Api;
use think\Db;

use app\common\model\circle\Index as Circle;    // 圈子内容

/**
 * 圈子成员相关
 */
class Member extends Api
{
    protected $noNeedLogin = ['memberlist', 'adminlist', 'search'];
    protected $noNeedRight = [];

    // 成员信息参数
    private $memberConfig = [
        'field' => 'id,user_id,group_id,nickname,avatar,status,jointype,notes_num,days_num,createtime,lastissuetime',
        'order' => 'createtime desc,id desc',
    ];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\circle\Member();

        $this->circle_id = $this->request->request('circle_id/d', 0, 'intval');
        if($this->circle_id){
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
        }

    }

    /** 加入圈子
     * @Author: hejiaz
     * @Date: 2020-11-30 14:37:38
     */
    public function join(){
        if($this->circle_id == 0){
            $this->error(__('Error'));
        }

        $member = $this->model->member($this->auth->id, $this->circle_id, 0);
        switch ($member['isaudit']) {
            case 1:
                // 已加入圈子
                $this->error(__('Joined the circle'));
                break;
            case 2:
                // 待审核
                $this->error(__('The application has been submitted, please wait for review'));
                break;
            case 3:
                // 被拒绝 重新申请
            case 9:
                // 被移除 重新申请
                $this->circle['jointype'] = 2; // 加入判断是需要审核的
                break;

            default:

                break;
        }

        // dump($params);die;
        $deal = false;
        Db::startTrans();
        try {
            switch ($this->circle['jointype']) {
                case 1:
                    // 更新数据
                    $deal = $this->model->save([
                        'user_id'   => $this->auth->id,
                        'circle_id' => $this->circle_id,
                        // 'jointype' => 1,// 加入类型
                    ]);

                    // 增加圈子成员数
                    (new Circle)->where('id', $this->circle_id)->setInc('member_num');

                    // 提示文字
                    $tiptext = 'Join the circle successfully';
                    break;
                case 2:
                    if($member){
                        // 更新数据
                        $deal = $this->model->where(['id' => $member['id']])->update([
                            'isaudit'    => 2,
                            'createtime' => time(),
                            'updatetime' => time(),
                            // 'jointype' => 1,// 加入类型
                        ]);
                    }else{
                        // 更新数据
                        $deal = $this->model->save([
                            'user_id'   => $this->auth->id,
                            'circle_id' => $this->circle_id,
                            'isaudit'   => 2,
                            // 'jointype' => 1,// 加入类型
                        ]);
                    }
                    $tiptext = 'Submitted successfully. Please wait for review';
                    break;
                case 3:
                    // 付费加入

                    break;
                default:

                    break;
            }

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
            $this->success(__($tiptext));
        }
    }

    /** 成员列表
     * @Author: hejiaz
     * @Date: 2020-11-30 15:37:28
     */
    public function memberlist(){
        if($this->circle_id == 0){
            $this->error(__('Error'));
        }

        // TODO 排序
        // $this->publicParams['order']
        // $this->publicParams['sort']

        $data = $this->model
            ->where([
                'circle_id' => $this->circle_id,
                'isaudit' => 1,
                'status' => 'member',
            ])
            ->with('user')
            ->field($this->memberConfig['field'])
            ->order($this->memberConfig['order'])
            ->paginate($this->publicParams['shownum'])->toarray();

        $this->success(__('Member list'), $data);
    }

    /** 圈主/管理员列表
     * @Author: hejiaz
     * @Date: 2020-11-30 16:19:10
     */
    public function adminlist(){
        if($this->circle_id == 0){
            $this->error(__('Error'));
        }

        $data = $this->model
            ->where([
                'circle_id' => $this->circle_id,
                'isaudit' => 1,
                'status' => ['in','admin'],
            ])
            ->with('user')
            ->field($this->memberConfig['field'])
            ->order('updatetime desc,id desc')
            ->select();

        $master = $this->model
            ->where([
                'circle_id' => $this->circle_id,
                'status' => 'master',
            ])
            ->with('user')
            ->field($this->memberConfig['field'])
            ->find();

        // 圈主信息插入列表最上面
        array_unshift($data, $master);

        // 转换格式
        // if ($data) {
        //     $data = collection($data)->toArray();
        // }

        $this->success(__('Master/administrator list'), $data);
    }

    /** 搜索列表
     * @Author: hejiaz
     * @Date: 2020-11-30 17:35:01
     */
    public function search(){
        if($this->circle_id == 0){
            $this->error(__('Error'));
        }

        // TODO 排序


        $data = $this->model
            ->where([
                'circle_id' => $this->circle_id,
                'isaudit' => 1,
                ''
            ])
            ->with('user')
            ->field($this->memberConfig['field'])
            ->order($this->memberConfig['order'])
            ->paginate($this->publicParams['shownum'])->toarray();

        dump($data);

        die;
        $this->success(__('Search list'), $data);
    }

}

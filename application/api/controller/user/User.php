<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-14 15:15:28
 * @FilePath       : \application\api\controller\user\User.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-15 16:40:46
 * @Description    : 会员信息控制器
 */
namespace app\api\controller\user;
use app\common\controller\Api;
use think\Db;

/**
 * 会员信息控制器
 */
class User extends Api
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
        // $this->model = new \app\common\model\note\Index();

        // $this->circle_id = $this->request->request('circle_id/d',0,'intval');
        // // TODO 验证是否加入该圈子
        // $this->member = (new Member)->member($this->auth->id, $this->circle_id);
        // if($this->circle_id){
        //     if(!$this->member || $this->member['isaudit'] != 1){
        //         dump($member['isaudit']);
        //         die;
        //         $this->error(__('Not to join'));
        //     }
        // }

    }

    /** 会员信息
     * @Author: hejiaz
     * @Date: 2020-12-07 09:57:05
     */
    public function userinfo(){
        $data = $this->auth->getUserinfo();
        // toarray($data);
        // dump($data);
        $this->success('', $data);
    }

    /** 编辑信息
     * @Author: hejiaz
     * @Date: 2020-12-07 10:37:25
     */
    public function editinfo(){
        $field = $this->request->request('field/s', '', 'trim');
        $value = $this->request->request('value/s', '', 'trim');

        // 保存信息
        $params = [
            $field => $value
        ];

        switch ($field) {
            case 'avatar':
                // 头像 判断是否是文件格式
            case 'nickname':
                // 昵称

                // 必填
                if($value == ''){
                    $this->error(__('Data is empty'));
                }
                break;

            case 'gender':
                // 性别
            case 'circle_showtype':
                // 展示类型
                $params[$field] = intval($value);

                break;

            case 'wechat_code':
                // 微信号
            case 'birthday':
                // 生日
            case 'bio':
                // 签名
                if($value == ''){
                    $this->success(__('Data is empty'));
                }
                break;

            case 'area':
                unset($params);
                // 地区
                $area = explode('-', $value);

                $params = [
                    'province' => $area[0],
                    'city'     => $area[1],
                    'area'     => $area[2],
                ];
                break;

            default:
                $this->error(__('Parameter error'));
                break;
        }

        // dump($params);die;

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            $deal = db('user')->where(['id'=>$this->auth->id])->update($params);

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
            // 更新会员信息

            $this->success(__('Operation completed'), $params);
        }
    }

    /** 设置圈子展示状态
     * @Author: hejiaz
     * @Date: 2020-12-09 16:47:44
     */
    public function set_circle_showtype(){
        $show_circle_ids = $this->request->request('show_circle_ids/s', '', 'trim');
        $hide_circle_ids = $this->request->request('hide_circle_ids/s', '', 'trim');

        $show_circle_ids = explode(',', $show_circle_ids);
        $hide_circle_ids = explode(',', $hide_circle_ids);

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            if($show_circle_ids){
                $deal = db('circle_member')->where([
                    'user_id'   => $this->auth->id,
                    'circle_id' => ['in', $show_circle_ids],
                    'showtype'  => 2,
                ])->update(['showtype' => 1]);
            }

            if($hide_circle_ids){
                $deal = db('circle_member')->where([
                    'user_id'   => $this->auth->id,
                    'circle_id' => ['in', $hide_circle_ids],
                    'showtype'  => 1,
                ])->update(['showtype' => 2]);
            }

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
            $this->success(__('Operation completed'));
        }
    }


    /** 会员点赞合集
     * @Author: hejiaz
     * @Date: 2020-12-08 10:13:20
     */
    public function likeids(){
        $data =  (new \app\common\model\user\Like)->get($this->auth->id)->toarray();
        $this->success('', $data);
    }




}

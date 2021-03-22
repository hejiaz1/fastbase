<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-14 15:15:28
 * @FilePath       : \application\api\controller\circle\Manage.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-21 13:59:46
 * @Description    : 圈子管理编辑控制器
 */
namespace app\api\controller\circle;
use app\common\controller\Api;
use think\Db;

use app\common\model\circle\Content;    // 圈子内容
use app\common\model\circle\Member;     // 圈子成员

/**
 * 圈子管理编辑
 */
class Manage extends Api
{
    protected $noNeedLogin = [];
    protected $noNeedRight = [];

    // 不验证权限方法 自定义
    protected $passAction = ['creation', 'edit_nickname'];

    // 编辑详情字段
    private $contentField = ['detail', 'pay', 'master'];

    // 详情信息类型
    private $contentType = ['text', 'image', 'voice', 'video'];

    // 可访问身份
    private $pass_status = ['master','admin', 'member' ];


    public function _initialize()
    {
        parent::_initialize();
        // $this->model = new \app\common\model\circle\Member();
        $this->model = new \app\common\model\circle\Index();

        // 除创建圈子都要身份判断
        $action = strtolower($this->request->action());

        if(!in_array($action, $this->passAction)){

            // 圈子信息
            $this->id = $this->request->request('id/d', 0, 'intval');
            if($this->id == 0){     $this->error(__('Parameter error'));}

            $this->circle = $this->model
                ->where([
                    'id'     => $this->id,
                    'status' => 'normal',
                ])
                ->field('master_id,jointype')
                ->find();
            // 圈子不存在
            if(!$this->circle){
                $this->error(__('Circle don\'t exist'));
            }

            // TODO 超管身份

            // 成员身份
            $this->member = (new Member)->member($this->auth->id, $this->id);

            if(!$this->member){
                $this->error(__('No access'));
            }else{
                // 判断身份
                if(!in_array($this->member['status'], $this->pass_status)){
                    $this->error(__('You have no permission'));
                }
            }

        }
    }


    /** 创建圈子
     * @Author: hejiaz
     * @Date: 2020-10-21 16:05:03
     */
    public function creation(){
        $name = $this->request->request('name/s', '');
        if ($name == '') {
            $this->error(__('Please fill in the name'));
        }
        // 过滤名称
        mask_words($name);

        $jointype = $this->request->request('jointype/d',1);
        if($jointype == 2){
            // TODO 验证当前会员是否有资格创建需审核计入的圈子

        }

        // 获取圈子默认头图
        $circle_default_head = \think\Config::get("site")['circle_default_head'];

        // TODO 合并一些默认写死的图片 保证没有空值
        // $default_head = [];
        // $circle_default_head = array_merge($circle_default_head, $default_head);

        $head_image = array_rand($circle_default_head,1);

        $params = [
            'master_id'  => $this->auth->id,
            'jointype'   => $jointype,
            'name'       => $name,
            'head_image' => $circle_default_head[$head_image],
        ];


        // TODO 查询创建上一个圈子的时间 判断时间间隔


        $deal = false;

        $memberModel  = new Member();
        $contentModel = new Content();

        Db::startTrans();
        try {
            $deal = $this->model->save($params);

            // 添加成员记录
            $member_deal = $memberModel->save([
                'user_id'   => $this->auth->id,
                'circle_id' => $this->model->id,
                'status'    => 'master', // 圈主
            ]);

            // 添加圈子详情
            $content_deal = $contentModel->save([
                'circle_id' => $this->model->id,
            ]);

            // 圈子配置信息
            db('circle_config')->insert(['circle_id' => $this->model->id]);

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Create a failure'));
        } else {
            $this->success(__('Creating a successful'), $this->model->id);
        }
    }

     /** 编辑本圈子昵称
     * @Author: hejiaz
     * @Date: 2020-10-29 17:56:29
     * @Param: $id  圈子ID
     * @Param: $nickname  昵称
     */
    public function edit_nickname(){
        $id = $this->request->request("id/d",0);
        if($id == 0){
            $this->error(__('Error'));
        }
        $member = (new Member)->member($this->auth->id, $id);
        if(!$member){
            $this->error(__('Not to join'));
        }

        $nickname = $this->request->request('nickname/s','','trim');
        if($nickname == ''){
            $this->error(__('Error'));
        }

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            $deal = (new Member)->save(['nickname'=>$nickname], ['id'=> $member['id']]);

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


    /** 圈子 改名
     * @Author: hejiaz
     * @Date: 2020-10-29 10:21:33
     */
    public function rename(){

        $name = $this->request->request('name/s', '');
        if ($name == '') {
            $this->error(__('Please fill in the name'));
        }
        mask_words($name);

        // 更新参数
        $params = [
            'name' => $name,
        ];

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            $deal = $this->model->save($params, ['id'=> $this->id]);

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
            // TODO 圈子更新记录

            $this->success(__('Operation completed'), $id);
        }
    }

    /** 更新圈子详情信息
     * @Author: hejiaz
     * @Date: 2020-10-29 11:04:01
     */
    public function edit_content(){

        $stype = $this->request->request('stype/s','','trim');
        if($stype == ''){
            $this->error(__('Error'));
        }
        if(!in_array($stype, $this->contentField)){
            $this->error(__('Type error'));
        }

        // 圈主信息编辑只有详情
        if($stype == 'master'){
            $master_wx = $this->request->request('master_wx/s','','trim');
            $content = $this->request->request("content");
            // 更新参数
            $params = [
                'master_content' => $content,
            ];

        }else{

            $content = $this->request->request("content/a",[]);
            if($content){
                foreach($content as $value){
                    if(!in_array($value['type'], $this->contentType)){
                        $this->error(__('Type error'));
                    }
                    if($value['value'] == ''){
                        $this->error(__('Data is empty'));
                    }
                }
            }
            // 更新参数
            $params = [
                $stype . '_content' => json_encode($content),
            ];
        }

        $deal = false;
        Db::startTrans();
        try {
            if($stype == 'master' && $master_wx){
                $this->model->save(['master_wx' => $master_wx], ['id'=> $this->id]);
            }

            // 更新数据
            $deal = (new Content)->save($params, ['circle_id'=> $this->id]);

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
            // TODO 圈子更新记录

            $this->success(__('Operation completed'), $id);
        }

    }

    /** 更新圈子公告信息
     * @Author: hejiaz
     * @Date: 2020-10-29 11:04:01
     */
    public function edit_notice(){

        $content = $this->request->request('content/s', '', 'trim');
        if($content == ''){
            $this->error(__('Error'));
        }

        // 更新参数
        $params = [
            'notice' => $content,
        ];

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            $deal = (new Content)->save($params, ['circle_id'=> $this->id]);

            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
            // TODO 圈子更新记录

            $this->success(__('Operation completed'), $id);
        }

    }

    /** 更新圈子头图
     * @Author: hejiaz
     * @Date: 2020-10-29 17:59:29
     * @Param: $param1
     * @Return: mixed array json boole
     */
    public function edit_head_image(){

        $head_image = $this->request->request('head_image/s', '');
        if($head_image == ''){
            $this->error(__('Data is empty'));
        }

        // TODO 判断图片文件合法性

        $deal = false;
        Db::startTrans();
        try {
            $deal = $this->model->save(['head_image' => $head_image], ['id'=> $this->id]);
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }

        if ($deal == false) {
            $this->error(__('Operation failed'));
        } else {
            // TODO 圈子更新记录

            $this->success(__('Operation completed'), $id);
        }

    }




}

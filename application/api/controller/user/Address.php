<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-14 15:15:28
 * @FilePath       : \application\api\controller\user\Address.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-05-08 17:09:58
 * @Description    : 会员地址控制器
 */
namespace app\api\controller\user;
use app\common\controller\Api;
use think\Db;
use think\Validate;

/**
 * 会员地址控制器
 */
class Address extends Api
{
    protected $noNeedLogin = [];
    protected $noNeedRight = '*';

    // 初始化
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\user\Address();
    }

    /**
     * 验证收货手机号 地区信息
     * @Author: hejiaz
     * @Date: 2021-05-08 11:33:08
     * @param {*} $params
     */
    public function checkinfo($params){

        // 判断用户地址总量
        $count = $this->model->where(['user_id'=>$this->auth->id])->count();
        if($count > 20){
            $this->error(__('Only 20 addresses can be saved, please delete and add'));
        }

        if($params['name'] == ''){
            $this->error(__('Must fill in name'));
        }
        if($params['mobile'] == ''){
            $this->error(__('Must fill in mobile phone'));
        }
        if (!Validate::regex($params['mobile'], "^1\d{10}$")) {
            $this->error(__('Mobile is incorrect'));
        }

        $areadb = db('area');
        $prov = $areadb->find($params['prov']);
        $city = $areadb->find($params['city']);
        $area = $areadb->find($params['area']);

        if(empty($prov) || empty($city) || empty($area)){
            $this->error(__('Area code error'));
        }

        if($params['address'] == ''){
            $this->error(__('The address must not be empty'));
        }

    }

    /**
     * 添加地址
     * @Author: hejiaz
     * @Date: 2021-05-06 17:07:18
     */
    public function add(){

        $params = [
            'user_id' => $this->auth->id,
            'name'    => $this->request->request('name/s', '', 'trim'),
            'mobile'  => $this->request->request('mobile/s', '', 'trim'),
            'prov'    => $this->request->request('prov/d', 0, 'intval'),
            'city'    => $this->request->request('city/d', 0, 'intval'),
            'area'    => $this->request->request('area/d', 0, 'intval'),
            'address' => $this->request->request('address/s', '', 'trim'),
            'isdefault'    => $this->request->request('isdefault/d', 2, 'intval'),
        ];

        // 判断手机号 省市区是否在数据库中
        $this->checkinfo($params);

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            $deal = $this->model->save($params);
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

    /**
     * 编辑地址
     * @Author: hejiaz
     * @Date: 2021-05-08 13:40:56
     */
    public function edit(){
        $id = $this->request->request('id/d', 0, 'intval');
        if($id == 0){
            $this->error(__('Parameter error'));
        }

        // 判断会员ID是否一致
        $row = $this->model->get($id);
        if(!$row){
            $this->error(__('Data is empty'));
        }

        if($row['user_id'] != $this->auth->id){
            $this->error(__('illegal operation'));
        }

        $params = [
            'user_id' => $this->auth->id,
            'name'    => $this->request->request('name/s', '', 'trim'),
            'mobile'  => $this->request->request('mobile/s', '', 'trim'),
            'prov'    => $this->request->request('prov/d', 0, 'intval'),
            'city'    => $this->request->request('city/d', 0, 'intval'),
            'area'    => $this->request->request('area/d', 0, 'intval'),
            'address' => $this->request->request('address/s', '', 'trim'),
            'isdefault'    => $this->request->request('isdefault/d', 2, 'intval'),
        ];

        // 判断手机号 省市区是否在数据库中
        $this->checkinfo($params);

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            $deal = $row->save($params);
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

    /**
     * 更改默认
     * @Author: hejiaz
     * @Date: 2021-05-08 14:50:03
     */
    public function edit_default(){
        $id = $this->request->request('id/d', 0, 'intval');
        if($id == 0){
            $this->error(__('Parameter error'));
        }

        // 判断会员ID是否一致
        $row = $this->model->get($id);
        if(!$row){
            $this->error(__('Data is empty'));
        }

        if($row['user_id'] != $this->auth->id){
            $this->error(__('illegal operation'));
        }

        if($row['isdefault'] == 1){
            $this->success('');
        }

        $params = [
            'user_id'   => $this->auth->id,
            'isdefault' => 1,
        ];

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            $deal = $row->save($params);
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


    /**
     * 地址列表
     * @Author: hejiaz
     * @Date: 2021-05-08 14:21:18
     */
    public function list(){

        $data = $this->model
            ->where(['user_id'=>$this->auth->id])
            ->field('weigh,createtime,updatetime',1)
            ->order('weigh desc,id desc')
            ->paginate($this->publicParams['shownum'])->toarray();

        $this->success($category['name'], $data);
    }

    /**
     * 详情
     * @Author: hejiaz
     * @Date: 2021-05-08 14:46:46
     */
    public function detail(){
        $id = $this->request->request('id/d', 0, 'intval');
        if($id == 0){
            $this->error(__('Parameter error'));
        }

        // 判断会员ID是否一致
        $row = $this->model->get($id);
        if(!$row){
            $this->error(__('Data is empty'));
        }

        if($row['user_id'] != $this->auth->id){
            $this->error(__('illegal operation'));
        }

        $this->success('', $row);
    }

    /**
     * 获取默认地址
     * @Author: hejiaz
     * @Date: 2021-05-08 14:49:31
     */
    public function default(){

        $row = $this->model
            ->where(['user_id'=>$this->auth->id])
            ->field('weigh,createtime,updatetime',1)
            ->order('isdefault desc,weigh desc,id desc')->find();
        if(!$row){
            $this->error(__('Data is empty'));
        }

        $this->success('', $row);
    }

    /**
     * 删除
     * @Author: hejiaz
     * @Date: 2021-05-08 16:34:02
     */
    public function delete(){
        $id = $this->request->request('id/d', 0, 'intval');
        if($id == 0){
            $this->error(__('Parameter error'));
        }

        // 判断会员ID是否一致
        $row = $this->model->get($id);
        if(!$row){
            $this->error(__('Data is empty'));
        }

        if($row['user_id'] != $this->auth->id){
            $this->error(__('illegal operation'));
        }

        $deal = false;
        Db::startTrans();
        try {
            // 更新数据
            $deal = $row->delete();
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






    // 自动验证片段 先留着
    public function asdasd(){
         if ($params) {
            $params = $this->preExcludeFields($params);

            if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                $params[$this->dataLimitField] = $this->auth->id;
            }
            $result = false;
            Db::startTrans();
            try {
                //是否采用模型验证
                if ($this->modelValidate) {
                    $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                    $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                    $this->model->validateFailException(true)->validate($validate);
                }
                $result = $this->model->allowField(true)->save($params);
                Db::commit();
            } catch (ValidateException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($result !== false) {
                $this->success();
            } else {
                $this->error(__('No rows were inserted'));
            }
        }

    }

}

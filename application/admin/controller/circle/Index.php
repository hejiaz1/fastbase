<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-14 15:15:28
 * @FilePath       : \application\admin\controller\circle\Index.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-11-23 17:36:58
 * @Description    : 圈子主体表
 */

namespace app\admin\controller\circle;
use think\Db;

use app\common\controller\Backend;
use app\common\model\circle\Member;     // 圈子成员
use app\common\model\circle\Content;    // 圈子内容

use app\common\model\circle\Index as mainModel; // 圈子主体模型
/**
 * 圈子主体
 *
 * @icon fa fa-circle
 */
class Index extends Backend
{

    /**
     * Index模型对象
     * @var \app\admin\model\circle\Index
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\circle\Index;
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("flagList", $this->model->getFlagList());
        $this->view->assign("starList", $this->model->getStarList());
    }

    public function import()
    {
        parent::import();
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
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                ->with("user")
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);

            // dump($this->model->getLastSql());
            // dump($list);
            // die;

            // 转换所属分类 分类名称
            if(!$list->isEmpty()){
                $list = addtion($list, ['category_ids']);
            }

            $result = array("total" => $list->total(), "rows" => $list->items());
            return json($result);
        }
        return $this->view->fetch();
    }


    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {


            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                // 成员 内容
                $memberModel = new Member();
                $contentModel = new Content();

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
                    // 添加主体
                    $result = $this->model->allowField(true)->save($params);

                    // 添加成员记录
                    $member_deal = $memberModel->save([
                        'user_id'   => $params['master_id'],
                        'circle_id' => $this->model->id,
                        'status'    => 'master',
                    ]);

                    // 添加圈子详情
                    $content_deal = $contentModel->save([
                        'circle_id' => $this->model->id,
                    ]);

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
                    // TODO 添加圈子成员变更记录 日志
                    // TODO 向圈主发一条消息

                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");

            // 成员
            $memberModel = new Member();

            if ($params) {
                $params = $this->preExcludeFields($params);
                // 分类ids 编码
                check_ids_encode($params['category_ids']);

                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }


                    // TODO 判断是否 更改圈主 处理圈子成员变更信息
                    // dump($row['master_id']);
                    // if($params['master_id'] != $row['master_id']){
                    //     $member_deal = $memberModel->save([
                    //         'user_id' => $params['master_id'],
                    //         'circle_id' => $this->model->id,
                    //     ]);
                    // }

                    // 保存更改信息
                    $result = $row->allowField(true)->save($params);
                    // dump($result);die;

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
                    // TODO 添加圈子成员变更记录 日志
                    // TODO 向圈主发一条消息

                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        // 分类ids 解码
        $row['category_ids'] = check_ids_decode($row['category_ids']);

        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /** 圈子详情
     * @Author: hejiaz
     * @Date: 2020-11-23 15:21:24
     */
    public function detail($ids)
    {
        // 获取详情
        $data = (new mainModel)
            ->with('content')
            ->find($ids);

        if (!$data) {
            $this->error(__('No Results were found'));
        }

        // 转换数据格式
        toarray($data, ['content.detail_content', 'content.pay_content']);
        // 附加关联字段数据
        addtion_one($data, ['category_ids']);

        // dump($data);
        // die;

        $this->view->assign("data", $data);
        return $this->view->fetch();
    }


    /**
     * 搜索下拉列表
     */
    public function searchlist()
    {
        $result = $this->model
            ->field('id,name')
            ->where(['status'=>'normal'])
            ->order('id,weigh', 'desc')
            ->select();

        $searchlist = [];
        foreach ($result as $key => $value) {
            $searchlist[] = ['id' => $value['id'], 'name' => $value['name']];
        }
        $data = ['searchlist' => $searchlist];
        $this->success('', null, $data);
    }


}

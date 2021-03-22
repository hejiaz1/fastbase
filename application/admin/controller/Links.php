<?php
/*
 * @Author         : hejiaz
 * @Date           : 2021-01-04 16:18:21
 * @FilePath       : \application\admin\controller\Links.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-01-05 15:46:03
 * @Description    : 链接管理控制器
 */

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 链接管理
 *
 * @icon fa fa-circle-o
 */
class Links extends Backend
{

    /**
     * Links模型对象
     * @var \app\admin\model\Links
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Links;
        $this->view->assign("statusList", $this->model->getStatusList());
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
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);

            if(!$list->isEmpty()){
                $list = addtion($list, ['category_id']);
            }

            $result = array("total" => $list->total(), "rows" => $list->items());
            return json($result);
        }
        return $this->view->fetch();
    }


}

<?php
/*
 * @Author         : hejiaz
 * @Date           : 2021-03-23 10:33:02
 * @FilePath       : \application\admin\controller\Article.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-07 15:07:11
 * @Description    :
 */

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 文章管理
 *
 * @icon fa fa-circle-o
 */
class Article extends Backend
{

    /**
     * Article模型对象
     * @var \app\admin\model\Article
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Article;
        $this->view->assign("flagList", $this->model->getFlagList());
        $this->view->assign("ishowList", $this->model->getIshowList());
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

            $map['ishow'] = $this->request->request("ishow");
            if ($map['ishow'] == "all" || $map['ishow'] == null) {
                unset($map['ishow']);
            }

            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            // dump($where);die;

            if(is_array($where)){
                $list = $this->model
                    ->where($where['where'])
                    ->whereor($where['whereor'])
                    ->where($map)
                    ->order($sort, $order)
                    ->paginate($limit);
            }else{
                $list = $this->model
                    ->where($where)
                    ->where($map)
                    ->order($sort, $order)
                    ->paginate($limit);
            }

            if(!$list->isEmpty()){
                $list = addtion($list, ['category_id']);
            }

            $result = array("total" => $list->total(), "rows" => $list->items());
            return json($result);
        }
        return $this->view->fetch();
    }

}

<?php
/*
 * @Author         : hejiaz
 * @Date           : 2021-01-11 16:25:24
 * @FilePath       : \application\admin\controller\cms\Column.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-01-15 16:20:49
 * @Description    : 栏目控制器
 */

namespace app\admin\controller\cms;

use app\common\controller\Backend;
use think\Db;
use fast\Tree;


/**
 * 前台栏目管理
 *
 * @icon fa fa-circle-o
 */
class Column extends Backend
{

    /**
     * Column模型对象
     * @var \app\admin\model\cms\Column
     */
    protected $model = null;
    protected $categorylist = [];
    protected $noNeedRight = ['selectpage'];


    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\cms\Column;
        $this->view->assign("statusList", $this->model->getStatusList());

        // TODO 栏目列表子级收纳

        // 获取栏目列表
        $tree = Tree::instance();
        $tree->init(collection($this->model
            ->order('weigh desc,id asc')
            // ->field('id,pid,name')
            ->select()
        )->toArray(), 'pid');

        $this->columnList = $tree->getTreeList($tree->getTreeArray(0), 'name');

        // // 循环列表赋值语言包 多语言用
        // foreach ($this->columnList as $k => &$v) {
        //     $v['name'] = __($v['name']);
        // }
        // unset($v);

        // 生成下拉菜单用的简化版
        $this->columndata = [0 => __('Topcolumn')];
        foreach ($this->columnList as $k => &$v) {
            $this->columndata[$v['id']] = $v['name'];
        }
        unset($v);
        $this->view->assign("columndata", $this->columndata);

    }

    public function import()
    {
        parent::import();
    }

    /** 动态加载栏目列表
     * Selectpage搜索
     * @internal
     */
    public function selectpage()
    {

        // $columndata[] = [
        //     'id'       => 0,
        //     'name'     => __('Nocolumn'),
        //     'haschild' => 0,
        // ];

        foreach($this->columnList as $key=>$value){
            $columndata[] = [
                'id'       => $value['id'],
                'pid'      => $value['pid'],
                'name'     => $value['name'],
                'type'     => $value['type'],
                'temp'     => $value['temp'],
                'tier'     => $value['tier'],
                'ishow'    => $value['ishow'],
                'haschild' => $value['haschild'],
            ];
        }

        // return json(['list' => $columndata, 'total' => count($columndata)]);

        $result = array("total" => count($columndata), "rows" => $columndata);
        return json($result);

        dump($columndata);
        die;


        // // 获取栏目列表
        // $tree = Tree::instance();
        // $tree->init(collection($this->model->order('weigh desc,id asc')->field('id,pid,name')->select())->toArray(), 'pid');
        // $columnList = $tree->getTreeList($tree->getTreeArray(0), 'name');

        // // 列表前插入顶级栏目
        // array_unshift($columnList, [
        //     'id' => 0, 'name' => __('Topcolumn')
        // ]);

        // return json(['list' => $columnList, 'total' => count($columnList)]);


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

        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            // 如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }

            // list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            // $list = $this->model
            //     ->where($where)
            //     ->order($sort, $order)
            //     ->paginate($limit);
            // $result = array("total" => $list->total(), "rows" => $list->items());



            $total = count($this->columnList);
            $result = array("total" => $total, "rows" => $this->columnList);


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
                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }

                if($params['pid'] == 0){
                    $params['path'] = ',';
                    $params['tier'] = 1;
                }else{
                    // 父级栏目
                    $pcolumn = $this->model->field('path,tier')->find($params['pid']);

                    // 判断最深层级
                    if($pcolumn['tier'] >= 4){
                        $this->error(__('Column level should not be more than 4 levels'));
                    }

                    // 生成栏目路径
                    $params['path'] = $pcolumn['path'] . $params['pid'] . ',';
                    $params['tier'] = $pcolumn['tier'] + 1;
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
            if ($params) {
                $params = $this->preExcludeFields($params);


                if($params['pid'] == 0){
                    $params['path'] = ',';
                    $params['tier'] = 1;
                }else{
                    if($params['pid'] == $ids){
                        $this->error(__('The parent of a column cannot be itself'));
                    }
                    // 父级栏目
                    $pcolumn = $this->model->field('path,tier')->find($params['pid']);

                    $path = trim($pcolumn['path'],',');
                    $pathArr = explode(',', $pcolumn['path']);
                    if(in_array($ids, $pathArr)){
                        $this->error(__('The parent of a column cannot be its own child'));
                    }

                    // TODO 判断当前栏目子级是否超出层级限制

                    // 判断最深层级
                    if($pcolumn['tier'] >= 4){
                        $this->error(__('Column level should not be more than 4 levels'));
                    }

                    // 生成栏目路径
                    $params['path'] = $pcolumn['path'] . $params['pid'] . ',';
                    $params['tier'] = $pcolumn['tier'] + 1;
                }

                // 判断是否需要更改所有子级路径
                if($row['path'] != $params['path']){
                    $editpath = 1;
                }

                // dump($editpath);
                // dump($params);die;

                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);

                    if($editpath){

                    }
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
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

}

<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-12-11 16:46:32
 * @FilePath       : \application\api\controller\content\Category.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-26 18:13:13
 * @Description    : 内容分类控制器
 */
namespace app\api\controller\content;

use app\common\controller\Api;

use fast\Tree; // 通用树形类

class Category extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    // 不获取类型方法
    protected $noNeedType = ['typelist'];

    private $type  = '';  // 分类类型
    private $pid   = 0;   // 父级ID
    private $pdata = [];  // 父级分类

    // 分类参数
    private $sqlConfig = [
        'where' => [
            'status' => 'normal',
        ],
        'field'    => 'id,pid,name,image,flag,createtime,updatetime',
        'outfield' => '',
        'order'    => 'weigh desc,id desc',
    ];

    // 初始化
    public function _initialize(){
        parent::_initialize();
        $this->model = new \app\common\model\Category();

        // 分类类型
        $this->type = $this->request->request('type/s', '', 'trim');

        if($this->type == '' && !in_array(strtolower($this->request->action()), $this->noNeedType)){
            $this->error(__('Parameter %s can not be empty', 'type'));
        }

        $this->sqlConfig['where']['type'] = $this->type;

        // 获取父级ID
        $this->pid = $this->request->request('pid/d', 0, 'intval');
        if($this->pid){
            // 判断父级是否存在
            $pdata = $this->model
                ->where($this->sqlConfig['where'])
                ->field($this->sqlConfig['field'])
                ->find($this->pid);

            if(!$pdata){
                $this->error(__('Parent classification does not exist'));
            }

            $this->pdata = $pdata;
        }

        $this->sqlConfig['where']['pid'] = $this->pid;
    }

    /** 分类类型列表
     * @Author: hejiaz
     * @Date: 2021-04-26 17:49:16
     */
    public function typelist(){
        $list = $this->model->getTypeList();
        $this->success('', $list);
    }

    /** 获取树状分类列表
     * @Author: hejiaz
     * @Date: 2021-04-26 16:50:51
     */
    public function treeArray(){
        // 销毁pid 保证原始数据完整
        unset($this->sqlConfig['where']['pid']);

        // 获取原始数据
        $rawList = $this->model
            ->where($this->sqlConfig['where'])
            ->field($this->sqlConfig['field'])
            ->order($this->sqlConfig['order'])
            ->select();

            // dump($rawList);

        if(!$rawList){
            $this->error(__('Data is empty'));
        }

        // 初始化树状模型
        $tree = Tree::instance();
        $tree->init(collection($rawList)->toArray(), 'pid');

        $list = $tree->getTreeArray(0);
        $this->success(__('All category'), $list);
    }

    /** 分类列表 父级子级
     * @Author: hejiaz
     * @Date: 2021-04-26 17:26:13
     */
    public function list(){

        $data['list'] = $this->model
            ->where($this->sqlConfig['where'])
            ->field($this->sqlConfig['field'])
            ->order($this->sqlConfig['order'])
            ->select();

        $data['parent'] = $this->pdata;

        $this->success('', $data);
    }

    /** 分类详情
     * @Author: hejiaz
     * @Date: 2021-04-26 18:08:47
     */
    public function detail(){
        $id = $this->request->request('id/d', 0, 'intval');
        if($id == 0){
            $this->error(__('Parameter error'));
        }

        $data = $this->model
            ->where($this->sqlConfig['where'])
            ->field($this->sqlConfig['field'])
            ->find($id);

        $this->success('', $data);
    }



}

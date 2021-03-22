<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-14 15:15:28
 * @FilePath       : \application\api\controller\circle\Index.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-24 15:26:56
 * @Description    : 圈子信息控制器 展示
 */
namespace app\api\controller\circle;
use app\common\controller\Api;

use app\common\model\Category as CategoryModel;
use app\common\model\circle\Index as mainModel;
use app\common\model\circle\Member;

use fast\Tree;  // 通用树形类

/**
 * 圈子信息
 */
class Index extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    // 分类参数
    private $categoryConfig = [
        'where' => [
            'type'   => 'circle',
            'status' => 'normal',
        ],
        'field' => 'id,pid,name,nickname,image,type,flag',
        'order' => 'weigh desc,id desc',
    ];

    // 圈子主体参数
    private $mianConfig = [
        'where' => [
            'status' => 'normal',
        ],
        'field' => 'id,master_id,name,keyword,intro_content,head_image,member_num,notes_num,vitality_num',
        'order' => 'weigh desc,id desc',
    ];

    // 圈子主体参数
    private $flagType = [
        'hot'       => '热门',
        'index'     => '首页推荐',
        'recommend' => '推荐'
    ];

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\common\model\circle\Index();
    }

    /** 圈子分类列表 全部
     * @Author: hejiaz
     * @Date: 2020-10-26 10:13:47
     */
    public function category(){

        // 获取原始数据
        $rawList = (new CategoryModel)
            ->where($this->categoryConfig['where'])
            ->field($this->categoryConfig['field'])
            ->order($this->categoryConfig['order'])
            ->select();
        if(!$rawList){
            $this->error(__('Data is empty'));
        }

        // 初始化树状模型
        $tree = Tree::instance();
        $tree->init(collection($rawList)->toArray(), 'pid');

        $list = $tree->getTreeArray(0);
        $this->success(__('All category'), $list);
    }

    /** 圈子分类列表 全部
     * @Author: hejiaz
     * @Date: 2020-10-26 15:36:59
     */
    public function category_list(){

        $pid = $this->request->request("pid/d");
        if($pid == 0){
            $this->error(__('Parameter error'));
        }

        $cmodel = (new CategoryModel);

        $this->categoryConfig['where']['id'] = $pid;
        $data['parent'] = $cmodel
            ->where($this->categoryConfig['where'])
            ->field($this->categoryConfig['field'])
            ->order($this->categoryConfig['order'])
            ->find();
        if(!$data['parent']){
            $this->error(__('Data is empty'));
        }

        unset($this->categoryConfig['where']['id']);
        $this->categoryConfig['where']['pid'] = $pid;
        $data['childlist'] = $cmodel
            ->where($this->categoryConfig['where'])
            ->field($this->categoryConfig['field'])
            ->order($this->categoryConfig['order'])
            ->select();

        $this->success($data['parent']['name'], $data);
    }

    /** 圈子分类列表 全部
     * @Author: hejiaz
     * @Date: 2020-10-26 15:36:59
     */
    public function category_flag(){

        // hot index recommend
        $flag = $this->request->request("flag/s");
        if($flag == ''){
            $this->error(__('Parameter %s can not be empty', 'flag'));
        }

        // $flagarray = $this->flagType;
        $flagkey = array_keys($this->flagType);
        if(!in_array($flag, $flagkey)){
            $this->error(__('Parameter error'));
        }

        $categoryConfig['where']['flag'] = ['like', '%' . $flag . '%'];
        $list = (new CategoryModel)
            ->where($this->categoryConfig['where'])
            ->field($this->categoryConfig['field'])
            ->order($this->categoryConfig['order'])
            ->select();

        $this->success($this->flagType[$flag], $list);
    }


    /** 圈子列表
     * @Author: hejiaz
     * @Date: 2020-10-26 16:28:51
     */
    public function list(){
        $cate_id = $this->request->request("cate_id/d");
        if($cate_id == 0){
            $this->error(__('Parameter error'));
        }

        $category = (new CategoryModel)->field($this->categoryConfig['field'])->find($cate_id);
        if(!$category){
            $this->error(__('Category is empty'));
        }

        // 父级分类获取子级分类
        if($category['pid'] == 0){
            $this->CategoryConfig['where']['pid'] = $cate_id;
            $cate_ids = (new CategoryModel)->where($this->CategoryConfig['where'])->column('id');
            $cate_ids[] = $cate_id;

            foreach($cate_ids as $k=>$val){
                $category_ids[] =  ['EQ', $val];
                $category_ids[] =  ['like', $val . ',%'];
                $category_ids[] =  ['like', '%,' . $val . ',%'];
                $category_ids[] =  ['like', '%,' . $val];
            }
        }else{
            $category_ids = [
                ['EQ', $cate_id],
                ['like', '%,' . $cate_id . ',%'],
            ];
        }

        $mainModel = new mainModel();
        $data = $mainModel
            ->where($this->mianConfig['where'])
            ->field($this->mianConfig['field'])
            ->where('category_ids', $category_ids, 'or')
            ->order($this->mianConfig['order'])
            ->paginate($this->publicParams['shownum'])->toarray();
        // 分类信息赋值
        $data['category'] = $category;

        $this->success($category['name'], $data);
    }

    /** 圈子主页
     * @Author: hejiaz
     * @Date: 2020-10-28 16:34:56
     * @Param: $param1
     * @Return: mixed array json boole
     */
    public function index(){
        $id = $this->request->request("id/d",0);
        if($id == 0){
            $this->error();
        }

        $data = (new mainModel)
            ->with(['master','content'])
            ->where($this->mianConfig['where'])
            ->field('weigh,createtime,updatetime,deletetime,status', true)
            ->find($id);
        if(!$data){
            $this->error(__('Data is empty'));
        }

        // 转换数据
        toarray($data, ['content.detail_content','content.pay_content']);

        if($this->auth->isLogin()){
            // 用户相对圈子成员信息
            $data['member'] = (new Member)->member($this->auth->id, $id);
            toarray($data['member']);
        }

        $this->success($data['name'], $data);
    }

    /** 我的圈子列表
     * @Author: hejiaz
     * @Date: 2020-12-04 14:51:12
     */
    public function my_list(){
        // 验证登录
        $this->checklogin();

        // $mainModel = new mainModel();
        $data = (new Member)
            ->where([
                'user_id'    => $this->auth->id,
                'isaudit'    => 1,
                'deletetime' => null,
            ])
            ->field('circle_id,nickname,status,showtype,notes_num,days_num')
            ->with('circle')
            ->order('updatetime desc, id desc')
            ->paginate($this->publicParams['shownum'])->toarray();

        $this->success('', $data);
    }




    public function asdasd(){
        echo 'post';
        dump($this->request->post());
        echo 'get';
        dump($this->request->get());

        echo 'reuqest';
        dump($this->request->request());

        echo 'input()';
        dump(input());

        echo __('All category');
        die;

    }
}

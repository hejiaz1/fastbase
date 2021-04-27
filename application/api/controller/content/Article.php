<?php
/*
 * @Author         : hejiaz
 * @Date           : 2021-04-27 17:43:46
 * @FilePath       : \application\api\controller\content\Article.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-27 17:45:10
 * @Description    : 文章信息
 */

namespace app\api\controller\content;

use app\common\controller\Api;
// use app\common\model\Category;

class Article extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    // 确认内容类型 表名/分类类型
    private $content_type = 'article';

    // 分类参数
    private $sqlConfig = [
        'where' => [
            'ishow' => 1,
        ],
        'field'    => 'id,category_id,title,list_image,pvnum,intro_content,createtime',
        'outfield' => 'admin_id,title1,title2,author,editor,source,source_href,releasetime,list_image_alt,list_image,show_image_alt,show_images,annex_files,extend_json,flag,content_content,ishow,seotitle,seokeywords,seodescription,weigh,updatetime,deletetime',
        'order'    => 'weigh desc,id desc',
    ];

    // 初始化
    public function _initialize(){
        parent::_initialize();
        $this->model = new \app\common\model\content\Article();

        $category_id = $this->request->request('category_id/d', 0, 'intval');
        if($category_id){

            $category = (new \app\common\model\Category)->obtain($category_id, $this->content_type);
            if(!$category){
                $this->error(__('Category error'));
            }
            $this->category = $category;
            $this->sqlConfig['where']['category_id'] = $category_id;

        }

    }

    /** 列表
     * @Author: hejiaz
     * @Date: 2021-04-27 10:25:36
     */
    public function list(){

        $data = $this->model
            ->where($this->sqlConfig['where'])
            ->field($this->sqlConfig['field'])
            ->order($this->sqlConfig['order'])
            ->paginate($this->publicParams['shownum'])->toarray();
        $this->success($this->category, $data);

    }

    /** 详情
     * @Author: hejiaz
     * @Date: 2021-04-27 11:09:30
     */
    public function detail(){
        $id = $this->request->request('id/d', 0, 'intval');
        if($id == 0){
            $this->error(__('Parameter error'));
        }

        $data = $this->model
            ->where($this->sqlConfig['where'])
            ->field($this->sqlConfig['outfield'], 1)
            ->find($id);

        // 浏览量
        if(addpvnum($this->content_type)){
            $this->model->where('id', $id)->setInc('pvnum');
        }

        // 转换图片路径
        convert_image_path($data, 'detail_content');

        $this->success('', $data);
    }

    /** 纯文本详情
     * @Author: hejiaz
     * @Date: 2021-04-27 17:32:21
     */
    public function plain_text(){
        $id = $this->request->request('id/d', 0, 'intval');
        if($id == 0){
            $this->error(__('Parameter error'));
        }

        $data = $this->model
            ->where($this->sqlConfig['where'])
            ->field('id,title,detail_content')
            ->find($id);

        $data['detail_content'] = strip_tags($data['detail_content']);

        // 浏览量
        if(addpvnum($this->content_type)){
            $this->model->where('id', $id)->setInc('pvnum');
        }

        $this->success('', $data);

    }
}

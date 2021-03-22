<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-12-11 16:46:32
 * @FilePath       : \application\api\controller\Search.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-18 17:56:46
 * @Description    : 搜索控制器
 */
namespace app\api\controller;

use app\common\controller\Api;

/**
 * 搜索控制器
 */
class Search extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';


    public function _initialize(){
        parent::_initialize();
        // $this->model = new \app\common\model\circle\Index();

        $this->kwords = $this->request->request('kwords/s', '', 'trim');
        if($this->kwords == ''){
            $this->error(__(''));
        }

    }

    /** 圈子列表
     * @Author: hejiaz
     * @Date: 2020-12-11 16:54:38
     */
    public function circle(){

        $where = [
            'name' => [
                'like', '%'.$this->kwords.'%'
            ],
        ];
        // dump($where);

        $data = (new \app\common\model\circle\Index)
            ->where($where)
            // ->where('category_ids', $category_ids, 'or')
            ->where(['status' => 'normal'])
            ->field('id,master_id,name,keyword,intro_content,head_image,member_num,notes_num,vitality_num')
            ->order('weigh desc,id desc')
            ->paginate($this->publicParams['shownum'])->toarray();

        // dump($data);die;
        $this->success($this->kwords, $data);
    }

    /** 笔记列表
     * @Author: hejiaz
     * @Date: 2020-12-14 11:28:21
     */
    public function note(){

        $where = [
            'content' => [
                'like', '%'.$this->kwords.'%'
            ],
        ];

        $data = (new \app\common\model\note\Index)->alias('n')
            ->join('circle c', 'n.circle_id=c.id','right')
            ->where([
                'n.show_status' => 1,
                'n.status'      => 1,
                'n.deletetime'  => null,
                'c.deletetime'  => null,
                'c.status'      => 'normal',
            ])
            ->where($where)
            ->order('c.weigh desc,n.is_top desc,n.weigh desc,n.createtime desc,n.id desc')
            ->field('n.*') // 取所有笔记字段 连表查询无法过滤
            ->with('annex,circle,user,member,comment')
            ->paginate($this->publicParams['shownum'])->toarray();

        // 赋值点赞信息
        (new \app\common\model\like\Note)->assign_like_user($data['data']);

        $this->success($circle, $data);
    }

    /** 会员列表
     * @Author: hejiaz
     * @Date: 2020-12-14 17:55:20
     */
    public function user(){

        $where = [
            'nickname' => [
                'like', '%'.$this->kwords.'%'
            ],
        ];

        // 获取关键字的位置：LOCATE(关键字,字段) 返回索引位置
        $field = 'id,uuid,uuid_code,nickname,avatar,gender,bio
            ,follow_num,fans_num,note_num,note_like_num,note_top_num
            ,LOCATE("'.$this->kwords.'",nickname) as nicknameIndex';

        $data = (new \app\common\model\User)
            ->where([
                'status' => 'normal',
            ])
            ->where($where)
            ->field($field)
            ->order('nicknameIndex asc,fans_num desc,note_like_num desc')//按tIndex 值排序
            ->paginate($this->publicParams['shownum'])->toarray();

        // dump($data);die;

        $this->success($this->kwords, $data);
    }
}

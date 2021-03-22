<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-21 16:28:07
 * @FilePath       : \application\common\model\like\Note.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-14 17:53:03
 * @Description    : 笔记点赞模型
 */
namespace app\common\model\like;

use think\Model;

class Note extends Model
{

    // 表名
    protected $name = 'like_note';
    // protected $pk = 'note_id';

    /** 获取点赞会员IDs
     * @Author: hejiaz
     * @Date: 2020-12-14 14:42:11
     * @Param: $note_id     笔记ID
     * @Return: json
     */
    public function get_like_uids($note_id){
        $like_uids = $this->where(['note_id'=>$note_id])->value('like_uids');
        if($like_uids){
            return json_decode($like_uids, true);
        }
        return '';
    }

    /** 获取
     * @Author: hejiaz
     * @Date: 2020-12-14 14:56:14
     * @Param: $data    笔记列表
     * @Return: mixed array json boole
     */
    public function assign_like_user(&$data, $isloop=1){
        if($isloop == 1){
            // 循环获取点赞 评论信息
            foreach($data as $key=>&$value){
                $like_uids = $this->get_like_uids($value['id']);
                if($like_uids){
                    // 获取成员信息
                    foreach($like_uids as $k=>$val){
                        $value['like'][$k]['user_id'] = $val;
                        $value['like'][$k]['user']    = (new \app\common\model\info\User)->user($val);
                        $value['like'][$k]['member']  = (new \app\common\model\info\Member)->member($val,$circle_id);
                    }
                }
            }
        }else{
            $like_uids = $this->get_like_uids($data['id']);

            if($like_uids){
                // 获取成员信息
                foreach($like_uids as $key=>$val){
                    $data['like'][$key]['user_id'] = $val;
                    $data['like'][$key]['user']    = (new \app\common\model\info\User)->user($val);
                    $data['like'][$key]['member']  = (new \app\common\model\info\Member)->member($val,$data['circle_id']);
                }
            }
        }

    }
}

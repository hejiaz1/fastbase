<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-20 16:48:45
 * @FilePath       : \application\common\model\user\Like.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-08 10:38:26
 * @Description    : 会员点赞/收藏模型
 */

namespace app\common\model\user;

use think\Model;

/**
 * 会员点赞/收藏模型
 */
class Like extends Model
{
    // 表名
    protected $name = 'user_like';
    // 主键
    protected $pk = 'user_id';

    /** 点赞 获取ids
     * @Author: hejiaz
     * @Date: 2020-12-03 15:50:58
     * @Param: $field 指定字段
     * @Param: $user_id 会员ID
     * @Param: $like_id 点赞/取消点赞ID
     * @Return: boole
     */
    public function like($field, $user_id, $like_id){
        $data = $this->where('user_id', $user_id)->find();

        if($data){
            $like_ids_data = $data[$field . '_ids'];
            $like_ids_arr = json_decode($like_ids_data, true);

            if($like_ids_data && $like_ids_arr){
                if(in_array($like_id, $like_ids_arr)){
                    // 定位键名 去除值
                    $k = array_search($like_id, $like_ids_arr);
                    unset($like_ids_arr[$k]);

                    $like_ids_arr = array_values($like_ids_arr);
                }else{
                    // 在前面插入数据保持更新
                    array_unshift($like_ids_arr, $like_id);
                }
                // dump($like_ids_arr);

                return json_encode($like_ids_arr);
            }else{
                return json_encode([$like_id]);
            }
        }else{
            return 'add';
        }
    }

}

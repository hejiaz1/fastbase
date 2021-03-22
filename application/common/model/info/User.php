<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-20 16:48:45
 * @FilePath       : \application\common\model\info\User.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-10 14:15:51
 * @Description    : 会员基础信息模型 就取值
 */

namespace app\common\model\info;

use think\Model;

/**
 * 会员基础信息模型
 */
class User extends Model
{
    // 表名
    protected $name = 'user';

    /**
     * 展示字段
     * @var array
     */
    protected $visible = [
        'uuid_code','nickname','avatar',
    ];

    /** 获取头像
     * @param   string $value
     * @param   array  $data
     * @return string
     */
    public function getAvatarAttr($value, $data)
    {
        if (!$value) {
            //如果不需要启用首字母头像，请使用
            //$value = '/assets/img/avatar.png';
            $value = letter_avatar($data['nickname']);
        }
        return $value;
    }

    /** 成员信息
     * @Author: hejiaz
     * @Date: 2020-10-28 17:38:35
     * @Param: $user_id 会员ID
     * @Return: array
     */
    public function user($user_id){

        $data = $this->find($user_id);

        toarray($data);
        return $data;
    }


}

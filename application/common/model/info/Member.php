<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-20 16:48:45
 * @FilePath       : \application\common\model\info\Member.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-04 14:19:35
 * @Description    : 会员基础信息模型 就取值
 */

namespace app\common\model\info;

use think\Model;

/**
 * 会员基础信息模型
 */
class Member extends Model
{
    // 表名
    protected $name = 'circle_member';

    /**
     * 展示字段
     * @var array
     */
    protected $visible = [
        'group_id','nickname','avatar','status',
        // id,group_id,nickname,status,isaudit
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
            // $value = '/assets/img/avatar.png'; // 固定图片
            // $value = letter_avatar($data['nickname']); // 昵称首字母
        }
        return $value;
    }

    /** 成员信息
     * @Author: hejiaz
     * @Date: 2020-10-28 17:38:35
     * @Param: $user_id 会员ID
     * @Param: $circle_id 圈子ID
     * @Param: $isaudit     加入状态 默认加入
     * @Return: array
     */
    public function member($user_id, $circle_id, $isaudit=1){
        $where = [
            'user_id'    => $user_id,
            'circle_id'  => $circle_id,
            'deletetime' => null
        ];

        if($isaudit){
            $where['isaudit'] = $isaudit;
        }

        $data = $this->where($where)->field('id,group_id,nickname,status,isaudit')->find();

        toarray($data);
        return $data;
    }

}

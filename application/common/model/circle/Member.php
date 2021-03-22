<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-21 16:28:07
 * @FilePath       : \application\common\model\circle\Member.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-04 14:13:35
 * @Description    : 圈子成员模型
 */
namespace app\common\model\circle;

use think\Model;

class Member extends Model
{

    // 表名
    protected $name = 'circle_member';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';


    /** 关联会员信息
     * @Author: hejiaz
     * @Date: 2020-11-27 18:07:10
     */
    public function user()
    {
        return $this->hasOne('\app\common\model\info\User', 'id', 'user_id');
    }

    /** 关联圈子信息
     * @Author: hejiaz
     * @Date: 2020-12-04 13:57:27
     * @Param: $param1
     * @Return: mixed array json boole
     */
    public function circle(){
        return $this->hasOne('\app\common\model\info\Circle', 'id', 'circle_id');
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

    /** 获取用户于该圈子累计天数
     * @Author: hejiaz
     * @Date: 2020-11-02 17:20:33
     * @Param: $user_id 会员
     * @Param: $circle_id
     * @Return: mixed
     */
    public function getDaysNum($user_id, $circle_id){
        return $this->where([
            'user_id'    => $user_id,
            'circle_id'  => $circle_id,
            'deletetime' => null
        ])
        ->value('days_num');
    }


}

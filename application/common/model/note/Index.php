<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-21 16:28:07
 * @FilePath       : \application\common\model\note\Index.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-14 17:01:09
 * @Description    :
 */
namespace app\common\model\note;

use think\Model;
use traits\model\SoftDelete;

class Index extends Model
{
    use SoftDelete;

    // 表名
    protected $name = 'note';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // // 追加属性
    // protected $append = [
    //     'flag_text',
    //     'star_text'
    // ];


    // protected static function init()
    // {
    //     self::afterInsert(function ($row) {
    //         $pk = $row->getPk();
    //         $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
    //     });
    // }


    // public function getFlagList()
    // {
    //     return ['index' => __('Flag index'), 'hot' => __('Flag hot'), 'recommend' => __('Flag recommend')];
    // }

    // public function getStarList()
    // {
    //     return ['1' => __('Star 1'), '2' => __('Star 2'), '3' => __('Star 3'), '4' => __('Star 4'), '5' => __('Star 5')];
    // }


    // public function getFlagTextAttr($value, $data)
    // {
    //     $value = $value ? $value : (isset($data['flag']) ? $data['flag'] : '');
    //     $valueArr = explode(',', $value);
    //     $list = $this->getFlagList();
    //     return implode(',', array_intersect_key($list, array_flip($valueArr)));
    // }


    // public function getStarTextAttr($value, $data)
    // {
    //     $value = $value ? $value : (isset($data['star']) ? $data['star'] : '');
    //     $list = $this->getStarList();
    //     return isset($list[$value]) ? $list[$value] : '';
    // }

    // protected function setFlagAttr($value)
    // {
    //     return is_array($value) ? implode(',', $value) : $value;
    // }


    /** 关联笔记附件信息
     * @Author: hejiaz
     * @Date: 2020-10-22 11:54:26
     */
    public function annex()
    {
        return $this->hasOne(Annex);
    }

    /** 关联圈子信息
     * @Author: hejiaz
     * @Date: 2020-12-14 16:59:51
     */
    public function circle()
    {
        return $this->hasOne('\app\common\model\info\Circle', 'id', 'circle_id');
    }

    /** 关联会员信息
     * @Author: hejiaz
     * @Date: 2020-11-27 18:07:10
     */
    public function user()
    {
        return $this->hasOne('\app\common\model\info\User', 'id', 'user_id');
    }

    /** 关联圈子成员信息
     * @Author: hejiaz
     * @Date: 2020-11-27 18:06:47
     */
    public function member()
    {
        return $this->hasOne('app\common\model\info\Member', 'id', 'member_id');
    }

    /** 关联笔记评论信息
     * @Author: hejiaz
     * @Date: 2020-11-27 18:06:47
     */
    public function comment()
    {
        return $this->hasMany('app\common\model\comment\Note', 'note_id', 'id')
            ->where('status',1)
            ->where('pid',0)
            ->field('circle_id,updatetime,deletetime,status', true)
            ->limit(2)
            ->with('annex,user,member,revertUser,revertMember')
            ->order('is_hot desc,id desc');

    }




    /** 获取本圈子最后一条笔记
     * @Author: hejiaz
     * @Date: 2020-11-03 10:36:32
     * @Param: $user_id     会员编号
     * @Param: $circle_id   圈子编号
     * @Return: array
     */
    public function getLastNote($user_id, $circle_id=0){
        return $this->where([
            'circle_id' => $circle_id,
            'user_id'   => $user_id,
        ])->order('createtime desc')->find();
    }


}

<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-21 16:28:07
 * @FilePath       : \application\common\model\circle\Index.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-24 15:31:49
 * @Description    :
 */
namespace app\common\model\circle;

use think\Model;
use traits\model\SoftDelete;

class Index extends Model
{
    use SoftDelete;

    // 表名
    protected $name = 'circle';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'flag_text',
        'star_text'
    ];

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }


    public function getFlagList()
    {
        return ['index' => __('Flag index'), 'hot' => __('Flag hot'), 'recommend' => __('Flag recommend')];
    }

    public function getStarList()
    {
        return ['1' => __('Star 1'), '2' => __('Star 2'), '3' => __('Star 3'), '4' => __('Star 4'), '5' => __('Star 5')];
    }


    public function getFlagTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['flag']) ? $data['flag'] : '');
        $valueArr = explode(',', $value);
        $list = $this->getFlagList();
        return implode(',', array_intersect_key($list, array_flip($valueArr)));
    }


    public function getStarTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['star']) ? $data['star'] : '');
        $list = $this->getStarList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setFlagAttr($value)
    {
        return is_array($value) ? implode(',', $value) : $value;
    }


    /** 圈主信息
     * @Author: hejiaz
     * @Date: 2020-10-22 11:54:26
     */
    public function master()
    {
        return $this->hasOne('\app\common\model\info\User', 'id', 'master_id');
    }

    /** 关联详情信息
     * @Author: hejiaz
     * @Date: 2020-10-22 11:54:26
     */
    public function content()
    {
        return $this->hasOne(Content);
    }

    /** 获取圈子基本信息
     * @Author: hejiaz
     * @Date: 2020-11-03 17:19:39
     * @Param: $id  圈子ID
     * @Return: mixed
     */
    public function getBaseInfo($id){
        $data = $this->where('status','normal')->field('id,name,head_image,member_num,notes_num')->find($id);
        toarray($data);
        return $data;
    }


}

<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-14 15:15:28
 * @FilePath       : \application\admin\model\circle\Index.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-11-23 16:48:23
 * @Description    : 圈子主体表模型
 */

namespace app\admin\model\circle;

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
        'status_text',
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

    public function getStatusList()
    {
        return ['normal' => __('Status normal'), 'hidden' => __('Status hidden')];
    }

    public function getFlagList()
    {
        return ['index' => __('Flag index'), 'hot' => __('Flag hot'), 'recommend' => __('Flag recommend')];
    }

    public function getStarList()
    {
        return ['1' => __('Star 1'), '2' => __('Star 2'), '3' => __('Star 3'), '4' => __('Star 4'), '5' => __('Star 5')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
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

    /**
     * 关联用户信息
     * @Author: hejiaz
     * @Date: 2020-10-22 11:54:26
     */
    public function user()
    {
        return $this->belongsTo('app\admin\model\User', 'master_id')->setEagerlyType(0);
    }

    // /** 关联详情信息
    //  * @Author: hejiaz
    //  * @Date: 2020-10-22 11:54:26
    //  */
    // public function content()
    // {
    //     return $this->hasOne('\app\common\model\circle\Content');
    // }


}

<?php
/*
 * @Author         : hejiaz
 * @Date           : 2021-03-23 10:33:02
 * @FilePath       : \application\admin\model\Article.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-07 15:08:36
 * @Description    :
 */

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Article extends Model
{

    use SoftDelete;



    // 表名
    protected $name = 'article';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'releasetime_text',
        'flag_text',
        'ishow_text'
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
        return ['hot' => __('Flag hot'), 'recommend' => __('Flag recommend')];
    }

    public function getIshowList()
    {
        return ['1' => __('Ishow 1'), '0' => __('Ishow 0')];
    }


    public function getReleasetimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['releasetime']) ? $data['releasetime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getFlagTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['flag']) ? $data['flag'] : '');
        $valueArr = explode(',', $value);
        $list = $this->getFlagList();
        return implode(',', array_intersect_key($list, array_flip($valueArr)));
    }


    public function getIshowTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['ishow']) ? $data['ishow'] : '');
        $list = $this->getIshowList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setReleasetimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }

    protected function setFlagAttr($value)
    {
        return is_array($value) ? implode(',', $value) : $value;
    }


}

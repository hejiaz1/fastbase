<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Note extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'note';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'is_top_text',
        'show_status_text',
        'status_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    
    public function getIsTopList()
    {
        return ['1' => __('Is_top 1'), '2' => __('Is_top 2')];
    }

    public function getShowStatusList()
    {
        return ['1' => __('Show_status 1'), '2' => __('Show_status 2'), '3' => __('Show_status 3'), '9' => __('Show_status 9')];
    }

    public function getStatusList()
    {
        return ['1' => __('Status 1'), '2' => __('Status 2')];
    }


    public function getIsTopTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['is_top']) ? $data['is_top'] : '');
        $list = $this->getIsTopList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getShowStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['show_status']) ? $data['show_status'] : '');
        $list = $this->getShowStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}

<?php

namespace app\admin\model\csmmeet;

use think\Model;


class Room extends Model
{

    

    

    // 表名
    protected $name = 'csmmeet_room';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'needaudit_text',
        'status_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    
    public function getNeedauditList()
    {
        return ['Y' => __('Needaudit y'), 'N' => __('Needaudit n')];
    }

    public function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }


    public function getNeedauditTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['needaudit']) ? $data['needaudit'] : '');
        $list = $this->getNeedauditList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}

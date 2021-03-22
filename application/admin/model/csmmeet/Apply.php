<?php

namespace app\admin\model\csmmeet;

use think\Model;


class Apply extends Model
{

    

    

    // 表名
    protected $name = 'csmmeet_apply';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'auditstatus_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    
    public function getAuditstatusList()
    {
        return ['-1' => __('Auditstatus -1'), '0' => __('Auditstatus 0'), '1' => __('Auditstatus 1')];
    }


    public function getAuditstatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['auditstatus']) ? $data['auditstatus'] : '');
        $list = $this->getAuditstatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}

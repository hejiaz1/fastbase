<?php
/*
 * @Author         : hejiaz
 * @Date           : 2021-01-15 18:47:08
 * @FilePath       : \application\admin\model\cms\Column.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-01-16 19:32:55
 * @Description    : 栏目管理模型
 */

namespace app\admin\model\cms;

use think\Model;
use traits\model\SoftDelete;

class Column extends Model
{

    use SoftDelete;



    // 表名
    protected $name = 'cms_column';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'status_text'
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
        return ['1' => __('Status 1'), '2' => __('Status 2')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}

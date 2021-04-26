<?php
/*
 * @Author         : hejiaz
 * @Date           : 2021-04-26 10:12:43
 * @FilePath       : \application\admin\model\Version.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-26 10:15:04
 * @Description    : 版本管理模型
 */

namespace app\admin\model;

use think\Model;

class Version extends Model
{

    // 表名
    protected $name = 'version';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';


    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

}

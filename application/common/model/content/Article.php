<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-21 16:28:07
 * @FilePath       : \application\common\model\content\Article.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-27 15:48:43
 * @Description    :
 */
namespace app\common\model\content;

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

    // protected static function init()
    // {
    //     self::afterInsert(function ($row) {
    //         $pk = $row->getPk();
    //         $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
    //     });
    // }


}

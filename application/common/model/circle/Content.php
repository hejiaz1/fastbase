<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-21 16:28:07
 * @FilePath       : \application\common\model\circle\Content.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-11-05 10:29:17
 * @Description    :
 */
namespace app\common\model\circle;

use think\Model;

class Content extends Model
{
    // 表名
    protected $name = 'circle_content';
    protected $pk   = 'circle_id';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = '';
    protected $updateTime = 'updatetime';

    /**
     * 新增圈子内容
     * @Author: hejiaz
     * @Date: 2020-10-23 15:34:00
     * @Param: $circle_id 圈子ID
     * @Return: boole
     */
    public function add($circle_id){
        return $this->insert([
            'circle_id'=>$circle_id
        ]);
    }


}

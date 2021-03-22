<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-20 16:48:45
 * @FilePath       : \application\common\model\info\Circle.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-09 11:39:30
 * @Description    : 会员基础信息模型 就取值
 */

namespace app\common\model\info;

use think\Model;

/**
 * 会员基础信息模型
 */
class Circle extends Model
{
    // 表名
    protected $name = 'circle';

    /**
     * 展示字段
     * @var array
     */
    protected $visible = [
        'name', 'head_image', 'member_num', 'notes_num',
    ];


}

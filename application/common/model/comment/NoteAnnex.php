<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-21 16:28:07
 * @FilePath       : \application\common\model\comment\NoteAnnex.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-11-05 15:20:30
 * @Description    : 笔记点赞模型
 */
namespace app\common\model\comment;

use think\Model;

class NoteAnnex extends Model
{

    // 表名
    protected $name = 'comment_note_annex';

    protected $pk = 'comment_id';

}

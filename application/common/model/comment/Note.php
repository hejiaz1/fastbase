<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-21 16:28:07
 * @FilePath       : \application\common\model\comment\Note.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-03 11:04:09
 * @Description    : 笔记评论模型
 */
namespace app\common\model\comment;

use think\Model;
use traits\model\SoftDelete;


class Note extends Model
{
    // 软删除
    use SoftDelete;

    // 表名
    protected $name = 'comment_note';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = '';
    protected $deleteTime = 'deletetime';


    /** 关联评论附件信息
     * @Author: hejiaz
     * @Date: 2020-10-22 11:54:26
     */
    public function annex()
    {
        return $this->hasOne(NoteAnnex, 'comment_id');
    }

    /** 关联会员信息
     * @Author: hejiaz
     * @Date: 2020-11-27 18:07:10
     */
    public function user()
    {
        return $this->hasOne('\app\common\model\info\User', 'id', 'user_id');
    }

    /** 关联圈子成员信息
     * @Author: hejiaz
     * @Date: 2020-11-27 18:06:47
     */
    public function member()
    {
        return $this->hasOne('app\common\model\info\Member', 'id', 'member_id');
    }

    /** 关联会员信息 回复会员
     * @Author: hejiaz
     * @Date: 2020-12-03 11:03:54
     */
    public function revertUser()
    {
        return $this->hasOne('\app\common\model\info\User', 'id', 'revert_user_id');
    }

    /** 关联圈子成员信息 回复成员
     * @Author: hejiaz
     * @Date: 2020-12-03 11:03:54
     */
    public function revertMember()
    {
        return $this->hasOne('app\common\model\info\Member', 'id', 'revert_member_id');
    }


}

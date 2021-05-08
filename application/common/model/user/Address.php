<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-20 16:48:45
 * @FilePath       : \application\common\model\user\Address.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-05-08 15:22:11
 * @Description    : 会员地址模型
 */

namespace app\common\model\user;

use think\Model;

/**
 * 会员地址模型
 */
class Address extends Model
{
    // 表名
    protected $name = 'user_address';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    // 追加属性
    protected $append = [
        'avatar',
        'prov_text',
        'city_text',
        'area_text',
    ];

    protected static function init()
    {
        self::afterInsert(function ($row) {
            // 获取主键
            $pk = $row->getPk();

            // 当前用户就有这一个地址则把地址改成默认的
            $addressnum = $row->getQuery()->where(['user_id'=>$row['user_id']])->count();
            if($addressnum == 1){
                $row->getQuery()->where($pk, $row[$pk])->update(['isdefault' => 1]);
            }

            if($row['isdefault'] == 1){
                $row->getQuery()->where($pk, 'NEQ', $row[$pk])->where('user_id',$row['user_id'])->update(['isdefault' => 2]);
            }

            // 更新权重为ＩＤ
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);

        });

        self::afterUpdate(function ($row) {
            // 获取主键
            $pk = $row->getPk();

            if($row['isdefault'] == 1){
                $row->getQuery()->where($pk, 'NEQ', $row[$pk])->where('user_id',$row['user_id'])->update(['isdefault' => 2]);
            }else{
                // 查询是否有默认地址 没有则设置当前为默认
                $default = $row->getQuery()->where(['user_id'=>$row['user_id'],'isdefault' => 1])->find();

                if(!$default){
                    $row->getQuery()->where($pk, $row[$pk])->update(['isdefault' => 1]);
                }
            }
        });
    }

    /**
     * 获取头像
     * @param   string $value
     * @param   array  $data
     * @return string
     */
    public function getAvatarAttr($value, $data)
    {
        if (!$value) {
            // 固定样式在方法后面加字段
            // $value = letter_avatar($data['name']);
            $value = letter_avatar($data['name'] ,'#ffffff', '255,117,181');
        }
        return $value;
    }

    // 获取省市区名称
    public function getProvTextAttr($value, $data){
        $value = $value ? $value : $data['prov'];
        return db('area')->where('id', $value)->value('name');
    }
    public function getCityTextAttr($value, $data){
        $value = $value ? $value : $data['city'];
        return db('area')->where('id', $value)->value('name');
    }
    public function getAreaTextAttr($value, $data){
        $value = $value ? $value : $data['area'];
        return db('area')->where('id', $value)->value('name');
    }




}

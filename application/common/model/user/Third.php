<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-20 16:48:45
 * @FilePath       : \application\common\model\user\Third.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-11-26 13:34:29
 * @Description    : 会员第三方登录模型
 */

namespace app\common\model\user;

use think\Model;

/**
 * 会员第三方登录模型
 */
class Third extends Model
{
    // 表名
    protected $name = 'user_third';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    /**
     * 通过平台 唯一标示 openid 获取第三方账号信息
     * @Author: hejiaz
     * @Date: 2020-10-20 17:02:40
     * @Param: $platform 第三方平台
     * @Param: $openid openid
     * @Return: mixed
     */
    public static function getThird($platform, $unionid, $openid){
        return self::where('platform', $platform)
            ->where('unionid', $unionid)
            ->where('openid', $openid)
            ->find();
    }

    /** 更新数据
     * @Author: hejiaz
     * @Date: 2020-11-26 11:21:24
     * @Param: $param1
     * @Return: boole
     */
    public function refreshData($id, $userinfo){
        $param = [
            'user_id' => $userinfo['id'],
            'uuid'    => $userinfo['uuid'],
            'mobile'  => $userinfo['mobile'],
        ];
        return self::where('id', $id)->update($param);
    }



}

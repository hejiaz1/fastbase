<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-16 10:44:24
 * @FilePath       : \application\common\model\ConfigSecret.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-10-20 09:53:21
 * @Description    : 账号秘钥配置公共模型
 */

namespace app\common\model;

use think\Model;
use think\Cache;

/**
 * 账号秘钥配置模型
 */
class ConfigSecret extends Model
{

    // 表名,不含前缀
    protected $name = 'config_secret';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = '';
    protected $updateTime = 'updatetime';


    /**
     * 获取账号秘钥关键字列表
     * @Author: hejiaz
     * @Date: 2020-10-16 11:52:32
     * @Return: array
     */
    public function getKeysList(){
        return $this->where('status', 'normal')
            ->cache('secretKeys')
            ->order('weigh desc,id desc')
            ->column('key');
    }


    /**
     * 获取账号秘钥信息
     * @Author: hejiaz
     * @Date: 2020-10-16 10:49:18
     * @Return: array
     */
    public function getDataList(){
        // 原始数据
        $rawData = $this->where('status', 'normal')
            ->order('weigh desc,id desc')
            ->field('weigh', true)
            ->cache('secretData')
            ->select();

        foreach ($rawData as $v) {
            // 转换数据
            $value = $v->toArray();
            // $value['tip']   = __($value['key'].' tip');
            $dataList[$value['key']] = $value;
        }

        return $dataList;
    }

    /**
     * 获取指定账号秘钥信息
     * @Author: hejiaz
     * @Date: 2020-10-16 14:21:55
     * @Param: $key
     * @Return: array
     */
    public static function getKeyData($key){
        $rawData = self::where('status', 'normal')
            ->where('key', $key)
            ->cache('secretData_'. $key)
            ->value('value');
        $value = json_decode($rawData, true);
        return $value;

    }

}

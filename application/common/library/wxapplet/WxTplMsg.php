<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-20 10:08:08
 * @FilePath       : \application\common\library\wxapplet\WxTplMsg.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-10-20 11:51:30
 * @Description    :
 */

namespace app\common\library\wxapplet;

/**
 * 微信模板消息
 * Class WxTplMsg
 * @package app\common\library\wxapplet
 */
class WxTplMsg extends WxBase
{
    /**
     * 发送模板消息
     * @param $params
     * @return bool
     */
    public function sendTemplateMessage($params)
    {
        // 微信接口url
        $access_token = $this->getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=' . $access_token;
        // 构建请求
        $data = [
            'touser' => $params['touser'],
            'template_id' => $params['template_id'],
            'page' => $params['page'],
            'form_id' => $params['form_id'],
            'data' => $this->createData($params['data'])
        ];
        $result = $this->post($url, json_encode($data, JSON_UNESCAPED_UNICODE));
        // 记录日志
        log_write([
            'params' => $data,
            'result' => $result
        ]);
        // 返回结果
        $response = json_decode($result, true);
        if (!isset($response['errcode'])) {
            $this->error = 'not found errcode';
            return false;
        }
        if ($response['errcode'] != 0) {
            $this->error = $response['errmsg'];
            return false;
        }
        return true;
    }

    /**
     * 生成关键字数据
     * @param $data
     * @return array
     */
    private function createData($data)
    {
        $params = [];
        foreach ($data as $key => $value) {
            $params[$key] = [
                'value' => $value,
                'color' => '#333333'
            ];
        }
        return $params;
    }

}
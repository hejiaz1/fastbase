<?php
namespace app\common\library\wxapplet;
use app\common\model\App as AppModel;
use app\common\exception\BaseException;
use think\facade\Cache;
/**
 * 微信支付
 * Class WxPay
 * @package app\common\library\wxapplet
 */
class WxPay extends WxBase
{
    private $config; // 微信支付配置
    /**
     * 构造函数
     * WxPay constructor.
     * @param $config
     */
    public function __construct($config)
    {
        parent::__construct($config['appid'], $config['app_secret']);

        $config = db('app')->where(['appid' => $config['appid'], 'app_secret' => $config['app_secret']])->find();

        $this->config = $config;
    }
    /**
     * 统一下单API
     * @param $order_no
     * @param $openid
     * @param $total_fee
     * @return array
     * @throws BaseException
     */
    public function unifiedorder($order_no, $openid, $total_fee, $notify_url= 'Wxnotify/callback')
    {

        // 当前时间
        $time = time();
        // 生成随机字符串
        $nonceStr = md5($time . $openid);
        // API参数
        $params = [
            'appid' => $this->appid,
			'mch_id' => $this->config['mchid'],
			'nonce_str' => $nonceStr,
            'attach' => 'test',
            'body' => $order_no,
            'notify_url' => base_url() . 'index.php/api/'. $notify_url,  // 异步通知地址
            'openid' => $openid,
            'out_trade_no' => $order_no,
            'spbill_create_ip' => \request()->ip(),
            'total_fee' => $total_fee * 100, // 价格:单位分
            'trade_type' => 'JSAPI',
        ];
        // 生成签名
        $params['sign'] = $this->makeSign($params);
        // 请求API
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $result = $this->post($url, $this->toXml($params));
        $prepay = $this->fromXml($result);
        // 请求失败
        if ($prepay['return_code'] === 'FAIL') {
            return [
                'code' => 501,
                'mess' => $prepay['return_msg']
            ];
        }

        if ($prepay['result_code'] === 'FAIL') {
            return [
                'code' => 501,
                'mess' => $prepay['err_code_des']
            ];
        }

        // 生成 nonce_str 供前端使用
        $paySign = $this->makePaySign($params['nonce_str'], $prepay['prepay_id'], $time);
        return [
            'prepay_id' => $prepay['prepay_id'],
            'nonceStr' => $nonceStr,
            'timeStamp' => (string)$time,
            'paySign' => $paySign
        ];
    }
    /**
     * 支付成功异步通知
     * @param \app\api\model\Order $OrderModel
     * @throws BaseException
     * @throws \Exception
     * @throws \think\exception\DbException
     */
    public function notify($OrderModel)
    {
        if (!$xml = file_get_contents('php://input')) {
            $this->returnCode(false, 'Not found DATA');
        }
		cache::set('pay',$xml);
		//测试用数据
		/* $xml = '<xml>
					<return_code><![CDATA[SUCCESS]]></return_code>
					<return_msg><![CDATA[OK]]></return_msg>
					<appid><![CDATA[wx1ff41ed2d337fdde]]></appid>
					<mch_id><![CDATA[10000100]]></mch_id>
					<nonce_str><![CDATA[IITRi8Iabbblz1Jc]]></nonce_str>
					<out_trade_no><![CDATA[2019011510297535]]></out_trade_no>
					<sign><![CDATA[7921E432F65EB8ED0CE9755F0E86D72F]]></sign>
					<result_code><![CDATA[SUCCESS]]></result_code>
					<prepay_id><![CDATA[wx201411101639507cbf6ffd8b0779950874]]></prepay_id>
					<trade_type><![CDATA[APP]]></trade_type>
				</xml>'; */
        // 将服务器返回的XML数据转化为数组
        $data = $this->fromXml($xml);
        // 记录日志
        $this->doLogs($xml);
        $this->doLogs($data);
        // 订单信息
        $order = $OrderModel->payDetail($data['out_trade_no']);
		empty($order) && $this->returnCode(false, '订单不存在');
        // 小程序配置信息
        $wxConfig = AppModel::getAppCache($order['app_id']);
        // 设置支付秘钥
        $this->config['apikey'] = $wxConfig['apikey'];
        // 保存微信服务器返回的签名sign
        $dataSign = $data['sign'];
        // sign不参与签名算法
        unset($data['sign']);
        // 生成签名
        $sign = $this->makeSign($data);
        // 判断签名是否正确  判断支付状态
        if (($sign === $dataSign)
            && ($data['return_code'] == 'SUCCESS')
            && ($data['result_code'] == 'SUCCESS')) {
            // 订单支付成功业务处理
            $order->paySuccess('wx201411101639507cbf6ffd8b0779950874');
            // 返回状态
            $this->returnCode(true, 'OK');
        }
        // 返回状态
        $this->returnCode(false, '签名失败');
    }
    /**
     * 返回状态给微信服务器
     * @param boolean $return_code
     * @param string $msg
     */
    private function returnCode($return_code = true, $msg = null)
    {
        // 返回状态
        $return = [
            'return_code' => $return_code ? 'SUCCESS' : 'FAIL',
            'return_msg' => $msg ?: 'OK',
        ];
        // 记录日志
        log_write([
            'describe' => '返回微信支付状态',
            'data' => $return
        ]);
        die($this->toXml($return));
    }
    /**
     * 生成paySign
     * @param $nonceStr
     * @param $prepay_id
     * @param $timeStamp
     * @return string
     */
    private function makePaySign($nonceStr, $prepay_id, $timeStamp)
    {
        $data = [
            'appId' => $this->appid,
            'nonceStr' => $nonceStr,
            'package' => 'prepay_id=' . $prepay_id,
            'signType' => 'MD5',
            'timeStamp' => $timeStamp,
        ];
        // 签名步骤一：按字典序排序参数
        ksort($data);
        $string = $this->toUrlParams($data);
        // 签名步骤二：在string后加入KEY
        $string = $string . '&key=' . $this->config['apikey'];
        // 签名步骤三：MD5加密
        $string = md5($string);
        // 签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }
    /**
     * 将xml转为array
     * @param $xml
     * @return mixed
     */
    private function fromXml($xml)
    {
        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }
    /**
     * 生成签名
     * @param $values
     * @return string 本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    private function makeSign($values)
    {
        //签名步骤一：按字典序排序参数
        ksort($values);
        $string = $this->toUrlParams($values);
        //签名步骤二：在string后加入KEY
        $string = $string . '&key=' . $this->config['apikey'];
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }
    /**
     * 格式化参数格式化成url参数
     * @param $values
     * @return string
     */
    private function toUrlParams($values)
    {
        $buff = '';
        foreach ($values as $k => $v) {
            if ($k != 'sign' && $v != '' && !is_array($v)) {
                $buff .= $k . '=' . $v . '&';
            }
        }
        return trim($buff, '&');
    }
    /**
     * 输出xml字符
     * @param $values
     * @return bool|string
     */
    private function toXml($values)
    {
        if (!is_array($values)
            || count($values) <= 0
        ) {
            return false;
        }
        $xml = "<xml>";
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }
}
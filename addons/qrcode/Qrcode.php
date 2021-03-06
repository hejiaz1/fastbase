<?php

namespace addons\qrcode;

use Endroid\QrCode\ErrorCorrectionLevel;
use think\Addons;
use think\Loader;

/**
 * 二维码生成
 */
class Qrcode extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }

    /**
     * 添加命名空间
     */
    public function appInit()
    {
        Loader::addNamespace('BaconQrCode', ADDON_PATH . 'qrcode' . DS . 'library' . DS . 'BaconQrCode' . DS);
        Loader::addNamespace('Endroid', ADDON_PATH . 'qrcode' . DS . 'library' . DS . 'Endroid' . DS);
        Loader::addNamespace('MyCLabs', ADDON_PATH . 'qrcode' . DS . 'library' . DS . 'MyCLabs' . DS);
        Loader::addNamespace('DASPRiD', ADDON_PATH . 'qrcode' . DS . 'library' . DS . 'DASPRiD' . DS);
    }

}

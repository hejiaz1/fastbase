<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-12-29 14:23:15
 * @FilePath       : \application\api\controller\Index.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-22 14:40:30
 * @Description    :
 */

namespace app\api\controller;

use app\common\controller\Api;

/**
 * 首页接口
 */
class Index extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 首页
     */
    public function index()
    {
        $this->success('请求成功');
    }

     /** 输出字体
     * @Author: hejiaz
     * @Date: 2021-03-29 15:59:31
     */
    public function ttf(){

        $host =  request()->domain();
        $file_dir = "/assets/index/fonts/";
        $file_name = "Muyao-Softbrush-2.ttf";

        $file = @fopen($host . $file_dir . $file_name, "r");

        if (!$file) {
            $this->error('文件不存在');
        } else {

            Header("Content-type: font/ttf");
            Header("Access-Control-Allow-Origin: *");
            Header("Accept-Ranges: bytes");
            // Header("Accept-Length: ".filesize($filepath . $filename));
            Header("Content-Disposition: attachment; filename=" . $file_name);

            while (!feof($file)) {
                echo fread($file, 50000);
            }

            fclose($file);
        }

    }
}

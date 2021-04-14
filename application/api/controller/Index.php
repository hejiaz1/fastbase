<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-12-29 14:23:15
 * @FilePath       : \application\api\controller\Index.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-14 17:39:01
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
     *
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

        $filepath = '/assets/index/fonts/';
        $filename = 'Muyao-Softbrush-2.ttf';

        Header("Content-type: font/ttf");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length: ".filesize($filepath . $filename));
        Header("Content-Disposition: attachment; filename=" . $filename);

        $myfile = fopen($filepath . $filename, "r") or die("Unable to open file!");

        echo fread($myfile,filesize($filepath . $filename));

        fclose($myfile);
    }

}

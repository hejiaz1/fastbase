<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-12-11 16:46:32
 * @FilePath       : \application\api\controller\Config.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-03-29 10:39:19
 * @Description    : 配置信息控制器
 */
namespace app\api\controller;

use app\common\controller\Api;

class Config extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    public function _initialize(){
        parent::_initialize();
        // $this->model = new \app\common\model\circle\Index();

    }

    /** 配置信息
     * @Author: hejiaz
     * @Date: 2021-03-25 18:08:30
     */
    public function index(){

        $this->success('', config('site'));
    }
}

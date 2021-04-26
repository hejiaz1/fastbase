<?php
/*
 * @Author         : hejiaz
 * @Date           : 2021-04-25 16:24:12
 * @FilePath       : \application\admin\controller\Version.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-26 10:14:01
 * @Description    :
 */

namespace app\admin\controller;

use app\common\controller\Backend;

use think\Controller;
use think\Request;

/**
 * 版本管理
 *
 * @icon fa fa-circle-o
 */
class Version extends Backend
{

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Version;
    }

}

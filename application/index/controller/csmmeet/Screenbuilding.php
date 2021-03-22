<?php 
namespace app\index\controller\csmmeet;


use app\common\controller\Frontend;

/**
 * 投屏幕
 * http://127.0.0.1/fastadmin_plugin_csmmeet/public/index/csmmeet.screenbuilding/index.html
 */
class Screenbuilding extends Frontend{
    protected $layout = 'default';
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];
    
    public function index(){
        $_nosidenav_ = $this->request->request("_nosidenav_",'0');//1为不显示
        $this->view->assign('_nosidenav_', $_nosidenav_);
        $this->view->assign('title', '我的预约');
        return $this->view->fetch();
    }
}
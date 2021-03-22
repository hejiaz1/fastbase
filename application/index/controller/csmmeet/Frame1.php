<?php 
namespace app\index\controller\csmmeet;


use app\common\controller\Frontend;

/**
 * 会员中心
 */
class Frame1 extends Frontend{
    protected $layout = 'default';
    protected $noNeedLogin = [];
    protected $noNeedRight = ['*'];
    
    public function index(){
        $_nosidenav_ = $this->request->request("_nosidenav_",'0');//1为不显示
        $this->view->assign('_nosidenav_', $_nosidenav_);
        $this->view->assign('title', '会议室申请');
        return $this->view->fetch();
    }
}
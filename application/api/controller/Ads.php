<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-12-11 16:46:32
 * @FilePath       : \application\api\controller\Ads.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-03-25 17:22:41
 * @Description    : 广告管理
 */
namespace app\api\controller;

use app\common\controller\Api;

class Ads extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    public function _initialize(){
        parent::_initialize();
        // $this->model = new \app\common\model\circle\Index();

    }

    /**
     * @Author: hejiaz
     * @Date: 2021-03-25 17:10:38
     */
    public function banner(){
        $data = (new \app\admin\model\Ads)
            ->where([
                'category_id' => 2,
                'status'      => 'normal',
            ])
            ->field('id,name,show_image')
            ->order('weigh desc,id desc')
            ->select();

        // toarray($data);

        // dump($data);
        // die;

        $this->success('', $data);
    }
}

<?php
/*
 * @Author         : hejiaz
 * @Date           : 2021-04-26 10:35:41
 * @FilePath       : \application\api\controller\Version.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-26 11:09:44
 * @Description    : 版本控制器
 */

namespace app\api\controller;

use app\common\controller\Api;

class Version extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    // 分类参数
    private $outfield = 'weigh,status';


    public function _initialize(){
        parent::_initialize();
        // $this->model = new \app\common\model\circle\Index();

        $appid = $this->request->request('appid/s', '', 'trim');
        if($appid == ''){
            $this->error(__('Appid error'));
        }

        if($appid != '__UNI__0976411'){
            $this->error(__('Appid error'));
        }

    }

    public function getcdn(){
        $cdn = config('view_replace_str')['__CDN__']? : request()->domain();
        $this->success('CDN', $cdn);
    }

    /** 版本列表
     * @Author: hejiaz
     * @Date: 2021-04-26 10:37:17
     */
    public function list(){

        $data = (new \app\admin\model\Version)
            ->where([
                'status'      => 'normal',
            ])
            ->field($this->outfield, true)
            ->order('weigh desc,id desc')
            ->select();

        $this->success('', $data);
    }

    /** 获取最新版本
     * @Author: hejiaz
     * @Date: 2021-04-26 11:04:53
     */
    public function latest(){

        $data = (new \app\admin\model\Version)
            ->where([
                'status'      => 'normal',
            ])
            ->field($this->outfield, true)
            ->order('weigh desc,id desc')
            ->find();

        $this->success('', $data);
    }


}

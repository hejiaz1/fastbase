<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-16 10:19:35
 * @FilePath       : \application\admin\controller\general\Secret.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-10-20 09:52:57
 * @Description    : 账号秘钥配置控制器
 */

namespace app\admin\controller\general;

use app\common\controller\Backend;
use app\common\model\ConfigSecret as SecretModel;

class Secret extends Backend
{

    /**
     * Secret模型对象
     * @var \app\admin\model\general\Secret
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();

        $this->view->assign("keysList", (new SecretModel)->getKeysList());
    }

    /**
     * 查看
     */
    public function index()
    {
        $dataList = (new SecretModel)->getDataList();
        $this->assign('dataList', $dataList);
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        if ($this->request->isPost()) {
            $this->token();

            // 更新数据
            $key = input("key/s", '', 'trim');
            $keyList = (new SecretModel)->getKeysList();
            if(!in_array($key, $keyList)){
                $this->error(__('The configuration keyword %s does not exist, please clear the cache and try again', $key));
            }

            $deal_data = $this->request->post($key.'/a');
            $deal_data['updatetime'] = time();

            $result = (new SecretModel)
                ->where(['key'=>$key])
                ->cache('secretData_'. $key)
                ->update($deal_data);
            if ($result !== false) {
                // 更新缓存
                cache('secretKeys', NULL);
                cache('secretData', NULL);

                $this->success(__('Save completed'),'index');
            } else {
                $this->error(__('Save failed'));
            }
        }
    }
}

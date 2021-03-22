<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-14 15:15:28
 * @FilePath       : \application\api\controller\login\Wxapplet.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-12-03 15:01:56
 * @Description    : 微信小程序授权控制器
 */
namespace app\api\controller\login;
use app\common\controller\Api;
use think\Db;
use think\Hook;
use think\Cookie;

use app\common\model\ConfigSecret;          // 账号秘钥配置
use app\common\library\wxapplet\WxUser;     // 小程序操作类

use app\common\model\User;
use app\common\model\user\Third; // 第三方登录模型

use fast\Random; // 随机类

/**
 * 微信小程序授权
 */
class Wxapplet extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    // 第三方平台标识
    private $platform = 'wxapplet';

    private $AppID     = '';
    private $AppSecret = '';
    private $WxUser;

    public function _initialize()
    {
        parent::_initialize();

        // 小程序账号秘钥
        $wxapplet_config = ConfigSecret::getKeyData($this->platform);
        $this->AppID     = $wxapplet_config['AppID'];
        $this->AppSecret = $wxapplet_config['AppSecret'];
        $this->WxUser    = new WxUser($this->AppID, $this->AppSecret);

        $auth = $this->auth;

        // 登录成功
        Hook::add('user_login_successed', function ($user) use ($auth) {
            $expire = input('post.keeplogin') ? 30 * 86400 : 0;
            Cookie::set('uid', $user->id, $expire);
            Cookie::set('token', $auth->getToken(), $expire);
        });

        // 注册成功
        Hook::add('user_register_successed', function ($user) use ($auth) {
            Cookie::set('uid', $user->id);
            Cookie::set('token', $auth->getToken());
        });

    }

    /** 授权登录/注册
     * @Author: hejiaz
     * @Date: 2020-11-24 17:38:00
     */
    public function auth_login()
    {
        $code = $this->request->request('code/s');
        if (!$code) {
            $this->error(__('Invalid parameters'));
        }

        // 解密code获取sessionkey和openid
        $session = $this->WxUser->get_sessionKey($code);
        if(!is_array($session)){
            $this->error(__('Session Key retrieval failed'), $session);
        }

        // dump($session);die;
        // $session['openid'] = 'oAKzY5XI2TsbUOXqUzLyibyzvGGA';

        // 查看是否有第三方信息
        $third = Third::getThird($this->platform, $session['unionid'],$session['openid']);

        // 保存会员信息
        // $userInfo = '{"nickName":"xxx","gender":"1","language":"zh_CN","city":"Harbin","province":"Heilongjiang","country":"China","avatarUrl":"https://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eoWibTvszsgHbLTm3t8ia9B59kbbTaptib084CicKSQ1hWhgz6RiaUdhDwsZP7UzG9jK8HK35RYMicFcBgA/132"}';
        // $userInfo = json_decode($userInfo, true);

        $userInfo = $this->request->request('userInfo/a', []);

        if(!$third){

            $params = [
                'platform' => $this->platform,
                'unionid'  => $session['unionid'],      // 关注公众号后会有这个唯一标示
                'openid'   => $session['openid'],
                'avatar'   => $userInfo['avatarUrl'],
                'nickname' => $userInfo['nickName'],
                'userinfo' => json_encode($userInfo),
            ];

            $deal = false;
            Db::startTrans();
            try {
                // 更新数据
                $deal = (new Third)->save($params);
                Db::commit();
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }

            if ($deal == false) {
                $this->error(__('Operation failed'));
            }

            // 重新获取第三方会员信息
            $third = Third::getThird($this->platform, $session['unionid'], $session['openid']);

        }else{
            // 判断是否更新了信息
            if($third['avatar'] != $userInfo['avatar'] || $third['avatar'] != $userInfo['nickname']){
                // 更新会员信息
                $params = [
                    'platform' => $this->platform,
                    'unionid'  => $session['unionid'],      // 关注公众号后会有这个唯一标示
                    'avatar'   => $userInfo['avatarUrl'],
                    'nickname' => $userInfo['nickName'],
                    'userinfo' => json_encode($userInfo),
                ];

                $deal = false;
                Db::startTrans();
                try {
                    // 更新数据
                    $deal = (new Third)->save($params, ['id'=> $third['id']]);
                    Db::commit();
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }

                if ($deal == false) {
                    $this->error(__('Operation failed'));
                }
                // 重新获取第三方会员信息
                $third = Third::getThird($this->platform, $session['unionid'], $session['openid']);
            }
        }

        // 判断是否需要强制绑定手机号
        if (\think\Config::get("site")['compel_bind_mobile'] == 1) {
            if($third['mobile'] == ''){
                $this->error(__('Please bind the authorized phone number'));
            }
        }

        toarray($third);
        // 未注册会员信息
        if(!$third['uuid']){
            // TODO 获取邀请人ID
            $share_uuid = $this->request->request('share_uuid/s', '', 'trim');
            $invite_code = $this->request->request('invite_code/s', '', 'trim');

            // 扩展信息
            $extend = [
                'nickname' => $third['nickname'],
                'avatar'   => $third['avatar'],
            ];
            // 注册会员信息
            $ret = $this->auth->register('', '', '', '', $extend);
            if ($ret) {
                $refresh = (new Third)->refreshData($third['id'], $this->auth->getUserinfo());
                if(!$refresh){
                    $this->error(__('Operation failed'));
                }
            } else {
                $this->error($this->auth->getError());
            }
        }

        //直接登录会员
        $login = $this->auth->direct($third['user_id']);
        if ($login) {
            $data = [
                'userinfo'=> $this->auth->getUserinfo(),
                'token' => $this->auth->gettoken()
            ];
            $this->success(__('Logged in successful'), $data);
        } else {
            $this->error(__('Logon failed'));
        }
    }

    /** 手机号授权注册
     * @Author: hejiaz
     * @Date: 2020-11-24 17:38:21
     */
    public function mobile_login()
    {
        $code          = $this->request->request('code/s');
        $encryptedData = $this->request->request('encryptedData/s', '', 'trim');
        $iv            = $this->request->request('iv/s', '', 'trim');

        if (!$code || !$encryptedData || !$iv) {
            $this->error(__('Invalid parameters'));
        }

        // 解密code获取sessionkey和openid
        $session = $this->WxUser->get_sessionKey($code);
        if(!is_array($session)){
            $this->error(__('Session Key retrieval failed'), $session);
        }
        // 解密数据
        $data = $this->WxUser->decryptData($session['session_key'], $encryptedData, $iv, $data);

        // $session['session_key'] = 'i7CDfhYCgaQnGkgTSYHRMA==';
        // $session['openid'] = 'oAKzY5XI2TsbUOXqUzLyibyzvGGA';
        // $data = [
        //     'state' => 1,
        //     'data' => [
        //         'purePhoneNumber' => '18704657891'
        //     ]
        // ];

        $mobile = $data['data']['purePhoneNumber'];

        // 有错误代码 返回错误代码
        if ($data['state'] != 1) {
            $this->error(__('Decryption failure'), $data['state']);
        }

        // 获取第三方信息
        $third = Third::getThird($this->platform, $session['unionid'], $session['openid']);
        if(!$third){
            // 未授权小程序会员信息
            $this->error(__('Unauthorized member information'));
        }


        toarray($third);

        // 未注册会员信息
        if(!$third['uuid']){
            // TODO 获取邀请人ID
            $share_uuid = $this->request->request('share_uuid/s', '', 'trim');
            $invite_code = $this->request->request('invite_code/s', '', 'trim');

            // 扩展信息
            $extend = [
                'mobile'   => $mobile,
                'nickname' => $third['nickname'],
                'avatar'   => $third['avatar'],
            ];
            // 注册会员信息
            $ret = $this->auth->register('', '', '', '', $extend);
            if ($ret) {
                $refresh = (new Third)->refreshData($third['id'], $this->auth->getUserinfo());
                if(!$refresh){
                    $this->error(__('Operation failed'));
                }
            } else {
                $this->error($this->auth->getError());
            }
        }else{
            if(!$third['mobile']){
                $deal = false;
                Db::startTrans();
                try {
                    // 更新数据
                    $deal = (new Third)->where('id', $third['id'])->update(['mobile' => $mobile]);
                    $deal = (new User)->where('id', $third['user_id'])->update(['mobile' => $mobile]);

                    Db::commit();
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }

                if ($deal == false) {
                    $this->error(__('Operation failed'));
                }
            }
        }

        //直接登录会员
        $login = $this->auth->direct($this->auth->id);
        if ($login) {
            $data = [
                'userinfo'=> $this->auth->getUserinfo(),
                'token' => $this->auth->gettoken()
            ];
            $this->success(__('Logged in successful'), $data);
        } else {
            $this->error(__('Logon failed'));
        }

    }


}

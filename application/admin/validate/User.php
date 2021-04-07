<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-12-29 14:23:14
 * @FilePath       : \application\admin\validate\User.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-03-26 11:23:40
 * @Description    : 会员信息验证
 */

namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'username' => 'require|regex:\w{3,32}|unique:user',
        'nickname' => 'require|unique:user',
        'password' => 'regex:\S{6,32}',
        'mobile'   => 'mobile|unique:user',
        'email'    => 'email|unique:user',
    ];

    /**
     * 字段描述
     */
    protected $field = [
    ];
    /**
     * 提示消息
     */
    protected $message = [
    ];
    /**
     * 验证场景
     */
    protected $scene = [

        'add'  => ['username','password', 'nickname', 'mobile','email'],
        'edit' => ['username','password', 'nickname', 'mobile','email'],
    ];

    public function __construct(array $rules = [], $message = [], $field = [])
    {
        $this->field = [
            'username' => __('Username'),
            'nickname' => __('Nickname'),
            'password' => __('Password'),
            'email'    => __('Email'),
            'mobile'   => __('Mobile')
        ];
        parent::__construct($rules, $message, $field);
    }

}

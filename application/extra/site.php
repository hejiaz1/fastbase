<?php
/*
 * @Author         : hejiaz
 * @Date           : 2021-04-23 14:21:10
 * @FilePath       : \application\extra\site.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-25 10:32:59
 * @Description    : 配置信息
 */

return [
    'name' => '我的网站',
    'beian' => '黑ICP备19006330号-1',
    'cdnurl' => '',
    'version' => '1.0.1',
    'timezone' => 'Asia/Shanghai',
    'forbiddenip' => '',
    'languages' => [
        'backend' => 'zh-cn',
        'frontend' => 'zh-cn',
    ],
    'fixedpage' => 'dashboard',
    'categorytype' => [
        'links' => 'Links',
        'ads' => 'Ads',
        'default' => 'Default',
        'page' => 'Page',
        'article' => 'Article',
        'test' => 'Test',
    ],
    'configgroup' => [
        'basic' => 'Basic',
        'email' => 'Email',
        'dictionary' => 'Dictionary',
        'user' => 'User',
        'extend' => 'Extend',
        'maskwords' => 'Mask Words',
        'example' => 'Example',
    ],
    'mail_type' => '1',
    'mail_smtp_host' => 'smtp.qq.com',
    'mail_smtp_port' => '465',
    'mail_smtp_user' => '10000',
    'mail_smtp_pass' => 'password',
    'mail_verify_type' => '2',
    'mail_from' => '123@qq.com',
    'compel_bind_mobile' => '1',
    'maskwords' => [
        '傻逼' => '哈哈哈',
        'SB' => '',
        '共产党' => '',
        '操你妈' => '',
    ],
    'uuid_code_prefix' => 'fb',
];

<?php
// +----------------------------------------------------------------------
// Csmmeet [ CSMMeet会议室预约系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-26
// +----------------------------------------------------------------------
return [
    [
        'name' => 'emailsuffix',
        'title' => '仅支持如下邮箱后缀登录',
        'type' => 'string',
        'content' => array(),
        'value' => '163.com,qq.com,163.net,gmail.com,126.com',
        'rule' => '',
        'msg' => '',
        'tip' => '用户首次需要输入邮箱和邮件验证码进入后方可预约会议室，<BR>为防止非本企业用户预约，需要限定本公司的邮箱才可登录；<BR>如果多个后缀，逗号分隔；邮箱后缀大小写不区分；',
        'ok' => '',
        'extend' => ''
    ],
    [
        'name' => '__tips__',
        'title' => '安装顺序',
        'type' => 'string',
        'content' => array(),
        'value' => "<B>1. 维护大楼和会议室，具体配置在：CSM会议室预约-大楼管理和会议室管理</B><BR>2. 维护发送邮箱配置，具体配置在：常规管理-系统配置-邮件配置，用于用户帐号邮箱激活发送验证码邮件用<BR>3. 为避免公司外部邮箱帐号预约会议室，可以限制登录邮箱的后缀，具体配置在：插件管理-CSM会议室预约-邮箱后缀登录，如果多个后缀，请使用逗号分隔，比如:163.com,qq.com<BR><BR>恭喜您，系统配置完毕。现在用于可以申请了，具体是手机微信、钉钉或者浏览器访问：<BR><span style='color:red;font-weight:bold'>移动端地址（微信、钉钉或手机浏览器可访问）：<br>" . $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . config('view_replace_str.__PUBLIC__') . "assets/addons/csmmeet/mo",
        'rule' => '',
        'msg' => '',
        'tip' => '',
        'ok' => '',
        'extend' => ''
    ]
];

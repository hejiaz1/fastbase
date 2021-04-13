<?php

return [
    'autoload' => false,
    'hooks' => [
        'app_init' => [
            'banip',
            'crontab',
            'epay',
            'log',
            'qrcode',
        ],
        'user_sidenav_after' => [
            'csmmeet',
        ],
        'ems_send' => [
            'faems',
        ],
        'ems_notice' => [
            'faems',
        ],
        'action_begin' => [
            'geetest',
        ],
        'config_init' => [
            'geetest',
            'third',
        ],
        'response_send' => [
            'loginvideo',
        ],
    ],
    'route' => [
        '/example$' => 'example/index/index',
        '/example/d/[:name]' => 'example/demo/index',
        '/example/d1/[:name]' => 'example/demo/demo1',
        '/example/d2/[:name]' => 'example/demo/demo2',
        '/qrcode$' => 'qrcode/index/index',
        '/qrcode/build$' => 'qrcode/index/build',
        '/third$' => 'third/index/index',
        '/third/connect/[:platform]' => 'third/index/connect',
        '/third/callback/[:platform]' => 'third/index/callback',
        '/third/bind/[:platform]' => 'third/index/bind',
        '/third/unbind/[:platform]' => 'third/index/unbind',
    ],
    'priority' => [],
];

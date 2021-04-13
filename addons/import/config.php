<?php

return [
    [
        'name' => 'exclude',
        'title' => '禁用导入的数据表',
        'type' => 'text',
        'content' => [],
        'value' => 'fa_admin'."\n"
            .'fa_admin_log'."\n"
            .'fa_attachment'."\n"
            .'fa_auth_group'."\n"
            .'fa_auth_group_access'."\n"
            .'fa_auth_rule',
        'rule' => '',
        'msg' => '',
        'tip' => '多个分行填列',
        'ok' => '',
        'extend' => '',
    ],
];

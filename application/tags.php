<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-30 14:02:02
 * @FilePath       : \application\tags.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2020-11-30 11:08:39
 * @Description    : 应用行为扩展定义文件
 */
return [
    // 应用初始化
    'app_init'     => [],
    // 应用开始
    'app_begin'    => [],
    // 应用调度
    'app_dispatch' => [
        'app\\common\\behavior\\Common',
    ],
    // 模块初始化
    'module_init'  => [
        'app\\common\\behavior\\Common',
    ],
    // 插件开始
    'addon_begin'  => [
        'app\\common\\behavior\\Common',
    ],
    // 操作开始执行
    'action_begin' => [],
    // 视图内容过滤
    'view_filter'  => [],
    // 日志写入
    'log_write'    => [],
    // 应用结束
    'app_end'      => [],
];

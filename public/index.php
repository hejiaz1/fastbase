<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-10 14:45:02
 * @FilePath       : \public\index.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-30 18:05:17
 * @Description    : 入口文件
 */

// header("Access-Control-Allow-Methods: *");
// header('Access-Control-Allow-Origin: *');

//  // 允许跨域
// header('Access-Control-Allow-Origin:*'); //允许跨域
// if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//     header('Access-Control-Allow-Headers:x-requested-with,content-type,token'); //浏览器页面ajax跨域请求会请求2次，第一次会发送OPTIONS预请求,不进行处理，直接exit返回，但因为下次发送真正的请求头部有带token，所以这里设置允许下次请求头带token否者下次请求无法成功
//     exit("ok");
// }

// [ 应用入口文件 ]
// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');

// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';

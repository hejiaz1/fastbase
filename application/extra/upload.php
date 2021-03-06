<?php
/*
 * @Author         : hejiaz
 * @Date           : 2020-10-10 14:45:02
 * @FilePath       : \application\extra\upload.php
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-25 17:20:55
 * @Description    : 上传配置文件
 */

return [
    /**
     * 上传地址,默认是本地上传
     */
    'uploadurl' => 'ajax/upload',
    /**
     * CDN地址
     */
    'cdnurl'    => '',
    /**
     * 文件保存格式
     */
    'savekey'   => '/uploads/{year}{mon}{day}/{filemd5}{.suffix}',
    /**
     * 最大可上传大小
     */
    'maxsize'   => '10mb',
    /**
     * 可上传的文件类型
     */
    'mimetype'  => 'jpg,png,bmp,jpeg,gif,zip,rar,xls,xlsx,wav,mp4,mp3,pdf,wgt',
    /**
     * 是否支持批量上传
     */
    'multiple'  => false,
    /**
     * 是否支持分片上传
     */
    'chunking'  => false,
    /**
     * 默认分片大小
     */
    'chunksize' => 2097152,
];

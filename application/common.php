<?php

// 公共助手函数
use Symfony\Component\VarExporter\VarExporter;

error_reporting(E_ERROR | E_PARSE);

if (!function_exists('__')) {

    /**
     * 获取语言变量值
     * @param string $name 语言变量名
     * @param array  $vars 动态变量值
     * @param string $lang 语言
     * @return mixed
     */
    function __($name, $vars = [], $lang = '')
    {
        if (is_numeric($name) || !$name) {
            return $name;
        }
        if (!is_array($vars)) {
            $vars = func_get_args();
            array_shift($vars);
            $lang = '';
        }
        return \think\Lang::get($name, $vars, $lang);
    }
}

if (!function_exists('format_bytes')) {

    /**
     * 将字节转换为可读文本
     * @param int    $size      大小
     * @param string $delimiter 分隔符
     * @param int    $precision 小数位数
     * @return string
     */
    function format_bytes($size, $delimiter = '', $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 6; $i++) {
            $size /= 1024;
        }
        return round($size, $precision) . $delimiter . $units[$i];
    }
}

if (!function_exists('datetime')) {

    /**
     * 将时间戳转换为日期时间
     * @param int    $time   时间戳
     * @param string $format 日期时间格式
     * @return string
     */
    function datetime($time, $format = 'Y-m-d H:i:s')
    {
        $time = is_numeric($time) ? $time : strtotime($time);
        return date($format, $time);
    }
}

if (!function_exists('human_date')) {

    /**
     * 获取语义化时间
     * @param int $time  时间
     * @param int $local 本地时间
     * @return string
     */
    function human_date($time, $local = null)
    {
        return \fast\Date::human($time, $local);
    }
}

if (!function_exists('cdnurl')) {

    /**
     * 获取上传资源的CDN的地址
     * @param string  $url    资源相对地址
     * @param boolean $domain 是否显示域名 或者直接传入域名
     * @return string
     */
    function cdnurl($url, $domain = false)
    {
        $regex = "/^((?:[a-z]+:)?\/\/|data:image\/)(.*)/i";
        $cdnurl = \think\Config::get('upload.cdnurl');
        $url = preg_match($regex, $url) || ($cdnurl && stripos($url, $cdnurl) === 0) ? $url : $cdnurl . $url;
        if ($domain && !preg_match($regex, $url)) {
            $domain = is_bool($domain) ? request()->domain() : $domain;
            $url = $domain . $url;
        }
        return $url;
    }
}

if (!function_exists('is_really_writable')) {

    /**
     * 判断文件或文件夹是否可写
     * @param string $file 文件或目录
     * @return    bool
     */
    function is_really_writable($file)
    {
        if (DIRECTORY_SEPARATOR === '/') {
            return is_writable($file);
        }
        if (is_dir($file)) {
            $file = rtrim($file, '/') . '/' . md5(mt_rand());
            if (($fp = @fopen($file, 'ab')) === false) {
                return false;
            }
            fclose($fp);
            @chmod($file, 0777);
            @unlink($file);
            return true;
        } elseif (!is_file($file) or ($fp = @fopen($file, 'ab')) === false) {
            return false;
        }
        fclose($fp);
        return true;
    }
}

if (!function_exists('rmdirs')) {

    /**
     * 删除文件夹
     * @param string $dirname  目录
     * @param bool   $withself 是否删除自身
     * @return boolean
     */
    function rmdirs($dirname, $withself = true)
    {
        if (!is_dir($dirname)) {
            return false;
        }
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }
        if ($withself) {
            @rmdir($dirname);
        }
        return true;
    }
}

if (!function_exists('copydirs')) {

    /**
     * 复制文件夹
     * @param string $source 源文件夹
     * @param string $dest   目标文件夹
     */
    function copydirs($source, $dest)
    {
        if (!is_dir($dest)) {
            mkdir($dest, 0755, true);
        }
        foreach (
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            ) as $item
        ) {
            if ($item->isDir()) {
                $sontDir = $dest . DS . $iterator->getSubPathName();
                if (!is_dir($sontDir)) {
                    mkdir($sontDir, 0755, true);
                }
            } else {
                copy($item, $dest . DS . $iterator->getSubPathName());
            }
        }
    }
}

if (!function_exists('mb_ucfirst')) {
    function mb_ucfirst($string)
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_strtolower(mb_substr($string, 1));
    }
}

if (!function_exists('addtion')) {

    /**
     * 附加关联字段数据
     * @param array $items  数据列表
     * @param mixed $fields 渲染的来源字段
     * @return array
     */
    function addtion($items, $fields)
    {
        if (!$items || !$fields) {
            return $items;
        }
        $fieldsArr = [];
        if (!is_array($fields)) {
            $arr = explode(',', $fields);
            foreach ($arr as $k => $v) {
                $fieldsArr[$v] = ['field' => $v];
            }
        } else {
            foreach ($fields as $k => $v) {
                if (is_array($v)) {
                    $v['field'] = isset($v['field']) ? $v['field'] : $k;
                } else {
                    $v = ['field' => $v];
                }
                $fieldsArr[$v['field']] = $v;
            }
        }
        foreach ($fieldsArr as $k => &$v) {
            $v = is_array($v) ? $v : ['field' => $v];
            $v['display'] = isset($v['display']) ? $v['display'] : str_replace(['_ids', '_id'], ['_names', '_name'], $v['field']);
            $v['primary'] = isset($v['primary']) ? $v['primary'] : '';
            $v['column'] = isset($v['column']) ? $v['column'] : 'name';
            $v['model'] = isset($v['model']) ? $v['model'] : '';
            $v['table'] = isset($v['table']) ? $v['table'] : '';
            $v['name'] = isset($v['name']) ? $v['name'] : str_replace(['_ids', '_id'], '', $v['field']);
        }
        unset($v);
        $ids = [];
        $fields = array_keys($fieldsArr);
        foreach ($items as $k => $v) {
            foreach ($fields as $m => $n) {
                if (isset($v[$n])) {
                    $ids[$n] = array_merge(isset($ids[$n]) && is_array($ids[$n]) ? $ids[$n] : [], explode(',', $v[$n]));
                }
            }
        }
        $result = [];
        foreach ($fieldsArr as $k => $v) {
            if ($v['model']) {
                $model = new $v['model'];
            } else {
                $model = $v['name'] ? \think\Db::name($v['name']) : \think\Db::table($v['table']);
            }
            $primary = $v['primary'] ? $v['primary'] : $model->getPk();
            $result[$v['field']] = isset($ids[$v['field']]) ? $model->where($primary, 'in', $ids[$v['field']])->column("{$primary},{$v['column']}") : [];
        }

        foreach ($items as $k => &$v) {
            foreach ($fields as $m => $n) {
                if (isset($v[$n])) {
                    $curr = array_flip(explode(',', $v[$n]));

                    $v[$fieldsArr[$n]['display']] = implode(',', array_intersect_key($result[$n], $curr));
                }
            }
        }
        return $items;
    }
}

if (!function_exists('addtion_one')) {

    /**
     * 单条数据 附加关联字段数据
     * @param array $items  待处理数据
     * @param mixed $fields 渲染的来源字段
     * @return array
     */
    function addtion_one(&$data, $fields)
    {
        if (!$data || !$fields) {
            return true;
        }
        $fieldsArr = [];
        if (!is_array($fields)) {
            $arr = explode(',', $fields);
            foreach ($arr as $k => $v) {
                $fieldsArr[$v] = ['field' => $v];
            }
        } else {
            foreach ($fields as $k => $v) {
                if (is_array($v)) {
                    $v['field'] = isset($v['field']) ? $v['field'] : $k;
                } else {
                    $v = ['field' => $v];
                }
                $fieldsArr[$v['field']] = $v;
            }
        }

        foreach ($fieldsArr as $k => &$v) {
            $v = is_array($v) ? $v : ['field' => $v];

            $v['display'] = isset($v['display']) ? $v['display'] : str_replace(['_ids', '_id'], ['_names', '_name'], $v['field']);
            $v['primary'] = isset($v['primary']) ? $v['primary'] : '';
            $v['column'] = isset($v['column']) ? $v['column'] : 'name';
            $v['model'] = isset($v['model']) ? $v['model'] : '';
            $v['table'] = isset($v['table']) ? $v['table'] : '';
            $v['name'] = isset($v['name']) ? $v['name'] : str_replace(['_ids', '_id'], '', $v['field']);
        }
        unset($v);
        $ids = [];
        $fields = array_keys($fieldsArr);

        foreach ($fields as $m => $n) {
            if (isset($data[$n])) {
                $data[$n] = trim($data[$n], ',');

                $ids[$n] = array_merge(isset($ids[$n]) && is_array($ids[$n]) ? $ids[$n] : [], explode(',', $data[$n]));
            }
        }

        $result = [];
        foreach ($fieldsArr as $k => $v) {
            if ($v['model']) {
                $model = new $v['model'];
            } else {
                $model = $v['name'] ? \think\Db::name($v['name']) : \think\Db::table($v['table']);
            }
            $primary = $v['primary'] ? $v['primary'] : $model->getPk();
            $result[$v['field']] = $model->where($primary, 'in', $ids[$v['field']])->column("{$primary},{$v['column']}");
        }

        foreach ($fields as $m => $n) {
            if (isset($data[$n])) {
                $curr = array_flip(explode(',', $data[$n]));

                $data[$fieldsArr[$n]['display']] = implode(',', array_intersect_key($result[$n], $curr));
            }
        }

        return true;
    }
}

if (!function_exists('var_export_short')) {

    /**
     * 使用短标签打印或返回数组结构
     * @param mixed   $data
     * @param boolean $return 是否返回数据
     * @return string
     */
    function var_export_short($data, $return = true)
    {
        return var_export($data, $return);
        $replaced = [];
        $count = 0;

        //判断是否是对象
        if (is_resource($data) || is_object($data)) {
            return var_export($data, $return);
        }

        //判断是否有特殊的键名
        $specialKey = false;
        array_walk_recursive($data, function (&$value, &$key) use (&$specialKey) {
            if (is_string($key) && (stripos($key, "\n") !== false || stripos($key, "array (") !== false)) {
                $specialKey = true;
            }
        });
        if ($specialKey) {
            return var_export($data, $return);
        }
        array_walk_recursive($data, function (&$value, &$key) use (&$replaced, &$count, &$stringcheck) {
            if (is_object($value) || is_resource($value)) {
                $replaced[$count] = var_export($value, true);
                $value = "##<{$count}>##";
            } else {
                if (is_string($value) && (stripos($value, "\n") !== false || stripos($value, "array (") !== false)) {
                    $index = array_search($value, $replaced);
                    if ($index === false) {
                        $replaced[$count] = var_export($value, true);
                        $value = "##<{$count}>##";
                    } else {
                        $value = "##<{$index}>##";
                    }
                }
            }
            $count++;
        });

        $dump = var_export($data, true);

        $dump = preg_replace('#(?:\A|\n)([ ]*)array \(#i', '[', $dump); // Starts
        $dump = preg_replace('#\n([ ]*)\),#', "\n$1],", $dump); // Ends
        $dump = preg_replace('#=> \[\n\s+\],\n#', "=> [],\n", $dump); // Empties
        $dump = preg_replace('#\)$#', "]", $dump); //End

        if ($replaced) {
            $dump = preg_replace_callback("/'##<(\d+)>##'/", function ($matches) use ($replaced) {
                return isset($replaced[$matches[1]]) ? $replaced[$matches[1]] : "''";
            }, $dump);
        }

        if ($return === true) {
            return $dump;
        } else {
            echo $dump;
        }
    }
}

if (!function_exists('letter_avatar')) {
    /**
     * 首字母头像
     * @param $text
     * @return string
     */
    function letter_avatar($text)
    {
        $total = unpack('L', hash('adler32', $text, true))[1];
        $hue = $total % 360;
        list($r, $g, $b) = hsv2rgb($hue / 360, 0.3, 0.9);

        $bg = "rgb({$r},{$g},{$b})";
        $color = "#ffffff";
        $first = mb_strtoupper(mb_substr($text, 0, 1));
        $src = base64_encode('<svg xmlns="http://www.w3.org/2000/svg" version="1.1" height="100" width="100"><rect fill="' . $bg . '" x="0" y="0" width="100" height="100"></rect><text x="50" y="50" font-size="50" text-copy="fast" fill="' . $color . '" text-anchor="middle" text-rights="admin" dominant-baseline="central">' . $first . '</text></svg>');
        $value = 'data:image/svg+xml;base64,' . $src;
        return $value;
    }
}

if (!function_exists('hsv2rgb')) {
    function hsv2rgb($h, $s, $v)
    {
        $r = $g = $b = 0;

        $i = floor($h * 6);
        $f = $h * 6 - $i;
        $p = $v * (1 - $s);
        $q = $v * (1 - $f * $s);
        $t = $v * (1 - (1 - $f) * $s);

        switch ($i % 6) {
            case 0:
                $r = $v;
                $g = $t;
                $b = $p;
                break;
            case 1:
                $r = $q;
                $g = $v;
                $b = $p;
                break;
            case 2:
                $r = $p;
                $g = $v;
                $b = $t;
                break;
            case 3:
                $r = $p;
                $g = $q;
                $b = $v;
                break;
            case 4:
                $r = $t;
                $g = $p;
                $b = $v;
                break;
            case 5:
                $r = $v;
                $g = $p;
                $b = $q;
                break;
        }

        return [
            floor($r * 255),
            floor($g * 255),
            floor($b * 255)
        ];
    }
}

if (!function_exists('check_nav_active')) {
    /**
     * 检测会员中心导航是否高亮
     */
    function check_nav_active($url, $classname = 'active')
    {
        $auth = \app\common\library\Auth::instance();
        $requestUrl = $auth->getRequestUri();
        $url = ltrim($url, '/');
        return $requestUrl === str_replace(".", "/", $url) ? $classname : '';
    }
}

if (!function_exists('check_cors_request')) {
    /**
     * 跨域检测
     */
    function check_cors_request()
    {
        if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN']) {
            $info = parse_url($_SERVER['HTTP_ORIGIN']);
            $domainArr = explode(',', config('fastadmin.cors_request_domain'));
            $domainArr[] = request()->host(true);
            if (in_array("*", $domainArr) || in_array($_SERVER['HTTP_ORIGIN'], $domainArr) || (isset($info['host']) && in_array($info['host'], $domainArr))) {
                header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
            } else {
                header('HTTP/1.1 403 Forbidden');
                exit;
            }

            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');

            if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
                    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
                }
                if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
                    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
                }
                exit;
            }
        }
    }
}

if (!function_exists('xss_clean')) {
    /**
     * 清理XSS
     */
    function xss_clean($content, $is_image = false)
    {
        return \app\common\library\Security::instance()->xss_clean($content, $is_image);
    }
}

if (!function_exists('curlGet')) {
    /** curl请求指定url (get)
     * @param $url
     * @param array $data
     * @return mixed
     */
    function curlGet($url, $data = [])
    {
        // 处理get数据
        if (!empty($data)) {
            $url = $url . '?' . http_build_query($data);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //这个是重点。
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}

if (!function_exists('curlPost')) {

    /** curl请求指定url (post)
     * @param $url
     * @param array $data
     * @return mixed
     */
    function curlPost($url, $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

if (!function_exists('combineURL')) {
    /** 拼接url
     * @param string $baseURL   请求的url
     * @param array  $keysArr   参数列表数组
     * @return string           返回拼接的url
     */
    function combineURL($baseURL, $keysArr)
    {
        $combined = $baseURL . "?";
        $valueArr = array();
        foreach ($keysArr as $key => $val) {
            $valueArr[] = "$key=$val";
        }
        $keyStr = implode("&", $valueArr);
        $combined .= ($keyStr);
        return $combined;
    }

}

if (!function_exists('toarray')) {
    /** 转换数据
     * @param array  $data  转换传过来的值
     * @param array  $fields  解码字段
     * @param string  $convertype  转换类型 默认json
     * @return bool
     */
    function toarray(&$data, $fields = [], $convertype = 'json')
    {
        if ($data && is_object($data)) {
            // 转换数据
            $data = $data->toarray();

            if ($fields) {
                foreach ($fields as $value) {
                    $val = explode('.', $value);
                    switch (count($val)) {
                        case 1:
                            if ($data[$val[0]]) {
                                switch ($convertype) {
                                    case 'json':
                                        $data[$val[0]] = json_decode($data[$val[0]], true);
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                            } else {
                                $data[$val[0]] = '';
                            }
                            break;

                        case 2:
                            if ($data[$val[0]][$val[1]]) {
                                switch ($convertype) {
                                    case 'json':
                                        $data[$val[0]][$val[1]] = json_decode($data[$val[0]][$val[1]], true);
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                            } else {
                                $data[$val[0]][$val[1]] = '';
                            }

                            break;

                        case 3:
                            if ($data[$val[0]][$val[1]][$val[2]]) {
                                switch ($convertype) {
                                    case 'json':
                                        $data[$val[0]][$val[1]][$val[2]] = json_decode($data[$val[0]][$val[1]][$val[2]], true);
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                            } else {
                                $data[$val[0]][$val[1]][$val[2]] = '';
                            }
                            break;

                        default:
                            # code...
                            break;
                    }
                }
            }
            return true;
        }
        return false;
    }

}

if (!function_exists('get_annex_params')) {
    /** 获取附件参数
     * @param array  $request   $this->request
     * @return array
     */
    function get_annex_params($request)
    {
        $annex_params = [
            'images' => $request->request('images/a', ''),
            'voice' => $request->request('voice/a', ''),
            'cover' => $request->request('cover/s', '', 'trim'),
            'video' => $request->request('video/s', '', 'trim'),
        ];

        if ($annex_params['images'] == '' && $annex_params['voice'] == '' && $annex_params['video'] == '') {
            // 全为空则没传附件
            $annex_params = '';
        } else {
            // 转换格式
            if ($annex_params['images']) {
                $annex_params['images'] = json_encode($annex_params['images']);
            }

            if ($annex_params['voice']) {
                $annex_params['voice'] = json_encode($annex_params['voice']);
            }

        }

        return $annex_params;
    }

}

if (!function_exists('mask_words')) {
    /** 屏蔽关键词
     * @param array  $request   $this->request
     * @return array
     */
    function mask_words(&$string)
    {
        // 获取屏蔽词列表
        $maskwords = \think\Config::get("site")['maskwords'];
        /**
         * 屏蔽词格式
         * array(3) {
        ["傻逼"] => string(3) "***"
        ["SB"] => string(3) "***"
        ["共产党"] => string(6) "***111"
        }
         */

        // 关键词
        $keylist = array_keys($maskwords);

        //定义正则表达式
        $pattern = "/" . implode("|", $keylist) . "/i";
        // 正则匹配非法词
        if (preg_match_all($pattern, $string, $matches)) {
            // 匹配到的数组
            $patternList = $matches[0];

            // 循环赋值替换文字
            foreach ($patternList as $key => $value) {
                if ($maskwords[$value]) {
                    // 指定替换文字
                    $replaceArray[$value] = $maskwords[$value];
                } else {
                    // 替换为对应数量星号
                    $replaceArray[$value] = str_repeat('*', mb_strlen($value, 'utf-8'));
                }
            }
            $string = strtr($string, $replaceArray); //结果替换
        }

        // 替换完成 返回
        return true;

        $keylist = array_keys($maskwords);
        $count = 0; //违规词的个数
        $sensitiveWord = ''; //违规词
        $stringAfter = $string; //替换后的内容

        $pattern = "/" . implode("|", $keylist) . "/i"; //定义正则表达式
        // dump($pattern);die;

        //匹配到了结果
        if (preg_match_all($pattern, $string, $matches)) {
            $patternList = $matches[0]; //匹配到的数组
            $count = count($patternList);
            $sensitiveWord = implode(',', $patternList); //敏感词数组转字符串
            $replaceArray = array_combine($patternList, array_fill(0, count($patternList), '*')); //把匹配到的数组进行合并，替换使用
            // $replaceArray = $maskwords;
            $stringAfter = strtr($string, $replaceArray); //结果替换
        }
        $string = "原句为 [ {$string} ]<br/>";
        if ($count == 0) {
            $string .= "暂未匹配到敏感词！";
        } else {
            $string .= "匹配到 [ {$count} ]个敏感词：[ {$sensitiveWord} ]<br/>" .
                "替换后为：[ {$stringAfter} ]";
        }
        return $string;

        die;
    }

}

if (!function_exists('check_ids_encode')) {
    /** 复选框ids格式编码
     * @Author: hejiaz
     * @Date: 2020-11-17 16:41:59
     * @Param: $ids     ids文本
     * @Return: boole
     */
    function check_ids_encode(&$ids)
    {
        if ($ids) {
            $ids = ',' . trim($ids, ',') . ',';
        }
        return true;
    }
}

if (!function_exists('check_ids_decode')) {
    /** 复选框ids格式解码
     * @Author: hejiaz
     * @Date: 2020-11-17 16:41:59
     * @Param: $ids     ids文本
     * @Return: boole
     */
    function check_ids_decode($ids)
    {
        if ($ids) {
            return trim($ids, ',');
        }
        return '';
    }
}

if (!function_exists('convert_image_path')) {
    /** 富文本转换图片路径
     * @Author: hejiaz
     * @Date: 2021-04-14 17:36:58
     */
    function convert_image_path(&$content, $fields)
    {
        // TODO 判断 CDN
        // dump(\think\Config::get('view_replace_str')['__CDN__']);

        // 判断是否是ssl
        $http_type = request()->isSsl() ? 'https://' : 'http://';
        $url = $http_type . request()->server()['HTTP_HOST'];

        $fieldsArr = [];
        if (!is_array($fields)) {
            $fieldsArr[] = $fields;
        } else {
            $fieldsArr = $fields;
        }

        // 匹配正则语句
        $pregRule = "/<[img|IMG].*?style=[\'|\"](.*?)[\'|\"].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/";

        /** 更严谨写法 加上图片后缀
         $pregRule = "/<[img|IMG].*?style=[\'|\"](.*?)[\'|\"].*?src=[\'|\"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/";
         */

        $replacement = '<img style="${1}" src="' . $url . '${2}">';

        foreach ($fieldsArr as $key => $value) {
            $content[$value] = preg_replace($pregRule, $replacement, $content[$value]);
        }

        // return $content;
    }
}

if (!function_exists('check_ip_allowed')) {
    /**
     * 检测IP是否允许
     * @param string $ip IP地址
     */
    function check_ip_allowed($ip = null)
    {
        $ip = is_null($ip) ? request()->ip() : $ip;
        $forbiddenipArr = config('site.forbiddenip');
        $forbiddenipArr = !$forbiddenipArr ? [] : $forbiddenipArr;
        $forbiddenipArr = is_array($forbiddenipArr) ? $forbiddenipArr : array_filter(explode("\n", str_replace("\r\n", "\n", $forbiddenipArr)));
        if ($forbiddenipArr && \Symfony\Component\HttpFoundation\IpUtils::checkIp($ip, $forbiddenipArr)) {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
    }
}

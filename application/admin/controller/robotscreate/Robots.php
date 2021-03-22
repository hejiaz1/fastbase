<?php

namespace app\admin\controller\robotscreate;

use app\common\controller\Backend;

/**
 * Robots生成
 *
 * @icon fa fa-circle-o
 */
class Robots extends Backend
{

    public function _initialize()
    {
        parent::_initialize();
    }


    /**
     * 查看
     */
    public function index()
    {
        $server_url = $_SERVER['SERVER_NAME']?"http://".$_SERVER['SERVER_NAME']:"http://".$_SERVER['HTTP_HOST'];

        $config = get_addon_config('robotscreate');
        $default_config = $config['robots_config'];


        if ($this->request->isPost()) {
            $data = input('data', '');
            $c = array(
                array(
                    'name' => 'robots_config',
                    'title' => 'robots配置',
                    'type' => 'text',
                    'content' => [],
                    'value' => $data,
                    'rule' => 'required',
                    'msg' => '',
                    'tip' => '',
                    'ok' => '',
                    'extend' => '',
                )
            );

            set_addon_fullconfig('robotscreate', $c);

            $dataJson = json_decode($data, true);
            $result = '# robots.txt
';
            if ($dataJson['all_spider'] == '1')
            {
                foreach ($dataJson['spider'] as $item) {
                    switch ($item['value']) {
                        case '1':
                            $result .= 'User-agent: '.$item['key'].'
';
                            $result .= 'Disallow: 
';
                            break;
                        case '2':
                            $result .= 'User-agent: '.$item['key'].'
';
                            $result .= 'Disallow: / 
';
                            break;
                    }
                }
                $result .= 'User-agent: *
';
                $result .= 'Disallow:
';
            }

            if (isset($dataJson['delay']))
            {
                $result .= 'Crawl-delay: '. $dataJson['delay'] .'
';
            }

            if (count($dataJson['limit_dir']) > 0)
            {
                foreach($dataJson['limit_dir'] as $item) {
                    $result .= 'Disallow: ' . $item . '
';
                }
            }

            if (count($dataJson['sitemap']) > 0)
            {
                foreach($dataJson['sitemap'] as $item) {
                    $result .= 'Sitemap: ' . $item . '
';
                }
            }

            $f = fopen($_SERVER['DOCUMENT_ROOT'] . "/robots.txt", "w") or die("Unable to open file!");
            fwrite($f, $result);
            fclose($f);


            $this->success();
        }

        $this->view->assign("server_url", $server_url);
        $this->view->assign("default_config", $default_config);

        return $this->view->fetch();
    }

}

<?php

namespace addons\robotscreate;

use app\common\library\Menu;
use think\Addons;

/**
 * 插件
 */
class Robotscreate extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name'    => 'robotscreate/robots',
                'title'   => 'robots生成',
                'icon'    => 'fa fa-bug',
                'sublist' => [
                    ["name"  => "robotscreate/robots/index","title" => "查看"]
                ]
            ]
        ];
        Menu::create($menu);
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete("robotscreate");
        Menu::delete("robotscreate/robots");
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        Menu::enable("robotscreate");
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        Menu::disable("robotscreate");
        return true;
    }

}

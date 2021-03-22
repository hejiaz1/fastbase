<?php
namespace addons\csmsignin;

use app\common\library\Menu;
use think\Addons;

/**
 * 插件
 */
class Csmsignin extends Addons
{

    /**
     * 插件安装方法
     *
     * @return bool
     */
    public function install()
    {
        $menu = [
            [
                'name' => 'csmsignin',
                'title' => 'CSM签到和活动',
                'sublist' => [
                    [
                        'name' => 'csmsignin/conf',
                        'title' => '活动管理',
                        'icon' => 'fa fa-meetup',
                        'sublist' => [
                            [
                                'name' => 'csmsignin/conf/index',
                                'title' => '查看'
                            ],
                            [
                                'name' => 'csmsignin/conf/add',
                                'title' => '添加'
                            ],
                            [
                                'name' => 'csmsignin/conf/edit',
                                'title' => '修改'
                            ],
                            [
                                'name' => 'csmsignin/conf/del',
                                'title' => '删除'
                            ]
                        ]
                    ],
                    [
                        'name' => 'csmsignin/weixinuser',
                        'title' => '微信帐号查询',
                        'icon' => 'fa fa-file-text-o',
                        'sublist' => [
                            [
                                'name' => 'csmsignin/weixinuser/index',
                                'title' => '查看'
                            ]
                        ]
                    ]
                ]
            ]
        ];
        Menu::create($menu);
        return true;
    }

    /**
     * 插件卸载方法
     *
     * @return bool
     */
    public function uninstall()
    {
        Menu::delete("csmsignin");
        return true;
    }

    /**
     * 插件启用方法
     *
     * @return bool
     */
    public function enable()
    {
        Menu::enable("csmsignin");
        return true;
    }

    /**
     * 插件禁用方法
     *
     * @return bool
     */
    public function disable()
    {
        Menu::disable("csmsignin");
        return true;
    }

 
}

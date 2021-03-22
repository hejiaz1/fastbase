<?php
// +----------------------------------------------------------------------
// Csmmeet [ CSMMeet会议室预约系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-26
// +----------------------------------------------------------------------
namespace addons\csmmeet;

use app\common\library\Menu;
use think\Addons;
use think\Request;

/**
 * 插件
 */
class Csmmeet extends Addons
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
                'name' => 'csmmeet',
                'title' => 'CSM会议室预约',
                'sublist' => [
                    [
                        'name' => 'csmmeet/building',
                        'title' => '大楼管理',
                        'icon' => 'fa fa-meetup',
                        'sublist' => [
                            [
                                'name' => 'csmmeet/building/index',
                                'title' => '查看'
                            ],
                            [
                                'name' => 'csmmeet/building/add',
                                'title' => '添加'
                            ],
                            [
                                'name' => 'csmmeet/building/edit',
                                'title' => '修改'
                            ],
                            [
                                'name' => 'csmmeet/building/del',
                                'title' => '删除'
                            ]
                        ]
                    ],
                    [
                        'name' => 'csmmeet/room',
                        'title' => '会议室管理',
                        'icon' => 'fa fa-file-text-o',
                        'sublist' => [
                            [
                                'name' => 'csmmeet/room/index',
                                'title' => '查看'
                            ],
                            [
                                'name' => 'csmmeet/room/add',
                                'title' => '添加'
                            ],
                            [
                                'name' => 'csmmeet/room/edit',
                                'title' => '修改'
                            ],
                            [
                                'name' => 'csmmeet/room/del',
                                'title' => '删除'
                            ]
                        ]
                    ],
                    [
                        'name' => 'csmmeet/apply',
                        'title' => '预约审核管理',
                        'icon' => 'fa fa-file-text-o',
                        'sublist' => [
                            [
                                'name' => 'csmmeet/apply/index',
                                'title' => '查看'
                            ],
                            [
                                'name' => 'csmmeet/apply/add',
                                'title' => '添加'
                            ],
                            [
                                'name' => 'csmmeet/apply/edit',
                                'title' => '修改'
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
        Menu::delete("csmmeet");
        return true;
    }

    /**
     * 插件启用方法
     *
     * @return bool
     */
    public function enable()
    {
        Menu::enable("csmmeet");
        return true;
    }

    /**
     * 插件禁用方法
     *
     * @return bool
     */
    public function disable()
    {
        Menu::disable("csmmeet");
        return true;
    }

    /**
     * 会员中心边栏后
     * @return mixed
     * @throws \Exception
     */
    public function userSidenavAfter()
    {
        $request = Request::instance();
        $actionname = strtolower($request->action());
        $data = [
            'actionname' => $actionname
        ];
        return $this->fetch('view/hook/user_sidenav_after', $data);
    }
}

// +----------------------------------------------------------------------
// Csmmeet [ CSMMeet会议室预约系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-26
// +----------------------------------------------------------------------
loader.define(function (require, exports, module) {

    var pageview = {};

    // 模块初始化定义
    pageview.init = function () {
        bui.init({
            id: "#app"
        });
        navTab();
    }

    // 底部导航
    function navTab() {

        //menu在tab外层,menu需要传id
        var tab = bui.tab({
            id: "#tabDynamic",
            menu: "#tabDynamicNav",
            animate: false,
            // 1: 声明是动态加载的tab
            autoload: true,
        })
        // 2: 监听加载后的事件
        tab.on("to", function (index) {
            switch (index) {
                case 0:
                    //alert('require_once frame1');
                    loader.require(["pages/csmmeet/frame1"], function (mod) {
                        //mod.init();
                    });
                    break;
                case 1:
                    // 这里是加载脚本第一次的时候触发
                    //alert('require_once frame2');
                    loader.require(["pages/csmmeet/frame2"], function (mod) {
                        //mod.init();
                    });
                    break;
                case 2:
                    // 这里是加载脚本第一次的时候触发
                    //alert('require_once frame2');
                    loader.require(["pages/csmmeet/frame3"], function (mod) {
                        //mod.init();
                    });
                    break;
            }
        }).to(0);
    }

    // 初始化
    pageview.init();

    // 输出模块
    return pageview;

})

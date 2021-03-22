// +----------------------------------------------------------------------
// Csmmeet [ CSMMeet会议室预约系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-26
// +----------------------------------------------------------------------
loader.define(function (require, exports, module) {

    core_vue({
        el: "#app_frame2",
        data: {
            dual: "x",
            title: "xx",
            sessionuser: "",
            version: ""
        },
        mounted: function () {
            var that = this;
     
            core_bui_ajax(
                "indexajax/getFrame3", {},
                function (res) {
                    that.sessionuser = res.data.sessionuser;
                    that.version = res.data.version;
                }
            );
        },
        methods: {
            submitLogout: function () {
                var that = this;
                core_bui_storage_set("csmlogintoken", "");
                core_bui_router("pages/csmmeet/login", { "redirect_type": "refresh" });
            },
            clickMyApply: function () {
                var that = this;
                core_bui_router("pages/csmmeet/myapply");
            }
        }
    });

})

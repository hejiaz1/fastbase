// +----------------------------------------------------------------------
// Csmmeet [ CSMMeet会议室预约系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-26
// +----------------------------------------------------------------------
loader.define(function (require, exports, module) {

    //如果本地有token，则先校验登录状态
    var logintoken = core_bui_storage_get("csmlogintoken");
    var ll = logintoken + "";
    if (logintoken != null && logintoken != "") {
        core_bui_ajax(
            "indexajax/loginByToken", {
            logintoken: ll
        }, function (res) {
            if (res.data.islogin == "1") {
                core_bui_router("pages/csmmeet/main");
            }
        }
        );
    }


    //没有登录的处理
    var v = core_vue({
        el: "#app_login",
        data: {
            title: "首页",
            user: {}
        },
        mounted: function () {
            var that = this;
            setTimeout(function () {
                var uiTab = bui.tab({
                    id: "#uiTab"
                });
            }, 500);
        },
        methods: {
            sendLoginCcodeByEmailAjax: function () {
                var that = this;
                var user_email = $("#user_email").val();
                var ff = core_checkrequired([user_email], ["邮箱"]);
                if (ff === false) {
                    return;
                }

                $(".readysend").css("display", "none !important");
                $(".sending").css("display", "block !important");
                $(".sended").css("display", "none !important");

                core_bui_ajax(
                    "indexajax/sendLoginCcodeByEmail", {
                    user_email: user_email,
                },
                    function (res) {
                        core_bui_toast("验证码已经发送");
                        $("#hassendccode").val("1");
                        $("#user_email").attr("readonly", true);
                        that.user = res.data.user;

                        $(".readysend").css("display", "none !important");
                        $(".sending").css("display", "none !important");
                        $(".sended").css("display", "block !important");
                        if (that.user == null) {
                            $("#div_user_name").css("display", "-webkit-box");
                            $("#div_nick_name").css("display", "-webkit-box");
                            $("#div_user_mobile").css("display", "-webkit-box");
                            $("#div_user_password").css("display", "-webkit-box");
                            that.user = {};
                        }
                    },
                    function (res) {
                        $(".readysend").css("display", "block !important");
                        $(".sending").css("display", "none !important");
                        $(".sended").css("display", "none !important");
                    }
                );

            },
            loginpasswordAjax: function () {
                var that = this;
                var login_user_email = $("#login_user_email").val();
                var login_user_psd = $("#login_user_psd").val();
                var ff = core_checkrequired([login_user_email, login_user_psd], ["邮箱", "密码"]);
                if (ff == false) {
                    return;
                }
                core_bui_ajax(
                    "indexajax/loginpassword", {
                    login_user_email: login_user_email,
                    login_user_psd: login_user_psd
                },
                    function (res) {
                        if (res.data.loginsuccess == "1") {
                            //alert(res.data.logintoken);
                            core_bui_storage_set("csmlogintoken", res.data.logintoken);
                            core_bui_router("pages/csmmeet/main");
                        } else {
                            core_bui_toast(res.data.failuremsg);
                        }
                    }
                );
            },
            loginAjax: function () {
                var that = this;
                if ($("#hassendccode").val() != "1") {
                    core_bui_toast("请先发送验证码!");
                    return;
                }

                var user_email = $("#user_email").val();
                var user_name = $("#user_name").val();
                var nick_name = $("#nick_name").val();
                var user_mobile = $("#user_mobile").val();
                var loginccode = $("#loginccode").val();
                var user_password = $("#user_password").val();
                var ff = core_checkrequired([user_email, user_name,nick_name, user_mobile, loginccode, user_password], ["邮箱", "登录名", "姓名", "手机", "验证码", "登录密码"]);
                if (ff === false) {
                    return;
                }
                core_bui_ajax(
                    "indexajax/login", {
                    user_email: user_email,
                    user_name: user_name,
                    nick_name: nick_name,
                    user_mobile: user_mobile,
                    loginccode: loginccode,
                    user_password: user_password
                },
                    function (res) {
                        if (res.data.loginsuccess == "1") {
                            core_bui_storage_set("csmlogintoken", res.data.logintoken);
                            core_bui_router("pages/csmmeet/main");
                        } else {
                            core_bui_toast(res.data.failuremsg);
                        }
                    }
                );
            },
        },
    });

})

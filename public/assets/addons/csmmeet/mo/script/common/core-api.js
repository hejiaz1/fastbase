// +----------------------------------------------------------------------
// Csmmeet [ CSMMeet会议室预约系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-26
// +----------------------------------------------------------------------
function core_checkrequired(fields, fieldsnames) {
    for (var i = 0, j = fields.length; i < j; i++) {
        if (fields[i] == null || fields[i] == "") {
            core_bui_toast("请填写" + fieldsnames[i]);
            return false;
        }
    }
    return true;
}

/*
v-for="item in data.pu_srcs"
v-if="data.taobao_items"
v-if="list!=null && list.length==0"
{{item.shop}}
v-on:click="submitApply"

    var v = core_vue({
        el: "#app",
        data: {
            title: "会议室申请",
        },
        mounted: function() {
            var that = this;
            core_bui_getParam(function(param) {
                getDataRender(that);
            });
        },
        methods: {
            submitApply:function(){
                var that = this;
                //var tb_url = $api.attr(event.currentTarget, 'tb_url');
                core_bui_ajax(
                    "index/submitRoomApplyAjax",{
                        apply_csmmeet_room_id:$("#apply_csmmeet_room_id").val(),
                    },
                    function(res){
                        dialog.close();
                        getDataRender(that);
                    }
                );
            }
        },
    });
*/
function core_vue(value) {
    let vue = new Vue(value);
    return vue;
}






//日期格式化函数   
/**
        var myDate = new Date();
        console.log("年月日S：" + myDate.toLocaleDateString());
        console.log("时分秒：" + myDate.toLocaleTimeString());
        console.log("当前年份：" + myDate.getFullYear());
        console.log("当前月份：" + myDate.getMonth());
        console.log("当前日期：" + myDate.getDate());
        console.log("当前星期：" + myDate.getDay());
        console.log("当前时间为：" + myDate.pattern("yyyy-MM-dd HH:mm:ss"));
        console.log("小时字符串：" + myDate.pattern("yyyy-MM-dd HH:mm:ss").substring(11,13));
        console.log("分钟字符串：" + myDate.pattern("yyyy-MM-dd HH:mm:ss").substring(14,16));
        console.log("秒字符串：" + myDate.pattern("yyyy-MM-dd HH:mm:ss").substring(17,19));
*/
Date.prototype.pattern = function (fmt) {
    var o = {
        "M+": this.getMonth() + 1, //月份           
        "d+": this.getDate(), //日           
        "h+": this.getHours() % 12 == 0 ? 12 : this.getHours() % 12, //小时           
        "H+": this.getHours(), //小时           
        "m+": this.getMinutes(), //分           
        "s+": this.getSeconds(), //秒           
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度           
        "S": this.getMilliseconds() //毫秒           
    };
    var week = {
        "0": "/u65e5",
        "1": "/u4e00",
        "2": "/u4e8c",
        "3": "/u4e09",
        "4": "/u56db",
        "5": "/u4e94",
        "6": "/u516d"
    };
    if (/(y+)/.test(fmt)) {
        fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    }
    if (/(E+)/.test(fmt)) {
        fmt = fmt.replace(RegExp.$1, ((RegExp.$1.length > 1) ? (RegExp.$1.length > 2 ? "/u661f/u671f" : "/u5468") : "") + week[this.getDay() + ""]);
    }
    for (var k in o) {
        if (new RegExp("(" + k + ")").test(fmt)) {
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        }
    }
    return fmt;
}



/**
Vue显示时间
{{item.createtime|core_timestampToTime("yyyy-MM-dd HH:mm:ss")}}
*/
Vue.filter('core_timestampToTime', function (value, format) {
    var myDate = new Date();
    return myDate.pattern(format);
});
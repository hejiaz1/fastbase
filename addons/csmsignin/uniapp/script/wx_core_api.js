// +----------------------------------------------------------------------
// Csmmeet [ CSM 端口基础类 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-28
// +----------------------------------------------------------------------

import Appconfig from '@/app-config.js'
import Appdict from '@/script/common/app-dict.js'
import Coreapi from '@/script/common/core-api.js'
import Appapi from '@/script/wx_core_api.js'


/*
core_bui_ajax(
    "index/sendLoginCcodeByEmailAjax",{id:param.id},
    function(res){
        that.title = res.data.title;
    }
);
*/
function core_bui_ajax(url, data, func, failurefunc) {

	var logintoken = core_bui_storage_get("csmlogintoken");
	console.log("core_bui_storage_get csmlogintoken"+logintoken);
	var ll = logintoken + "";

	url = Appconfig.window._cfg.remote_baseurl + "/" + url;
	console.log("request:" + url);
	console.log(data);

	uni.request({
		url: url,
		data: data,
		header: {
			csmlogintoken: ll
		},
		success: (sr) => {
			// 成功回调
			console.log(sr);
			var res = sr.data;
			if (res.code == "1") {
				func(sr);
			} else if (res.code == "0") {
				core_bui_toast(res.msg);
				console.log("res.msg="+res.msg);
				if (res.url != null && res.url != "") {
					var url = res.url;
					var rurl = "#" + url.substr(url.indexOf("/public/") + 8, url.indexOf(".html") - url.indexOf("/public/") - 8);
					//alert(rurl);
					core_bui_router(rurl, {
						"redirect_type": "refresh"
					});
				}
				if (failurefunc) {
					failurefunc(sr);
				}
			} else {
				core_bui_toast("数据交互异常");
			}
		}
	});
}

/*
获取页面传传
core_bui_getParam(function(param) {
  //console.log(param);
});
unimplement
*/
function core_bui_getParam(func) {
	var getParams = bui.getPageParams();
	getParams.done(function(param) {
		func(param);
	});
} 

function core_bui_toast(msg) {
	console.log("core_bui_toast="+msg);
	uni.showToast({  
	    title: msg,
	    duration: 2000,
		icon:"none"
	});
}

function core_bui_storage_get(key) {
	return uni.getStorageSync(key);
}

function core_bui_storage_set(key, value) {
	uni.setStorageSync(key, value);
}
/*
option={
    "redirect_type":"router"//default,other:refresh
}
unimplement
*/
function core_bui_router(modulename, option) {
	if (option == null) {
		option = {};
	}
	if (option.redirect_type == null) {
		option.redirect_type = "router";
	}
	if (option.redirect_type == "refresh") {
		//window.location.href = modulename;
		router.load({
			url: modulename,
			param: {},
			cache: false
		});
		window.location.reload();
	} else {
		router.load({
			url: modulename,
			param: {},
			cache: false
		});
	}
}
/*
unimplement
*/
function core_bui_confirm(msg, func) {
	bui.confirm(msg, function(e) {
		var text = $(e.target).text();
		if (text == "确定") {
			func();
		}
		this.close()
	});
}
/**
 * unimplement
 */
function core_bui_datetimepicker(jquerydomkey, onchangeFunc) {
	var myDate = new Date();
	var input2 = $(jquerydomkey);
	var uiPickerdate = bui.pickerdate({
		handle: jquerydomkey,
		bindValue: true,
		value: myDate.pattern("yyyy-MM-dd"),
		// input 显示的日期格式
		formatValue: "yyyy-MM-dd",
		cols: {
			hour: "none",
			minute: "none",
			second: "none"
		},
		onChange: function(value) {
			if (value != null && value != "") {
				//alert(value);
				onchangeFunc(value);
			}
		}
	});
}

/**
 * unimplement
 */
var _alldialog = {};

function core_bui_showdialog(jquerydomkey) {
	if (_alldialog[jquerydomkey] == null || _alldialog[jquerydomkey] == "") {
		var dialog = bui.dialog({
			id: jquerydomkey,
			position: "top",
			effect: "fadeInUp",
			onMask: function(argument) {
				dialog.close();
			}
		});
		_alldialog[jquerydomkey] = dialog;
	}
	_alldialog[jquerydomkey].open();
}
/**
 * unimplement
 */
function core_bui_closedialog(jquerydomkey) {
	if (_alldialog[jquerydomkey] != null && _alldialog[jquerydomkey] != "") {
		_alldialog[jquerydomkey].close();
	}
}


export default {
	core_bui_ajax,
	core_bui_getParam,
	core_bui_toast,
	core_bui_storage_get,
	core_bui_storage_set,
	core_bui_router,
	core_bui_confirm,
	core_bui_datetimepicker,
	core_bui_showdialog,
	core_bui_closedialog
}

// +----------------------------------------------------------------------
// Csmmeet [ CSMMeet会议室预约系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-26
// +----------------------------------------------------------------------
window._dict = {
	"fa_csmmeet_apply.auditstatus": {
		"-2": "申请已撤销",
		"-1": "审核退回",
		"0": "待审核",
		"1": "审核通过"
	}
}

/**
获取字段值对应的名称
alert(core_dict("fa_spmet_apply.auditstatus","1"));
*/
function core_dict(setname, val) {
	return window._dict[setname][val];
}

/**
Vue显示替换中文
{{item.auditstatus|core_dict("fa_spmet_apply.auditstatus")}}
*/
Vue.filter('core_dict', function (value, setname) {
	return core_dict(setname, value)
});
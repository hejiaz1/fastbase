/*
 * @Author         : hejiaz
 * @Date           : 2021-03-22 10:40:41
 * @FilePath       : \public\assets\js\backend\example\customform.js
 * @LastEditors    : hejiaz
 * @LastEditTime   : 2021-04-07 17:32:54
 * @Description    : 自定义表单示例
 */
define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            //因为日期选择框不会触发change事件，导致无法刷新textarea，所以加上判断
            $(document).on("dp.change", "#second-form .datetimepicker", function () {
                $(this).parent().prev().find("input").trigger("change");
            });
            $(document).on("fa.event.appendfieldlist", "#second-form .btn-append", function (e, obj) {
                Form.events.selectpage(obj);
                Form.events.datetimepicker(obj);
                Form.events.minicolors(obj);
            });
            Form.api.bindevent($("form[role=form]"), function (data, ret) {
                Layer.alert(data.data);
            });
        },
    };
    return Controller;
});
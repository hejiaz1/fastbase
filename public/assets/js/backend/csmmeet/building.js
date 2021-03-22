define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'csmmeet/building/index' + location.search,
                    add_url: 'csmmeet/building/add',
                    edit_url: 'csmmeet/building/edit',
                    del_url: 'csmmeet/building/del',
                    multi_url: 'csmmeet/building/multi',
                    table: 'csmmeet_building',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
                        { field: 'name', title: __('Name') },
                        //{field: 'weigh', title: __('Weigh')},
                        { field: 'status', title: __('Status'), searchList: { "normal": __('Normal'), "hidden": __('Hidden') }, formatter: Table.api.formatter.status },
                        //{field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        //{field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        //{field: 'user_id', title: __('User_id')},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table, events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate,
                            //formatter: Table.api.formatter.buttons,
                            buttons: [
                                {
                                    name: 'screenbuilding',
                                    text: __('屏幕投屏'),
                                    classname: 'btn btn-xs btn-success btn-click',
                                    icon: 'fa fa-file',
                                    click: function (res, row) {
                                    	Layer.open({
                                    	    title: '屏幕投屏',
                                    	    content: '\
                                    	        <div class="form-group">\
                                    	            <label class="control-label col-xs-12 col-sm-6">每页显示条数:</label>\
                                    	            <div class="col-xs-12 col-sm-6">\
                                    	                <input   data-rule="required" class="form-control" id="screenpagesize" type="text" value="20">\
                                    	            </div>\
	                                	            <label class="control-label col-xs-12 col-sm-6">页面刷新时间(秒):</label>\
	                                	            <div class="col-xs-12 col-sm-6">\
	                                	                <input   data-rule="required" class="form-control" id="screensettimeout" type="text" value="5">\
	                                	            </div>\
                                    	        </div>\
                                    	    ',
                                    	    yes: function (index, layero) {
                                    	    	window.open(Fast.api.cdnurl("/index/csmmeet.screenbuilding/index.html?building_id=" + row.id+"&pagesize="+$("#screenpagesize").val()+"&settimeout="+$("#screensettimeout").val()));                    
                                    	    	Layer.closeAll(); 
                                    	    }           
                                    	});
                                    	
                                    },
                                },
                            ]
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
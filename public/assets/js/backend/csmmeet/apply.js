define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'csmmeet/apply/index' + location.search,
                    add_url: 'csmmeet/apply/add',
                    edit_url: 'csmmeet/apply/edit',
                    del_url: 'csmmeet/apply/del',
                    multi_url: 'csmmeet/apply/multi',
                    table: 'csmmeet_apply',
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
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'roomname', title: __('Csmmeet_room_id')},
                        {field: 'buildingname', title: __('所属大楼')},
                        {field: 'title', title: __('Title')},
                        {field: 'username', title: __('Username')},
                        {field: 'applydatetime', title: __('Applydate')},
                        //{field: 'beginhour', title: __('Beginhour')},
                        //{field: 'beginmin', title: __('Beginmin')},
                        //{field: 'endhour', title: __('Endhour')},
                        //{field: 'endmin', title: __('Endmin')},
                        //{field: 'userkey', title: __('Userkey')},
                        //{field: 'userkeyfrom', title: __('Userkeyfrom')},
                        
                        //{field: 'audituser_id', title: __('Audituser_id')},
                        {field: 'audituser', title: __('Audituser')},
                        //{field: 'weigh', title: __('Weigh')},
                        {field: 'auditstatus', title: __('Auditstatus'), searchList: {"-1":__('Auditstatus -1'),"0":__('Auditstatus 0'),"1":__('Auditstatus 1')}, formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        //{field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        //{field: 'user_id', title: __('User_id')},
                        //{field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                        {
                        	field: 'operate', 
                        	title: __('Operate'), 
                        	table: table, events: Table.api.events.operate, 
                        	//formatter: Table.api.formatter.operate,
                        	formatter: Table.api.formatter.buttons,
                            buttons: [
                                {
                                    name: 'auditstatus1',
                                    text: __('审核退回'),
                                    classname: 'btn btn-xs btn-primary btn-ajax',
                                    icon: 'fa fa-file',
                                    url: 'csmmeet/apply/submitAuditAjax?id={ids}&auditstatus=-1',
                                    refresh: true,
                                    visible: function (row) {
                                        if(row.auditstatus=='0'||row.auditstatus=='1'){
                                        	return true;
                                        }else{
                                        	return false;
                                        }    
                                    }                                    
                                },
                                {
                                    name: 'auditstatus2',
                                    text: __('审核通过'),
                                    classname: 'btn btn-xs btn-success btn-ajax',
                                    icon: 'fa fa-file',
                                    url: 'csmmeet/apply/submitAuditAjax?id={ids}&auditstatus=1',
                                    refresh: true,
                                    visible: function (row) {
                                        if(row.auditstatus=='0'||row.auditstatus=='-1'){
                                        	return true;
                                        }else{
                                        	return false;
                                        }    
                                    }    
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
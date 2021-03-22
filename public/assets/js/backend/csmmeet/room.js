define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'csmmeet/room/index' + location.search,
                    add_url: 'csmmeet/room/add',
                    edit_url: 'csmmeet/room/edit',
                    del_url: 'csmmeet/room/del',
                    multi_url: 'csmmeet/room/multi',
                    table: 'csmmeet_room',
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
                        { field: 'buildingname', title: __('Csmmeet_building_id') },
                        { field: 'name', title: __('Name') },
                        { field: 'needaudit',
                            title: __('Needaudit'),
                            searchList: {
                                "Y": __('Needaudit y'),
                                "N": __('Needaudit n')
                            },
                            formatter: Table.api.formatter.normal
                        },
                        //{field: 'weigh', title: __('Weigh')},
                        { field: 'status', title: __('Status'), searchList: { "normal": __('Normal'), "hidden": __('Hidden') }, formatter: Table.api.formatter.status },
                        //{field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        //{field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        //{field: 'user_id', title: __('User_id')},
                        { field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate }

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
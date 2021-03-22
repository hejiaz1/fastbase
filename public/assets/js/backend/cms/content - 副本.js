define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init();
            this.table.first();
            this.table.second();
        },

        // index: function () {

        //     // 初始化表格参数配置
        //     Table.api.init({
        //         extend: {
        //             index_url: 'cms/content/index' + location.search,
        //             add_url: 'cms/content/add',
        //             edit_url: 'cms/content/edit',
        //             del_url: 'cms/content/del',
        //             multi_url: 'cms/content/multi',
        //             import_url: 'cms/content/import',
        //             table: 'cms_content',
        //         }
        //     });

        //     var table = $("#table");

        //     // 初始化表格
        //     table.bootstrapTable({
        //         url: $.fn.bootstrapTable.defaults.extend.index_url,
        //         pk: 'id',
        //         sortName: 'weigh',
        //         columns: [
        //             [
        //                 {checkbox: true},
        //                 {field: 'id', title: __('Id')},
        //                 {field: 'column_id', title: __('Column_id')},
        //                 {field: 'admin_id', title: __('Admin_id')},
        //                 {field: 'category_id', title: __('Category_id')},
        //                 {field: 'title', title: __('Title'), operate: 'LIKE'},
        //                 {field: 'keywords', title: __('Keywords'), operate: 'LIKE'},
        //                 {field: 'subtitle', title: __('Subtitle'), operate: 'LIKE'},
        //                 {field: 'title1', title: __('Title1'), operate: 'LIKE'},
        //                 {field: 'title2', title: __('Title2'), operate: 'LIKE'},
        //                 {field: 'title3', title: __('Title3')},
        //                 {field: 'author', title: __('Author'), operate: 'LIKE'},
        //                 {field: 'editor', title: __('Editor'), operate: 'LIKE'},
        //                 {field: 'source', title: __('Source'), operate: 'LIKE'},
        //                 {field: 'releasetime', title: __('Releasetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
        //                 {field: 'list_image_alt', title: __('List_image_alt'), operate: 'LIKE'},
        //                 {field: 'list_image', title: __('List_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
        //                 {field: 'show_image', title: __('Show_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
        //                 {field: 'show1_image', title: __('Show1_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
        //                 {field: 'annex_file', title: __('Annex_file'), operate: false},
        //                 {field: 'flag', title: __('Flag'), searchList: {"hot":__('Flag hot'),"recommend":__('Flag recommend')}, operate:'FIND_IN_SET', formatter: Table.api.formatter.label},
        //                 {field: 'pvnum', title: __('Pvnum')},
        //                 {field: 'ishow', title: __('Ishow')},
        //                 {field: 'istop', title: __('Istop')},
        //                 {field: 'top_duetime', title: __('Top_duetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
        //                 {field: 'isindex', title: __('Isindex')},
        //                 {field: 'index_deutime', title: __('Index_deutime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
        //                 {field: 'href', title: __('Href'), operate: 'LIKE'},
        //                 {field: 'isblank', title: __('Isblank')},
        //                 {field: 'seotitle', title: __('Seotitle'), operate: 'LIKE'},
        //                 {field: 'seokeywords', title: __('Seokeywords'), operate: 'LIKE'},
        //                 {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status},
        //                 {field: 'weigh', title: __('Weigh'), operate: false},
        //                 {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
        //                 {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
        //                 {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
        //             ]
        //         ]
        //     });

        //     // 为表格绑定事件
        //     Table.api.bindevent(table);
        // },


        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'cms/content/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title'), align: 'left'},
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '130px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'cms/content/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'cms/content/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
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
        },

        table: {
            first: function () {
                // 表格1
                var table1 = $("#table1");
                table1.bootstrapTable({
                    url: 'example/tablelink/table1',
                    toolbar: '#toolbar1',
                    sortName: 'id',
                    search: false,
                    columns: [
                        [
                            // {field: 'state', checkbox: true,},
                            {field: 'id', title: 'ID'},
                            {field: 'username', title: __('Nickname')},
                            {
                                field: 'operate', title: __('Operate'), table: table1, events: Table.api.events.operate, buttons: [
                                    {
                                        name: 'log',
                                        title: '日志列表',
                                        text: '日志列表',
                                        icon: 'fa fa-list',
                                        classname: 'btn btn-primary btn-xs btn-click',
                                        click: function (e, data) {
                                            $("#myTabContent2 .form-commonsearch input[name='username']").val(data.username);
                                            $("#myTabContent2 .btn-refresh").trigger("click");
                                        }
                                    }
                                ], formatter: Table.api.formatter.operate
                            }
                        ]
                    ]
                });

                // 为表格1绑定事件
                Table.api.bindevent(table1);
            },
            second: function () {
                // 表格2
                var table2 = $("#table2");
                table2.bootstrapTable({
                    url: 'example/tablelink/table2',
                    extend: {
                        index_url: '',
                        add_url: '',
                        edit_url: '',
                        del_url: '',
                        multi_url: '',
                        table: '',
                    },
                    toolbar: '#toolbar2',
                    sortName: 'id',
                    search: false,
                    columns: [
                        [
                            {field: 'state', checkbox: true,},
                            {field: 'id', title: 'ID'},
                            {field: 'username', title: __('Nickname')},
                            {field: 'title', title: __('Title')},
                            {field: 'url', title: __('Url'), align: 'left', formatter: Table.api.formatter.url},
                            {field: 'ip', title: __('ip')},
                            {field: 'createtime', title: __('Createtime'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
                        ]
                    ]
                });

                // 为表格2绑定事件
                Table.api.bindevent(table2);
            }
        },
    };
    return Controller;
});



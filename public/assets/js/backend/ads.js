define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ads/index' + location.search,
                    add_url: 'ads/add',
                    edit_url: 'ads/edit',
                    del_url: 'ads/del',
                    multi_url: 'ads/multi',
                    import_url: 'ads/import',
                    table: 'ads',
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
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate},

                        {field: 'category_name', title: __('Category_id'),operate: false,},
                        // {field: 'type', title: __('Type'), searchList: {"1":__('Type 1'),"2":__('Type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        // {field: 'title', title: __('Title'), operate: 'LIKE'},
                        // {field: 'title1', title: __('Title1'), operate: 'LIKE'},
                        // {field: 'title2', title: __('Title2'), operate: 'LIKE'},

                        {field: 'show_image', title: __('Show_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'imgalt', title: __('Imgalt'), operate: 'LIKE'},

                        {
                            field: 'href',
                            title: __('Href'),
                            formatter: Table.api.formatter.url,
                            operate: 'LIKE',
                        },
                        {field: 'isblank', title: __('Isblank'), operate: false, formatter: Table.api.formatter.toggle},

                        // {field: 'icon_image', title: __('Icon_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        // {field: 'show1_image', title: __('Show1_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        // {field: 'show2_image', title: __('Show2_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        // {field: 'show3_image', title: __('Show3_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        // {field: 'imgalt1', title: __('Imgalt1'), operate: 'LIKE'},
                        // {field: 'imgalt2', title: __('Imgalt2'), operate: 'LIKE'},
                        // {field: 'imgalt3', title: __('Imgalt3'), operate: 'LIKE'},

                        {field: 'istop', title: __('Istop'), operate: false, sortable: true, formatter: Table.api.formatter.toggle},

                        // {field: 'top_stime', title: __('Top_stime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        // {field: 'top_etime', title: __('Top_etime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},

                        {field: 'weigh', title: __('Weigh'), operate: false},
                        {field: 'status', title: __('Status'), searchList: {"normal":__('Status normal'),"hidden":__('Status hidden')}, formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
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
                url: 'ads/recyclebin' + location.search,
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
                                    url: 'ads/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'ads/destroy',
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
        }
    };
    return Controller;
});
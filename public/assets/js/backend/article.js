define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url : 'article/index' + location.search,
                    add_url   : 'article/add',
                    edit_url  : 'article/edit',
                    del_url   : 'article/del',
                    multi_url : 'article/multi',
                    import_url: 'article/import',
                    table     : 'article',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',

                // 更多配置参数可参考：https://doc.fastadmin.net/doc/190.html
                // 亦可以参考require-table.js中defaults的配置
                // 快捷搜索,这里可在控制器定义快捷搜索的字段
                search: true,
                //启用普通表单搜索
                commonSearch: true,
                //启用跨页选择
                // maintainSelected: true,


                searchFormVisible: true,
                searchFormTemplate: 'customformtpl',

                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate},
                        {field: 'category_name', title: __('Category_id')},
                        // {field: 'admin_id', title: __('Admin_id')},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        // {field: 'keywords', title: __('Keywords'), operate: 'LIKE'},
                        {field: 'subtitle', title: __('Subtitle'), operate: 'LIKE'},
                        // {field: 'title1', title: __('Title1'), operate: 'LIKE'},
                        // {field: 'title2', title: __('Title2'), operate: 'LIKE'},
                        // {field: 'author', title: __('Author'), operate: 'LIKE'},
                        // {field: 'editor', title: __('Editor'), operate: 'LIKE'},
                        // {field: 'source', title: __('Source'), operate: 'LIKE'},
                        // {field: 'source_href', title: __('Source_href'), operate: 'LIKE'},
                        // {field: 'releasetime', title: __('Releasetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        // {field: 'list_image_alt', title: __('List_image_alt'), operate: 'LIKE'},
                        {field: 'list_image', title: __('List_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        // {field: 'show_image_alt', title: __('Show_image_alt'), operate: 'LIKE'},
                        // {field: 'show_image', title: __('Show_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        // {field: 'annex_files', title: __('Annex_files'), operate: false},
                        // {field: 'flag', title: __('Flag'), searchList: {"hot":__('Flag hot'),"recommend":__('Flag recommend')}, operate:'FIND_IN_SET', formatter: Table.api.formatter.label},
                        {field: 'pvnum', title: __('Pvnum')},

                        // {field: 'ishow', title: __('Ishow'), searchList: {"1":__('Ishow 1'),"2":__('Ishow 2')}, formatter: Table.api.formatter.normal},
                        {
                            field: 'ishow',
                            title: __('Ishow'),
                            align: 'center',
                            table: table,
                            formatter: Table.api.formatter.toggle
                        },

                        {field: 'weigh', title: __('Weigh'), operate: false},
                        // {field: 'seotitle', title: __('Seotitle'), operate: 'LIKE'},
                        // {field: 'seokeywords', title: __('Seokeywords'), operate: 'LIKE'},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            // //绑定TAB事件
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                // var options = table.bootstrapTable(tableOptions);
                var ishowStr = $(this).attr("href").replace('#', '');
                var options = table.bootstrapTable('getOptions');
                options.pageNumber = 1;
                options.queryParams = function (params) {
                    // params.filter = JSON.stringify({type: typeStr});
                    params.ishow = ishowStr;
                    return params;
                };
                table.bootstrapTable('refresh', {});
                return false;

            });
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
                url: 'article/recyclebin' + location.search,
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
                                    url: 'article/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'article/destroy',
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
            },
            formatter: {//渲染的方法
                category_name: function (value, row, index) {
                    return '<div class="input-group input-group-sm" style="width:250px;"><input type="text" class="form-control input-sm" value="' + value + '"><span class="input-group-btn input-group-sm"><a href="' + value + '" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-link"></i></a></span></div>';
                },

                // ip: function (value, row, index) {
                //     return '<a class="btn btn-xs btn-ip bg-success"><i class="fa fa-map-marker"></i> ' + value + '</a>';
                // },
                // browser: function (value, row, index) {
                //     //这里我们直接使用row的数据
                //     return '<a class="btn btn-xs btn-browser">' + row.useragent.split(" ")[0] + '</a>';
                // },
                // custom: function (value, row, index) {
                //     //添加上btn-change可以自定义请求的URL进行数据处理
                //     return '<a class="btn-change text-success" data-url="example/bootstraptable/change" data-confirm="确认切换状态？" data-id="' + row.id + '"><i class="fa ' + (row.title == '' ? 'fa-toggle-on fa-flip-horizontal text-gray' : 'fa-toggle-on') + ' fa-2x"></i></a>';
                // },
            },
        }
    };
    return Controller;
});
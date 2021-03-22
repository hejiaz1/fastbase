define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init();
            this.table.first();
            this.table.second();
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
            console.log(location)

            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            formatter: {
                subnode: function (value, row, index) {
                    // console.log(row)
                    if(row.haschild){
                        // return '<a href="javascript:;" data-toggle="tooltip" title="' + __('Toggle sub menu') + '" data-id="' + row.id + '" data-pid="' + row.pid + '" class="btn btn-xs ' + (row.haschild == 1 ? 'btn-success' : 'btn-default disabled') + ' btn-node-sub"><i class="fa fa-sitemap"></i></a>';
                        return '<a href="javascript:;" title="' + __('Toggle sub menu') + '" data-id="' + row.id + '" data-pid="' + row.pid + '" class="btn btn-xs ' + (row.haschild == 1 ? 'btn-success' : 'btn-default disabled') + ' btn-node-sub"><i class="fa fa-sitemap"></i></a>';
                    }else{
                        return '<a href="javascript:;" title="' + __('Content') + '" data-id="' + row.id + '" data-pid="' + row.pid + '" class="btn btn-primary btn-xs btn-click btn-node-sub btn-check-content" ><i class="fa fa-th-list"></i></a>';

                    }
                }
            },

            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },

        },


        table: {
            first: function () {
                // 表格1
                var firstTable = $("#firstTable");

                firstTable.bootstrapTable({
                    url: 'cms/column/selectpage',
                    toolbar: '#toolbar1',
                    sortName: 'id',
                    search: false,
                    commonSearch: false,
                    pagination: false, // 不展示分页
                    escape: false,   // tr不原样输出

                    columns: [
                        [
                            {field: 'id', title: 'ID'},
                            {field: 'name', title: __('ColumnName'),align: 'left',},
                            {
                                field: 'operate',
                                title: '<a href="javascript:;" class="btn btn-success btn-xs btn-toggle"><i class="fa fa-chevron-up"></i></a>',
                                operate: false,

                                table: firstTable,
                                events: Table.api.events.operate,

                                formatter: Controller.api.formatter.subnode
                            },

                        ]
                    ],
                });

                //表格内容渲染前
                firstTable.on('pre-body.bs.table', function (e, data) {
                    var options = firstTable.bootstrapTable("getOptions");
                    options.escape = true;
                });
                //当内容渲染完成后
                firstTable.on('post-body.bs.table', function (e, data) {
                    var options = firstTable.bootstrapTable("getOptions");
                    options.escape = false;
                    //默认隐藏所有子节点
                    //$("a.btn[data-id][data-pid][data-pid!=0]").closest("tr").hide();
                    $(".btn-node-sub.disabled").closest("tr").hide();

                    //显示隐藏子节点
                    $(".btn-node-sub").off("click").on("click", function (e) {
                        var status = $(this).data("shown") ? true : false;
                        $("a.btn[data-pid='" + $(this).data("id") + "']").each(function () {
                            $(this).closest("tr").toggle(!status);
                        });
                        $(this).data("shown", !status);
                        return false;
                    });

                    $(".btn-check-content").on("click", function (data) {
                        // 添加点击事件
                        // console.log($(this).data('id'))

                        $("#myTabContent2 .form-commonsearch input[name='column_id']").val($(this).data('id'));
                        $("#myTabContent2 .btn-refresh").trigger("click");

                        // 设置添加按钮动态
                        // $("#myTabContent2 .btn-add").addClass('btn-dialog');
                        $("#myTabContent2 .btn-add").attr('class','btn btn-success btn-add btn-dialog');
                        $("#myTabContent2 .btn-add").attr('href','cms/content/add?column_id=' + $(this).data('id'));
                    });


                    // 点击切换/排序/删除操作后刷新左侧菜单
                    $(".btn-change[data-id],.btn-delone,.btn-dragsort").data("success", function (data, ret) {
                        if ($(this).hasClass("btn-change")) {
                            var index = $(this).data("index");
                            var row = Table.api.getrowbyindex(table, index);
                            row.ismenu = $("i.fa.text-gray", this).length > 0 ? 1 : 0;
                            firstTable.bootstrapTable("updateRow", {index: index, row: row});
                        } else if ($(this).hasClass("btn-delone")) {
                            if ($(this).closest("tr[data-index]").find("a.btn-node-sub.disabled").length > 0) {
                                $(this).closest("tr[data-index]").remove();
                            } else {
                                firstTable.bootstrapTable('refresh');
                            }
                        } else if ($(this).hasClass("btn-dragsort")) {
                            firstTable.bootstrapTable('refresh');
                        }
                        Fast.api.refreshmenu();
                        return false;
                    });

                });

                // 展示隐藏全部子级 为btn-toggle类绑定事件
                $(document.body).on("click", ".btn-toggle", function (e) {
                    $("a.btn[data-id][data-pid][data-pid!=0].disabled").closest("tr").hide();
                    var that = this;
                    var show = $("i", that).hasClass("fa-chevron-down");
                    $("i", that).toggleClass("fa-chevron-down", !show);
                    $("i", that).toggleClass("fa-chevron-up", show);
                    $("a.btn[data-id][data-pid][data-pid!=0]").not('.disabled').closest("tr").toggle(show);
                    $(".btn-node-sub[data-pid=0]").data("shown", show);
                });

                // 为表格1绑定事件
                Table.api.bindevent(firstTable);
            },

            second: function () {
                // 表格2
                var secondTable = $("#secondTable");

                secondTable.bootstrapTable({

                    url: 'cms/content/contentList',

                    // TODO 添加按钮点击会出现两次 先让他报错 页面看不出来 回头再改

                    extend: {
                        index_url: 'cms/content/index' + location.search,
                        // add_url: 'cms/content/add',
                        // add_url: '#',
                        edit_url: 'cms/content/edit',
                        del_url: 'cms/content/del',
                        multi_url: 'cms/content/multi',
                        import_url: 'cms/content/import',
                        table: 'cms_content',
                    },
                    toolbar: '#toolbar2',
                    search: false,

                    pk: 'id',
                    sortName: 'weigh',
                    pageSize: 15,

                    columns: [
                        [
                            {checkbox: true},
                            {field: 'id', title: __('Id')},

                            {
                                field: 'operate',
                                title: __('Operate'),
                                table: secondTable,
                                events: Table.api.events.operate,
                                formatter: Table.api.formatter.operate
                            },

                            {field: 'column_id', title: __('Column_id')},
                            {field: 'admin_id', title: __('Admin_id')},
                            {field: 'category_id', title: __('Category_id')},
                            {field: 'title', title: __('Title'), operate: 'LIKE'},

                            // {field: 'keywords', title: __('Keywords'), operate: 'LIKE'},
                            // {field: 'subtitle', title: __('Subtitle'), operate: 'LIKE'},
                            // {field: 'title1', title: __('Title1'), operate: 'LIKE'},
                            // {field: 'title2', title: __('Title2'), operate: 'LIKE'},
                            // {field: 'title3', title: __('Title3')},
                            // {field: 'author', title: __('Author'), operate: 'LIKE'},
                            // {field: 'editor', title: __('Editor'), operate: 'LIKE'},
                            // {field: 'source', title: __('Source'), operate: 'LIKE'},
                            // {field: 'releasetime', title: __('Releasetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                            // {field: 'list_image_alt', title: __('List_image_alt'), operate: 'LIKE'},
                            {field: 'list_image', title: __('List_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},

                            // {field: 'show_image', title: __('Show_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                            // {field: 'show1_image', title: __('Show1_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                            // {field: 'annex_file', title: __('Annex_file'), operate: false},
                            // {field: 'flag', title: __('Flag'), searchList: {"hot":__('Flag hot'),"recommend":__('Flag recommend')}, operate:'FIND_IN_SET', formatter: Table.api.formatter.label},
                            {field: 'pvnum', title: __('Pvnum')},
                            {field: 'ishow', title: __('Ishow')},
                            {field: 'istop', title: __('Istop')},
                            {field: 'top_duetime', title: __('Top_duetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                            {field: 'isindex', title: __('Isindex')},
                            {field: 'index_deutime', title: __('Index_deutime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                            {field: 'href', title: __('Href'), operate: 'LIKE'},
                            {field: 'isblank', title: __('Isblank')},
                            {field: 'seotitle', title: __('Seotitle'), operate: 'LIKE'},
                            {field: 'seokeywords', title: __('Seokeywords'), operate: 'LIKE'},
                            {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status},

                            {field: 'weigh', title: __('Weigh'), operate: false},

                            {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                            {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                            // {field: 'operate', title: __('Operate'), table: secondTable, events: Table.api.events.operate, formatter: Table.api.formatter.operate},

                        ]
                    ]
                });
                // 为表格2绑定事件
                Table.api.bindevent(secondTable);
            }
        },
    };
    return Controller;
});



define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'cms/column/index' + location.search,
                    add_url: 'cms/column/add',
                    edit_url: 'cms/column/edit',
                    del_url: 'cms/column/del',
                    multi_url: 'cms/column/multi',
                    import_url: 'cms/column/import',
                    table: 'cms_column',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',

                pagination: false,   // 不展示分页
                escape    : false,   // tr不原样输出

                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate},

                        // {field: 'pid', title: __('Pid')},
                        // {field: 'category_id', title: __('Category_id')},
                        {field: 'name', title: __('Name'), align: 'left', operate: 'LIKE'},
                        {field: 'type', title: __('Type'), operate: false},
                        {field: 'temp', title: __('Temp'), operate: false},
                        // {field: 'path', title: __('Path'), operate: 'LIKE'},
                        // {field: 'tier', title: __('Tier')},
                        // {field: 'title', title: __('Title'), operate: 'LIKE'},
                        // {field: 'subtitle', title: __('Subtitle'), operate: 'LIKE'},
                        // {field: 'entitle', title: __('Entitle'), operate: 'LIKE'},
                        // {field: 'title1', title: __('Title1'), operate: 'LIKE'},
                        // {field: 'title2', title: __('Title2'), operate: 'LIKE'},
                        // {field: 'title3', title: __('Title3'), operate: 'LIKE'},
                        // {field: 'title4', title: __('Title4'), operate: 'LIKE'},
                        // {field: 'title5', title: __('Title5'), operate: 'LIKE'},
                        // {field: 'show_image', title: __('Show_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        // {field: 'show1_image', title: __('Show1_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        // {field: 'show2_image', title: __('Show2_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        // {field: 'show3_image', title: __('Show3_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        // {field: 'show4_image', title: __('Show4_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        // {field: 'show5_image', title: __('Show5_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        // {field: 'annex_file', title: __('Annex_file'), operate: false},
                        // {field: 'annex1_file', title: __('Annex1_file'), operate: false},
                        // {field: 'annex2_file', title: __('Annex2_file'), operate: false},

                        // {
                        //     field: 'href',
                        //     title: __('Href'),
                        //     formatter: Table.api.formatter.url,
                        //     operate: 'LIKE',
                        // },
                        // {field: 'isblank', title: __('Isblank'), operate: false, formatter: Table.api.formatter.toggle},

                        // {field: 'seotitle', title: __('Seotitle'), operate: 'LIKE'},
                        // {field: 'seokeywords', title: __('Seokeywords'), operate: 'LIKE'},

                        {field: 'ishow', title: __('Ishow'), searchList: {"1":__('Display'),"0":__('Hidden')}, table: table, formatter: Table.api.formatter.toggle},
                        {field: 'ishead', title: __('Ishead'), searchList: {"1":__('Display'),"0":__('Hidden')}, table: table, formatter: Table.api.formatter.toggle},
                        // {field: 'isfoot', title: __('Isfoot'), searchList: {"1":__('Display'),"0":__('Hidden')}, table: table, formatter: Table.api.formatter.toggle},
                        // {field: 'isright', title: __('Isright'), searchList: {"1":__('Display'),"0":__('Hidden')}, table: table, formatter: Table.api.formatter.toggle},
                        // {field: 'isleft', title: __('Isleft'), searchList: {"1":__('Display'),"0":__('Hidden')}, table: table, formatter: Table.api.formatter.toggle},


                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status},

                        {field: 'weigh', title: __('Weigh'), operate: false},

                        {
                            field: 'operate',
                            title: '<a href="javascript:;" class="btn btn-success btn-xs btn-toggle"><i class="fa fa-chevron-up"></i></a>',
                            operate: false,
                            formatter: Controller.api.formatter.subnode
                        },

                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},

                    ]
                ]
            });

            //表格内容渲染前
            table.on('pre-body.bs.table', function (e, data) {
                var options = table.bootstrapTable("getOptions");
                options.escape = true;
            });


            //当内容渲染完成后
            table.on('post-body.bs.table', function (e, data) {
                var options = table.bootstrapTable("getOptions");
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

                //点击切换/排序/删除操作后刷新左侧菜单
                $(".btn-change[data-id],.btn-delone,.btn-dragsort").data("success", function (data, ret) {
                    if ($(this).hasClass("btn-change")) {
                        var index = $(this).data("index");
                        var row = Table.api.getrowbyindex(table, index);
                        row.ismenu = $("i.fa.text-gray", this).length > 0 ? 1 : 0;
                        table.bootstrapTable("updateRow", {index: index, row: row});
                    } else if ($(this).hasClass("btn-delone")) {
                        if ($(this).closest("tr[data-index]").find("a.btn-node-sub.disabled").length > 0) {
                            $(this).closest("tr[data-index]").remove();
                        } else {
                            table.bootstrapTable('refresh');
                        }
                    } else if ($(this).hasClass("btn-dragsort")) {
                        table.bootstrapTable('refresh');
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

            // 为表格绑定事件
            Table.api.bindevent(table);
            table.off('dbl-click-row.bs.table');
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
                url: 'cms/column/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name'), align: 'left',operate: 'LIKE',},
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
                                    url: 'cms/column/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'cms/column/destroy',
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
            formatter: {

                subnode: function (value, row, index) {
                    return '<a href="javascript:;" data-toggle="tooltip" title="' + __('Toggle sub menu') + '" data-id="' + row.id + '" data-pid="' + row.pid + '" class="btn btn-xs ' + (row.haschild == 1 || row.pid == 0 ? 'btn-success' : 'btn-default disabled') + ' btn-node-sub"><i class="fa fa-sitemap"></i></a>';


                    // if(row.haschild){
                    //     return '<a href="javascript:;" title="' + __('Toggle sub menu') + '" data-id="' + row.id + '" data-pid="' + row.pid + '" class="btn btn-xs ' + (row.haschild == 1 ? 'btn-success' : 'btn-default disabled') + ' btn-node-sub"><i class="fa fa-sitemap"></i></a>';
                    // }else{
                    //     return '<a href="javascript:;" title="' + __('Content') + '" data-id="' + row.id + '" data-pid="' + row.pid + '" class="btn btn-primary btn-xs btn-click btn-node-sub btn-check-content" ><i class="fa fa-th-list"></i></a>';

                    // }
                }
            },
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
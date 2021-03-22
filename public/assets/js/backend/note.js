define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url : 'note/index' + location.search,
                    add_url   : 'note/add',
                    edit_url  : 'note/edit',
                    del_url   : 'note/del',
                    multi_url : 'note/multi',
                    import_url: 'note/import',
                    table     : 'note',
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
                        // {field: 'id', title: __('Id')},
                        // {field: 'circle_name', title: __('Circle_id')},

                        {
                            field: 'circle_name',
                            title: __('Circle_id'),
                            searchList: $.getJSON('circle/index/searchlist'),
                            addClass: "selectpicker",
                        },

                        // {field: 'topic_id', title: __('Topic_id')},
                        {field: 'user_id', title: __('User_id')},
                        {field: 'days_num', title: __('Days_num')},
                        // {field: 'group_id', title: __('Group_id')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},

                        {field: 'pv_num', title: __('Pv_num'), sortable: true,},
                        {field: 'like_num', title: __('Like_num'), sortable: true,},
                        {field: 'comment_num', title: __('Comment_num'),sortable: true,},
                        {field: 'share_num', title: __('Share_num'),sortable: true,},

                        // {field: 'weigh', title: __('Weigh'), operate: false},
                        {field: 'is_top', title: __('Is_top'), searchList: {"0":__('Is_top 0'),"1":__('Is_top 1'),}, formatter: Table.api.formatter.normal},
                        {field: 'show_status', title: __('Show_status'), searchList: {"1":__('Show_status 1'),"2":__('Show_status 2'),"3":__('Show_status 3'),"9":__('Show_status 9')}, formatter: Table.api.formatter.status},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status},

                        {
                            field    : 'operate',
                            title    : __('Operate'),
                            table    : table,
                            events   : Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        },

                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            // width: "150px",
                            buttons: [
                                // {
                                //     name: 'click',
                                //     title: __('点击执行事件'),
                                //     classname: 'btn btn-xs btn-info btn-click',
                                //     icon: 'fa fa-leaf',
                                //     // dropdown: '更多',//如果包含dropdown，将会以下拉列表的形式展示
                                //     click: function (data) {
                                //         Layer.alert("点击按钮执行的事件");
                                //     }
                                // },
                                // {
                                //     name: 'detail',
                                //     title: __('弹出窗口打开'),
                                //     classname: 'btn btn-xs btn-primary btn-dialog',
                                //     icon: 'fa fa-list',
                                //     url: 'example/bootstraptable/detail',
                                //     callback: function (data) {
                                //         Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                //     }
                                // },
                                // {
                                //     name: 'ajax',
                                //     title: __('发送Ajax'),
                                //     classname: 'btn btn-xs btn-success btn-magic btn-ajax',
                                //     icon: 'fa fa-magic',
                                //     confirm: '确认发送Ajax请求？',
                                //     url: 'example/bootstraptable/detail',
                                //     success: function (data, ret) {
                                //         Layer.alert(ret.msg + ",返回数据：" + JSON.stringify(data));
                                //         //如果需要阻止成功提示，则必须使用return false;
                                //         //return false;
                                //     },
                                //     error: function (data, ret) {
                                //         console.log(data, ret);
                                //         Layer.alert(ret.msg);
                                //         return false;
                                //     }
                                // },
                                // {
                                //     name: 'addtabs',
                                //     title: __('新选项卡中打开'),
                                //     classname: 'btn btn-xs btn-warning btn-addtabs',
                                //     icon: 'fa fa-folder-o',
                                //     url: 'example/bootstraptable/detail'
                                // }
                            ],
                            formatter: Table.api.formatter.operate
                        },
                    ]
                ],

                // 更多配置参数可参考：https://doc.fastadmin.net/doc/190.html
                // 亦可以参考require-table.js中defaults的配置
                // 快捷搜索,这里可在控制器定义快捷搜索的字段
                search: true,
                //启用普通表单搜索
                commonSearch: true,
                //启用跨页选择
                maintainSelected: true,
                //启用固定列
                fixedColumns: true,
                //固定右侧列数
                fixedRightNumber: 1,

                //可以控制是否默认显示搜索单表,false则隐藏,默认为false
                searchFormVisible: true,
                queryParams: function (params) {
                    // //这里可以追加搜索条件
                    // var filter = JSON.parse(params.filter);
                    // var op = JSON.parse(params.op);
                    // //这里可以动态赋值，比如从URL中获取admin_id的值，filter.admin_id=Fast.api.query('admin_id');
                    // filter.admin_id = 1;
                    // op.admin_id = "=";
                    // params.filter = JSON.stringify(filter);
                    // params.op = JSON.stringify(op);
                    // return params;
                },
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
                url: 'note/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
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
                                    url: 'note/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'note/destroy',
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


        // api: {
        //     bindevent: function () {
        //         Form.api.bindevent($("form[role=form]"));
        //     }
        // }



        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {//渲染的方法
                circle: function (value, row, index) {
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
            events: {//绑定事件的方法
                // ip: {
                //     //格式为：方法名+空格+DOM元素
                //     'click .btn-ip': function (e, value, row, index) {
                //         e.stopPropagation();
                //         var container = $("#table").data("bootstrap.table").$container;
                //         var options = $("#table").bootstrapTable('getOptions');
                //         //这里我们手动将数据填充到表单然后提交
                //         $("form.form-commonsearch [name='ip']", container).val(value);
                //         $("form.form-commonsearch", container).trigger('submit');
                //         Toastr.info("执行了自定义搜索操作");
                //     }
                // },
                // browser: {
                //     'click .btn-browser': function (e, value, row, index) {
                //         e.stopPropagation();
                //         Layer.alert("该行数据为: <code>" + JSON.stringify(row) + "</code>");
                //     }
                // },
            }
        }
    };
    return Controller;
});
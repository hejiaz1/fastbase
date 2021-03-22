define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'circle/index/index' + location.search,
                    // add_url: 'circle/index/add',
                    edit_url: 'circle/index/edit',
                    del_url: 'circle/index/del',
                    multi_url: 'circle/index/multi',
                    import_url: 'circle/index/import',
                    table: 'circle',
                }

            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',

                search:true,
                // searchFormVisible: true,
                searchFormTemplate: 'customformtpl',

                fixedColumns    : true,   // 启用固定列
                fixedRightNumber: 1,      // 固定右侧列数

                columns: [
                    [
                        {checkbox: true},

                        // {field: 'id', title: __('Id')},
                        // {field: 'weigh', title: __('Weigh'), operate: false},

                        {
                            field: 'flag',
                            title: __('Flag'),
                            searchList: {
                                "index":__('Flag index'),
                                "hot":__('Flag hot'),
                                "recommend":__('Flag recommend')
                            },
                            operate:'FIND_IN_SET',
                            formatter: Table.api.formatter.label
                        },

                        {field: 'category_names', title: __('Category_ids'), operate: 'LIKE'},

                        {field: 'head_image', title: __('Head_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'name', title: __('Name'), operate: 'LIKE'},

                        {
                            field: 'user.nickname',
                            title: __('Master_id'),
                            formatter: Controller.api.formatter.user
                        },

                        // 审核加入开关
                        // {field: 'audit_switch', title: __('Audit_switch'), operate: false, formatter: Controller.api.formatter.audit_switch},

                        {
                            field: 'createtime',
                            title: __('Createtime'),
                            operate:'RANGE',
                            addclass:'datetimerange',
                            autocomplete:false,
                            formatter: Table.api.formatter.datetime,
                            sortable: true,
                        },

                        // {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},

                        {
                            field: 'status_text',
                            title: __('Status'),
                        },

                        // {
                        //     field: 'status',
                        //     title: __('Status'),
                        //     searchList: {
                        //         "normal":__('Status normal'),
                        //         "hidden":__('Status hidden')
                        //     },
                        //     formatter: Table.api.formatter.status
                        // },

                        {field: 'member_num', title: __('Member_num'), sortable: true,},
                        {field: 'notes_num', title: __('Notes_num'), sortable: true,},

                        // {field: 'vitality_num', title: __('Vitality_num')},

                        // {field: 'vitality_date', title: __('Vitality_date'), operate:'RANGE', addclass:'datetimerange', autocomplete:false},
                        // {field: 'star', title: __('Star'), searchList: {"1":__('Star 1'),"2":__('Star 2'),"3":__('Star 3'),"4":__('Star 4'),"5":__('Star 5')}, formatter: Table.api.formatter.normal},

                        // {
                        //     field: 'operate',
                        //     title: __('Operate'),
                        //     table: table,
                        //     events: Table.api.events.operate,
                        //     formatter: Table.api.formatter.operate
                        // },

                        {
                            field: 'operate',
                            // width: "150px",
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
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
                                {
                                    name: 'detail',
                                    text: __('detail'),
                                    title: __('Detail'),
                                    classname: 'btn btn-xs btn-info btn-dialog',
                                    icon: 'fa fa-file-text-o',
                                    url: 'circle/index/detail',
                                    callback: function (data) {
                                        Layer.alert("接收到回传数据：" + JSON.stringify(data), {title: "回传数据"});
                                    }
                                },

                            ],
                            formatter: Table.api.formatter.operate
                        },
                    ]
                ]
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
                url: 'circle/index/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name'), align: 'left'},
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
                                    url: 'circle/index/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'circle/index/destroy',
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
            formatter: {
                user: function (value, row, index) {
                    //这里手动构造URL
                    // url = "user/user?" + this.field + "=" + value;
                    url = "user/user?nickname=" + value;

                    //方式一,直接返回class带有addtabsit的链接,这可以方便自定义显示内容
                    //return '<a href="' + url + '" class="label label-success addtabsit" title="' + __("Search %s", value) + '">' + __('Search %s', value) + '</a>';

                    //方式二,直接调用Table.api.formatter.addtabs
                    this.url = url;
                    return Table.api.formatter.addtabs.call(this, value, row, index);
                },

                // audit_switch: function (value, row, index) {example/bootstraptable/change
                //     //添加上btn-change可以自定义请求的URL进行数据处理
                //     return '<a class="btn-change text-success" data-url="example/bootstraptable/change" data-confirm="确认切换状态？" data-id="' + row.id + '"><i class="fa ' + (row.title == '' ? 'fa-toggle-on fa-flip-horizontal text-gray' : 'fa-toggle-on') + ' fa-2x"></i></a>';
                // },
            }
        }
    };
    return Controller;
});
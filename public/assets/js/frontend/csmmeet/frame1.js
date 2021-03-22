define(['jquery', 'bootstrap', 'frontend', 'template', 'form', 'bootstrap-datetimepicker', 'toastr'],
    function ($, undefined, Frontend, Template, Form, datetimepicker, toastr) {
        var Controller = {
            index: function () {

                window.toastr = toastr;

                //begin full copy from mojs
                var v = core_vue({
                    el: "#app_frame1",
                    data: {
                        title: "会议室申请",
                        buildings: [],
                        applylist: [],
                        applylist_selectroom: []//根据选择room的applylist
                    },
                    mounted: function () {
                        var that = this;

                    },
                    methods: {
                        submitApply: function () {
                            var that = this;
                            //var tb_url = $api.attr(event.currentTarget, 'tb_url');
                            var applyhour = parseInt($("#apply_beginhour").val());
                            var beginmin = parseInt($("#apply_beginmin").val());
                            var beginmins = $("#apply_beginmin").val();
                            var endhour = parseInt($("#apply_endhour").val());
                            var endmin = parseInt($("#apply_endmin").val());
                            var endmins = $("#apply_endmin").val();
                            if ((applyhour * 60 + beginmin) >= (endhour * 60 + endmin)) {
                                core_bui_toast("预约的截止时间不能早于开始时间");
                                return;
                            }


                            core_bui_ajax(
                                "indexajax/submitRoomApply", {
                                apply_csmmeet_room_id: $("#apply_csmmeet_room_id").val(),
                                apply_applaydate: $("#apply_applaydate").val(),
                                apply_beginhour: applyhour,
                                apply_beginmin: beginmins,
                                apply_endhour: endhour,
                                apply_endmin: endmins,
                                apply_meettitle: $("#apply_meettitle").val(),
                            },
                                function (res) {
                                    //dialog.close();
                                    core_bui_closedialog("#actionsheet");
                                    getDataRender(that);
                                }
                            );
                        },
                        closeApplyPage: function () {
                            var that = this;
                            //dialog.close(); 
                            core_bui_closedialog("#actionsheet");
                        },
                        clickShow0t7: function () {
                            var that = this;
                            $(".hmorning").css("display", "table-cell");
                            $(".hnormal").css("display", "none");
                            $(".hevening").css("display", "none");
                        },
                        clickShow8t18: function () {
                            var that = this;
                            $(".hmorning").css("display", "none");
                            $(".hnormal").css("display", "table-cell");
                            $(".hevening").css("display", "none");
                        },
                        clickShow19t0: function () {
                            var that = this;
                            $(".hmorning").css("display", "none");
                            $(".hnormal").css("display", "none");
                            $(".hevening").css("display", "table-cell");
                        },
                        clickMyApply: function () {
                            var that = this;
                            core_bui_router("pages/csmmeet/myapply");
                        },
                        clickApplyCancel: function (event) {
                            var that = this;
                            var id = $(event.currentTarget).attr("item_id");
                            var auditstatus = $(event.currentTarget).attr('auditstatus');
                            if (auditstatus == "0" || auditstatus == "1") {
                                core_bui_confirm("您要确认撤销该申请吗？", function (e) {
                                    core_bui_ajax(
                                        "indexajax/cancelApply", {
                                        id: id,
                                    },
                                        function (res) {
                                            core_bui_toast("申请已经撤销");
                                            getDataRender(that)
                                        }
                                    );
                                });
                            }
                        }
                    },
                });

                /*ajax读取数据，vue展现*/
                function getDataRender(v) {
                    var that = v;


                    var input2value2 = $("#datepicker_input").val();
                    core_bui_ajax(
                        "indexajax/getRoomApplyInfo", {
                        applaydate: input2value2
                    }, function (res) {
                        that.buildings = res.data.buildings;
                        that.applylist = res.data.applylist;
                        setTimeout(function () {
                            $('.apply_meet').on("click", function (argument) {
                                var input2value = $("#datepicker_input").val();
                                $("#apply_csmmeet_room_name2").text($(this).attr("roomname"));
                                $("#apply_csmmeet_room_name").text($(this).attr("roomname"));
                                $("#apply_csmmeet_room_id").val($(this).attr("roomid"));
                                $("#apply_applaydate_span").text(input2value);
                                $("#apply_applaydate").val(input2value);
                                $("#apply_beginhour").val($(this).attr("hour"));
                                //end是begin+59分钟
                                $("#apply_endhour").val(parseInt($(this).attr("hour")));
                                $("#apply_endmin").val("59");

                                var selectroomid = $(this).attr("roomid");
                                console.log(that.applylist);
                                that.applylist_selectroom = [];
                                for (var index in that.applylist) {
                                    var item = that.applylist[index];
                                    if (item.csmmeet_room_id == selectroomid) {
                                        that.applylist_selectroom.push(item);
                                    }
                                }
                                //dialog.open();
                                core_bui_showdialog("#actionsheet");
                            });
                        }, 500);

                    });
                }

                Vue.component('td-applyhour', {
                    props: ['applycount', 'addclass'],
                    computed: {
                        rcount: function () {
                            var sr = this.applycount;

                            if (sr == 0 || sr == 1) {
                                sr = '';
                            }
                            return sr;
                        },
                        ruserd: function () {
                            var sr = this.applycount;
                            if (sr > 0) {
                                sr = 'apply_meet used  ' + this.addclass;
                            } else {
                                sr = 'apply_meet unused  ' + this.addclass;
                            }
                            return sr;
                        },
                    },
                    template: '<td :class="ruserd" >{{rcount}}</td>'
                });

                core_bui_datetimepicker("#datepicker_input", function (val) {
                    getDataRender(v);
                });
                //end full copy from mojs


                var myDate = new Date();
                $("#datepicker_input").val(myDate.pattern("yyyy-MM-dd"));
                getDataRender(v);
            }
        }
        return Controller;
    });
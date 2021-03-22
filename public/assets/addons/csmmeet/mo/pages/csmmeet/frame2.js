// +----------------------------------------------------------------------
// Csmmeet [ CSMMeet会议室预约系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-26
// +----------------------------------------------------------------------
loader.define(function(require, exports, module) {
    var v = core_vue({
        el: "#app_myapply",
        data: {
            title: "",
            applylist: []
        },
        mounted: function() {
            var that = this;
            getDataRender(that);
        },
        methods: {
            submitApply:function(){
                var that = this;
                //var tb_url = $api.attr(event.currentTarget, 'tb_url');
                core_bui_ajax(
                    "indexajax/submitRoomApply",{
                        apply_csmmeet_room_id:$("#apply_csmmeet_room_id").val(),
                    },
                    function(res){
                        dialog.close();
                        getDataRender(that);
                    }
                );
            },
            clickApplyCancel:function(event){
                var that = this;
                var id = $(event.currentTarget).attr("item_id");
                var auditstatus = $(event.currentTarget).attr('auditstatus');
                if(auditstatus=="0" || auditstatus=="1"){
                    core_bui_confirm("您要确认撤销该申请吗？", function(e) {
                        core_bui_ajax(
                            "indexajax/cancelApply",{
                                id:id,
                            },
                            function(res){
                                core_bui_toast("申请已经撤销");
                                getDataRender(that)
                            }
                        );
                    });                     
                }
            } 
        },
    });
    function getDataRender(v){
        core_bui_ajax(
            "indexajax/getMyApplyList",{},
            function(res){
                v.applylist = res.data.applylist;
            }
        );    	
    }


});
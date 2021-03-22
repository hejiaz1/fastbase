define(['jquery', 'bootstrap', 'frontend', 'template', 'form', 'bootstrap-datetimepicker', 'toastr'],
    function ($, undefined, Frontend, Template, Form, datetimepicker, toastr) {
        var Controller = {
            index: function () {
                window.toastr = toastr;

                var v = core_vue({
                    el: "#app_screenbuilding",
                    data: {
                        title: "",
                        applylist: [],
                        applylist_display: [],
                        building_id:'',
                        pagesize:20,
                        settimeout:5,
                    },
                    mounted: function() {
                        var that = this;
                        core_bui_getParam(function(param) {
                            that.building_id = param.building_id;
                            that.pagesize = param.pagesize;
                            that.settimeout = param.settimeout;
                            console.log(that.pagesize);
                            getDataRender(that);  
                        });
                    },
                    methods: {
                             
                    },
                });
                core_bui_datetimepicker("#datepicker_input",function(val){
                    getDataRender(v);
                });  
                
                function getDataRender(v) {
                    var that = v;
                    var input2 = $("#datepicker_input");
                    var input2value = input2.val();
                    if(input2.val()==""){
                        var myDate = new Date(); 
                        input2value = myDate.pattern("yyyy-MM-dd");
                    }            
                    var myDate = new Date();
                    core_bui_ajax(
                        "screenajax/querybuildingapply", {
                            building_id:that.building_id,
                            applydate:input2value,
                            currenthour:myDate.pattern("HH")
                        },function(res) {
                            console.log(that.applylist);
                            that.applylist = res.data.applylist;

                            //定时刷新，每个n秒翻页，翻页到最后一页重新刷新数据
                            var ii = 0;
                            var jj = that.applylist.length;
                            console.log("that.pagesize="+that.pagesize);
                            console.log("that.settimeout="+that.settimeout);
                            var ss = setInterval(function(){
                                that.applylist_display = [];
                                for(var i=0,j=that.pagesize;i<j;i++){
                                    //console.log("ii="+ii+",jj="+jj+",i="+i);
                                    if(ii<jj){
                                        that.applylist_display[i] = that.applylist[ii];
                                    }else{
                                        clearInterval(ss);
                                        getDataRender(v);
                                        break;
                                    }
                                    ii++;

                                }
                                //alert('hi');
                            },that.settimeout*1000);


                        }
                    );
                }
                //end full copy from mojs


            }
        }
        return Controller;
    });
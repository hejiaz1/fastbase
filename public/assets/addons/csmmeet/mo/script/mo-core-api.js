// +----------------------------------------------------------------------
// Csmmeet [ CSMMeet会议室预约系统 ]
// Author: chensm <chenshiming0802@163.com>
// Create by chensm at 2020-02-26
// +----------------------------------------------------------------------
/*
core_bui_ajax(
    "index/sendLoginCcodeByEmailAjax",{id:param.id},
    function(res){
        that.title = res.data.title;
    }
);
*/
function core_bui_ajax(url,data,func,failurefunc){
    var logintoken = core_bui_storage_get("csmlogintoken");
    var ll = logintoken+"";

    url = window._cfg.remote_baseurl+"/"+url;
    console.log("APP Call:"+url);
    console.log(data);
    bui.ajax({
        url: url,
        data: data,
        headers:{
            csmlogintoken: ll
        }
    }).then(function(res){
        // 成功回调
        console.log(res);
        if(res.code=="1"){
            func(res);
        }else if(res.code=="0"){
            core_bui_toast(res.msg);
            if(res.url!=null&&res.url!=""){
                var url = res.url;
                var rurl = "#"+url.substr(url.indexOf("/public/")+8,url.indexOf(".html")-url.indexOf("/public/")-8);
                //alert(rurl);
                core_bui_router(rurl,{"redirect_type":"refresh"});
            }
            if(failurefunc){
                failurefunc(res);
            }
        }else{
            core_bui_toast("数据交互异常");
        }   
    },function(res,status){
        // 失败回调
        console.log(status);
    })
}

/*
获取页面传传
core_bui_getParam(function(param) {
  //console.log(param);
});
*/
function core_bui_getParam(func){
    var getParams = bui.getPageParams();
    getParams.done(function(param){
        func(param);
    });
}


function core_bui_toast(msg){
     bui.hint({ content: msg, position: "top", effect: "fadeInDown" });   
}

function core_bui_storage_get(key){
    var storage = bui.storage();
    return storage.get(key);    
}
function core_bui_storage_set(key,value){
    var storage = bui.storage();
    storage.set(key, value);  
}
/*
option={
    "redirect_type":"router"//default,other:refresh
}
*/
function core_bui_router(modulename,option){
    if(option==null){
        option = {};
    }
    if(option.redirect_type==null){
        option.redirect_type = "router";
    }
    if(option.redirect_type=="refresh"){
        //window.location.href = modulename;
        router.load({ url: modulename, param: {},cache:false });
        window.location.reload();        
    }else{
        router.load({ url: modulename, param: {},cache:false });
    }  
}
function core_bui_confirm(msg,func){
    bui.confirm(msg, function(e){
        var text = $(e.target).text();
        if (text == "确定") {
            func();
        }
        this.close()
    }); 
}

function core_bui_datetimepicker(jquerydomkey,onchangeFunc){
    var myDate = new Date();    
    var input2 = $(jquerydomkey);
    var uiPickerdate = bui.pickerdate({
            handle:jquerydomkey,
            bindValue: true,
            value:myDate.pattern("yyyy-MM-dd"),
            // input 显示的日期格式
            formatValue: "yyyy-MM-dd",
            cols: {
                    hour: "none",
                    minute:"none",
                    second: "none"
            },
            onChange: function(value) {
                if(value!=null&&value!=""){
                    //alert(value);
                    onchangeFunc(value);  
                }  
            }
    });
}

var _alldialog = {};
function core_bui_showdialog(jquerydomkey){
    if(_alldialog[jquerydomkey]==null||_alldialog[jquerydomkey]==""){
        var dialog = bui.dialog({
            id: jquerydomkey,
            position:"top",
            effect:"fadeInUp",
            onMask: function (argument) {
                dialog.close();
            }
        });   
        _alldialog[jquerydomkey] = dialog;   
    }
    _alldialog[jquerydomkey].open();
}
function core_bui_closedialog(jquerydomkey){
    if(_alldialog[jquerydomkey]!=null&&_alldialog[jquerydomkey]!=""){
        _alldialog[jquerydomkey].close();
    }
}
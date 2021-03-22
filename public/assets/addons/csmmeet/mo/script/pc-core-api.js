/*
core_bui_ajax(
    "index/sendLoginCcodeByEmailAjax",{id:param.id},
    function(res){
        that.title = res.data.title;
    }
);
*/
function core_bui_ajax(url,data,func){
    var logintoken = core_bui_storage_get("csmlogintoken");
    var ll = logintoken+"";

    url = window._cfg.remote_baseurl+"/"+url;

    if(url.indexOf("?")>=0){
        url += "&";
    }else{
        url += "?";
    }
    url += "csmversion="+Date.parse(new  Date());
    console.log("APP Call:"+url);
    $.ajax({
        url: url,
        data: data, 
        headers:{
            csmlogintoken: ll
        },
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
    var getParams = {};

    var url = location.search; //获取url中"?"符后的字串  
    if (url.indexOf("?") != -1) {  
        var str = url.substr(1);  
        strs = str.split("&");  
        for(var i = 0; i < strs.length; i ++) {
            getParams[strs[i].split("=")[0]]=decodeURI(strs[i].split("=")[1]);  
        }  
    }
    //console.log(getParams);alert('hi');
    func(getParams);
}


function core_bui_toast(msg){
    toastr = window.toastr;
    toastr.info(msg);
    // bootoast.toast({
    //     message: msg,
    //     type: 'warn',
    //     position: 'top',
    //     icon: null,
    //     timeout: 2,
    //     animationDuration: 300,
    //     dismissible: true
    // });   
}

function core_bui_storage_get(key){
    //alert("core_bui_storage_get:"+key+"="+window.sessionStorage.getItem(key));
    return window.sessionStorage.getItem(key);
}
function core_bui_storage_set(key,value){
    //alert("core_bui_storage_set:"+key+"/"+value);
    window.sessionStorage .setItem(key,value);
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
    var url = "../../"+modulename+".html";
    if(option.redirect_type=="refresh"){
        window.location = url;  
    }else{    
        window.location = url; 
    }  
}

function core_bui_confirm(msg,func){
    if(confirm(msg)){
        func();
    }

}

function core_bui_datetimepicker(jquerydomkey,onchangeFunc){
    
    $(jquerydomkey).datetimepicker({
        showClose:true,
        format:'YYYY-MM-DD'
    }).on('dp.change',function (ev) {
        var v = $(jquerydomkey).val();
        onchangeFunc(v);
    });
    var myDate = new Date();
    $(jquerydomkey).val(myDate.pattern("yyyy-MM-dd"));
}

function core_bui_showdialog(jquerydomkey){
    $(jquerydomkey).modal('show');
}
function core_bui_closedialog(jquerydomkey){
    $(jquerydomkey).modal('hide');
}
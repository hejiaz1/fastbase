/*
 use exam:
	import modal from '@/common/js/js_sdk/plus-modal/plus-modal.js';
	modal.show({
		type:'err',//'success'|'err'|'warn'|'info'|'ask'
		content: '您是否要开始进行下载应用，进行更新升级操作？您是否要开始进行下载应用，进行更新升级操作？',
		// height:60, //可选项，不填则根据content长度自动计算高度。
		timeout:0, align:'left', point:'bottom',//'top'|'middle'|'bottom'
		title:'请确认', click:((e)=>{console.log('您点击了'+e.title+'！')})
	});
*/

"use strict";
Object.defineProperty(exports,"__esModule",{value:true});

var _maskView,_contentView,
	_loadingProgress,_screenHeight,_screenWidth,
	_config={
		icon:"/static/img/png/xuan-popup/err.png",title:'提示',
		releaseNotes:"",noteAglin:"center",loadingColor:"#ff6666",
		bgcolor:"#ffffff",color:"#2f2f2f",timeout:0,
		windowHeight:50,offset:0,modal:false,//false-'toast'|true-'modal'
		cancelText:"取消",cancelColor:"#000000",
		confirmText:"确定",confirmColor:"#ff6666",
		click:false,
	},
	_calculatePosition=function(){
		var a=(_screenHeight-_config.windowHeight)/2,
		b=.1*_screenWidth/2,
		c=_screenWidth-2*b;
		return{top:a,left:b,width:c,right:b}
	},
	_createMask=function(){
		_maskView=new plus.nativeObj.View("maskView",
			{top:"0px",left:"0px",width:"100%",height:"100%",backgroundColor:"rgba(0,0,0,0.2)"},
		);
		(!_config.modal)&&(_maskView.addEventListener("click", close, false));
	},
	_createContentView=function(){
		var a=_calculatePosition(),cf=_config.modal?50:0,of=_config.modal?50:0;
		_contentView=new plus.nativeObj.View("contentView",
			{top:a.top+_config.offset+"px",left:a.left+"px",height:_config.windowHeight+cf+of+"px",width:a.width+"px"}),
		_contentView.drawRect({color:_config.bgcolor,radius:"10px"},{width:"100%",height:"100%"},"roundedRect");
		_config.modal&&( _contentView.drawText(_config.title,
			{height:cf+"px",left:"50px",right:"10px",top:"0px"},
			{size:"18px",weight:"bold",color:_config.color,align:"left",verticalAlign:"middle",whiteSpace:"normal",overflow:"ellipsis"},"userInfoTitle")),
		_contentView.drawBitmap(_config.icon,{},{top:(_config.windowHeight+cf+of-30)/2+of+"px",width:"30px",height:"30px",left:10+"px"},"userInfoIcon"),
		_contentView.drawText(_config.releaseNotes,
			{height:_config.windowHeight+"px",left:"50px",right:"10px",top:cf+"px"},
			{size:"16px",color:_config.color,align:_config.noteAglin,verticalAlign:"middle",whiteSpace:"normal",overflow:"ellipsis"},"userInfoContent");
		var c=_config.windowHeight+cf;
		_config.modal&&(_contentView.drawRichText(
			"<font style=\"font-size:16px;\" color=\""+_config.cancelColor+"\">"+_config.cancelText+"</font>",
			{width:"50%",height:of-30+"px",top:c+15+"px",left:"0px"},
			{align:"center",onClick:function(){close({index:0,title:'取消'});}},
			"cancel")),
		_config.modal&&(_contentView.drawRichText(
			"<font color=\""+_config.confirmColor+"\" style=\"font-size:16px;\">"+_config.confirmText+"</font>",
			{width:"50%",height:of-30+"px",top:c+15+"px",right:"0px"},
			{align:"center",onClick:function(){close({index:1,title:'确认'});}},
			"submit"));
	},
	init=function(a){
		var b=a.height, c=a.type, d=a.content,
			e=a.align, f=a.loadingColor, g=a.timeout,
			h=a.point, i=a.modal, j=a.click, k=a.title;
		_screenHeight=plus.screen.resolutionHeight, _screenWidth=plus.screen.resolutionWidth,
			d&&(_config.windowHeight=Math.ceil(d.length/15)*30+20),
			b&&(_config.windowHeight=b),
			c&&(_config.modal=c=='ask'?true:false),
			c&&(_config.icon="/static/img/png/xuan-popup/"+c+".png"),
			c&&(_config.bgcolor=c=='success'?"#f0f9eb":(c=='warn'?"#fdf6ec":(c=='info'?"#edf2fc":(c=='err'?"#fef0f0":"#ffffff")))),
			c&&(_config.color=c=='success'?"#67c23a":(c=='warn'?"#e6a23c":(c=='info'?"#67bafe":(c=='err'?"#f56c6c":"#2f2f2f")))),
			d&&(_config.releaseNotes=d),
			e&&(_config.noteAglin=e),
			f&&(_config.loadingColor=f),
			h&&(_config.offset=h=='top'?(-200):(h=='bottom'?200:0)),
			j&&(_config.click=j),
			k&&(_config.title=k),
			g&&(_config.timeout=g);
		_createMask(), _createContentView();
		(!_config.modal&&_config.timeout>0)&&(setTimeout(close,_config.timeout));
	},
	show=function(a){
		init(a),
		_maskView&&_maskView.show(),
		_contentView&&_contentView.show()
	},
	close=function(o={index:-1,title:'遮罩'}){
		_maskView&&_maskView.close(),//hide()
		_contentView&&_contentView.close(),//hide()
		(_config.modal&&_config.click)&&_config.click(o)
	};
exports.default={
	init:init,
	show:show,
	close:close
};	


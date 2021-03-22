```
import modal from '@/common/js/js_sdk/plus-modal/plus-modal.js';
	modal.show({
		type:'err',//'success'|'err'|'warn'|'info'|'ask'
		content: '您是否要开始进行下载应用，进行更新升级操作？您是否要开始进行下载应用，进行更新升级操作？',
		// height:60, //可选项，不填则根据content长度自动计算高度。
		timeout:0, align:'left', point:'bottom',//'top'|'middle'|'bottom'
		title:'请确认', click:((e)=>{console.log('您点击了'+e.title+'！')})
	});
```
```
import modal from '@/common/js/js_sdk/plus-modal/plus-modal.js';
	modal.show({
		type:'err',//'success'|'err'|'warn'|'info'|'ask'
		content: '���Ƿ�Ҫ��ʼ��������Ӧ�ã����и����������������Ƿ�Ҫ��ʼ��������Ӧ�ã����и�������������',
		// height:60, //��ѡ����������content�����Զ�����߶ȡ�
		timeout:0, align:'left', point:'bottom',//'top'|'middle'|'bottom'
		title:'��ȷ��', click:((e)=>{console.log('�������'+e.title+'��')})
	});
```
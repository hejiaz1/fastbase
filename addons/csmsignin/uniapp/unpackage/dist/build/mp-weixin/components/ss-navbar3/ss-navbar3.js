(global["webpackJsonp"]=global["webpackJsonp"]||[]).push([["components/ss-navbar3/ss-navbar3"],{1542:function(t,n,e){},"16e6":function(t,n,e){"use strict";Object.defineProperty(n,"__esModule",{value:!0}),n.default=void 0;var r={name:"ssNavbar1",props:{navArr:{type:Array,default:function(){return[{title:"文本1",url:"text1"},{title:"文本2",url:"text2"},{title:"文本3",url:"text3"}]}},tabCurrentIndex:{type:Number,default:0}},computed:{itemWidth:function(){return 100/this.navArr.length+"%"}},data:function(){return{}},methods:{tabChange:function(t){this.$emit("navbarTap",t)}}};n.default=r},"307a":function(t,n,e){"use strict";e.r(n);var r=e("16e6"),a=e.n(r);for(var u in r)"default"!==u&&function(t){e.d(n,t,function(){return r[t]})}(u);n["default"]=a.a},"89fc":function(t,n,e){"use strict";var r=e("1542"),a=e.n(r);a.a},"9ade":function(t,n,e){"use strict";e.r(n);var r=e("acc7"),a=e("307a");for(var u in a)"default"!==u&&function(t){e.d(n,t,function(){return a[t]})}(u);e("89fc");var c,i=e("f0c5"),f=Object(i["a"])(a["default"],r["b"],r["c"],!1,null,null,null,!1,r["a"],c);n["default"]=f.exports},acc7:function(t,n,e){"use strict";var r,a=function(){var t=this,n=t.$createElement;t._self._c},u=[];e.d(n,"b",function(){return a}),e.d(n,"c",function(){return u}),e.d(n,"a",function(){return r})}}]);
;(global["webpackJsonp"] = global["webpackJsonp"] || []).push([
    'components/ss-navbar3/ss-navbar3-create-component',
    {
        'components/ss-navbar3/ss-navbar3-create-component':(function(module, exports, __webpack_require__){
            __webpack_require__('543d')['createComponent'](__webpack_require__("9ade"))
        })
    },
    [['components/ss-navbar3/ss-navbar3-create-component']]
]);

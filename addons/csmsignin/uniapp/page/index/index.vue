<template>
	<view class="content">
		<uni-swiper-dot :info="info" :current="current" field="content" :mode="mode">
			<swiper class="swiper-box" @change="change" autoplay="true">
				<swiper-item v-for="(item ,index) in info" :key="index">
					<view class="swiper-item">
						<image class="image" :src="item" mode="aspectFill" />
					</view>
				</swiper-item>
			</swiper>
		</uni-swiper-dot>
		<view class="view-title">{{confmodel.name}}</view>
		<view class="view-subtitle" v-if="confmodel.meetdate!=null">
			会议时间：{{confmodel.meetdate}}
		</view>
		<view class="view-subtitle" v-if="confmodel.meetaddress!=null">
			会议地点：{{confmodel.meetaddress}}
		</view>
		<view class="view-subtitle" v-if="confmodel.unsignedcontentq!=null">
			{{confmodel.unsignedcontentq}}
		</view>
		<view class="view-signin">
			<button open-type="getPhoneNumber" @getphonenumber="getPhoneNumber">点击签到</button>
		</view>
		<view class="view-subtitle" v-if="hassignin=='Y'">
			您是第{{signordernum}}个签到的参会者。
		</view>
		<view class="view-line"></view>
		<view class="view-body">
			<ss-navbar :navArr="navArr3" :tabCurrentIndex="currentIndex3" @navbarTap="navbarTapHandler3"></ss-navbar>
			<view class="view-content">
				<rich-text :nodes="navArr3str" v-if="(confmodel.requiredsiginin=='N')||(confmodel.requiredsiginin=='Y' && hassignin=='Y' )"></rich-text>
				<rich-text v-if="confmodel.requiredsiginin=='Y' && hassignin=='N' ">请您先签到！</rich-text>
			</view>
		</view>
	</view>
</template>

<script>
	import Appconfig from '@/app-config.js'
	import Coreapi from '@/script/common/core-api.js'
	import Appapi from '@/script/wx_core_api.js'

	import uniSwiperDot from "@/components/uni-swiper-dot/uni-swiper-dot.vue"
	import ssNavbar3 from '@/components/ss-navbar3/ss-navbar3.vue'
	export default {
		components: {
			uniSwiperDot,
			ssNavbar3
		},
		data() {
			return {
				confid: Appconfig.window._cfg.default_confid,
				//项目的基础URL
				baseurl: "",
				//轮播图
				info: [],
				confmodel: {},
				current: 0,
				mode: 'round',
				//Nav文字数组
				navArr3str: "",
				navArr3: [{
					title: '介绍',
					content: ""
				}, ],
				currentIndex3: 0,
				//是否签到及签到名词
				hassignin: '-', //-,Y,N
				signordernum: -1,
			}
		},
		onLoad: function(options) {
			var that = this;
			console.log(options);
			var scene = options.scene;
			if (scene != null) {
				console.log(scene);
				var pp = decodeURIComponent(scene);
				var pps = pp.split("&");
				var ppjson = {};
				for (var i = 0, j = pps.length; i < j; i++) {
					var iitem = pps[i].split("=");
					ppjson[iitem[0]] = iitem[1];
				}
				if (ppjson.confid != null && ppjson.confid != "") {
					that.confid = ppjson.confid;
				}
			}


			//获取会议信息		
			Appapi.core_bui_ajax(
				"indexajax/getFullConference", {
					id: that.confid
				},
				function(res) {
					that.baseurl = res.data.data.baseurl;

					that.info = res.data.data.conf.imagearray;

					//如果是相对路径，则加上根路径（因为小程序没有主域名）
					for (var i = 0, j = that.info.length; i < j; i++) {
						var iitem = that.info[i];
						if (iitem.indexOf("http") != 0) {
							that.info[i] = that.baseurl + that.info[i];
						}
					}

					//栏目内容格式化，图片扩展为100%宽度，路径加上跟路径
					that.navArr3 = res.data.data.confinfos;
					console.log("ready to format navArr3:");
					if (that.navArr3 != null) {
						for (var i = 0, j = that.navArr3.length; i < j; i++) {
							var ss = that.navArr3[i].content;
							ss = ss.replace(new RegExp('style="width: ([0-9]*)px;"', "gm"), 'style="width:100%"');
							that.navArr3[i].content = ss.replace(new RegExp('src="/', "gm"), 'src="' + that.baseurl + '/');
						}
					}



					if (that.navArr3.length > 0) {
						that.navArr3str = that.navArr3[0].content;
					}
					that.confmodel = res.data.data.conf;
				}



			);

			//获取微信的用户信息
			uni.login({
				provider: 'weixin',
				success: function(loginRes) {
					console.log("loginRes=");
					console.log(loginRes);
					// 获取用户信息
					console.log("ready invoke:uni.getUserInfo");
					Appapi.core_bui_ajax(
						"weixinajax/loginbyweixincode", {
							code: loginRes.code,
							avatarUrl: '-',
							city: '-',
							country: '-',
							gender: '-',
							language: '-',
							nickName: '-',
							province: '-',
						},
						function(res) {
							console.log("ready invoke:weixinajax/loginbyweixincode callback");
							Appapi.core_bui_storage_set("csmlogintoken", res.data.data.openid);
							//获取签到信息
							Appapi.core_bui_ajax(
								"indexajax/getSignininfo", {
									id: that.confid
								},
								function(res) {
									var confuser = res.data.data.confuser;

									if (confuser != null && confuser.signinstatus == 'Y') {
										that.hassignin = 'Y';
										that.signordernum = confuser.signordernum;
									} else {
										that.hassignin = 'N';
									}
									console.log("that.hassignin=" + that.hassignin);

								}
							);
						}
					);
					// 	uni.getUserInfo({
					// 		provider: 'weixin',
					// 		success: function(infoRes) {
					// 			console.log("ready invoke:weixinajax/loginbyweixincode");
					// 			Appapi.core_bui_ajax( 
					// 				"weixinajax/loginbyweixincode", {
					// 					code: loginRes.code,
					// 					avatarUrl: infoRes.userInfo.avatarUrl,
					// 					city: infoRes.userInfo.city,
					// 					country: infoRes.userInfo.country,
					// 					gender: infoRes.userInfo.gender,
					// 					language: infoRes.userInfo.language,
					// 					nickName: infoRes.userInfo.nickName,
					// 					province: infoRes.userInfo.province
					// 				},
					// 				function(res) {
					// 					console.log("ready invoke:weixinajax/loginbyweixincode callback");
					// 					Appapi.core_bui_storage_set("csmlogintoken", res.data.data.openid);
					// 					//获取签到信息
					// 					Appapi.core_bui_ajax(
					// 						"indexajax/getSignininfo", {
					// 							id: that.confid
					// 						},
					// 						function(res) {
					// 							var confuser = res.data.data.confuser;

					// 							if (confuser != null && confuser.signinstatus == 'Y') {
					// 								that.hassignin = 'Y';
					// 								that.signordernum = confuser.signordernum;
					// 							} else {
					// 								that.hassignin = 'N';
					// 							}
					// 							console.log("that.hassignin="+that.hassignin);

					// 						}
					// 					);
					// 				}
					// 			);
					// 		}
					// 	});
				}
			});
		},
		methods: {
			bindgetuserinfo(e) {
				var that = this;
				console.log(e);

			},
			//获取用户的手机号码，需要先获取用户信息
			getPhoneNumber(e) {
				var that = this;
				console.log(e.detail.errMsg);
				console.log(e.detail.iv);
				console.log(e.detail.encryptedData);
				if (e.detail.errMsg == "getPhoneNumber:ok") {
					//提交微信的手机加密信息，加密后提交签到按钮
					Appapi.core_bui_ajax(
						"weixinajax/logintogetmobile", {
							encryptedData: e.detail.encryptedData,
							iv: e.detail.iv
						},
						function(res) {
							Appapi.core_bui_ajax(
								"indexajax/submitsigin", {
									id: that.confid
								},
								function(res) {
									console.log(res);
									that.hassignin = "Y";
									that.signordernum = res.data.data.signordernum;
									Appapi.core_bui_toast("签到成功，您是第" + that.signordernum + "个签到人！");
								}
							);
						}
					);
				} else {
					Appapi.core_bui_toast("获取手机失败，无法签到！");
				}
			},
			change(e) {
				this.current = e.detail.current;
			},
			navbarTapHandler3(index) {
				this.currentIndex3 = index;
				var obj = this.navArr3[index];
				this.navArr3str = obj.content;
			},
			// //获取用户信息
			// clickSigninBtn(e) {

			// }

		}
	}
</script>

<style>
	.swiper-box {
		width: 750upx;
		height: 500upx;
	}

	.image {
		width: 750upx;
		height: 500upx;
	}

	.view-title {
		font-size: 30upx;
		line-height: 40upx;
		color: #000;
		font-weight: bold;
		padding: 20upx;
	}

	.view-line {
		height: 10upx;
		background-color: #cccccc;
	}

	.view-content {
		font-size: 28upx;
		color: #000;
		line-height: 40upx;
		padding: 20upx;
	}

	.view-subtitle {
		padding: 10upx 5upx 10upx 20upx;
		font-size: 28upx;
		color: #000;
		line-height: 30upx;
	}

	.view-signin {
		padding: 20upx;
	}

	img {
		width: 100% !important;
	}
</style>

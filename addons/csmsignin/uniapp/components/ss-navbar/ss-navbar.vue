<template>
	<view class="ss-navbar">
		<view 
		v-for="(item, index) in navArr" 
		:key="item.url" 
		class="nav-item"
		:class="{current: index === tabCurrentIndex}"
		@click="tabChange(index)"
		:style="{ width: itemWidth }"
		>
			<text>{{item.title}}</text>
		</view>
	</view>
</template>

<script>
	export default {
		name: 'ssNavbar',
		props: {
			navArr: {
				type: Array,
				default () {
					return [
						{
							title: '文本1',
							url: 'text1'
						}, {
							title: '文本2',
							url: 'text2'
						}, {
							title: '文本3',
							url: 'text3'
						}
					]
				}
			},
			tabCurrentIndex: {
				type: Number,
				default: 0
			}
		},
		computed: {
			itemWidth: function () {
				return (100 / this.navArr.length) + '%';
			}
		},
		data () {
			return {}
		},
		methods: {
			/**
			 * 导航栏navbar
			 * 点击事件
			 */
			tabChange (index) {
				this.$emit('navbarTap', index);
			}
		}
	}
</script>

<style lang="scss">
	/* 顶部tabbar */
	.ss-navbar {
		position: relative;
		height: 90upx;
		box-shadow: 0 2upx 8upx rgba(0,0,0,.06);
		background-color: #fff;
		display: flex;
		flex-direction: row;
		
		.nav-item {
			height: 90upx;
			text-align: center;
			font-size: 30upx;
			color: #303133;
			position: relative;
			display: flex;
			flex-direction: row;
			justify-content: center;
			align-items: center;
			
			&:after{
				content: '';
				width: 0;
				height: 0;
				border-bottom: 4upx solid $uni-theme-color;
				position: absolute;
				left: 50%;
				bottom: 0;
				transform: translateX(-50%);
				transition: .3s;
			}
		}
		
		.current{
			color: $uni-theme-color;
			
			&:after{
				width: 50%;
			}
		}
	}
</style>

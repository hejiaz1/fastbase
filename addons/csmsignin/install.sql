
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


--
-- 表的结构 `__PREFIX__csmsgin_conf`
--
CREATE TABLE `__PREFIX__csmsignin_conf` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(200) NOT NULL COMMENT '活动名称',
  `images` varchar(1000) DEFAULT NULL COMMENT '活动图片,图片比例2.5:1',
  `requiredsiginin` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT '是否需要签到才可看会议信息:Y=需要签到,N=无需签到',
  `canoutusersignin` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT '是否限定参会人员:Y=不限定,N=限定',
  `unsignedcontentq` text COMMENT '签到前信息',
  `siginedcontent` varchar(200) DEFAULT NULL COMMENT '签到后提示信息',
  `meetdate` varchar(100) NOT NULL COMMENT '会议时间',
  `meetaddress` varchar(200) NOT NULL COMMENT '会议地点',
  `begintime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '签到开始时间',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '签到截止时间',
  `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员',
  `b1` varchar(100) DEFAULT NULL COMMENT '备用字段1',
  `b2` varchar(100) DEFAULT NULL COMMENT '备用字段2',
  `b3` varchar(100) DEFAULT NULL COMMENT '备用字段3',
  `b4` varchar(100) DEFAULT NULL COMMENT '备用字段4',
  `b5` varchar(100) DEFAULT NULL COMMENT '备用字段5',
  `b6` varchar(100) DEFAULT NULL COMMENT '备用字段6',
  `b7` varchar(100) DEFAULT NULL COMMENT '备用字段7',
  `b8` varchar(100) DEFAULT NULL COMMENT '备用字段8',
  `b9` varchar(100) DEFAULT NULL COMMENT '备用字段9',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='活动信息'

;

--
-- 表的结构 `__PREFIX__csmsignin_confinfo`
--
CREATE TABLE `__PREFIX__csmsignin_confinfo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `csmsignin_conf_id` int(10) unsigned NOT NULL COMMENT '活动',
  `title` varchar(200) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `isneedsigined` enum('N','Y') DEFAULT NULL COMMENT '是否签到后可查看:Y=需要签到,N=不需要签到',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `status` enum('normal','hidden') DEFAULT 'normal' COMMENT '状态',
  `b1` varchar(100) DEFAULT NULL COMMENT '备用字段1',
  `b2` varchar(100) DEFAULT NULL COMMENT '备用字段2',
  `b3` varchar(100) DEFAULT NULL COMMENT '备用字段3',
  `b4` varchar(100) DEFAULT NULL COMMENT '备用字段4',
  `b5` varchar(100) DEFAULT NULL COMMENT '备用字段5',
  `b6` varchar(100) DEFAULT NULL COMMENT '备用字段6',
  `b7` varchar(100) DEFAULT NULL COMMENT '备用字段7',
  `b8` varchar(100) DEFAULT NULL COMMENT '备用字段8',
  `b9` varchar(100) DEFAULT NULL COMMENT '备用字段9',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='活动内容信息'
;

--
-- 表的结构 `__PREFIX__csmsignin_confuser`
-- 
CREATE TABLE `__PREFIX__csmsignin_confuser` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `csmsignin_conf_id` int(10) unsigned NOT NULL COMMENT '活动',
  `username` varchar(200) NOT NULL COMMENT '参会人姓名',
  `usermobile` varchar(200) NOT NULL COMMENT '参会人手机',
  `signinstatus` enum('N','Y') NOT NULL DEFAULT 'N' COMMENT '状态:Y=已签到,N=未签到',
  `signintime` int(10) unsigned DEFAULT '0' COMMENT '签到时间',
  `signordernum` int(10) DEFAULT NULL COMMENT '签到名次',
  `weixinuser_id` int(10) DEFAULT NULL COMMENT '签到用户',
  `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `b1` varchar(100) DEFAULT NULL COMMENT '备用字段1',
  `b2` varchar(100) DEFAULT NULL COMMENT '备用字段2',
  `b3` varchar(100) DEFAULT NULL COMMENT '备用字段3',
  `b4` varchar(100) DEFAULT NULL COMMENT '备用字段4',
  `b5` varchar(100) DEFAULT NULL COMMENT '备用字段5',
  `b6` varchar(100) DEFAULT NULL COMMENT '备用字段6',
  `b7` varchar(100) DEFAULT NULL COMMENT '备用字段7',
  `b8` varchar(100) DEFAULT NULL COMMENT '备用字段8',
  `b9` varchar(100) DEFAULT NULL COMMENT '备用字段9',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='活动信息'
;


--
-- 表的结构 `__PREFIX__csmsignin_weixinuser`
--
CREATE TABLE `__PREFIX__csmsignin_weixinuser` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `wxappid` varchar(100) DEFAULT NULL COMMENT '微信appid',
  `currentsessionkey` varchar(100) NOT NULL COMMENT '当前微信sessionkey',
  `openid` varchar(200) NOT NULL COMMENT 'Openid',
  `avatarUrl` varchar(200) NOT NULL COMMENT '头像',
  `city` varchar(200) NOT NULL COMMENT '城市',
  `country` varchar(200) NOT NULL COMMENT '国家',
  `gender` varchar(200) NOT NULL COMMENT '性别',
  `language` varchar(200) NOT NULL COMMENT '语言',
  `nickName` varchar(200) NOT NULL COMMENT '昵称',
  `province` varchar(200) NOT NULL COMMENT '地区',
  `phoneNumber` varchar(200) NOT NULL COMMENT '用户绑定的手机号（国外手机号会有区号）',
  `purePhoneNumber` varchar(200) NOT NULL COMMENT '没有区号的手机号',
  `countryCode` varchar(200) NOT NULL COMMENT '区号',
  `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `b1` varchar(100) DEFAULT NULL COMMENT '备用字段1',
  `b2` varchar(100) DEFAULT NULL COMMENT '备用字段2',
  `b3` varchar(100) DEFAULT NULL COMMENT '备用字段3',
  `b4` varchar(100) DEFAULT NULL COMMENT '备用字段4',
  `b5` varchar(100) DEFAULT NULL COMMENT '备用字段5',
  `b6` varchar(100) DEFAULT NULL COMMENT '备用字段6',
  `b7` varchar(100) DEFAULT NULL COMMENT '备用字段7',
  `b8` varchar(100) DEFAULT NULL COMMENT '备用字段8',
  `b9` varchar(100) DEFAULT NULL COMMENT '备用字段9',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='微信用户信息'
;


--
-- 转存表中的数据 `__PREFIX__csmsignin_conf`
--
INSERT INTO `__PREFIX__csmsignin_conf`(`id`,`name`,`images`,`requiredsiginin`,`canoutusersignin`,`unsignedcontentq`,`siginedcontent`,`meetdate`,`meetaddress`,`begintime`,`endtime`,`status`,`createtime`,`updatetime`,`admin_id`,`b1`,`b2`,`b3`,`b4`,`b5`,`b6`,`b7`,`b8`,`b9`) VALUES
(1,'2020年CSM软件分享会','https://fademo.163fan.com/uploads/20200229/6c4e77525a149577a9b96fafefad91b2.png,https://fademo.163fan.com/uploads/20200229/ce69d9c9cec716714f6b51755554de2a.png','Y','Y','欢迎光临来前往参加此会议','我好我','xxxx年xx月xx日9:30开始','上海XXXXXX酒店XX楼',1577896200,1830267000,'normal',1582716223,1583029203,0,'','','','','','','','','')
 ;


--
-- 转存表中的数据 `__PREFIX__csmsignin_confinfo`
--
INSERT INTO `__PREFIX__csmsignin_confinfo`(`id`,`csmsignin_conf_id`,`title`,`content`,`weigh`,`createtime`,`updatetime`,`isneedsigined`,`admin_id`,`status`,`b1`,`b2`,`b3`,`b4`,`b5`,`b6`,`b7`,`b8`,`b9`) VALUES
(5,1,'简介','<p>这个是测试数据，</p><p>请到<b>后台管理-CSM签到和活动-活动管理</b>配置活动信息，</p><p></p>并活动管理页面中的<b style=\"color:red\">小程序二维码</b>进入签到会议系统。<p></p><p><br></p>',5,1582726807,1582987610,NULL,0,'normal','','','','','','','','',''),(6,1,'联系人','这个是测试数据，请到后台维护',1,1582726819,1582726919,NULL,0,'normal','','','','','','','','',''),(7,1,'内容','这个是测试数据，请到后台维护',4,1582726832,1582726922,NULL,0,'normal','','','','','','','','',''),(8,1,'交通','这个是测试数据，请到后台维护',2,1582726842,1582726926,NULL,0,'normal','','','','','','','','','')
;


COMMIT;
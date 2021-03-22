
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


--
-- 表的结构 `__PREFIX__csmmeet_building`
--
CREATE TABLE IF NOT EXISTS `__PREFIX__csmmeet_building` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(100) NOT NULL COMMENT '大楼名称',
  `description` text COMMENT '备注',
  `weigh` int(10) DEFAULT '0' COMMENT '排序',
  `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='大楼'
;


--
-- 表的结构 `__PREFIX__csmmeet_room`
--
CREATE TABLE IF NOT EXISTS `__PREFIX__csmmeet_room` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `csmmeet_building_id` int(10) unsigned NOT NULL COMMENT '所属大楼',
  `name` varchar(100) NOT NULL COMMENT '会议室名称',
  `description` text COMMENT '备注',
  `needaudit` enum('Y','N') DEFAULT NULL COMMENT '预约是否需要审批:Y=需要,N=不需要',
  `weigh` int(10) DEFAULT '0' COMMENT '排序',
  `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='会议室'
;



--
-- 表的结构 `__PREFIX__csmmeet_apply`
--
CREATE TABLE IF NOT EXISTS `__PREFIX__csmmeet_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `csmmeet_room_id` int(10) unsigned NOT NULL COMMENT '会议室',
  `title` varchar(100) NOT NULL COMMENT '会议名称',
  `applydate` varchar(10) DEFAULT NULL COMMENT '申请日期',
  `beginhour` int(11) DEFAULT NULL COMMENT '开始小时',
  `beginmin` varchar(11) DEFAULT NULL COMMENT '开始分钟',
  `endhour` int(11) DEFAULT NULL COMMENT '截止小时',
  `endmin` varchar(11) DEFAULT NULL COMMENT '截止分钟',
  `userkey` varchar(10) DEFAULT NULL COMMENT '申请人关键字来源',
  `userkeyfrom` varchar(100) DEFAULT NULL COMMENT '申请人关键字',
  `username` varchar(100) DEFAULT NULL COMMENT '申请人姓名',
  `audituser_id` varchar(100) DEFAULT NULL COMMENT '审核人',
  `audituser` varchar(100) DEFAULT NULL COMMENT '审核人姓名',
  `description` text COMMENT '备注',
  `weigh` int(10) DEFAULT '0' COMMENT '排序',
  `auditstatus` enum('-1','0','1','-2') NOT NULL DEFAULT '0' COMMENT '审核状态:-2=申请撤销,-1=审核退回,0=待审核,1=审核通过',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `applytype` varchar(10) DEFAULT NULL COMMENT '申请类型，按月，按周等',
  `applytyperegular` varchar(100) DEFAULT NULL COMMENT '申请类型对应规则',
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
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8 COMMENT='会议室申请'
;


--
-- 表的结构 `__PREFIX__csmmeet_applyhour`
--
CREATE TABLE IF NOT EXISTS `__PREFIX__csmmeet_applyhour` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `csmmeet_apply_id` int(10) unsigned NOT NULL COMMENT '会议室预约',
  `applydate` varchar(100) NOT NULL COMMENT '申请日期',
  `applyhour` varchar(10) DEFAULT NULL COMMENT '申请时间',
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
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8 COMMENT='会议室预约小时表'
;


COMMIT;
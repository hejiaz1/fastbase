/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 5.7.26 : Database - fastadmin
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `fa_admin` */

DROP TABLE IF EXISTS `fa_admin`;

CREATE TABLE `fa_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '昵称',
  `password` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '密码',
  `salt` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '密码盐',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '头像',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '电子邮箱',
  `loginfailure` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '失败次数',
  `logintime` int(10) DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '登录IP',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `token` varchar(59) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'Session标识',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员表';

/*Data for the table `fa_admin` */

insert  into `fa_admin`(`id`,`username`,`nickname`,`password`,`salt`,`avatar`,`email`,`loginfailure`,`logintime`,`loginip`,`createtime`,`updatetime`,`token`,`status`) values (1,'admin','Admin','9054ab357507063a474af7b2ce8883c4','4b9d01','/assets/img/avatar.png','admin@admin.com',0,1619334512,'127.0.0.1',1491635035,1619334512,'068afa2c-dffe-4d58-9d83-513f8eb662d2','normal');

/*Table structure for table `fa_admin_log` */

DROP TABLE IF EXISTS `fa_admin_log`;

CREATE TABLE `fa_admin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `username` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '管理员名字',
  `url` varchar(1500) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作页面',
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '日志标题',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `ip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'IP',
  `useragent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'User-Agent',
  `createtime` int(10) DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `name` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员日志表';

/*Data for the table `fa_admin_log` */

insert  into `fa_admin_log`(`id`,`admin_id`,`username`,`url`,`title`,`content`,`ip`,`useragent`,`createtime`) values (1,1,'admin','/oEwQfxDlzk.php/index/login?url=%2FoEwQfxDlzk.php','登录','{\"url\":\"\\/oEwQfxDlzk.php\",\"__token__\":\"***\",\"username\":\"admin\",\"password\":\"***\",\"captcha\":\"5cnz\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:87.0) Gecko/20100101 Firefox/87.0',1619334512);

/*Table structure for table `fa_area` */

DROP TABLE IF EXISTS `fa_area`;

CREATE TABLE `fa_area` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(10) DEFAULT NULL COMMENT '父id',
  `shortname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '简称',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '名称',
  `mergename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '全称',
  `level` tinyint(4) DEFAULT NULL COMMENT '层级 0 1 2 省市区县',
  `pinyin` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '拼音',
  `code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '长途区号',
  `zip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '邮编',
  `first` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '首字母',
  `lng` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '经度',
  `lat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '纬度',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='地区表';

/*Data for the table `fa_area` */

/*Table structure for table `fa_attachment` */

DROP TABLE IF EXISTS `fa_attachment`;

CREATE TABLE `fa_attachment` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '物理路径',
  `imagewidth` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '宽度',
  `imageheight` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '高度',
  `imagetype` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片类型',
  `imageframes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图片帧数',
  `filename` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '文件名称',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `mimetype` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'mime类型',
  `extparam` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '透传数据',
  `createtime` int(10) DEFAULT NULL COMMENT '创建日期',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `uploadtime` int(10) DEFAULT NULL COMMENT '上传时间',
  `storage` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local' COMMENT '存储位置',
  `sha1` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '文件 sha1编码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='附件表';

/*Data for the table `fa_attachment` */

insert  into `fa_attachment`(`id`,`admin_id`,`user_id`,`url`,`imagewidth`,`imageheight`,`imagetype`,`imageframes`,`filename`,`filesize`,`mimetype`,`extparam`,`createtime`,`updatetime`,`uploadtime`,`storage`,`sha1`) values (1,1,0,'/assets/img/qrcode.png','150','150','png',0,'qrcode.png',21859,'image/png','',1491635035,1491635035,1491635035,'local','17163603d0263e4838b9387ff2cd4877e8b018f6');

/*Table structure for table `fa_auth_group` */

DROP TABLE IF EXISTS `fa_auth_group`;

CREATE TABLE `fa_auth_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父组别',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '组名',
  `rules` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '规则ID',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='分组表';

/*Data for the table `fa_auth_group` */

insert  into `fa_auth_group`(`id`,`pid`,`name`,`rules`,`createtime`,`updatetime`,`status`) values (1,0,'Admin group','*',1491635035,1491635035,'normal');
insert  into `fa_auth_group`(`id`,`pid`,`name`,`rules`,`createtime`,`updatetime`,`status`) values (2,1,'Second group','13,14,16,15,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,40,41,42,43,44,45,46,47,48,49,50,55,56,57,58,59,60,61,62,63,64,65,1,9,10,11,7,6,8,2,4,5',1491635035,1491635035,'normal');
insert  into `fa_auth_group`(`id`,`pid`,`name`,`rules`,`createtime`,`updatetime`,`status`) values (3,2,'Third group','1,4,9,10,11,13,14,15,16,17,40,41,42,43,44,45,46,47,48,49,50,55,56,57,58,59,60,61,62,63,64,65,5',1491635035,1491635035,'normal');
insert  into `fa_auth_group`(`id`,`pid`,`name`,`rules`,`createtime`,`updatetime`,`status`) values (4,1,'Second group 2','1,4,13,14,15,16,17,55,56,57,58,59,60,61,62,63,64,65',1491635035,1491635035,'normal');
insert  into `fa_auth_group`(`id`,`pid`,`name`,`rules`,`createtime`,`updatetime`,`status`) values (5,2,'Third group 2','1,2,6,7,8,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34',1491635035,1491635035,'normal');

/*Table structure for table `fa_auth_group_access` */

DROP TABLE IF EXISTS `fa_auth_group_access`;

CREATE TABLE `fa_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '会员ID',
  `group_id` int(10) unsigned NOT NULL COMMENT '级别ID',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='权限分组表';

/*Data for the table `fa_auth_group_access` */

insert  into `fa_auth_group_access`(`uid`,`group_id`) values (1,1);

/*Table structure for table `fa_auth_rule` */

DROP TABLE IF EXISTS `fa_auth_rule`;

CREATE TABLE `fa_auth_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('menu','file') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'file' COMMENT 'menu为菜单,file为权限节点',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规则名称',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规则名称',
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图标',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规则URL',
  `condition` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '条件',
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为菜单',
  `menutype` enum('addtabs','blank','dialog','ajax') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '菜单类型',
  `extend` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '扩展属性',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  KEY `pid` (`pid`),
  KEY `weigh` (`weigh`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='节点表';

/*Data for the table `fa_auth_rule` */

insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (1,'file',0,'dashboard','Dashboard','fa fa-dashboard','','','Dashboard tips',1,NULL,'',1491635035,1491635035,143,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (2,'file',0,'general','General','fa fa-cogs','','','',1,NULL,'',1491635035,1491635035,137,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (3,'file',0,'category','Category','fa fa-leaf','','','Category tips',1,NULL,'',1491635035,1491635035,119,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (4,'file',0,'addon','Addon','fa fa-rocket','','','Addon tips',1,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (5,'file',0,'auth','Auth','fa fa-group','','','',1,NULL,'',1491635035,1491635035,99,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (6,'file',2,'general/config','Config','fa fa-cog','','','Config tips',1,NULL,'',1491635035,1491635035,60,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (7,'file',2,'general/attachment','Attachment','fa fa-file-image-o','','','Attachment tips',1,NULL,'',1491635035,1491635035,53,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (8,'file',2,'general/profile','Profile','fa fa-user','','','',1,NULL,'',1491635035,1491635035,34,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (9,'file',5,'auth/admin','Admin','fa fa-user','','','Admin tips',1,NULL,'',1491635035,1491635035,118,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (10,'file',5,'auth/adminlog','Admin log','fa fa-list-alt','','','Admin log tips',1,NULL,'',1491635035,1491635035,113,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (11,'file',5,'auth/group','Group','fa fa-group','','','Group tips',1,NULL,'',1491635035,1491635035,109,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (12,'file',5,'auth/rule','Rule','fa fa-bars','','','Rule tips',1,NULL,'',1491635035,1491635035,104,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (13,'file',1,'dashboard/index','View','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,136,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (14,'file',1,'dashboard/add','Add','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,135,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (15,'file',1,'dashboard/del','Delete','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,133,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (16,'file',1,'dashboard/edit','Edit','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,134,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (17,'file',1,'dashboard/multi','Multi','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,132,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (18,'file',6,'general/config/index','View','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,52,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (19,'file',6,'general/config/add','Add','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,51,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (20,'file',6,'general/config/edit','Edit','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,50,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (21,'file',6,'general/config/del','Delete','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,49,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (22,'file',6,'general/config/multi','Multi','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,48,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (23,'file',7,'general/attachment/index','View','fa fa-circle-o','','','Attachment tips',0,NULL,'',1491635035,1491635035,59,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (24,'file',7,'general/attachment/select','Select attachment','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,58,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (25,'file',7,'general/attachment/add','Add','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,57,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (26,'file',7,'general/attachment/edit','Edit','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,56,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (27,'file',7,'general/attachment/del','Delete','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,55,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (28,'file',7,'general/attachment/multi','Multi','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,54,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (29,'file',8,'general/profile/index','View','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,33,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (30,'file',8,'general/profile/update','Update profile','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,32,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (31,'file',8,'general/profile/add','Add','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,31,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (32,'file',8,'general/profile/edit','Edit','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,30,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (33,'file',8,'general/profile/del','Delete','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,29,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (34,'file',8,'general/profile/multi','Multi','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,28,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (35,'file',3,'category/index','View','fa fa-circle-o','','','Category tips',0,NULL,'',1491635035,1491635035,142,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (36,'file',3,'category/add','Add','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,141,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (37,'file',3,'category/edit','Edit','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,140,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (38,'file',3,'category/del','Delete','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,139,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (39,'file',3,'category/multi','Multi','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,138,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (40,'file',9,'auth/admin/index','View','fa fa-circle-o','','','Admin tips',0,NULL,'',1491635035,1491635035,117,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (41,'file',9,'auth/admin/add','Add','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,116,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (42,'file',9,'auth/admin/edit','Edit','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,115,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (43,'file',9,'auth/admin/del','Delete','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,114,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (44,'file',10,'auth/adminlog/index','View','fa fa-circle-o','','','Admin log tips',0,NULL,'',1491635035,1491635035,112,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (45,'file',10,'auth/adminlog/detail','Detail','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,111,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (46,'file',10,'auth/adminlog/del','Delete','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,110,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (47,'file',11,'auth/group/index','View','fa fa-circle-o','','','Group tips',0,NULL,'',1491635035,1491635035,108,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (48,'file',11,'auth/group/add','Add','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,107,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (49,'file',11,'auth/group/edit','Edit','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,106,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (50,'file',11,'auth/group/del','Delete','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,105,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (51,'file',12,'auth/rule/index','View','fa fa-circle-o','','','Rule tips',0,NULL,'',1491635035,1491635035,103,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (52,'file',12,'auth/rule/add','Add','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,102,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (53,'file',12,'auth/rule/edit','Edit','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,101,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (54,'file',12,'auth/rule/del','Delete','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,100,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (55,'file',4,'addon/index','View','fa fa-circle-o','','','Addon tips',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (56,'file',4,'addon/add','Add','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (57,'file',4,'addon/edit','Edit','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (58,'file',4,'addon/del','Delete','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (59,'file',4,'addon/downloaded','Local addon','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (60,'file',4,'addon/state','Update state','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (63,'file',4,'addon/config','Setting','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (64,'file',4,'addon/refresh','Refresh','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (65,'file',4,'addon/multi','Multi','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (66,'file',0,'user','User','fa fa-list','','','',1,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (67,'file',66,'user/user','User','fa fa-user','','','',1,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (68,'file',67,'user/user/index','View','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (69,'file',67,'user/user/edit','Edit','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (70,'file',67,'user/user/add','Add','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (71,'file',67,'user/user/del','Del','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (72,'file',67,'user/user/multi','Multi','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (73,'file',66,'user/group','User group','fa fa-users','','','',1,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (74,'file',73,'user/group/add','Add','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (75,'file',73,'user/group/edit','Edit','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (76,'file',73,'user/group/index','View','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (77,'file',73,'user/group/del','Del','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (78,'file',73,'user/group/multi','Multi','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (79,'file',66,'user/rule','User rule','fa fa-circle-o','','','',1,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (80,'file',79,'user/rule/index','View','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (81,'file',79,'user/rule/del','Del','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (82,'file',79,'user/rule/add','Add','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (83,'file',79,'user/rule/edit','Edit','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');
insert  into `fa_auth_rule`(`id`,`type`,`pid`,`name`,`title`,`icon`,`url`,`condition`,`remark`,`ismenu`,`menutype`,`extend`,`createtime`,`updatetime`,`weigh`,`status`) values (84,'file',79,'user/rule/multi','Multi','fa fa-circle-o','','','',0,NULL,'',1491635035,1491635035,0,'normal');

/*Table structure for table `fa_category` */

DROP TABLE IF EXISTS `fa_category`;

CREATE TABLE `fa_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '栏目类型',
  `name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `flag` set('hot','index','recommend') COLLATE utf8mb4_unicode_ci DEFAULT '',
  `image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片',
  `keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '关键字',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '描述',
  `diyname` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '自定义名称',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `weigh` (`weigh`,`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='分类表';

/*Data for the table `fa_category` */

insert  into `fa_category`(`id`,`pid`,`type`,`name`,`nickname`,`flag`,`image`,`keywords`,`description`,`diyname`,`createtime`,`updatetime`,`weigh`,`status`) values (1,0,'page','官方新闻','news','recommend','/assets/img/qrcode.png','','','news',1491635035,1491635035,1,'normal');
insert  into `fa_category`(`id`,`pid`,`type`,`name`,`nickname`,`flag`,`image`,`keywords`,`description`,`diyname`,`createtime`,`updatetime`,`weigh`,`status`) values (2,0,'page','移动应用','mobileapp','hot','/assets/img/qrcode.png','','','mobileapp',1491635035,1491635035,2,'normal');
insert  into `fa_category`(`id`,`pid`,`type`,`name`,`nickname`,`flag`,`image`,`keywords`,`description`,`diyname`,`createtime`,`updatetime`,`weigh`,`status`) values (3,2,'page','微信公众号','wechatpublic','index','/assets/img/qrcode.png','','','wechatpublic',1491635035,1491635035,3,'normal');
insert  into `fa_category`(`id`,`pid`,`type`,`name`,`nickname`,`flag`,`image`,`keywords`,`description`,`diyname`,`createtime`,`updatetime`,`weigh`,`status`) values (4,2,'page','Android开发','android','recommend','/assets/img/qrcode.png','','','android',1491635035,1491635035,4,'normal');
insert  into `fa_category`(`id`,`pid`,`type`,`name`,`nickname`,`flag`,`image`,`keywords`,`description`,`diyname`,`createtime`,`updatetime`,`weigh`,`status`) values (5,0,'page','软件产品','software','recommend','/assets/img/qrcode.png','','','software',1491635035,1491635035,5,'normal');
insert  into `fa_category`(`id`,`pid`,`type`,`name`,`nickname`,`flag`,`image`,`keywords`,`description`,`diyname`,`createtime`,`updatetime`,`weigh`,`status`) values (6,5,'page','网站建站','website','recommend','/assets/img/qrcode.png','','','website',1491635035,1491635035,6,'normal');
insert  into `fa_category`(`id`,`pid`,`type`,`name`,`nickname`,`flag`,`image`,`keywords`,`description`,`diyname`,`createtime`,`updatetime`,`weigh`,`status`) values (7,5,'page','企业管理软件','company','index','/assets/img/qrcode.png','','','company',1491635035,1491635035,7,'normal');
insert  into `fa_category`(`id`,`pid`,`type`,`name`,`nickname`,`flag`,`image`,`keywords`,`description`,`diyname`,`createtime`,`updatetime`,`weigh`,`status`) values (8,6,'page','PC端','website-pc','recommend','/assets/img/qrcode.png','','','website-pc',1491635035,1491635035,8,'normal');
insert  into `fa_category`(`id`,`pid`,`type`,`name`,`nickname`,`flag`,`image`,`keywords`,`description`,`diyname`,`createtime`,`updatetime`,`weigh`,`status`) values (9,6,'page','移动端','website-mobile','recommend','/assets/img/qrcode.png','','','website-mobile',1491635035,1491635035,9,'normal');
insert  into `fa_category`(`id`,`pid`,`type`,`name`,`nickname`,`flag`,`image`,`keywords`,`description`,`diyname`,`createtime`,`updatetime`,`weigh`,`status`) values (10,7,'page','CRM系统 ','company-crm','recommend','/assets/img/qrcode.png','','','company-crm',1491635035,1491635035,10,'normal');
insert  into `fa_category`(`id`,`pid`,`type`,`name`,`nickname`,`flag`,`image`,`keywords`,`description`,`diyname`,`createtime`,`updatetime`,`weigh`,`status`) values (11,7,'page','SASS平台软件','company-sass','recommend','/assets/img/qrcode.png','','','company-sass',1491635035,1491635035,11,'normal');
insert  into `fa_category`(`id`,`pid`,`type`,`name`,`nickname`,`flag`,`image`,`keywords`,`description`,`diyname`,`createtime`,`updatetime`,`weigh`,`status`) values (12,0,'test','测试1','test1','recommend','/assets/img/qrcode.png','','','test1',1491635035,1491635035,12,'normal');
insert  into `fa_category`(`id`,`pid`,`type`,`name`,`nickname`,`flag`,`image`,`keywords`,`description`,`diyname`,`createtime`,`updatetime`,`weigh`,`status`) values (13,0,'test','测试2','test2','recommend','/assets/img/qrcode.png','','','test2',1491635035,1491635035,13,'normal');

/*Table structure for table `fa_config` */

DROP TABLE IF EXISTS `fa_config`;

CREATE TABLE `fa_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '变量名',
  `group` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '分组',
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '变量标题',
  `tip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '变量描述',
  `type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '类型:string,text,int,bool,array,datetime,date,file',
  `value` text COLLATE utf8mb4_unicode_ci COMMENT '变量值',
  `content` text COLLATE utf8mb4_unicode_ci COMMENT '变量字典数据',
  `rule` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '验证规则',
  `extend` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '扩展属性',
  `setting` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '配置',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='系统配置';

/*Data for the table `fa_config` */

insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (1,'name','basic','Site name','请填写站点名称','string','我的网站','','required','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (2,'beian','basic','Beian','粤ICP备15000000号-1','string','','','','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (3,'cdnurl','basic','Cdn url','如果全站静态资源使用第三方云储存请配置该值','string','','','','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (4,'version','basic','Version','如果静态资源有变动请重新配置该值','string','1.0.1','','required','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (5,'timezone','basic','Timezone','','string','Asia/Shanghai','','required','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (6,'forbiddenip','basic','Forbidden ip','一行一条记录','text','','','','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (7,'languages','basic','Languages','','array','{\"backend\":\"zh-cn\",\"frontend\":\"zh-cn\"}','','required','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (8,'fixedpage','basic','Fixed page','请尽量输入左侧菜单栏存在的链接','string','dashboard','','required','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (9,'categorytype','dictionary','Category type','','array','{\"default\":\"Default\",\"page\":\"Page\",\"article\":\"Article\",\"test\":\"Test\"}','','','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (10,'configgroup','dictionary','Config group','','array','{\"basic\":\"Basic\",\"email\":\"Email\",\"dictionary\":\"Dictionary\",\"user\":\"User\",\"example\":\"Example\"}','','','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (11,'mail_type','email','Mail type','选择邮件发送方式','select','1','[\"请选择\",\"SMTP\"]','','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (12,'mail_smtp_host','email','Mail smtp host','错误的配置发送邮件会导致服务器超时','string','smtp.qq.com','','','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (13,'mail_smtp_port','email','Mail smtp port','(不加密默认25,SSL默认465,TLS默认587)','string','465','','','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (14,'mail_smtp_user','email','Mail smtp user','（填写完整用户名）','string','10000','','','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (15,'mail_smtp_pass','email','Mail smtp password','（填写您的密码或授权码）','string','password','','','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (16,'mail_verify_type','email','Mail vertify type','（SMTP验证方式[推荐SSL]）','select','2','[\"无\",\"TLS\",\"SSL\"]','','','');
insert  into `fa_config`(`id`,`name`,`group`,`title`,`tip`,`type`,`value`,`content`,`rule`,`extend`,`setting`) values (17,'mail_from','email','Mail from','','string','10000@qq.com','','','','');

/*Table structure for table `fa_ems` */

DROP TABLE IF EXISTS `fa_ems`;

CREATE TABLE `fa_ems` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '事件',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '邮箱',
  `code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '验证码',
  `times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'IP',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='邮箱验证码表';

/*Data for the table `fa_ems` */

/*Table structure for table `fa_sms` */

DROP TABLE IF EXISTS `fa_sms`;

CREATE TABLE `fa_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '事件',
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '手机号',
  `code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '验证码',
  `times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'IP',
  `createtime` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='短信验证码表';

/*Data for the table `fa_sms` */

/*Table structure for table `fa_test` */

DROP TABLE IF EXISTS `fa_test`;

CREATE TABLE `fa_test` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID(单选)',
  `category_ids` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分类ID(多选)',
  `week` enum('monday','tuesday','wednesday') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '星期(单选):monday=星期一,tuesday=星期二,wednesday=星期三',
  `flag` set('hot','index','recommend') COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '标志(多选):hot=热门,index=首页,recommend=推荐',
  `genderdata` enum('male','female') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'male' COMMENT '性别(单选):male=男,female=女',
  `hobbydata` set('music','reading','swimming') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '爱好(多选):music=音乐,reading=读书,swimming=游泳',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '标题',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片',
  `images` varchar(1500) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图片组',
  `attachfile` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '附件',
  `keywords` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '关键字',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '描述',
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '省市',
  `json` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '配置:key=名称,value=值',
  `price` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `views` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击',
  `startdate` date DEFAULT NULL COMMENT '开始日期',
  `activitytime` datetime DEFAULT NULL COMMENT '活动时间(datetime)',
  `year` year(4) DEFAULT NULL COMMENT '年',
  `times` time DEFAULT NULL COMMENT '时间',
  `refreshtime` int(10) DEFAULT NULL COMMENT '刷新时间(int)',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `switch` tinyint(1) NOT NULL DEFAULT '0' COMMENT '开关',
  `status` enum('normal','hidden') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal' COMMENT '状态',
  `state` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态值:0=禁用,1=正常,2=推荐',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='测试表';

/*Data for the table `fa_test` */

insert  into `fa_test`(`id`,`admin_id`,`category_id`,`category_ids`,`week`,`flag`,`genderdata`,`hobbydata`,`title`,`content`,`image`,`images`,`attachfile`,`keywords`,`description`,`city`,`json`,`price`,`views`,`startdate`,`activitytime`,`year`,`times`,`refreshtime`,`createtime`,`updatetime`,`deletetime`,`weigh`,`switch`,`status`,`state`) values (1,0,12,'12,13','monday','hot,index','male','music,reading','我是一篇测试文章','<p>我是测试内容</p>','/assets/img/avatar.png','/assets/img/avatar.png,/assets/img/qrcode.png','/assets/img/avatar.png','关键字','描述','广西壮族自治区/百色市/平果县','{\"a\":\"1\",\"b\":\"2\"}',0.00,0,'2017-07-10','2017-07-10 18:24:45',2017,'18:24:45',1491635035,1491635035,1491635035,NULL,0,1,'normal','1');

/*Table structure for table `fa_user` */

DROP TABLE IF EXISTS `fa_user`;

CREATE TABLE `fa_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '组别ID',
  `username` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '昵称',
  `password` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '密码',
  `salt` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '密码盐',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '电子邮箱',
  `mobile` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '手机号',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '头像',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '等级',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `bio` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '格言',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `score` int(10) NOT NULL DEFAULT '0' COMMENT '积分',
  `successions` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '连续登录天数',
  `maxsuccessions` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '最大连续登录天数',
  `prevtime` int(10) DEFAULT NULL COMMENT '上次登录时间',
  `logintime` int(10) DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '登录IP',
  `loginfailure` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '失败次数',
  `joinip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '加入IP',
  `jointime` int(10) DEFAULT NULL COMMENT '加入时间',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `token` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT 'Token',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '状态',
  `verification` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '验证',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `mobile` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员表';

/*Data for the table `fa_user` */

insert  into `fa_user`(`id`,`group_id`,`username`,`nickname`,`password`,`salt`,`email`,`mobile`,`avatar`,`level`,`gender`,`birthday`,`bio`,`money`,`score`,`successions`,`maxsuccessions`,`prevtime`,`logintime`,`loginip`,`loginfailure`,`joinip`,`jointime`,`createtime`,`updatetime`,`token`,`status`,`verification`) values (1,1,'admin','admin','53c2750b1d106f9a4ac21f90bd93e371','f1587d','admin@163.com','13888888888','',0,0,'2017-04-08','',0.00,0,1,1,1491635035,1491635035,'127.0.0.1',0,'127.0.0.1',1491635035,0,1491635035,'','normal','');

/*Table structure for table `fa_user_group` */

DROP TABLE IF EXISTS `fa_user_group`;

CREATE TABLE `fa_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '组名',
  `rules` text COLLATE utf8mb4_unicode_ci COMMENT '权限节点',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` enum('normal','hidden') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员组表';

/*Data for the table `fa_user_group` */

insert  into `fa_user_group`(`id`,`name`,`rules`,`createtime`,`updatetime`,`status`) values (1,'默认组','1,2,3,4,5,6,7,8,9,10,11,12',1491635035,1491635035,'normal');

/*Table structure for table `fa_user_money_log` */

DROP TABLE IF EXISTS `fa_user_money_log`;

CREATE TABLE `fa_user_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更余额',
  `before` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更前余额',
  `after` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更后余额',
  `memo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员余额变动表';

/*Data for the table `fa_user_money_log` */

/*Table structure for table `fa_user_rule` */

DROP TABLE IF EXISTS `fa_user_rule`;

CREATE TABLE `fa_user_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) DEFAULT NULL COMMENT '父ID',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '名称',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '标题',
  `remark` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `ismenu` tinyint(1) DEFAULT NULL COMMENT '是否菜单',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员规则表';

/*Data for the table `fa_user_rule` */

insert  into `fa_user_rule`(`id`,`pid`,`name`,`title`,`remark`,`ismenu`,`createtime`,`updatetime`,`weigh`,`status`) values (1,0,'index','Frontend','',1,1491635035,1491635035,1,'normal');
insert  into `fa_user_rule`(`id`,`pid`,`name`,`title`,`remark`,`ismenu`,`createtime`,`updatetime`,`weigh`,`status`) values (2,0,'api','API Interface','',1,1491635035,1491635035,2,'normal');
insert  into `fa_user_rule`(`id`,`pid`,`name`,`title`,`remark`,`ismenu`,`createtime`,`updatetime`,`weigh`,`status`) values (3,1,'user','User Module','',1,1491635035,1491635035,12,'normal');
insert  into `fa_user_rule`(`id`,`pid`,`name`,`title`,`remark`,`ismenu`,`createtime`,`updatetime`,`weigh`,`status`) values (4,2,'user','User Module','',1,1491635035,1491635035,11,'normal');
insert  into `fa_user_rule`(`id`,`pid`,`name`,`title`,`remark`,`ismenu`,`createtime`,`updatetime`,`weigh`,`status`) values (5,3,'index/user/login','Login','',0,1491635035,1491635035,5,'normal');
insert  into `fa_user_rule`(`id`,`pid`,`name`,`title`,`remark`,`ismenu`,`createtime`,`updatetime`,`weigh`,`status`) values (6,3,'index/user/register','Register','',0,1491635035,1491635035,7,'normal');
insert  into `fa_user_rule`(`id`,`pid`,`name`,`title`,`remark`,`ismenu`,`createtime`,`updatetime`,`weigh`,`status`) values (7,3,'index/user/index','User Center','',0,1491635035,1491635035,9,'normal');
insert  into `fa_user_rule`(`id`,`pid`,`name`,`title`,`remark`,`ismenu`,`createtime`,`updatetime`,`weigh`,`status`) values (8,3,'index/user/profile','Profile','',0,1491635035,1491635035,4,'normal');
insert  into `fa_user_rule`(`id`,`pid`,`name`,`title`,`remark`,`ismenu`,`createtime`,`updatetime`,`weigh`,`status`) values (9,4,'api/user/login','Login','',0,1491635035,1491635035,6,'normal');
insert  into `fa_user_rule`(`id`,`pid`,`name`,`title`,`remark`,`ismenu`,`createtime`,`updatetime`,`weigh`,`status`) values (10,4,'api/user/register','Register','',0,1491635035,1491635035,8,'normal');
insert  into `fa_user_rule`(`id`,`pid`,`name`,`title`,`remark`,`ismenu`,`createtime`,`updatetime`,`weigh`,`status`) values (11,4,'api/user/index','User Center','',0,1491635035,1491635035,10,'normal');
insert  into `fa_user_rule`(`id`,`pid`,`name`,`title`,`remark`,`ismenu`,`createtime`,`updatetime`,`weigh`,`status`) values (12,4,'api/user/profile','Profile','',0,1491635035,1491635035,3,'normal');

/*Table structure for table `fa_user_score_log` */

DROP TABLE IF EXISTS `fa_user_score_log`;

CREATE TABLE `fa_user_score_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `score` int(10) NOT NULL DEFAULT '0' COMMENT '变更积分',
  `before` int(10) NOT NULL DEFAULT '0' COMMENT '变更前积分',
  `after` int(10) NOT NULL DEFAULT '0' COMMENT '变更后积分',
  `memo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员积分变动表';

/*Data for the table `fa_user_score_log` */

/*Table structure for table `fa_user_token` */

DROP TABLE IF EXISTS `fa_user_token`;

CREATE TABLE `fa_user_token` (
  `token` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Token',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `expiretime` int(10) DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会员Token表';

/*Data for the table `fa_user_token` */

/*Table structure for table `fa_version` */

DROP TABLE IF EXISTS `fa_version`;

CREATE TABLE `fa_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `oldversion` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '旧版本号',
  `newversion` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '新版本号',
  `packagesize` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '包大小',
  `content` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '升级内容',
  `downloadurl` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '下载地址',
  `enforce` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '强制更新',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='版本表';

/*Data for the table `fa_version` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

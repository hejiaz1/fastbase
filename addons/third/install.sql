
CREATE TABLE IF NOT EXISTS `__PREFIX__third` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '会员ID',
  `platform` varchar(30) DEFAULT '' COMMENT '第三方应用',
  `unionid` varchar(100) DEFAULT '' COMMENT '第三方UNIONID',
  `openid` varchar(100) DEFAULT '' COMMENT '第三方OPENID',
  `openname` varchar(100) DEFAULT '' COMMENT '第三方会员昵称',
  `access_token` varchar(255) NULL DEFAULT '' COMMENT 'AccessToken',
  `refresh_token` varchar(255) DEFAULT 'RefreshToken',
  `expires_in` int(10) unsigned DEFAULT '0' COMMENT '有效期',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `logintime` int(10) unsigned DEFAULT NULL COMMENT '登录时间',
  `expiretime` int(10) unsigned DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `platform` (`platform`,`openid`),
  KEY `user_id` (`user_id`,`platform`),
  KEY `unionid` (`platform`,`unionid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='第三方登录表';

ALTER TABLE `__PREFIX__third` ADD COLUMN `unionid` varchar(100) NULL DEFAULT '' COMMENT '第三方UnionID' AFTER `platform`;
ALTER TABLE `__PREFIX__third` ADD INDEX `unionid`(`platform`, `unionid`);

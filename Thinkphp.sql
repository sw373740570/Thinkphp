DROP TABLE IF EXISTS `sms`;
CREATE TABLE `sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `mobile` char(13) NOT NULL COMMENT '手机号',
  `code` char(6) NOT NULL COMMENT '验证码',
  `message` varchar(200) NOT NULL COMMENT '发送信息',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `ip` char(15) DEFAULT '' COMMENT 'IP地址',
  `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8 COMMENT='短信验证码';
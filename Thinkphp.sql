DROP TABLE IF EXISTS `sms`;
CREATE TABLE `sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `mobile` char(13) NOT NULL COMMENT '�ֻ���',
  `code` char(6) NOT NULL COMMENT '��֤��',
  `message` varchar(200) NOT NULL COMMENT '������Ϣ',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `ip` char(15) DEFAULT '' COMMENT 'IP��ַ',
  `update_time` int(10) DEFAULT NULL COMMENT '����ʱ��',
  `create_time` int(10) NOT NULL COMMENT '����ʱ��',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8 COMMENT='������֤��';
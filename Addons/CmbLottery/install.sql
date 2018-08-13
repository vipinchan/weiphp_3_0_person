CREATE TABLE IF NOT EXISTS `wp_lottery_prize` (
`id`  int(10) NULL  COMMENT '奖品ID',
`name`  varchar(50) NULL  COMMENT '名称',
`num`  int(10) NULL  DEFAULT 0 COMMENT '数量',
`token`  varchar(100) NULL  COMMENT 'token'

) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `wp_lottery_prize` (`id`,`name`,`num`,`token`) VALUES ('2','ipad','0','gh_b29225c2a12a');
INSERT INTO `wp_lottery_prize` (`id`,`name`,`num`,`token`) VALUES ('1','iphone','2','gh_b29225c2a12a');
INSERT INTO `wp_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('lottery_prize','招行抽奖奖品表','0','','0','["id","name","num"]','1:基础','','','','','id:奖品ID\r\nname:名称\r\nnum:数量\r\nids:操作:[EDIT]|编辑,[DELETE]|删除','10','name:请输入名称','','1482042343','1482245011','1','MyISAM','CmbLottery');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('id','奖品ID','int(10) NULL','num','','奖品ID','1','','0','','1','1','1482042398','1482042398','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('name','名称','varchar(50) NULL','string','','名称','1','','0','','1','1','1482042428','1482042428','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('num','数量','int(10) NULL','num','0','数量','1','','0','','1','1','1482042468','1482042468','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(100) NULL','string','','','0','','0','','0','1','1482042558','1482042558','','3','','regex','get_token','3','function');
UPDATE `wp_attribute` a, wp_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;

CREATE TABLE IF NOT EXISTS `wp_lottery_user_prized` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`uid`  varchar(255) NULL  COMMENT '用户ID',
`truename`  varchar(100) NULL  COMMENT '姓名',
`mobile`  varchar(50) NULL  COMMENT '电话',
`address`  varchar(255) NULL  COMMENT '地址',
`prize_name`  varchar(255) NULL  COMMENT '奖品名称',
`prizeid`  int(10) NULL  COMMENT '奖品ID',
`ctime`  int(10) NULL  COMMENT '创建时间',
`token`  varchar(255) NULL  DEFAULT 'token' COMMENT 'token',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `wp_lottery_user_prized` (`id`,`uid`,`truename`,`mobile`,`address`,`prize_name`,`prizeid`,`ctime`,`token`) VALUES ('3','1','111','','','ipad','2','1482329117','gh_b29225c2a12a');
INSERT INTO `wp_lottery_user_prized` (`id`,`uid`,`truename`,`mobile`,`address`,`prize_name`,`prizeid`,`ctime`,`token`) VALUES ('4','1','111','','','ipad','2','1482329128','gh_b29225c2a12a');
INSERT INTO `wp_lottery_user_prized` (`id`,`uid`,`truename`,`mobile`,`address`,`prize_name`,`prizeid`,`ctime`,`token`) VALUES ('5','1','111','','','iphone','1','1482329517','gh_b29225c2a12a');
INSERT INTO `wp_lottery_user_prized` (`id`,`uid`,`truename`,`mobile`,`address`,`prize_name`,`prizeid`,`ctime`,`token`) VALUES ('6','1','111','','','ipad','2','1482329520','gh_b29225c2a12a');
INSERT INTO `wp_lottery_user_prized` (`id`,`uid`,`truename`,`mobile`,`address`,`prize_name`,`prizeid`,`ctime`,`token`) VALUES ('7','1','111','111','1111','ipad','2','1482329555','gh_b29225c2a12a');
INSERT INTO `wp_lottery_user_prized` (`id`,`uid`,`truename`,`mobile`,`address`,`prize_name`,`prizeid`,`ctime`,`token`) VALUES ('8','1','111','','','iphone','1','1482329565','gh_b29225c2a12a');
INSERT INTO `wp_lottery_user_prized` (`id`,`uid`,`truename`,`mobile`,`address`,`prize_name`,`prizeid`,`ctime`,`token`) VALUES ('9','1','111','','','iphone','1','1482329567','gh_b29225c2a12a');
INSERT INTO `wp_lottery_user_prized` (`id`,`uid`,`truename`,`mobile`,`address`,`prize_name`,`prizeid`,`ctime`,`token`) VALUES ('10','1','111','','','iphone','1','1482329568','gh_b29225c2a12a');
INSERT INTO `wp_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('lottery_user_prized','招行抽奖中奖信息表','0','','1','["uid","truename","mobile","address","prize_name"]','1:基础','','','','','id:ID\r\ntruename:姓名\r\nmobile:电话\r\nuid:用户ID\r\naddress:地址\r\nprize_name:奖品名称\r\nids:操作:[EDIT]|编辑,[DELETE]|删除','10','truename:请输入姓名','','1482041301','1482245002','1','MyISAM','CmbLottery');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('uid','用户ID','varchar(255) NULL','string','','用户ID','1','','0','','1','1','1482242250','1482041348','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('truename','姓名','varchar(100) NULL','string','','姓名','1','','0','','1','1','1482041407','1482041407','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('mobile','电话','varchar(50) NULL','string','','电话','1','','0','','1','1','1482077841','1482041440','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('address','地址','varchar(255) NULL','string','','地址','1','','0','','1','1','1482041510','1482041510','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('prize_name','奖品名称','varchar(255) NULL','string','','奖品名称','1','','0','','1','1','1482041594','1482041594','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('prizeid','奖品ID','int(10) NULL','num','','奖品ID','0','','0','','1','1','1482041645','1482041645','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('ctime','创建时间','int(10) NULL','datetime','','创建时间','0','','0','','1','1','1482041693','1482041693','','3','','regex','time','1','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','token','token','0','','0','','0','1','1482043151','1482043151','','3','','regex','get_token','3','function');
UPDATE `wp_attribute` a, wp_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;

CREATE TABLE IF NOT EXISTS `wp_lottery_user` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`truename`  varchar(100) NULL  COMMENT '姓名',
`mobile`  varchar(50) NULL  COMMENT '电话',
`ctime`  int(10) NULL  COMMENT '创建时间',
`chance`  int(10) NULL  COMMENT '抽奖机会',
`token`  varchar(255) NULL  COMMENT 'token',
`uid`  varchar(255) NULL  COMMENT '用户ID',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `wp_lottery_user` (`id`,`truename`,`mobile`,`ctime`,`chance`,`token`,`uid`) VALUES ('2','111','122','1482077196','4','gh_b29225c2a12a','1');
INSERT INTO `wp_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('lottery_user','招行抽奖用户表','0','','1','["uid","truename","mobile","chance"]','1:基础','','','','','id:ID\r\ntruename:姓名\r\nmobile:电话\r\nuid:用户ID\r\nchance:抽奖次数\r\nids:操作:[EDIT]|编辑,[DELETE]|删除','10','truename:请输入姓名','','1482040141','1482244990','1','MyISAM','CmbLottery');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('truename','姓名','varchar(100) NULL','string','','姓名','1','','0','','1','1','1482040803','1482040600','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('mobile','电话','varchar(50) NULL','string','','电话','1','','0','','1','1','1482040788','1482040676','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('ctime','创建时间','int(10) NULL','datetime','','创建时间','0','','0','','0','1','1482040762','1482040762','','3','','regex','time','1','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('chance','抽奖机会','int(10) NULL','num','','抽奖机会','1','','0','','1','1','1482254795','1482041219','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('token','token','varchar(255) NULL','string','','token','0','','0','','0','1','1482043196','1482043196','','3','','regex','get_token','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('uid','用户ID','varchar(255) NULL','string','','用户ID','1','','0','','0','1','1482242194','1482242194','','3','','regex','','3','function');
UPDATE `wp_attribute` a, wp_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;
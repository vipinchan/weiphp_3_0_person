CREATE TABLE IF NOT EXISTS `wp_mom_product_category` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`name`  varchar(255) NULL  COMMENT '分类名',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `wp_mom_product_category` (`id`,`name`) VALUES ('1','普通女性');
INSERT INTO `wp_mom_product_category` (`id`,`name`) VALUES ('2','产后妈妈');
INSERT INTO `wp_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('mom_product_category','美丽妈妈-产品分类表','0','','1','["name"]','1:基础','','','','','id:ID\r\nname:分类名称\r\nids:操作:detail?id=[id]|,[EDIT]|编辑,[DELETE]|删除','10','','','1534460516','1534584890','1','MyISAM','BeautyMom');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('name','分类名','varchar(255) NULL','string','','','1','','0','','0','1','1534572390','1534572390','','3','','regex','','3','function');
UPDATE `wp_attribute` a, wp_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;

CREATE TABLE IF NOT EXISTS `wp_mom_projectexpid_uid` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`projectid`  varchar(255) NULL  COMMENT '项目ID',
`uid`  varchar(255) NULL  COMMENT '用户ID',
`cTime`  int(10) NULL  COMMENT '创建时间',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;
INSERT INTO `wp_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('mom_projectexpid_uid','美丽妈妈-预约项目用户关联表','0','','1','["projectid","uid"]','1:基础','','','','','uid:用户ID\r\nprojectid:项目ID\r\ntruename:姓名\r\nmobile:手机号\r\ncTime|time_format:提交时间','10','','','1534804672','1534805501','1','MyISAM','BeautyMom');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('projectid','项目ID','varchar(255) NULL','string','','','1','','0','','0','1','1534804701','1534804701','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('uid','用户ID','varchar(255) NULL','string','','','1','','0','','0','1','1534804715','1534804715','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('cTime','创建时间','int(10) NULL','datetime','','','0','','0','','1','1','1534804756','1534804748','','3','','regex','','3','function');
UPDATE `wp_attribute` a, wp_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;

CREATE TABLE IF NOT EXISTS `wp_mom_user` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`uid`  varchar(255) NULL  COMMENT '用户ID',
`baby_birthday`  int(10) NULL  COMMENT '宝宝生日',
`is_second_baby`  tinyint(2) NULL  DEFAULT 0 COMMENT '是否二胎',
`credit_num`  int(10) NULL  COMMENT '积分',
`ecard_num`  decimal(7,2) NULL  COMMENT '充值卡金额',
`memberid`  varchar(255) NULL  COMMENT '会员ID',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

INSERT INTO `wp_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('mom_user','美丽妈妈-用户表','0','','1','["uid","baby_birthday","is_second_baby","credit_num","ecard_num","memberid"]','1:基础','','','','','memberid:会员ID\r\nheadimgurl|url_img_html:头像\r\nnickname|deal_emoji:用户昵称\r\ntruename:姓名\r\nsex|get_name_by_status:性别\r\nmobile:手机号\r\nids:操作:detail?uid=[uid]|详细资料,[EDIT]|编辑','10','','','1534432150','1534806658','1','MyISAM','BeautyMom');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('uid','用户ID','varchar(255) NULL','string','','','1','','0','','1','1','1534460970','1534460970','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('baby_birthday','宝宝生日','int(10) NULL','date','','','1','','0','','0','1','1534475510','1534475510','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('is_second_baby','是否二胎','tinyint(2) NULL','bool','0','0为否，1为是','1','0:否\r\n1:是','0','','0','1','1534641067','1534475908','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('credit_num','积分','int(10) NULL','num','','','1','','0','','0','1','1534476439','1534476439','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('ecard_num','充值卡金额','decimal(7,2) NULL','num','','','1','','0','','0','1','1534478472','1534478472','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('memberid','会员ID','varchar(255) NULL','string','','','1','','0','','0','1','1534806619','1534805909','','3','','regex','','3','function');
UPDATE `wp_attribute` a, wp_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;

CREATE TABLE IF NOT EXISTS `wp_mom_product` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`title`  varchar(255) NULL  COMMENT '标题',
`cost`  decimal(7,2) NULL  COMMENT '价格',
`service_times`  int(3) NULL  COMMENT '服务次数',
`cover`  int(10) NULL  COMMENT '封面图',
`content`  text NULL  COMMENT '内容',
`cat_id`  varchar(100) NULL  COMMENT '所属分类',
`description`  text NULL  COMMENT '描述',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

INSERT INTO `wp_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('mom_product','美丽妈妈-产品表','0','','1','["title","description","cover","cat_id","cost","service_times","content"]','1:基础','','','','','id:ID\r\ntitle:名称\r\ncost:价格\r\nservice_times:服务次数\r\ncat_id:所属分类\r\nids:操作:[EDIT]|编辑,[DELETE]|删除','10','','','1534460230','1534692299','1','MyISAM','BeautyMom');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('title','标题','varchar(255) NULL','string','','','1','','0','','1','1','1534572653','1534572506','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('cost','价格','decimal(7,2) NULL','num','','','1','','0','','0','1','1534572643','1534572643','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('service_times','服务次数','int(3) NULL','num','','','1','','0','','0','1','1534572700','1534572700','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('cover','封面图','int(10) NULL','picture','','','1','','0','','1','1','1534689478','1534584512','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('content','内容','text NULL','editor','','','1','','0','','0','1','1534592848','1534584529','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('cat_id','所属分类','varchar(100) NULL','dynamic_select','','','1','table=mom_product_category&value_field=id&title_field=name','0','','0','1','1534592624','1534584575','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('description','描述','text NULL','textarea','','','1','','0','','0','1','1534689114','1534689114','','3','','regex','','3','function');
UPDATE `wp_attribute` a, wp_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;

CREATE TABLE IF NOT EXISTS `wp_mom_project_exp` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
`title`  varchar(255) NULL  COMMENT '标题',
`cover`  int(10) UNSIGNED NULL  COMMENT '封面图',
`description`  text NULL  COMMENT '描述',
`content`  text  NULL  COMMENT '内容',
PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci CHECKSUM=0 ROW_FORMAT=DYNAMIC DELAY_KEY_WRITE=0;

INSERT INTO `wp_model` (`name`,`title`,`extend`,`relation`,`need_pk`,`field_sort`,`field_group`,`attribute_list`,`template_list`,`template_add`,`template_edit`,`list_grid`,`list_row`,`search_key`,`search_list`,`create_time`,`update_time`,`status`,`engine_type`,`addon`) VALUES ('mom_project_exp','美丽妈妈-体验项目表','0','','1','["title","description","cover","content"]','1:基础','','','','','id:ID\r\ntitle:名称\r\nids:操作:expUserLists?id=[id]|预约用户,[EDIT]|编辑,[DELETE]|删除','10','','','1534460335','1534804496','1','MyISAM','BeautyMom');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('title','标题','varchar(255) NULL','string','','','1','','0','','1','1','1534692697','1534692697','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('cover','封面图','int(10) UNSIGNED NULL','picture','','','1','','0','','0','1','1534692727','1534692727','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('description','描述','text NULL','textarea','','','1','','0','','0','1','1534692747','1534692747','','3','','regex','','3','function');
INSERT INTO `wp_attribute` (`name`,`title`,`field`,`type`,`value`,`remark`,`is_show`,`extra`,`model_id`,`model_name`,`is_must`,`status`,`update_time`,`create_time`,`validate_rule`,`validate_time`,`error_info`,`validate_type`,`auto_rule`,`auto_time`,`auto_type`) VALUES ('content','内容','text  NULL','editor','','','1','','0','','0','1','1534692765','1534692765','','3','','regex','','3','function');
UPDATE `wp_attribute` a, wp_model m SET a.model_id = m.id WHERE a.model_name=m.`name`;
DELETE FROM `wp_attribute` WHERE `model_name`='mom_product';
DELETE FROM `wp_model` WHERE `name`='mom_product' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_mom_product`;

DELETE FROM `wp_attribute` WHERE `model_name`='mom_product_category';
DELETE FROM `wp_model` WHERE `name`='mom_product_category' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_mom_product_category`;

DELETE FROM `wp_attribute` WHERE `model_name`='mom_project_exp';
DELETE FROM `wp_model` WHERE `name`='mom_project_exp' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_mom_project_exp`;

DELETE FROM `wp_attribute` WHERE `model_name`='mom_projectexpid_uid';
DELETE FROM `wp_model` WHERE `name`='mom_projectexpid_uid' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_mom_projectexpid_uid`;

DELETE FROM `wp_attribute` WHERE `model_name`='mom_user';
DELETE FROM `wp_model` WHERE `name`='mom_user' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_mom_user`;
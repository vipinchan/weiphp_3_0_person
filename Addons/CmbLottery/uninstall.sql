DELETE FROM `wp_attribute` WHERE `model_name`='lottery_prize';
DELETE FROM `wp_model` WHERE `name`='lottery_prize' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_lottery_prize`;

DELETE FROM `wp_attribute` WHERE `model_name`='lottery_user_prized';
DELETE FROM `wp_model` WHERE `name`='lottery_user_prized' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_lottery_user_prized`;

DELETE FROM `wp_attribute` WHERE `model_name`='lottery_user';
DELETE FROM `wp_model` WHERE `name`='lottery_user' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_lottery_user`;
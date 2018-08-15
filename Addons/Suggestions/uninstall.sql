DELETE FROM `wp_attribute` WHERE `model_name`='suggestions';
DELETE FROM `wp_model` WHERE `name`='suggestions' ORDER BY id DESC LIMIT 1;
DROP TABLE IF EXISTS `wp_suggestions`;
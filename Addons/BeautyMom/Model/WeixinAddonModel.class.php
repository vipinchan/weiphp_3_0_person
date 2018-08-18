<?php
        	
namespace Addons\BeautyMom\Model;
use Home\Model\WeixinModel;
        	
/**
 * BeautyMom的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'BeautyMom' ); // 获取后台插件的配置参数	
		//dump($config);
	}
}
        	
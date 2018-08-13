<?php
        	
namespace Addons\Example\Model;
use Home\Model\WeixinModel;
        	
/**
 * Example的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	function reply($dataArr, $keywordArr = array()) {
		$config = getAddonConfig ( 'Example' ); // 获取后台插件的配置参数	

		$this->replyText('13244');
		//dump($config);
	}
}
        	
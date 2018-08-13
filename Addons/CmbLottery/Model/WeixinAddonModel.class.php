<?php
        	
namespace Addons\CmbLottery\Model;
use Home\Model\WeixinModel;
        	
/**
 * CmbLottery的微信模型
 */
class WeixinAddonModel extends WeixinModel{
	// function reply($dataArr, $keywordArr = array()) {
	// 	$config = getAddonConfig ( 'CmbLottery' ); // 获取后台插件的配置参数	
	// 	//dump($config);
	// }

	function reply($dataArr, $keywordArr = array()) {
		return $this->replyText('123');


		// 其中token和openid这两个参数一定要传，否则程序不知道是哪个微信用户进入了系统
		// $param ['token'] = get_token ();
		// $param ['openid'] = get_openid ();
		// $url = addons_url ( 'CmbLottery://CmbLottery/index', $param );
		
		// // 组装微信需要的图文数据，格式是固定的
		// $articles [0] = array (
		// 		'Title' => 'test',
		// 		'Description' => '123',
		// 		'PicUrl' => '',
		// 		'Url' => $url 
		// );
		
		// $res = $this->replyNews ( $articles );
	}
}
        	
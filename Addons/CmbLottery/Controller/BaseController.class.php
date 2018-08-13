<?php

namespace Addons\CmbLottery\Controller;
use Home\Controller\AddonsController;

class BaseController extends AddonsController{

	function _initialize() {
		parent::_initialize();
		
		$controller = strtolower ( _CONTROLLER );
		$action = strtolower ( _ACTION );
		
		$res ['title'] = '用户管理';
		$res ['url'] = addons_url ( 'CmbLottery://LotteryUser/lists' );
		$res ['class'] = $controller == 'lotteryuser' ? 'current' : '';
		$nav [] = $res;

		$res ['title'] = '奖品库';
		$res ['url'] = addons_url ( 'CmbLottery://LotteryPrize/lists' );
		$res ['class'] = ($controller == 'lotteryprize') ? 'current' : '';
		$nav [] = $res;

		$res ['title'] = '中奖信息';
		$res ['url'] = addons_url ( 'CmbLottery://LotteryUserPrized/lists' );
		$res ['class'] = ($controller == 'lotteryuserprized') ? 'current' : '';
		$nav [] = $res;

		$this->assign ( 'nav', $nav );
		// $normal_tips = "分享制作网址：".addons_url ( 'WishCard://Wap/card_type',array('token'=>get_token()));	
		// $this->assign ( 'normal_tips', $normal_tips );
	}
}

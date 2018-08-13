<?php

namespace Addons\CmbLottery\Controller;
use Home\Controller\AddonsController;

class LotteryPrizeController extends BaseController{

    /**
	 * 显示指定模型列表数据
	 */
	public function lists() {
	    $isAjax = I ( 'isAjax' );
	    $isRadio = I ( 'isRadio' );
	    $param['mdm']=$_GET['mdm'];

	    $model = $this->getModel ( 'lottery_prize' );
		$list_data = $this->_get_model_list ( $model, 0, 'id desc', true );
	    if ($isAjax) {
	        $this->assign('isRadio',$isRadio);
	        $this->assign ( $list_data );
	        $this->display ( 'ajax_lists_data' );
	    } else {
	        $this->assign ( $list_data );
	        $this->display (  );
	    }

	}
}

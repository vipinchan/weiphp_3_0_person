<?php
namespace Addons\BeautyMom\Controller;

/**
 * 产品管理
 * 
 * @authors VipinChan (vipinchan@hotmail)
 * @date    2018-08-18 07:59:56
 * @version $Id$
 */

class ProductController extends BaseController {
	function _initialize() {
		parent::_initialize ();
	}
	
    public function index(){
        $this->display();
    }

    /**
     * 显示指定模型列表数据
     */
    public function lists() {
        // 获取模型信息
        $model = $this->getModel ('mom_product');
        
        // 获取模型列表数据
        $list_data = $this->_get_model_list ( $model );
        
        $this->assign ( $list_data );
        $this->display ();

    }

    public function category() {
        // 获取模型信息
        $model = $this->getModel ('mom_product_category');
        
        // 获取模型列表数据
        $list_data = $this->_get_model_list ( $model );
        
        $this->assign ( $list_data );
        $this->display ('lists');
    }

    function detail() {
        $uid = I ( 'uid' );
        $userInfo = D('User')->getUserInfoByUid($uid);
        $this->assign ( 'info', $userInfo );
        
        $this->display ();
    }

/*===============移动端管理-start================*/

    public function m_lists() {
        $lists = M('mom_product')->select();
        // var_dump($lists);
        $this->assign ( 'lists', $lists );
        $this->display ();
    }

    public function m_detail() {
        $id = I('id');
        $info = M('mom_product')->find();
        // var_dump($info);die();
        $this->assign ( 'info', $info );
        $this->display ();
    }

/*===============移动端管理-end================*/
}
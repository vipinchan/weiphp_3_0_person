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
        $cateArr = M ( 'mom_product_category' )->where ( $map )->getFields ( 'id,name' );
        $list_data = $this->_get_model_list ( $model );
        foreach ( $list_data ['list_data'] as &$data ) {
            $data ['cat_id'] = $cateArr [$data ['cat_id']];
        }
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

/*===============移动端管理-start================*/

    public function m_lists() {
        $lists = M('mom_product')->select();
        // var_dump($lists);
        $this->assign ( 'lists', $lists );
        $this->display ();
    }

    public function m_detail() {
        $data['id'] = I('id');
        $info = M('mom_product')->where($data)->find();
        // var_dump($info);die();
        $this->assign ( 'info', $info );
        $this->display ();
    }

/*===============移动端管理-end================*/
}
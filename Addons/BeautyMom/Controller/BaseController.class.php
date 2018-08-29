<?php
namespace Addons\BeautyMom\Controller;

use Home\Controller\AddonsController;

/**
 * BaseController
 *
 * @authors VipinChan (vipinchan@hotmail.com)
 * @date    2018-08-17 13:56:40
 * @version $Id$
 */

class BaseController extends AddonsController
{

    public function _initialize()
    {
        parent::_initialize();

        $uid = get_mid();
        if (isMobile() && (empty($uid) || $uid <= 0)) {
            $openId = get_openid();
            if (empty($openId) || $openId == '-1') {
                header("Content-Type:text/html;charset=UTF-8");
                echo '只开放微信用户访问';
                die();
            }
        }

        $controller = strtolower(_CONTROLLER);
        $action     = strtolower(_ACTION);

        if ($controller == 'user') {
            $res['title'] = '用户管理';
            $res['url']   = addons_url('BeautyMom://User/lists', array('mdm' => I('mdm')));
            $res['class'] = $controller . '/' . $action == 'user/lists' ? 'current' : '';
            $nav[]        = $res;

            if ($action == 'panel' || $action == 'rechargequery' || $action == 'creditquery' || $action == 'myproducts') {
                $res['title'] = '管理面板';
                $res['url']   = addons_url('BeautyMom://User/panel', array('uid' => I('uid')));
                $res['class'] = $controller . '/' . $action == 'user/panel' ? 'current' : '';
                $nav[]        = $res;

            }

            if ($action == 'rechargequery') {
                $res['title'] = '充值查询';
                $res['url']   = addons_url('BeautyMom://User/rechargeQuery', array('uid' => I('uid')));
                $res['class'] = $controller . '/' . $action == 'user/rechargequery' ? 'current' : '';
                $nav[]        = $res;
            }
            if ($action == 'creditquery') {
                $res['title'] = '积分查询';
                $res['url']   = addons_url('BeautyMom://User/creditQuery', array('uid' => I('uid')));
                $res['class'] = $controller . '/' . $action == 'user/creditquery' ? 'current' : '';
                $nav[]        = $res;
            }
        }

        if ($controller == 'product') {
            $res['title'] = '产品列表';
            $res['url']   = addons_url('BeautyMom://Product/lists', array('mdm' => I('mdm')));
            $res['class'] = $controller . '/' . $action == 'product/lists' ? 'current' : '';
            $nav[]        = $res;

            $res['title'] = '产品分类';
            $res['url']   = addons_url('BeautyMom://Product/category', array('mdm' => I('mdm')));
            $res['class'] = $controller . '/' . $action == 'product/category' ? 'current' : '';
            $nav[]        = $res;
        }

        if ($controller == 'projectexp') {
            $res['title'] = '体验项目';
            $res['url']   = addons_url('BeautyMom://ProjectExp/lists', array('mdm' => I('mdm')));
            $res['class'] = $controller . '/' . $action == 'projectexp/lists' ? 'current' : '';
            $nav[]        = $res;
        }

        if ($controller == 'order') {
            $res['title'] = '用户管理';
            $res['url']   = addons_url('BeautyMom://User/lists', array('mdm' => I('mdm')));
            $res['class'] = $controller . '/' . $action == 'user/lists' ? 'current' : '';
            $nav[]        = $res;

            $res['title'] = '管理面板';
            $res['url']   = addons_url('BeautyMom://User/panel', array('uid' => I('uid')));
            $res['class'] = $controller . '/' . $action == 'user/panel' ? 'current' : '';
            $nav[]        = $res;

            if ($action == 'lists' || $action == 'detail') {
                $res['title'] = '订单查询';
                $res['url']   = addons_url('BeautyMom://Order/lists', array('uid' => I('uid')));
                $res['class'] = $controller . '/' . $action == 'order/lists' ? 'current' : '';
                $nav[]        = $res;
            }
            if ($action == 'productcenter') {
                $res['title'] = '新建订单';
                $res['url']   = addons_url('BeautyMom://Order/productCenter', array('uid' => I('uid')));
                $res['class'] = $controller . '/' . $action == 'order/productcenter' ? 'current' : '';
                $nav[]        = $res;
            }
        }

        $this->assign('nav', $nav);
    }
}

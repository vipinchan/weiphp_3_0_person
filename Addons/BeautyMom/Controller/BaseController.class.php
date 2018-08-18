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

class BaseController extends AddonsController {
    
    function _initialize() {
        parent::_initialize();
        
        $controller = strtolower ( _CONTROLLER );
        $action = strtolower ( _ACTION );
        
        $res ['title'] = '用户管理';
        $res ['url'] = addons_url ( 'BeautyMom://User/lists' );
        $res ['class'] = $controller == 'user' ? 'current' : '';
        $nav [] = $res;

        $this->assign ( 'nav', $nav );
    }
}
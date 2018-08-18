<?php
namespace Addons\BeautyMom\Controller;
use Home\Controller\AddonsController;

/**
 * 用户管理
 * 
 * @authors VipinChan (vipinchan@hotmail)
 * @date    2018-08-18 07:59:56
 * @version $Id$
 */

class UserController extends BaseController {
    
    public function index(){
        $this->display();
    }

    /**
     * 显示指定模型列表数据
     */
    public function lists() {
        // 获取模型信息
        $model = $this->getModel ('mom_user');
        
        // 获取模型列表数据
        $list_data = $this->_get_model_list ( $model );
        
        // 获取相关的用户信息
        $uids = getSubByKey ( $list_data ['list_data'], 'uid' );
        $uids = array_filter ( $uids );
        $uids = array_unique ( $uids );
        if (! empty ( $uids )) {
            $map ['uid'] = array (
                    'in',
                    $uids 
            );
            $members = M ( 'user' )->where ( $map )->field ( 'uid,nickname,truename,mobile' )->select ();
            foreach ( $members as $m ) {
                ! empty ( $m ['truename'] ) || $m ['truename'] = $m ['nickname'];
                $user [$m ['uid']] = $m;
            }
            foreach ( $list_data ['list_data'] as &$vo ) {
                $vo ['mobile'] = $user [$vo ['uid']] ['mobile'];
                $vo ['truename'] = $user [$vo ['uid']] ['truename'];
            }
        }
        $this->assign ( $list_data );
        
        $this->display ();

    }           
}
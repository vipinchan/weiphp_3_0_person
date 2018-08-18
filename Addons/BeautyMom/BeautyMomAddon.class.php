<?php

namespace Addons\BeautyMom;
use Common\Controller\Addon;

/**
 * 美丽妈妈插件
 * @author vipinchan
 */

    class BeautyMomAddon extends Addon{

        public $info = array(
            'name'=>'BeautyMom',
            'title'=>'美丽妈妈',
            'description'=>'美丽妈妈产品。',
            'status'=>1,
            'author'=>'vipinchan',
            'version'=>'0.1',
            'has_adminlist'=>0
        );

	public function install() {
		$install_sql = './Addons/BeautyMom/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/BeautyMom/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }
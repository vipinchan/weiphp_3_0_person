<?php

namespace Addons\CmbLottery;
use Common\Controller\Addon;

/**
 * 招行摇一摇抽奖插件
 * @author VipinChan
 */

    class CmbLotteryAddon extends Addon{

        public $info = array(
            'name'=>'CmbLottery',
            'title'=>'招行摇一摇抽奖',
            'description'=>'招行抽奖应用',
            'status'=>1,
            'author'=>'VipinChan',
            'version'=>'0.1',
            'has_adminlist'=>1
        );

	public function install() {
		$install_sql = './Addons/CmbLottery/install.sql';
		if (file_exists ( $install_sql )) {
			execute_sql_file ( $install_sql );
		}
		return true;
	}
	public function uninstall() {
		$uninstall_sql = './Addons/CmbLottery/uninstall.sql';
		if (file_exists ( $uninstall_sql )) {
			execute_sql_file ( $uninstall_sql );
		}
		return true;
	}

        //实现的weixin钩子方法
        public function weixin($param){

        }

    }
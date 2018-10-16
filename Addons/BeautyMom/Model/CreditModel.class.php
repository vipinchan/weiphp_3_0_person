<?php
/**
 * 积分模型管理
 * @authors VipinChan (vipinchan@hotmail.com)
 * @date    2018-08-27 05:54:05
 * @version $Id$
 */

namespace Addons\BeautyMom\Model;

use Think\Model;

class CreditModel extends Model {
	protected $tableName = 'mom_credit_log';

	/**
	 * 添加积分变更记录
	 *
	 * @DateTime 2018-08-27T20:53:57+0800
	 * @author vipinchan
	 * @param    [type]
	 * @param    [type]
	 * @param    [type]
	 * @param    [type]
	 * @return   [type]
	 */
    function handleCredit($uid, $creditNum, $source, $sourceId) {
    	$data['credit_num'] = $creditNum;
    	$data['source'] = $source;
    	$data['source_id'] = $sourceId;
    	$data['uid'] = $uid;
    	$data['cTime'] = time();
    	return $this->data($data)->add();
    }

    /**
     * 提交订单产生积分
     *
     * @DateTime 2018-10-03T21:58:46+0800
     * @author vipinchan
     * @param    [type]
     * @return   [type]
     */
    function handleOrderCredit($order) {
        // 变更用户积分
        $uid                    = $order['uid'];
        $userInfo               = D('User')->getUserInfoByUid($uid);
        $userData['id']         = $userInfo['id'];
        $getCreditNum           = intval($order['amount'] / 100);
        $userData['credit_num'] = $userInfo['credit_num'] + $getCreditNum; // 100元1积分
        M('mom_user')->data($userData)->save();

        // 添加积分变更记录
        return $this->handleCredit($uid, $getCreditNum, 'order', $order['order_no']);
    }
}
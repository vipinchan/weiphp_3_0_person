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
}
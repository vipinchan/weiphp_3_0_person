<?php
namespace Addons\BeautyMom\Model;

use Think\Model;

/**
 * UserModel
 * 
 * @authors VipinChan (vipinchan@hotmail.com)
 * @date    2018-08-18 10:03:46
 * @version $Id$
 */

class UserModel extends Model {
	protected $tableName = 'mom_user';
    
    /**
     * 获取用户信息
     *
     * @DateTime 2018-08-18T12:57:26+0800
     * @author vipinchan
     *
     * @version [version]
     * @param [type] 
     * @return [type]
     */
	function getUserInfoByUid($uid) {
		$userinfo = getUserInfo($uid);

		$map['uid'] = $uid;
		$user = $this->where($map)->find();
		$user['nickname'] = $userinfo['nickname'];
		$user['mobile'] = $userinfo['mobile'];
		$user['truename'] = $userinfo['truename'];
		$user['sex'] = $userinfo['sex'];
		$user['sex_name'] = $userinfo['sex_name'];
		$user['headimgurl'] = $userinfo['headimgurl'];
		$user['birthday'] = $userinfo['birthday'];
		$user['profession'] = $userinfo['profession'];
		$user['address'] = $userinfo['address'];
		$user['reg_time'] = $userinfo['reg_time'];

		return $user;
	}

	/**
	 * 更新用户信息
	 * 并更新基础用户信息表
	 *
	 * @DateTime 2018-08-18T13:07:44+0800
	 * @author vipinchan
	 *
	 * @version [version]
	 * @param [type] 
	 * @param [type] 
	 * @param [type] 
	 * @return [type]
	 */
	public function updateUserFields($uid, $momUserData, $baseUserData) {
		if (empty ( $uid ) || empty ( $momUserData ) || empty ( $baseUserData )) {
			$this->error = '参数错误！';
			return false;
		}
		
		// 更新用户信息
		if ($this->create ( $momUserData )) {
			$res = $this->where ( array (
					'uid' => $uid 
			) )->save ( $momUserData );

			D('Common/User')->updateUserWithoutPassword($uid, $baseUserData);
			return $res;
		}
		return false;
	}
}
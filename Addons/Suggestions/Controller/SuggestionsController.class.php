<?php

namespace Addons\Suggestions\Controller;

use Home\Controller\AddonsController;

class SuggestionsController extends AddonsController {
	function lists() {
		// 获取模型信息
		$model = $this->getModel ();
		
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
		
		$this->display ( $model ['template_list'] );
	}
	function suggest($token) {
		$this->assign('token', $token);

		$config = getAddonConfig ( 'Suggestions' );
		$this->assign ( $config );
		
		$data ['uid'] = $this->mid;
		$user = get_userinfo($this->mid);
		$this->assign ( 'user', $user );
		
		if (IS_POST) {
			// 保存用户信息
			$truename = I ( 'truename' );
			if ($config ['need_truename'] && ! empty ( $truename )) {
				$member ['truename'] = $truename;
			}
			$mobile = I ( 'mobile' );
			if ($config ['need_mobile'] && ! empty ( $mobile )) {
				$member ['mobile'] = $mobile;
			}
			if (! empty ( $member )) {
				M ( 'user' )->where ( $data )->save ( $member );
			}
			
			// 保存内容
			$data ['cTime'] = time ();
			$data ['content'] = I ( 'content' );
			$data['token'] = get_token();
			
			$res = M ( 'suggestions' )->add ( $data );
			if ($res) {
				$msg = '增加成功，谢谢您的反馈';
				if($token == 'gh_4349363de83e') $msg = '再次感谢您对美丽妈妈（五缘湾店）的关心和支持！';
				$this->assign('closeWin', 1); // 提示后自动关闭当前窗口
				$this->success ( $msg );
			} else
				$this->error ( '增加失败，请稍后再试' );
		} else {
			$this->display ();
		}
	}
}

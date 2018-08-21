<?php
/**
 * 体验项目
 * 
 * @authors VipinChan (vipinchan@hotmail.com)
 * @date    2018-08-19 23:34:50
 * @version $Id$
 */

namespace Addons\BeautyMom\Controller;

class ProjectExpController extends BaseController {
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
        $model = $this->getModel ('mom_project_exp');
        
        // 获取模型列表数据
        $list_data = $this->_get_model_list ( $model );

        $this->assign ( $list_data );
        $this->display ();

    }

    public function expUserLists() {
        $projectid = I('id');

        // 获取模型信息
        $model = $this->getModel ('mom_projectexpid_uid');
        
        // 获取模型列表数据
        $list_data = $this->_get_model_list ( $model );

        $newList = Array();
        foreach ($list_data ['list_data'] as $item) {
            if($item['projectid'] == $projectid) {
                array_push($newList, $item);
            }
        }
        unset($list_data ['list_data']);
        $list_data ['list_data'] = $newList;
        // 获取相关的用户信息
        $uids = getSubByKey ( $list_data ['list_data'], 'uid' );
        $uids = array_filter ( $uids );
        $uids = array_unique ( $uids );
        if (! empty ( $uids )) {
            $map ['uid'] = array (
                    'in',
                    $uids 
            );
            $members = M ( 'user' )->where ( $map )->field ( 'uid,truename,mobile' )->select ();
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
        $this->display ('lists');

    }

/*===============移动端管理-start================*/

    public function m_lists() {
        $lists = M('mom_project_exp')->select();
        // var_dump($lists);
        
        foreach ($lists as &$project) {
            $projectid = $project['id'];
            $uid = get_mid();
            $project['userExped'] = $this->_userExped($projectid, $uid) ? '1' : '0';
        }
        $this->assign ( 'lists', $lists );
        $this->display ();
    }

    public function m_detail() {
        $data['id'] = I('id');
        $info = M('mom_project_exp')->where($data)->find();
        // var_dump($info);die();
        $this->assign ( 'info', $info );

        $userExped = $this->_userExped(I('id'), get_mid()) ? '1' : '0';
        $this->assign('userExped', $userExped);
        $this->display ();
    }

    /**
     * 判断用户是否已预约
     *
     * @DateTime 2018-08-20T22:38:48+0800
     * @author vipinchan
     *
     * @version [version]
     * @param [type] 
     * @param [type] 
     * @return [type]
     */
    public function _userExped($projectid, $uid) {
        $data['projectid'] = $projectid;
        $data['uid'] = $uid;
        $model = M('mom_projectexpid_uid');
        $info = $model->where($data)->find();
        if(!$info) return false;
        return true;
    }

    public function applyExp() {
        $projectid = I('id');
        $isTrue = I('isTrue');
        $uid = get_mid();

        if(!get_truename($uid)) {
            $this->error ( '请先填写注册信息');
            die();
        }

        if(!empty($projectid) && !empty($uid)) {
            $data['projectid'] = $projectid;
            $data['uid'] = $uid;

            $model = M('mom_projectexpid_uid');
            $userExped = $this->_userExped($projectid, $uid);
            if(!$userExped && $isTrue == 1) { // not found
                $data['cTime'] = time();
                if($model->data($data)->add()) {
                    $this->success ( '预约成功');
                }
            } else if($userExped && $isTrue == 0) {
                if($model->where($data)->delete()) {
                    $this->success ( '已取消');
                }
            }
        }
        $this->error ( '操作失败');
    }

/*===============移动端管理-end================*/
}
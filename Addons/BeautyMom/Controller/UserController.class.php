<?php
namespace Addons\BeautyMom\Controller;

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
/*===============PC端管理-start================*/

    /**
     * 用户列表管理
     *
     * @DateTime 2018-08-19T08:09:31+0800
     * @author vipinchan
     *
     * @version [version]
     * @return [type]
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
            $members = M ( 'user' )->where ( $map )->field ( 'uid,nickname,truename,mobile,sex,headimgurl' )->select ();
            foreach ( $members as $m ) {
                ! empty ( $m ['truename'] ) || $m ['truename'] = $m ['nickname'];
                $user [$m ['uid']] = $m;
            }
            foreach ( $list_data ['list_data'] as &$vo ) {
                $vo ['nickname'] = $user [$vo ['uid']] ['nickname'];
                $vo ['mobile'] = $user [$vo ['uid']] ['mobile'];
                $vo ['truename'] = $user [$vo ['uid']] ['truename'];
                $vo ['sex'] = $user [$vo ['uid']] ['sex'];
                $vo ['headimgurl'] = $user [$vo ['uid']] ['headimgurl'];
            }
        }
        $this->assign ( $list_data );
        
        $this->display ();

    }

    /**
     * 查看用户详细信息
     *
     * @DateTime 2018-08-19T08:09:48+0800
     * @author vipinchan
     *
     * @version [version]
     * @return [type]
     */
    function detail() {
        $uid = I ( 'uid' );
        $userInfo = D('User')->getUserInfoByUid($uid);
        $this->assign ( 'info', $userInfo );
        
        $this->display ();
    }

    /**
     * 编辑用户信息
     * 只会修改不会新增。
     *
     * @DateTime 2018-08-19T08:10:03+0800
     * @author vipinchan
     *
     * @version [version]
     * @return [type]
     */
    function edit() {
        $id = I ( 'id', 0, 'intval' );
        $uid = $id;
        $userInfo = D('User')->getUserInfoByUid($uid);

        $model = $this->getModel ( 'mom_user' );

        if (IS_POST) {
            // var_dump($_POST);die();
            $act = empty ( $id ) ? 'add' : 'save';
            $Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
            // 获取模型的字段信息
            $Model = $this->checkAttr ( $Model, $model ['id'] );

            $res = false;

            $data = I ( 'post.' );
            $date = preg_replace('/-/', '', day_format($userInfo['reg_time']));
            if(empty($userInfo['memberid'])) $data['memberid'] = $date . substr('000' . $userInfo['id'], -4); // 生成会员ID
            $Model->create ($data) && $res = $Model->$act ();
            if ($res !== false) {
                $baseUserData = array(
                    'truename' => I('truename'),
                    'mobile' => I('mobile'),
                    'birthday' => strtotime(I('birthday')),
                    'profession' => I('profession'),
                    'address' => I('address')
                );
                D('Common/User')->updateUserWithoutPassword($uid, $baseUserData);

                $this->success ( '保存成功！', U ( 'lists?model=' . $model ['name'], $this->get_param ) );
            } else {
                $this->error ( $Model->getError () );
            }
        } else {
            // var_dump($userInfo);
            // 获取数据
            $this->assign ( 'data', $userInfo );

            $this->display ( 'edit' );
        }
    }
/*===============PC端管理-end================*/

/*===============移动端管理-start================*/

    function m_detail() {
        $uid = get_mid();
        $userInfo = D('User')->getUserInfoByUid($uid);
        $this->assign ( 'info', $userInfo );
        
        $this->display ();
    }

    function m_regist() {
        $uid = get_mid();
        $userInfo = getUserInfo($uid);
        $model = $this->getModel ( 'mom_user' );
// var_dump($userInfo);
        if (IS_POST) {
            // var_dump($_POST);die();
            if (!empty(I('truename')) && !empty(I('mobile'))) {
                $baseUserData = array(
                    'truename' => I('truename'),
                    'mobile' => I('mobile')
                );
                D('Common/User')->updateUserWithoutPassword($uid, $baseUserData);


                if(empty($userInfo['memberid'])) {
                    $date = preg_replace('/-/', '', day_format($userInfo['reg_time']));
                    $data['memberid'] = $date . substr('000' . $userInfo['id'], -4); // 生成会员ID
                    $data['id'] = $userInfo['id'];
                    M('mom_user')->data($data)->save ();
                }

                $this->success ( '保存成功！');
            } else {
                $this->error ( '姓名或手机号不能为空' );
            }
        } else {
            // var_dump($userInfo);
            // 获取数据
            $this->assign ( 'info', $userInfo );

            $this->display ();
        }
    }

    function m_edit() {
        $uid = get_mid();
        $userInfo = D('User')->getUserInfoByUid($uid);
        $model = $this->getModel ( 'mom_user' );
// var_dump($userInfo);
        if (IS_POST) {
            // var_dump($_POST);die();
            $act = 'save';
            $Model = D ( parse_name ( get_table_name ( $model ['id'] ), 1 ) );
            // 获取模型的字段信息
            $Model = $this->checkAttr ( $Model, $model ['id'] );

            $res = false;
            $Model->create () && $res = $Model->$act ();
            if ($res !== false) {
                $baseUserData = array(
                    'truename' => I('truename'),
                    'mobile' => I('mobile'),
                    'birthday' => strtotime(I('birthday')),
                    'profession' => I('profession'),
                    'address' => I('address'),
                    'sex' => I('sex')
                );
                D('Common/User')->updateUserWithoutPassword($uid, $baseUserData);

                if(empty($userInfo['memberid'])) {
                    $date = preg_replace('/-/', '', day_format($userInfo['reg_time']));
                    $data['memberid'] = $date . substr('000' . $userInfo['id'], -4); // 生成会员ID
                    $data['id'] = $userInfo['id'];
                    M('mom_user')->data($data)->save ();
                }

                $this->success ( '保存成功！');
            } else {
                $this->error ( $Model->getError () );
            }
        } else {
            // var_dump($userInfo);
            // 获取数据
            $this->assign ( 'info', $userInfo );

            $this->display ();
        }
    }

/*===============移动端管理-end================*/
}
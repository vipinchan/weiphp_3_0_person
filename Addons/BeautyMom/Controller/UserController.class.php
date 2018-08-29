<?php
namespace Addons\BeautyMom\Controller;

/**
 * 用户管理
 *
 * @authors VipinChan (vipinchan@hotmail)
 * @date    2018-08-18 07:59:56
 * @version $Id$
 */

class UserController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
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
    public function lists()
    {
        // 获取模型信息
        $model = $this->getModel('mom_user');

        // 获取模型列表数据
        $list_data = $this->_get_model_list($model);

        // 获取相关的用户信息
        $uids = getSubByKey($list_data['list_data'], 'uid');
        $uids = array_filter($uids);
        $uids = array_unique($uids);
        if (!empty($uids)) {
            $map['uid'] = array(
                'in',
                $uids,
            );
            $members = M('user')->where($map)->field('uid,nickname,truename,mobile,sex,headimgurl')->select();
            foreach ($members as $m) {
                !empty($m['truename']) || $m['truename'] = $m['nickname'];
                $user[$m['uid']]                         = $m;
            }
            foreach ($list_data['list_data'] as &$vo) {
                $vo['nickname']   = $user[$vo['uid']]['nickname'];
                $vo['mobile']     = $user[$vo['uid']]['mobile'];
                $vo['truename']   = $user[$vo['uid']]['truename'];
                $vo['sex']        = $user[$vo['uid']]['sex'];
                $vo['headimgurl'] = $user[$vo['uid']]['headimgurl'];
            }
        }
        $this->assign($list_data);

        $this->display();

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
    public function detail()
    {
        $uid      = I('uid');
        $userInfo = D('User')->getUserInfoByUid($uid);
        $this->assign('info', $userInfo);

        $this->display();
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
    public function edit()
    {
        $id       = I('id', 0, 'intval');
        $uid      = $id;
        $userInfo = D('User')->getUserInfoByUid($uid);

        $model = $this->getModel('mom_user');

        if (IS_POST) {
            // var_dump($_POST);die();
            $act   = empty($id) ? 'add' : 'save';
            $Model = D(parse_name(get_table_name($model['id']), 1));
            // 获取模型的字段信息
            $Model = $this->checkAttr($Model, $model['id']);

            $res = false;

            $data = I('post.');
            $date = preg_replace('/-/', '', day_format($userInfo['reg_time']));
            if (empty($userInfo['memberid'])) {
                $data['memberid'] = $date . substr('000' . $userInfo['id'], -4);
            }
            // 生成会员ID
            $Model->create($data) && $res = $Model->$act();
            if ($res !== false) {
                $baseUserData = array(
                    'truename'   => I('truename'),
                    'mobile'     => I('mobile'),
                    'birthday'   => strtotime(I('birthday')),
                    'profession' => I('profession'),
                    'address'    => I('address'),
                );
                D('Common/User')->updateUserWithoutPassword($uid, $baseUserData);

                $this->success('保存成功！', U('lists?model=' . $model['name'], $this->get_param));
            } else {
                $this->error($Model->getError());
            }
        } else {
            // var_dump($userInfo);
            // 获取数据
            $this->assign('data', $userInfo);

            $this->display('edit');
        }
    }

    /**
     * 管理面板
     *
     * @DateTime 2018-08-26T06:44:55+0800
     * @author vipinchan
     */
    public function panel()
    {
        $uid = I('uid');
        if(!isset($uid)) {
            $this-error('出错了！');
        }

        $userData = D('User')->getUserInfoByUid($uid);
        $this->assign('user', $userData);
        $this->assign('uid', $uid);
        $this->display();
    }

    /**
     * 充值
     *
     * @DateTime 2018-08-26T06:56:30+0800
     * @author vipinchan
     * @return   [type]
     */
    public function recharge()
    {
        $uid = I('uid');
        $this->assign('uid', $uid);
        if (IS_POST) {
            $data['uid']    = $uid;
            $data['amount'] = I('amount');
            $data['remark'] = '充值';
            if (empty($data['uid']) || empty($data['amount'])) {
                $this . error('金额不能为空');
            }

            // 入库，充值记录表
            $data['mid']   = $this->mid;
            $data['cTime'] = time();
            $res           = false;
            $res           = M('mom_ecard_log')->data($data)->add();
            // 入库，用户当前金额
            if ($res) {
                $userInfo   = D('User')->getUserInfoByUid($data['uid']);
                $user['id'] = $userInfo['id'];
                $user['ecard_num'] = floatval($userInfo['ecard_num']) + $data['amount'];
                $res = M('mom_user')->data($user)->save();

                if($res) $this->success('充值成功');

                // 充值失败
                $record = M('mom_ecard_log')->data($data)->find();
                M('mom_ecard_log')->delete($record['id']);
            }

            $this->error('充值失败');
        }
        $this->display();
    }

    /**
     * 充值查询
     *
     * @DateTime 2018-08-26T06:57:41+0800
     * @author vipinchan
     * @return   [type]
     */
    public function rechargeQuery()
    {
        $this->assign('add_button', false);
        $this->assign('del_button', false);
        $uid = I('uid');
        // 获取模型信息
        $model = $this->getModel('mom_ecard_log');

        // 获取模型列表数据
        $list_data = $this->_get_model_list($model);

        $newList = Array();
        foreach ($list_data ['list_data'] as $item) {
            if($item['uid'] == $uid) {
                array_push($newList, $item);
            }
        }
        unset($list_data ['list_data']);
        $list_data ['list_data'] = $newList;

        $this->assign($list_data);
        $this->display('lists');
    }

    /**
     * 积分查询
     *
     * @DateTime 2018-08-27T06:04:16+0800
     * @author vipinchan
     * @return   [type]
     */
    public function creditQuery()
    {
        $this->assign('add_button', false);
        $this->assign('del_button', false);
        $uid = I('uid');
        // 获取模型信息
        $model = $this->getModel('mom_credit_log');

        // 获取模型列表数据
        $list_data = $this->_get_model_list($model);

        $newList = Array();
        foreach ($list_data ['list_data'] as $item) {
            if($item['uid'] == $uid) {
                array_push($newList, $item);
            }
        }
        unset($list_data ['list_data']);
        $list_data ['list_data'] = $newList;

        $this->assign($list_data);
        $this->display('lists');
    }

    /**
     * 我订购的服务项目
     *
     * @DateTime 2018-08-27T21:35:11+0800
     * @author vipinchan
     * @param    [type]
     * @return   [type]
     */
    public function myProducts($uid)
    {
        $this->assign('add_button', false);
        $this->assign('del_button', false);
        // 获取模型信息
        $model = $this->getModel('mom_order_product');

        // 获取模型列表数据
        $list_data = $this->_get_model_list($model);

        // 不显示用户ID
        $list_grids = $list_data['list_grids'];
        $keys  = array_keys($list_grids);
        $index = array_search('uid', $keys);
        array_splice($list_grids, $index);
        unset($list_data['list_grids']);
        $list_data['list_grids'] = $list_grids;

        if (!empty($uid)) {
            $newList = array();
            foreach ($list_data['list_data'] as $item) {
                if ($item['uid'] == $uid) {
                    array_push($newList, $item);
                }
            }
            unset($list_data['list_data']);
            $list_data['list_data'] = $newList;
        }
        // var_dump($list_data);
        $this->assign($list_data);

        // $products = M('mom_order_product')->where(array('uid' => $uid))->select();
        // $this->assign('products', $products);
        $this->display('lists');
    }

    public function editMyProduct()
    {
        $id       = I('id', 0, 'intval');
        $productInfo = M('mom_order_product')->where('id=' . $id)->find();

        $model = $this->getModel('mom_order_product');

        if (IS_POST) {
            // var_dump($_POST);die();
            $act   = empty($id) ? 'add' : 'save';
            $Model = D(parse_name(get_table_name($model['id']), 1));
            // 获取模型的字段信息
            $Model = $this->checkAttr($Model, $model['id']);

            $res = false;

            $data = I('post.');

            $Model->create($data) && $res = $Model->$act();
            if ($res !== false) {
                $this->success('保存成功！', U('myProducts', $this->get_param));
            } else {
                $this->error($Model->getError());
            }
        } else {
            // var_dump($productInfo);
            // 获取数据
            $this->assign('data', $productInfo);

            $this->display();
        }
    }
/*===============PC端管理-end================*/

/*===============移动端管理-start================*/

    public function m_detail()
    {
        $uid      = get_mid();
        $userInfo = D('User')->getUserInfoByUid($uid);

        if (empty($userInfo['id'])) {
            $url = addons_url('BeautyMom://User/m_edit');
            redirect($url);
        }
        $this->assign('info', $userInfo);

        $this->display();
    }

    public function m_regist()
    {
        $uid      = get_mid();
        $userInfo = getUserInfo($uid);
        $model    = $this->getModel('mom_user');
// var_dump($userInfo);
        if (IS_POST) {
            // var_dump($_POST);die();
            $truename = I('truename');
            $mobile   = I('mobile');
            if (!empty($truename) && !empty($mobile)) {
                // if (!empty(I('truename')) && !empty(I('mobile'))) {  // 真是奇葩，这种直接获取request参数判断服务器就是会报500
                $baseUserData = array(
                    'truename' => $truename,
                    'mobile'   => $mobile,
                );
                D('Common/User')->updateUserWithoutPassword($uid, $baseUserData);

                $act             = 'add';
                $res             = false;
                $userData['uid'] = $uid;
                if (!empty($userInfo['id'])) {
                    $userData['id'] = $userInfo['id'];
                    $act            = 'save';
                } else {
                    $date                 = preg_replace('/-/', '', day_format($userInfo['reg_time']));
                    $userData['memberid'] = $date . substr('000' . $uid, -4); // 生成会员ID
                }
                $res = M('mom_user')->data($userData)->$act();

                $this->success('保存成功！');
            } else {
                $this->error('姓名或手机号不能为空');
            }
        } else {
            // var_dump($userInfo);
            // 获取数据
            $this->assign('info', $userInfo);

            $this->display();
        }
    }

    public function m_edit()
    {
        $uid      = get_mid();
        $userInfo = D('User')->getUserInfoByUid($uid);
// var_dump($userInfo);
        if (IS_POST) {
            // var_dump($_POST);die();
            $truename = I('truename');
            $mobile   = I('mobile');
            if (empty($truename) || empty($mobile)) {
                $this->success('姓名和手机号不能为空');
            }

            $act             = 'add';
            $res             = false;
            $userData        = I('post.');
            $userData['uid'] = $uid;
            if (!empty($userData['baby_birthday'])) {
                $userData['baby_birthday'] = strtotime($userData['baby_birthday']);
            }

            if (!empty($userInfo['id'])) {
                $userData['id'] = $userInfo['id'];
                $act            = 'save';
            } else {
                $date                 = preg_replace('/-/', '', day_format($userInfo['reg_time']));
                $userData['memberid'] = $date . substr('000' . $uid, -4); // 生成会员ID
            }
            $res = M('mom_user')->data($userData)->$act();
            if ($res !== false) {
                $baseUserData = array(
                    'truename'   => I('truename'),
                    'mobile'     => I('mobile'),
                    'birthday'   => strtotime(I('birthday')),
                    'profession' => I('profession'),
                    'address'    => I('address'),
                    'sex'        => I('sex'),
                );
                D('Common/User')->updateUserWithoutPassword($uid, $baseUserData);

                // if(empty($userInfo['memberid'])) {
                //     $date = preg_replace('/-/', '', day_format($userInfo['reg_time']));
                //     $data['memberid'] = $date . substr('000' . $userInfo['id'], -4); // 生成会员ID
                //     $data['id'] = $userInfo['id'];
                //     M('mom_user')->data($data)->save ();
                // }

                $this->success('保存成功！');
            } else {
                $this->error($Model->getError());
            }
        } else {
            // var_dump($userInfo);
            // 获取数据
            $this->assign('info', $userInfo);

            $this->display();
        }
    }

/*===============移动端管理-end================*/
}

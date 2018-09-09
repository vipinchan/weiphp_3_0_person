<?php
/**
 * 体验项目
 *
 * @authors VipinChan (vipinchan@hotmail.com)
 * @date    2018-08-19 23:34:50
 * @version $Id$
 */

namespace Addons\BeautyMom\Controller;

class ProjectExpController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $this->display();
    }

    /**
     * 显示指定模型列表数据
     */
    public function lists()
    {
        // 获取模型信息
        $model = $this->getModel('mom_project_exp');

        // 获取模型列表数据
        $list_data = $this->_get_model_list($model);
        foreach ($list_data['list_data'] as &$data) {
            $data['current_count'] = $this->_getExpUserCount($data['id']);
        }

        $this->assign($list_data);
        $this->display();

    }

    public function expUserLists()
    {
        $this->assign('add_button', false);
        $projectid = I('id');

        // 获取模型信息
        $model = $this->getModel('mom_projectexpid_uid');

        // 获取模型列表数据
        $list_data = $this->_get_model_list($model);

        $newList = array();
        foreach ($list_data['list_data'] as $item) {
            if ($item['projectid'] == $projectid) {
                array_push($newList, $item);
            }
        }
        unset($list_data['list_data']);
        $list_data['list_data'] = $newList;
        // 获取相关的用户信息
        $uids = getSubByKey($list_data['list_data'], 'uid');
        $uids = array_filter($uids);
        $uids = array_unique($uids);
        if (!empty($uids)) {
            $map['uid'] = array(
                'in',
                $uids,
            );
            $members = M('user')->where($map)->field('uid,truename,mobile')->select();
            foreach ($members as $m) {
                !empty($m['truename']) || $m['truename'] = $m['nickname'];
                $user[$m['uid']]                         = $m;
            }
            foreach ($list_data['list_data'] as &$vo) {
                $vo['mobile']   = $user[$vo['uid']]['mobile'];
                $vo['truename'] = $user[$vo['uid']]['truename'];
            }
        }

        $this->assign($list_data);
        $this->display('lists');

    }

/*===============移动端管理-start================*/

    public function m_lists()
    {
        $lists = M('mom_project_exp')->select();
        // var_dump($lists);

        foreach ($lists as &$project) {
            $projectid            = $project['id'];
            $uid                  = get_mid();
            $project['userExped'] = $this->_userIsExped($projectid, $uid) ? '1' : '0';
            $project['isOverExp'] = $this->_isOverExp($projectid) ? 1 : 0;
        }
        $this->assign('lists', $lists);
        $this->display();
    }

    public function m_detail()
    {
        $data['id'] = I('id');
        $info       = M('mom_project_exp')->where($data)->find();
        // var_dump($info);die();
        $this->assign('info', $info);

        $userExped = $this->_userIsExped(I('id'), get_mid()) ? '1' : '0';
        $this->assign('userExped', $userExped);

        // 判断预约是否满了
        $isOverExp = $this->_isOverExp(I('id'));
        $this->assign('isOverExp', $isOverExp ? 1 : 0);
        $this->display();
    }

    /**
     * 申请预约项目
     *
     * @DateTime 2018-09-09T12:34:37+0800
     * @author vipinchan
     * @return   [type]
     */
    public function applyExp()
    {
        $projectid     = I('id');
        $isTrue        = I('isTrue');
        $cancel_reason = I('cancel_reason');
        $order_time    = I('order_time');
        $uid           = get_mid();

        if(empty($projectid)) {
            $this->error('参数有误');
        }

        if (!get_truename($uid)) {
            $this->error('请先填写注册信息', -1);
            die();
        }

        if (empty($order_time) && empty($cancel_reason)) {
            $this->error('请填写完整');
        }

        if (!empty($cancel_reason) && strlen($cancel_reason) > 200) {
            $this->error('字数不要超出200字');
        }

        if (!empty($projectid) && !empty($uid)) {
            $data['projectid'] = $projectid;
            $data['uid']       = $uid;
            if (!empty($order_time)) {
                $data['order_time'] = strtotime($order_time);
            }

            if (!empty($cancel_reason)) {
                $data['cancel_reason'] = $cancel_reason;
            }

            $model     = M('mom_projectexpid_uid');
            $userExped = $this->_userExped($projectid, $uid);
            if ($isTrue == 1) {
                if($this->_isOverExp($projectid)) {
                    $this->error('预约已满');die();
                }
                if ($userExped == null) {
                    // not found
                    $data['cTime']  = time();
                    $data['status'] = 1;
                    if ($model->data($data)->add()) {
                        $this->success('预约成功');
                    }
                } else {
                    $userExped['order_time'] = $data['order_time'];
                    $userExped['status'] = 1;
                    if($model->data($userExped)->save()) $this->success('预约成功');
                }

            } else if ($userExped != null && $isTrue == 0) {
                $myData['id']            = $userExped['id'];
                $myData['status']        = 0;
                $myData['cancel_reason'] = $cancel_reason;
                if ($model->data($myData)->save()) {
                    $this->success('已取消');
                }
            }
        }
        $this->error('操作失败');
    }

/*===============移动端管理-end================*/

    /**
     * 获取当前项目已预约用户数
     *
     * @DateTime 2018-09-09T12:37:37+0800
     * @author vipinchan
     * @param    [type]
     * @return   [type]
     */
    public function _getExpUserCount($projectid) {
        $data['projectid'] = $projectid;
        $data['status']     = 1;
        $model             = M('mom_projectexpid_uid');
        $list              = $model->where($data)->select();

        return count($list);
    }

    /**
     * 判断预约是否满了
     *
     * @DateTime 2018-09-04T00:31:35+0800
     * @author vipinchan
     * @param    [type]
     * @return   boolean
     */
    public function _isOverExp($projectid) {
        $data['projectid'] = $projectid;
        $data['status']     = 1;
        $model             = M('mom_projectexpid_uid');
        $list              = $model->where($data)->select();

        $info       = M('mom_project_exp')->where('id = '. $projectid)->find();
        if(!empty($info['limit_count']) && count($list) >= $info['limit_count']) return true;

        return false;
    }

    /**
     * 获取已预约用户信息
     *
     * @DateTime 2018-08-20T22:38:48+0800
     * @author vipinchan
     *
     * @version [version]
     * @param [type]
     * @param [type]
     * @return [type]
     */
    public function _userExped($projectid, $uid)
    {
        $data['projectid'] = $projectid;
        $data['uid']       = $uid;
        $model             = M('mom_projectexpid_uid');
        $info              = $model->where($data)->find();
        if (!$info) {
            return null;
        }

        return $info;
    }

    /**
     * 判断用户是否已预约
     *
     * @DateTime 2018-09-09T12:35:03+0800
     * @author vipinchan
     * @param    [type]
     * @param    [type]
     * @return   [type]
     */
    public function _userIsExped($projectid, $uid)
    {
        $data['projectid'] = $projectid;
        $data['uid']       = $uid;
        $data['status']     = 1;
        $model             = M('mom_projectexpid_uid');
        $info              = $model->where($data)->find();
        if (!$info)  return false;

        return true;
    }
}

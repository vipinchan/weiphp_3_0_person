<?php
/**
 * 订单管理
 *
 * @authors VipinChan (vipinchan@hotmail.com)
 * @date    2018-08-26 09:53:18
 * @version $Id$
 */

namespace Addons\BeautyMom\Controller;

class OrderController extends BaseController
{
    /**
     * 产品中心，挑选产品，创建订单
     *
     * @DateTime 2018-08-26T09:56:04+0800
     * @author vipinchan
     * @return   [type]
     */
    public function productCenter()
    {
        $uid = I('uid');
        $this->assign('uid', $uid);
        // 获取模型信息
        $model = $this->getModel('mom_product');

        // 获取模型列表数据
        $cateArr   = M('mom_product_category')->where($map)->getFields('id,name');
        $list_data = $this->_get_model_list($model);
        foreach ($list_data['list_data'] as &$data) {
            $data['cat_id'] = $cateArr[$data['cat_id']];
        }
        $list_grids = $list_data['list_grids'];
        $keys       = array_keys($list_grids);
        $index      = array_search('ids', $keys);
        array_splice($list_grids, $index);

        unset($list_data['list_grids']);
        $list_data['list_grids'] = $list_grids;
        $this->assign($list_data);
        $this->display();
    }

    /**
     * 提交订单，还未支付
     *
     * @DateTime 2018-08-26T14:57:44+0800
     * @author vipinchan
     * @param    [type]
     * @return   [type]
     */
    public function submitOrder($ids = null, $uid = null)
    {
        if (empty($ids) || empty($uid)) {
            $this->error('请选择产品');
        }
        $data['order_no'] = date('Ymd') . time() . mt_rand(1000, 9999); // 生成订单号
        $data['cTime']    = time();
        $data['mid']      = $this->mid;
        $data['status']   = 0;
        $data['uid']      = $uid;

        $where['id'] = array('in', $ids);
        $products    = M('mom_product')->where($where)->select();

        $data['amount'] = 0;

        foreach ($products as $item) {
            $data['amount'] += $item['cost'];

            $temp['order_no']              = $data['order_no'];
            $temp['product_id']            = $item['id'];
            $temp['product_title']         = $item['title'];
            $temp['product_service_times'] = $item['service_times'];
            $temp['remain_service_times'] = $item['service_times'];
            $temp['product_cost']          = $item['cost'];
            $temp['uid']          = $uid;
            $orderProduct[]                = $temp;
        }
        $res = M('mom_order_product')->addAll($orderProduct);
        if ($res) {
            M('mom_order')->data($data)->add();
            $this->success('订单创建成功', U('detail', array('order_no' => $data['order_no'], 'uid' => $uid)));
        }
        $this->error('订单创建失败');
    }

    /**
     * 订单列表
     *
     * @DateTime 2018-08-26T15:04:52+0800
     * @author vipinchan
     * @return   [type]
     */
    public function lists($uid = null)
    {
        $this->assign('add_button', false);
        $this->assign('del_button', false);
        // 获取模型信息
        $model = $this->getModel('mom_order');

        // 获取模型列表数据
        $list_data = $this->_get_model_list($model);

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

        $this->assign($list_data);
        $this->display('lists');
    }

    /**
     * 订单详情页
     *
     * @DateTime 2018-08-26T14:59:24+0800
     * @author vipinchan
     * @param    [type]
     * @return   [type]
     */
    public function detail($order_no, $uid)
    {
        if (empty($order_no) || empty($uid)) {
            $this->error('出错了！');
        }
        $userInfo = D('User')->getUserInfoByUid($uid);
        $order    = M('mom_order')->where(array('order_no' => $order_no))->find();
        $products = M('mom_order_product')->where(array('order_no' => $order_no))->select();
        // $where['id'] = array('in', $productIds);
        // $products = M('mom_product')->where($where)->select();
        // var_dump($products);
        $this->assign('user', $userInfo);
        $this->assign('order', $order);
        $this->assign('products', $products);
        $this->display();
    }

    /**
     * 更新订单状态
     *
     * @DateTime 2018-08-26T15:01:34+0800
     * @author vipinchan
     * @return   [type]
     */
    public function changeStatus($status)
    {
        $order_no = I('order_no');

        $order = M('mom_order')->where(array('order_no' => $order_no))->find();
        // var_dump($order);

        if ($order['status'] == '0') {
            $data['id']     = $order['id'];
            $data['status'] = $status;
            if (M('mom_order')->data($data)->save()) {
                if ($status == '1') {
                    // 变更用户积分
                    $uid                    = $order['uid'];
                    $userInfo               = D('User')->getUserInfoByUid($uid);
                    $userData['id']         = $userInfo['id'];
                    $getCreditNum           = intval($order['amount'] / 100);
                    $userData['credit_num'] = $userInfo['credit_num'] + $getCreditNum; // 100元1积分
                    M('mom_user')->data($userData)->save();

                    // 添加积分变更记录
                    D('Credit')->handleCredit($uid, $getCreditNum, 'order', $order['order_no']);
                }
                $this->success('操作成功');
            }
        }
        $this->error('操作失败');
    }

    public function pay($order_no) {
        $order    = M('mom_order')->where(array('order_no' => $order_no))->find();
        $userInfo = D('User')->getUserInfoByUid($order['uid']);

        if (IS_POST) {
            $pay_by_ecard = I('pay_by_ecard');
            $pay_by_cash = I('pay_by_cash');

            if (empty($pay_by_ecard) && empty($pay_by_cash)) {
                $this->error('金额不能为空');
            }
            $ecard = floatval($pay_by_ecard);
            $cash = floatval($pay_by_cash);
            // var_dump($userInfo['ecard_num']);die();
            if($ecard > $userInfo['ecard_num'] || $ecard + $cash != $order['amount']) {
                $this->error('支付金额不对，请重新核对');
            }

            $status = 1; // 已支付状态
            $order = M('mom_order')->where(array('order_no' => $order_no))->find();

            if ($order['status'] == '0') {
                $orderData['id']     = $order['id'];
                $orderData['status'] = $status;
                $orderData['pay_by_cash'] = $cash;
                $orderData['pay_by_ecard'] = $ecard;
                if (M('mom_order')->data($orderData)->save()) {
                    // 添加积分变更记录
                    D('Credit')->handleOrderCredit($order);

                    $data['uid']    = $order['uid'];
                    $data['amount'] = -$ecard;  // 本次使用余额支付金额
                    $data['remark'] = '订单消费';

                    // 入库，充值记录表
                    $data['mid']   = $this->mid;
                    $data['cTime'] = time();
                    M('mom_ecard_log')->data($data)->add();
                    // 入库，用户当前金额
                    $user['id'] = $userInfo['id'];
                    $user['ecard_num'] = floatval($userInfo['ecard_num']) - $ecard;
                    $res = M('mom_user')->data($user)->save();

                    if($res) $this->success('操作成功');
                }
            }

            $this->error('操作失败');
        }

        $this->assign('order', $order);
        $this->assign('user', $userInfo);
        $this->display();
    }
}

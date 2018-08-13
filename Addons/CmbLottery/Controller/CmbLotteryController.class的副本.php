<?php

namespace Addons\CmbLottery\Controller;
use Home\Controller\AddonsController;

class CmbLotteryController extends BaseController{

    public function index(){
        $openId = I('openid');
        if(empty($openId)) $openId = get_openid ();
        $this->assign('openId', $openId);

        $map['uid'] = $openId;
        $user = M('lottery_user')->where($map)->find();

        if(!empty($user)){
            $this->assign('user', $user);
        }

        $url = GetCurUrl();

        vendor('jssdk.jssdk', '' ,'.php');
        $options = array(
              'token'=>'', //填写你设定的key
              'encodingaeskey'=>'', //填写加密用的EncodingAESKey
              'appid'=>'wxcea8f8ccfef5fb41', //填写高级调用功能的app id
              'appsecret'=>'3af9f8fdeeb93ddec4113ca5bc1e7ddf' //填写高级调用功能的密钥
          );
        $weObj = new \JSSDK($options);

        $jssdkConfig = $weObj->getJsSign($url);

        if(!empty($user)){
            $this->assign('jssdkConfig', $jssdkConfig);
        }

        $this->display();
    }

    public function info() {
    	$data['success'] = false;
    	$data['chance'] = 0;
    	$data['msg'] = 'error';
    	$data['isValidate'] = false; //是否合法用户

    	$openId = I('openId');
    	if(!empty($openId)){
    		$map['uid'] = $openId;
    		$user = M('lottery_user')->where($map)->find();
    		if(!empty($user)) {
	    		$data['success'] = true;
		    	$data['chance'] = $user['chance'];
		    	$data['msg'] = 'success';
		    	$data['isValidate'] = true; //是否合法用户
    		} else {
    			$data['success'] = true;
    		}
    	}
    	echo json_encode($data);
    }

    public function submitPrizeInfo() {
    	$data['success'] = false;
    	$data['msg'] = '操作失败';

    	$openId = I('openId');
    	$name = I('name');
    	$phone = I('phone');
    	$prizeId = I('prizeId');
    	$recordId = I('recordId');
    	$addr = I('addr');
    	if(!empty($openId) && !empty($name) && !empty($phone) && !empty($addr) && !empty($prizeId) && !empty($recordId)){
    		$map['id'] = $recordId;
	    	$map['prizeid'] = $prizeId;
	    	$map['uid'] = $openId;
    		$item = M('lottery_user_prized')->where($map)->find();
            //$data['msg'] = implode('|', $map);

    		if(!empty($item)) {
    			$item['truename'] = $name;
    			$item['mobile'] = $phone;
    			$item['address'] = trim($addr);
	    		if(M('lottery_user_prized')->data($item)->save()) {
	    			$data['success'] = true;
    				$data['msg'] = '提取成功';
	    		}
    		} else {
                $data['msg'] = '中奖信息有误';
            }
    	} else {
            $data['msg'] = '请填写完整';
        }

    	echo json_encode($data);
    }

    //暂无用
    public function submitSignInfo() {
    	$data['success'] = false;
    	$data['msg'] = '操作失败';

    	$openId = I('openId');
    	$name = I('name');
    	$phone = I('phone');
    	if(!empty($openId) && !empty($name) && !empty($phone)){
    		$map['uid'] = $openId;
    		$user = M('lottery_user')->where($map)->find();
    		if(empty($user)) {
	    		$map['truename'] = $name;
	    		$map['mobile'] = $phone;
	    		$map['ctime'] = time();
	    		$map['token'] = get_token();
	    		if(M('lottery_user')->data($map)->save()) {
	    			$data['success'] = true;
    				$data['msg'] = '操作成功';
	    		}
    		}
    	}

    	echo json_encode($data);
    }

    public function submitValidateInfo() {
 	
    	$data['success'] = false;
    	$data['chance'] = 0;
    	$data['msg'] = 'error';
    	$data['isValidate'] = false; //是否合法用户


    	$openId = I('openId');
    	$name = I('name');
    	$phone = I('phone');

    	if(!empty($openId) && !empty($name) && !empty($phone)){
    		$map['truename'] = $name;
	    	$map['mobile'] = $phone;
    		$user = M('lottery_user')->where($map)->find();

    		if(!empty($user)) {
    			$user['uid'] = $openId;
	    		if(M('lottery_user')->data($user)->save()) {
	    			$data['success'] = true;
    				$data['msg'] = '操作成功';
    				$data['playerName'] = $name;
    				$data['isValidate'] = true; //是否合法用户
    				$data['chance'] = $user['chance'];
	    		}
    		}
    	}

    	echo json_encode($data);
    }

    public function shake() {
    	$data['success'] = false;
    	$data['chance'] = 0;
    	$data['msg'] = '机会用完了哦';

    	// $data['prizeId'] = 1;
    	// $data['recordId'] = '2172';

    	$openId = I('openId');
    	if(!empty($openId)){
    		$map['uid'] = $openId;
    		$user = M('lottery_user')->where($map)->find();

    		if(!empty($user) && $user['chance'] > 0) {
    			$prizes = M('lottery_prize')->where('num > 0')->select();

    			if(!empty($prizes)) {
    				$count = count($prizes);
    				$rnd = rand(0, $count - 1);
    				$prize = $prizes[$rnd];

    				$record['token'] = get_token();
	    			$record['ctime'] = time();
	    			$record['prizeid'] = $prize['id'];
	    			$record['prize_name'] = $prize['name'];
	    			$record['uid'] = $openId;
	    			$record['truename'] = $user['truename'];
	    			$insertId = M('lottery_user_prized')->data($record)->add();


	    			$user['chance'] = $user['chance'] - 1;
	    			M('lottery_user')->data($user)->save();


	    			$prize['num'] -= 1;
	    			M('lottery_prize')->data($prize)->save();

		    		$data['success'] = true;
			    	$data['chance'] = $user['chance'];
			    	$data['msg'] = 'success';
			    	$data['prizeId'] = $prize['id'];
	    			$data['recordId'] = $insertId;
    			} else {
                    $data['chance'] = $user['chance'];
                    $data['msg'] = '奖品被抢光了';
                }
    		}
    	}

    	echo json_encode($data);
    }

    public function myPrizes() {
		// $prizes = array
		// (
		//     array("id"=>18,"name"=>"时尚毛毯","recordId"=>"2172","hasInfo"=>false),
		//     array("id"=>1,"name"=>"ipad","recordId"=>"2171","hasInfo"=>true),
		// );

    	$data['success'] = false;
    	$data['msg'] = 'error';

    	$openId = I('openId');
    	if(!empty($openId)){
    		$map['uid'] = $openId;
    		$prizes = M('lottery_user_prized')->where($map)->select();
    		$data['success'] = true;
    		$data['msg'] = 'success';
    		$data['list'] = $prizes;
    	}
    	
    	echo json_encode($data);
    }
}

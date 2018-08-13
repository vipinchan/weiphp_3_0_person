<?php
echo THINK_PATH.'Library/Vendor/jssdk/WeChat.php';
require 'ThinkPHP/Library/Vendor/jssdk/WeChat.php';
$options = array(
      'token'=>'', //填写你设定的key
      'encodingaeskey'=>'', //填写加密用的EncodingAESKey
      'appid'=>'wxcea8f8ccfef5fb41', //填写高级调用功能的app id
      'appsecret'=>'3af9f8fdeeb93ddec4113ca5bc1e7ddf' //填写高级调用功能的密钥
  );
$weObj = new WeChat($options);

$signPackage = $weObj->getJsSign("http://baidu.com");
var_dump($signPackage);die();
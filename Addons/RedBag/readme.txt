微信红包插件实现企业商户给用户自动发放红包的功能，它可创建一个活动，可配置活动发放红包金额总数，发放最多人数，每人领取红包时随机取到的最大和最小金额值，可配置活动的开始时间和结束时间，发放红包的企业商户名称，每人领取次数限制等。功能强大，配置灵活。

由于发红包涉及证书，支付密钥等关键私密信息，因此安装插件稍复杂些，安装前请注意以下几点：

1、测试环境需要在外网环境，不可以是本地开发环境，因为与微信进行双向验证时微信无法请求到本地环境

2、领取红包时系统需要获取用户的openid，需要保证系统中的get_openid能正常获取到值

3、上面的openid值应该是微信支付商户对应的公众号下的openid

4、配置的API密钥的值必须正确，否则会提示签名失败

5、本插件已经在我们官方系统运营使用，整体功能是正常，如果您安装后有问题，请检查各种配置参数是否正确


请按以下步骤安装插件

1、下载插件，解压放到WeiPHP产品的Addons目录下

2、进入后台，进入插件管理=》微信插件页面，找到微信红包插件，点击右边的安装链接进行安装

3、下载商户的两个证书：apiclient_cert.pem 和 apiclient_key.pem，请放置到你的服务器上，并把证书的位置配置到 RedBag/Controller/WapController.class.php 文件中，建议不要放到WEB虚拟目录下。

4、在 RedBag/Controller/WapController.class.php 中还需要配置商户号，API密钥信息，商户名中对应的APPID要与当前系统的公众号APPID一致 
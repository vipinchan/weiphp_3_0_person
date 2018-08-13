
/**
 * author arvin
 * version 1.0.0
 *
 */
//所有接口通过wx对象(也可使用jWeixin对象)来调用，参数是一个对象，除了每个接口本身需要传的参数之外，还有以下通用参数：
//success：接口调用成功时执行的回调函数。
//fail：接口调用失败时执行的回调函数。
//complete：接口调用完成时执行的回调函数，无论成功或失败都会执行。
//cancel：用户点击取消时的回调函数，仅部分有用户取消操作的api才会用到。
//trigger: 监听Menu中的按钮点击时触发的方法，该方法仅支持Menu中的相关接口。
//以上几个函数都带有一个参数，类型为对象，其中除了每个接口本身返回的数据之外，还有一个通用属性errMsg，其值格式如下：
//调用成功时："xxx:ok" ，其中xxx为调用的接口名
//用户取消时："xxx:cancel"，其中xxx为调用的接口名
//调用失败时：其值为具体错误信息

(function(win){

    function _wxapi($, wx){
        var wxapi = {};
        var isReady = false;
        var debug = false;
        var debugMsg = false;
        var readyCallback = function(){
        };
        var url = '';
        var appId = ''; // 必填，公众号的唯一标识
        var timestamp = ''; // 必填，生成签名的时间戳
        var nonceStr = ''; // 必填，生成签名的随机串
        var signature = ''; // 必填，签名，见附录1
        var jsApiList = ['onMenuShareTimeline', 'onMenuShareAppMessage', 'onMenuShareQQ', 'onMenuShareWeibo', 'startRecord', 'stopRecord', 'onVoiceRecordEnd', 'playVoice', 'pauseVoice', 'stopVoice', 'onVoicePlayEnd', 'uploadVoice', 'downloadVoice', 'chooseImage', 'previewImage', 'uploadImage', 'downloadImage', 'translateVoice', 'getNetworkType', 'openLocation', 'getLocation', 'hideOptionMenu', 'showOptionMenu', 'hideMenuItems', 'showMenuItems', 'hideAllNonBaseMenuItem', 'showAllNonBaseMenuItem', 'closewin', 'scanQRCode', 'chooseWXPay', 'openProductSpecificView', 'addCard', 'chooseCard', 'openCard']
        
        var shareConfig = {
            title: document.title, // 分享标题
            desc: $('body > *').eq(0).length ? $('body > *').eq(0).html() : '分享描述未定义', // 分享描述
            link: win.location.href, // 分享链接
            imgUrl: $('body img').length ? $('body img').eq(0)[0].src : '', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空,
            success: function(res){
                // 用户确认分享后执行的回调函数
            },
            cancel: function(res){
                // 用户取消分享后执行的回调函数
            }
        }
        
        //错误打印
        function log(msg){
        
            win.seajs && seajs.log ? seajs.log(msg) : console.log(msg);
            
            if (debugMsg) {
                var errorMsg = $('.error-msg');
                if (!errorMsg.length) {
                    errorMsg = $('<div class="error-msg" style=" position:fixed; z-index:10000; left:0px; top:50%; width:100%; height:100%; overflow:auto; text-align:center; background:rgba(255, 255, 255, 0.6);"></div>');
                    $('body').append(errorMsg)
                }
                errorMsg.html(errorMsg.html() + '<br/>' + msg);
            }
            
        }
        //获取初始化微信配置
        function getConfig(){
            log('getConfig');

            $.ajax({
                url: url || 'json/getWxConfig.json',
				method: 'POST',
                data: {
					url: win.location.href
				},
                success: function(data){
                    if (typeof data !== 'object') {
                        data = JSON.parse(data);
                    }
                    if (!data.success) {
                        log(data.msg);
                        return;
                    }
					//data = data.result;
                    appId = data.appId;
                    timestamp = data.timestamp;
                    nonceStr = data.nonceStr;
                    signature = data.signature;
                    log('getConfig success');
                    //初始化微信配置
                    setConfig();
                    
                },
                error: function(data){
                    log('getConfig 请求失败！');
                }
            })
        }
        function setConfig(){
            log('setConfig');
            wx.config({
                debug: debug, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                appId: appId, // 必填，公众号的唯一标识
                timestamp: timestamp, // 必填，生成签名的时间戳
                nonceStr: nonceStr, // 必填，生成签名的随机串
                signature: signature,// 必填，签名，见附录1
                jsApiList: jsApiList // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
            });
            wx.ready(function(){
                log('ready');
                isReady = true;
                checkJsApi();
                setShare();
                typeof readyCallback === 'function' && readyCallback();
                
                // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
            });
            wx.error(function(res){
                log(res.errMsg);
                // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
            
            });
            
        }
        function setShare(){
            log('setShare');
            //获取“分享到朋友圈”按钮点击状态及自定义分享内容接口
            // wx.onMenuShareTimeline({
            //     title: shareConfig.desc, // 分享标题
            //     link: shareConfig.link, // 分享链接
            //     imgUrl: shareConfig.imgUrl, // 分享图标
            //     success: function(res){
            //         // 用户确认分享后执行的回调函数
            //         log(res.errMsg);
            //         typeof shareConfig.success === 'function' && shareConfig.success(res);
                    
            //     },
            //     cancel: function(res){
            //         // 用户取消分享后执行的回调函数
            //         log(res.errMsg);
            //         typeof shareConfig.cancel === 'function' && shareConfig.cancel(res);
            //     }
            // });
            // //获取“分享给朋友”按钮点击状态及自定义分享内容接口
            // wx.onMenuShareAppMessage({
            //     title: shareConfig.title, // 分享标题
            //     desc: shareConfig.desc, // 分享描述
            //     link: shareConfig.link, // 分享链接
            //     imgUrl: shareConfig.imgUrl, // 分享图标
            //     type: shareConfig.type, // 分享类型,music、video或link，不填默认为link
            //     dataUrl: shareConfig.dataUrl, // 如果type是music或video，则要提供数据链接，默认为空
            //     success: function(res){
            //         // 用户确认分享后执行的回调函数
            //         log(res.errMsg);
            //         typeof shareConfig.success === 'function' && shareConfig.success(res);
            //     },
            //     cancel: function(res){
            //         // 用户取消分享后执行的回调函数
            //         log(res.errMsg);
            //         typeof shareConfig.cancel === 'function' && shareConfig.cancel(res);
            //     }
            // });
            // //获取“分享到QQ”按钮点击状态及自定义分享内容接口
            // wx.onMenuShareQQ({
            //     title: shareConfig.title, // 分享标题
            //     desc: shareConfig.desc, // 分享描述
            //     link: shareConfig.link, // 分享链接
            //     imgUrl: shareConfig.imgUrl, // 分享图标
            //     success: function(res){
            //         // 用户确认分享后执行的回调函数
            //         log(res.errMsg);
            //         typeof shareConfig.success === 'function' && shareConfig.success(res);
            //     },
            //     cancel: function(res){
            //         // 用户取消分享后执行的回调函数
            //         log(res.errMsg);
            //         typeof shareConfig.cancel === 'function' && shareConfig.cancel(res);
            //     }
            // });
            // //获取“分享到腾讯微博”按钮点击状态及自定义分享内容接口
            // wx.onMenuShareWeibo({
            //     title: shareConfig.title, // 分享标题
            //     desc: shareConfig.desc, // 分享描述
            //     link: shareConfig.link, // 分享链接
            //     imgUrl: shareConfig.imgUrl, // 分享图标
            //     success: function(res){
            //         // 用户确认分享后执行的回调函数
            //         log(res.errMsg);
            //         typeof shareConfig.success === 'function' && shareConfig.success(res);
            //     },
            //     cancel: function(res){
            //         // 用户取消分享后执行的回调函数
            //         log(res.errMsg);
            //         typeof shareConfig.cancel === 'function' && shareConfig.cancel(res);
            //     }
            // });
        }
        function checkJsApi(){
            wx.checkJsApi({
                jsApiList: jsApiList, // 需要检测的JS接口列表，所有JS接口列表见附录2
                success: function(res){
                    log(res.errMsg);
                    // 以键值对的形式返回，可用的api值true，不可用为false
                    // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
                },
                fail: function(res){
                    log(res.errMsg);
                }
            })
        }
        //初始化
        function init(config){
			
			config = config || {};
        
            debug = !!config.debug;
            debugMsg = !!config.debugMsg;
            url = config.url || '';
            appId = config.appId || '';
            timestamp = config.timestamp || '';
            nonceStr = config.nonceStr || '';
            signature = config.signature || '';
            readyCallback = config.readyCallback ||
            function(){
            };
            
            //获取配置
            getConfig();
        }
        
        $.extend(wxapi, wx);
        
        wxapi.init = function(config){
            init(config);
        }
        
        wxapi.setShare = function(config){
            $.extend(shareConfig, config);
            if (isReady) {
                setShare();
            }
        }
        wxapi.log = function(msg){
            return log(msg);
        };
        return wxapi;
    }
    
    !!win.define ? define(function(require, exports, module){
        var wx = require('http://res.wx.qq.com/open/js/jweixin-1.0.0.js');
		wx = win.wx || wx;
        return _wxapi(win.$, wx)
    }) : (win.wxapi = _wxapi($, wx));
    
}(window))


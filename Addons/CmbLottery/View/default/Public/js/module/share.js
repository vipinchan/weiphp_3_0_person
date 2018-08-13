define(function(require, exports, module) {
    seajs.log('load share');

    function init() {
        //初始化分享
        wxapi.init({
            debug: false,
            debugMsg: false,
            readyCallback: function() {

            }
        });
    }



    function setShare(type) {

        var page = type || $('body').attr('data-page');
        var title, desc, link;

        switch (page) {
            case "sign":
                title = document.title;
                desc = "招商银行老客户推荐新客户办卡，新老客户可100%中奖！最高拿IPAD！";
                link = "sign.aspx";
                break;
            case "index":
                if (!window.prizeId) {
                    title = document.title;
                    desc = "招商银行摇幸运铃，赢取专属大奖";
                    link = "index.aspx";
                    break;
                }
            case "share":
                title = document.title;
                desc = window.playerName + "在招商银行“人荐人爱”活动中抽中了" + window.prizes[window.prizeId - 1].name;

                link = "share.aspx?ownerId=" + (window.ownerId || window.openId);
                break;
        }

        wxapi.setShare({
            title: title, // 分享标题
            desc: desc, // 分享描述
            link: window.location.protocol + '//' + window.location.host + '/zsyhcj/' + link, // 分享链接
            imgUrl: window.location.protocol + '//' + window.location.host + '/zsyhcj/images/' + (window.prizeId && page != 'sign' ? ('share' + window.prizeId + '.jpg?v=1') : 'wxShare.jpg'), // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空,
            success: function(res) {
                typeof callback === 'function' && callback();
            },
            cancel: function(res) {
                // 用户取消分享后执行的回调函数
            }

        })
    }



    module.exports = {
        setShare: setShare,
        init: init
    }



})
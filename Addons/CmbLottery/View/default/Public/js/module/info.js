define(function(require, exports, module) {
    seajs.log('load info');


    var loading = require('./loading');
    var alert = require('./alert');
    var share = require('./share');

    var audio = require('./audio');

    var ring = audio.create({
        src: publicPath + '/audio/ring.mp3',
        autoplay: false
    });

    function get(callback) {
        loading.show();
        $.ajax({
            //url: 'json/info.json',
            url: infoUrl,
            method: 'POST',
            data: {
                openId: window.openId
            },
            success: function(data) {

                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }

                //提交失败后的报错
                if (!data.success) {
                    alert.show(data.msg);
                    loading.hide();
                    return;
                }
                loading.hide();

                window.isValidate = data.isValidate;
                window.chance = data.chance;


                updateInfo();
                getMyPrizes();

                typeof callback === 'function' && callback(data);

            },
            error: function() {

                alert.show('info.ashx请求失败请重试');
                loading.hide();
            }
        })

    }

    function updateInfo() {

        window.isValidate ? $('.s9, .s10').addClass('skip') : $('.s9, .s10').removeClass('skip');
        $('.slide .slide-item.s10 .content .desc span').html(window.chance);
        $('.s10 .content .name .ellipsis, .s12 .content .name .ellipsis, .s15 .content .name .ellipsis').html(window.playerName);
        $('.s11 .content .desc span').html(window.chance);

    }

    window.submitSignInfo = submitSignInfo;

    function submitSignInfo(el, callback) {
        if ($(el).hasClass('disabled')) {
            return;
        }
        var _this = this;
        var info = getSignData();
        if (typeof info === 'string') {
            alert.show(info);
            return;
        }
        $(el).addClass('disabled');
        loading.show();
        /**
         * 返回的json数据结构
         * ｛
         *      success：true,//true代表请求成功，false代表失败
         *      msg: '' //返回的消息
         *  ｝
         */
        $.ajax({
            //url: 'json/submitSignInfo.json',
            url: submitSignInfoUrl,
            method: 'POST',
            data: info,
            success: function(data) {
                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }
                //提交失败后的报错
                if (!data.success) {
                    alert.show(data.msg);
                    loading.hide();
                    $(el).removeClass('disabled');
                    return;
                }
                $(el).removeClass('disabled');
                loading.hide();

                alert.show('信息提交成功<br/>小招会尽快与您联系哦', function() {
                    window.swipeUp();
                });


                typeof callback === 'function' && callback.call(el, data);



            },
            error: function() {
                alert.show(JSON.stringify(arguments));
                $(el).removeClass('disabled');
                loading.hide();
            }
        })

    }

    function getSignData() {
        var name = $('.s7 .form input[name="name"]').val().trim();
        var phone = parseInt($('.s7 .form input[name="phone"]').val().trim());
        var card = $('.s7 .form input[name="card"]').val().trim();

        if (!name) {
            return '请输入您的姓名';
        }
        if (isNaN(phone)) {
            return '请输入正确的电话号码';
        }
        if (!card) {
            return '请选择推荐卡别';
        }
        return {
            phone: phone,
            name: name,
            card: card,
            type: $('.s7 .form').attr('data-type'),
            openId: window.openId
        }
    }


    window.submitValidateInfo = submitValidateInfo;

    function submitValidateInfo(el, callback) {
        if ($(el).hasClass('disabled')) {
            return;
        }
        var _this = this;
        var info = getValidateData();
        if (typeof info === 'string') {
            alert.show(info);
            return;
        }
        $(el).addClass('disabled');
        loading.show();
        /**
         * 返回的json数据结构
         * ｛
         *      success：true,//true代表请求成功，false代表失败
         *      msg: '' //返回的消息
         *  ｝
         */
        $.ajax({
            // url: 'json/submitValidateInfo.json',
            url: submitValidateInfoUrl,
            method: 'POST',
            data: info,
            success: function(data) {
                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }
                //提交失败后的报错
                if (!data.success) {
                    loading.hide();
                    $(el).removeClass('disabled');
                    alert.show('验证错误<div style="font-size:0.6em; font-weight:normal; padding:5px; 0px;">可能是信息填写错误或者<br/>您还没参加活动哦！</div> <div class="buttons"><div class="button yellow">重新填写</div> <div class="button yellow" ontouchstart="window.location.href=\'sign.html\'">查看规则</div></div>')
                    return;
                }
                $(el).removeClass('disabled');
                loading.hide();

                window.swipeUp();

                window.chance = data.chance;

                window.playerName = data.playerName;

                window.isValidate = data.isValidate;
                updateInfo();

                share.setShare();

                window.localStorage.setItem('toPageShake', true);

                setTimeout(function() {
                    var href = window.location.href;

                    if (/\?/g.test(href)) {
                        href += '&t=' + (+new Date())
                    } else {
                        href += '?t=' + (+new Date())
                    }

                    window.location.href = href;
                    //window.swipeUp();
                }, 2000);

                typeof callback === 'function' && callback.call(el, data);



            },
            error: function() {
                alert.show(JSON.stringify(arguments));
                $(el).removeClass('disabled');
                loading.hide();
            }
        })

    }

    function getValidateData() {
        var name = $('.s9 .form input[name="name"]').val().trim();
        var phone = parseInt($('.s9 .form input[name="phone"]').val().trim());

        if (!name) {
            return '请输入您的姓名';
        }
        if (isNaN(phone)) {
            return '请输入正确的电话号码';
        }

        return {
            phone: phone,
            name: name,
            openId: window.openId
        }
    }

    window.submitPrizeInfo = submitPrizeInfo;

    function submitPrizeInfo(el, callback) {
        if ($(el).hasClass('disabled')) {
            return;
        }
        var _this = this;
        var info = getPrizeData();
        if (typeof info === 'string') {
            alert.show(info);
            return;
        }
        $(el).addClass('disabled');
        loading.show();
        /**
         * 返回的json数据结构
         * ｛
         *      success：true,//true代表请求成功，false代表失败
         *      msg: '' //返回的消息
         *  ｝
         */
        $.ajax({
            // url: 'json/submitPrizeInfo.json',
            url: submitPrizeInfoUrl,
            method: 'POST',
            data: info,
            success: function(data) {
                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }
                //提交失败后的报错
                if (!data.success) {
                    alert.show(data.msg);
                    loading.hide();
                    $(el).removeClass('disabled');
                    return;
                }
                $(el).removeClass('disabled');
                loading.hide();

                //刷新奖品列表
                getMyPrizes();

                // alert.show('信息提交成功 <div class="buttons"><div class="button yellow">告知好友</div></div>', function() {
                alert.show('信息提交成功', function() {
                    // $('.s14').removeClass('skip');
                    // window.swipeUp();
                    // setTimeout(function() {
                    //     $('.s14').addClass('skip');
                    // }, 100)
                    $('.s12').removeClass('skip');
                    $('.s13').addClass('skip');
                });

                typeof callback === 'function' && callback.call(el, data);



            },
            error: function() {
                alert.show(JSON.stringify(arguments));
                $(el).removeClass('disabled');
                loading.hide();
            }
        })

    }

    function getPrizeData() {
        var name = $('.s13 .form input[name="name"]').val().trim();
        var phone = parseInt($('.s13 .form input[name="phone"]').val().trim());
        var addr = $('.s13 .form input[name="addr"]').val().trim();

        if (!name) {
            return '请输入您的姓名';
        }
        if (isNaN(phone)) {
            return '请输入正确的电话号码';
        }
        if (!addr) {
            return '请输入您的地址';
        }

        return {
            phone: phone,
            name: name,
            addr: addr,
            prizeId: $('.s13 .form').attr('data-id'),
            recordId: $('.s13 .form').attr('data-recordid'),
            openId: window.openId
        }
    }

    function getMyPrizes(callback) {

        var target = $('.s12 .content .list .table');


        loading.show();
        $.ajax({
            // url: 'json/myPrizes.json',
            url: myPrizesUrl,
            method: 'POST',
            data: {
                openId: window.openId
            },
            success: function(data) {
                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }
                //提交失败后的报错
                if (!data.success) {
                    alert.show(data.msg);
                    loading.hide();
                    return;
                }
                loading.hide();

                target.html('');

                window.prizeId = data.list && data.list.length && $(data.list).first()[0].prizeid;
                share.setShare();


                $(data.list).each(function() {
                    target.append('<div class="tr"><div class="td">' + this.prize_name + '</div><div class="td"><div class="buttons">' + (this.addr ? '<div class="button">已填资料</div>' : '<div class="button yellow" data-recordid="' + this.id + '" data-id="' + this.prizeid + '" ontouchend="window.toPrizeInfoPage(this)">领取奖品</div>') + '</div></div></div>')
                });
                if (!data.list || data.list && !data.list.length) {
                    target.append('<div class="tr"><div class="td" style="text-align:right">暂无中奖信息</div><div class="td"></div></div>');
                }

                typeof callback === 'function' && callback.call(el, data);


            },
            error: function() {
                alert.show(JSON.stringify(arguments));
                loading.hide();
            }
        })
    }

    window.toPrizeInfoPage = toPrizeInfoPage;

    function toPrizeInfoPage(el) {
        $('.s13').removeClass('skip');
        $('.s13 .form').attr('data-id', $(el).attr('data-id'));
        $('.s13 .form').attr('data-recordid', $(el).attr('data-recordid'));
        window.swipeUp();

        setTimeout(function() {
            $('.s13').addClass('skip');
        }, 100)
    }

    window.toShakePrizeInfoPage = toShakePrizeInfoPage;

    function toShakePrizeInfoPage(el) {
        $('.s12').addClass('skip');
        $('.s13').removeClass('skip');
        $('.s13 .form').attr('data-id', $(el).parents('.content').attr('data-id'));
        $('.s13 .form').attr('data-recordid', $(el).parents('.content').attr('data-recordid'));
        window.swipeUp();
        setTimeout(function() {
            $('.s12').removeClass('skip');
            $('.s13').addClass('skip');
        }, 100)
    }


    window.shakeRing = shakeRing;

    $(window).bind('shake', function() {
        if (!$('.alert.hide').length) {
            return;
        }
        if (!$('.s11.active').length) {
            return;
        }
        shakeRing($('.s11 .shake')[0]);
    })

    function shakeRing(el) {
        if ($(el).hasClass('disabled')) {
            return;
        }
        $(el).addClass('disabled');

        loading.show();
        $.ajax({
            // url: 'json/shake.json',
            url: shakeUrl,
            method: 'POST',
            data: {
                openId: window.openId
            },
            success: function(data) {

                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }

                window.chance = data.chance;
                updateInfo();

                //提交失败后的报错
                if (!data.success) {
                    alert.show(data.msg);
                    loading.hide();
                    $(el).removeClass('disabled');
                    return;
                }
                loading.hide();

                ring.restart();

                getMyPrizes();


                $('.s11').addClass('shake');
                $('.s11_1 .content').attr('data-id', data.prizeId);
                $('.s11_1 .content').attr('data-recordid', data.recordId);
                setTimeout(function() {
                    $(el).removeClass('disabled');
                    $('.s11').removeClass('shake');
                    $('.s11_1').removeClass('skip');
                    window.swipeUp();
                    setTimeout(function() {
                        $('.s11_1').addClass('skip');
                    }, 100)
                    ring.pause();
                }, 2000);



                typeof callback === 'function' && callback.call(el, data);


            },
            error: function() {
                alert.show(JSON.stringify(arguments));
                $(el).removeClass('disabled');
                loading.hide();
            }
        })
    }



    if (/iphone/g.test(window.navigator.userAgent.toLowerCase())) {
        $('input').bind('touchend', function() {
            $(this).focus();
        })
    } else {
        $('input').hammer().on('tap', function(e) {
            $(this).focus();
        })
    }

    $('input').bind('focus', function() {

    })
    $('input').bind('blur', function() {
        $('html,body').scrollTop(0);
    })
    $('select').bind('change focus', function() {
        $('html,body').scrollTop(0);
    })

    $('select').bind('change', function() {
        $(this).prev('input').val($(this).val());
        !$(this).find('option').eq(0).attr('value') && $(this).find('option').eq(0).remove();
    })


    window.itemTap = function(e) {

        if (e.target.tagName.toLowerCase() != 'input' && e.target.tagName.toLowerCase() != 'select' && e.target.tagName.toLowerCase() != 'textarea') {
            $('input, select, textarea').blur();
        }
    }



    module.exports = {
        getMyPrizes: getMyPrizes,
        get: get
    }



})
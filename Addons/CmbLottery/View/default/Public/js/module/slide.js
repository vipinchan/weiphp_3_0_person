define(function(require, exports, module) {
    seajs.log('load slide');

    var imageLoad = require('./imageLoad');

    var isAndroid = /android/g.test(window.navigator.userAgent.toLowerCase());


    var startTouch = moveTouch = endTouch = null;
    var slideItems = $('.slide .slide-item');
    var activeItem = prevItem = nextItem = null;
    var slide = $('body > .slide');
    var slideHeight = null;
    var slideWidth = null;

    var deltaX = deltaY = deltaDuration = deltaStartTime = 0;

    var transitioning = false;

    var changePercent = 0.3;

    var changeDuration = 600;

    var loopDown = true;

    var loopUp = true;

    var slideCallback = function() {};

    //默认touchstart被冒泡阻止时使用
    var isTouchstart = false;



    var transformOriginTopCenter = 'top center';
    var transformOriginBottomCenter = 'bottom center';
    var transformOriginCenterCenter = 'center center';



    function init(config) {
        if (config) {

            loopDown = config.loopDown !== false;
            loopUp = config.loopUp !== false;

            slideCallback = typeof config.callback === 'function' ? config.callback : slideCallback;
        }

        slideHeight = slide.height();
        slideWidth = slide.width();

        loadImage();
        bindEvent();

        touchstart();

        $('.slide-item.scroll').each(function() {

            var cls = $(this).attr('class');

            if (cls.indexOf(' ') != -1) {
                cls = cls.split(' ');
                $(cls).each(function(i) {
                    cls[i] = '.' + cls[i];
                })
                cls = cls.join('');
            } else {
                clas = '.' + cls;
            }
            $(this).data('iscroll', new IScroll(cls + ' > .content', {
                bounce: false,
                scrollX: false,
                scrollY: true
            }));

            $(this).bind('touchstart touchmove touchend', function(e) {
                $(this).data('iscroll-status', e.type);
                e.type == 'touchstart' && $(this).data('iscroll-start', e.originalEvent.targetTouches[0].clientY);
                e.type == 'touchmove' && $(this).data('iscroll-end', e.originalEvent.targetTouches[0].clientY);
            })

            $(this).data('iscroll').on('beforeScrollStart', function() {
                $(this.scroller).parents('.slide-item').data('iscroll-y', Math.abs(this.y));
            })
            $(this).data('iscroll').on('scrollEnd', function() {
                var _this = $(this.scroller).parents('.slide-item');
                var touchStart = _this.data('iscroll-start');
                var touchEnd = _this.data('iscroll-end');
                var direction = _this.data('iscroll-direction');
                var ey = Math.abs(this.y);
                var sy = _this.data('iscroll-y');
                var end = parseInt($(this.scroller).height() * (this.scale || 1) - $(this.scroller).parent().height());

                if (!end) {
                    end = 0;
                }
                if (_this.hasClass('disabled')) {
                    return;
                }

                if (_this.data('iscroll-status') == 'touchend') {
                    if (((ey == end && sy == end) || (ey == 0 && sy == 0 && end <= 0)) && touchStart - touchEnd > 0) {

                        if (activeItem && !activeItem.next().length && !loopUp) {

                        } else {
                            window.swipeUp();
                        }
                    }
                    if (ey == 0 && sy == 0 && touchStart - touchEnd < 0) {
                        if (activeItem && !activeItem.prev().length && !loopDown) {

                        } else {
                            window.swipeDown();
                        }
                    }
                }
                _this.data('iscroll-status', null);
            })
        })

    }

    function bindEvent() {
        slide.bind('touchstart touchmove touchend touchcancel', function(e) {


            if ($(e.target).hasClass('unprevent')) {
                return;
            }


            if (e.type == 'touchmove' || (!$(e.target).hasClass('unpreventdefault') && !$(e.target).parents('.unpreventdefault').length)) {
                e.preventDefault();
            }


            if (transitioning) {
                return;
            }

            switch (e.type) {
                case 'touchstart':
                    touchstart(e);
                    isTouchstart = true;
                    break;
                case 'touchmove':
                    if (!isTouchstart) {
                        touchstart(e);
                        isTouchstart = true;
                    }
                    touchmove(e);
                    break;
                case 'touchend':
                case 'touchcancel':
                    touchend(e);
                    break;
            }
        })

    }

    function isDisabled() {
        return activeItem.hasClass('disabled') || activeItem.hasClass('scroll');
    }


    function touchstart(e) {

        activeItem = slide.find('.slide-item.active');
        prevItem = activeItem.prev();
        nextItem = activeItem.next();
        deltaY = 0;
        deltaX = 0;

        if (!prevItem.length) {
            prevItem = slideItems.last();
        }
        if (!nextItem.length) {
            nextItem = slideItems.first();
        }

        //如果上下没有就有BUG了
        while (prevItem.hasClass('skip') && prevItem.length) {
            prevItem = prevItem.prev();
        }

        while (nextItem.hasClass('skip') && nextItem.length) {
            nextItem = nextItem.next();
        }

        if (!prevItem.length) {
            prevItem = slideItems.last();
        }
        if (!nextItem.length) {
            nextItem = slideItems.first();
        }


        deltaDuration = 0;
        deltaStartTime = +new Date();

        slideItems.hide();
        activeItem.show();
        prevItem.show();
        nextItem.show();

        if (isDisabled()) {
            return;
        }

        e && (startTouch = $.extend({}, e.originalEvent.targetTouches[0]));

    }

    function touchmove(e) {
        if (!startTouch) {
            return;
        }
        if (isDisabled()) {
            return;
        }

        moveTouch = $.extend({}, e.originalEvent.targetTouches[0]);
        deltaX = moveTouch.clientX - startTouch.clientX;
        deltaY = moveTouch.clientY - startTouch.clientY;

        if (activeItem.hasClass('disabled-down') && deltaY > 0) {
            deltaY = deltaX = 0;
            slide.trigger('touchend');
            return;
        }
        if (activeItem.hasClass('disabled-up') && deltaY < 0) {
            deltaY = deltaX = 0;
            slide.trigger('touchend');
            return;
        }

        if (!canLoop()) {
            return;
        }

        if (moveTouch.clientY <= 0) {
            slide.trigger('touchend');
            return;
        }

        transform(activeItem, 0, deltaY);
        transform(prevItem, 0, -slideHeight + deltaY);
        transform(nextItem, 0, slideHeight + deltaY);

    }

    function touchend(e) {

        isTouchstart = false;

        if (!startTouch) {
            return;
        }
        if (isDisabled()) {
            return;
        }

        //endTouch = e.originalEvent.targetTouches[0];
        startTouch = null;
        moveTouch = null;
        deltaDuration = (+new Date()) - deltaStartTime;

        var direction = 'back';

        if (deltaY === 0) {
            direction = 'back';
        }

        if (deltaY > 0) {
            if (deltaY > slideHeight * changePercent) {
                direction = 'down';
            } else {
                direction = 'back';
            }
            if (Math.abs(deltaY) / deltaDuration > 0.6 && deltaDuration > 0) {
                direction = 'down';
            }
        }
        if (deltaY < 0) {
            if (Math.abs(deltaY) > slideHeight * changePercent) {
                direction = 'up';
            } else {
                direction = 'back';
            }
            if (Math.abs(deltaY) / deltaDuration > 0.6 && deltaDuration > 0) {
                direction = 'up';
            }
        }

        switch (direction) {
            case 'back':
                swipeBack();
                break;
            case 'up':
                swipeUp();
                break;
            case 'down':
                swipeDown();
                break;
        }

    }

    window.swipeUp = function() {

        if (transitioning) {
            return;
        }

        touchstart();

        if (prevItem.index() === nextItem.index()) {
            prevItem = null;
        }

        transform(activeItem, 0, 0);
        transform(prevItem, 0, -slideHeight);
        transform(nextItem, 0, slideHeight);

        setTimeout(function() {
            swipeUp();
        }, 1000 / 60)
    };

    window.swipeDown = function() {
        if (transitioning) {
            return;
        }

        touchstart();

        if (prevItem.index() === nextItem.index()) {
            nextItem = null;
        }

        transform(activeItem, 0, 0);
        transform(prevItem, 0, -slideHeight);
        transform(nextItem, 0, slideHeight);

        setTimeout(function() {
            swipeDown();
        }, 1000 / 60)
    }

    function swipeUp() {

        setTransition(true);

        var duration = changeDuration - getDuration();

        setTimeout(function() {
            nextItem.addClass('active');
        }, duration / 2);

        function callback() {
            activeItem.removeClass('active');

            setTransition(false);

            if (nextItem.data('iscroll')) {
                nextItem.data('iscroll').refresh();
            }

            touchstart();

            typeof slideCallback === 'function' && slideCallback();
        }

        if (duration == 0) {
            callback();
        }

        transform(activeItem, 0, -slideHeight, duration, callback);
        transform(prevItem, 0, -slideHeight, duration);
        transform(nextItem, 0, 0, duration);

    }



    function swipeDown() {

        setTransition(true);

        var duration = changeDuration - getDuration();

        setTimeout(function() {
            prevItem.addClass('active');
        }, duration / 2);

        function callback() {
            activeItem.removeClass('active');
            setTransition(false);

            if (prevItem.data('iscroll')) {
                prevItem.data('iscroll').refresh();
            }

            touchstart();

            typeof slideCallback === 'function' && slideCallback();
        }

        if (duration == 0) {
            callback();
        }



        transform(activeItem, 0, slideHeight, duration, callback);
        transform(prevItem, 0, 0, duration);
        transform(nextItem, 0, slideHeight, duration);

    }

    function swipeBack() {

        setTransition(true);

        var duration = getDuration();

        function callback() {
            activeItem.addClass('active');
            setTransition(false);

            if (activeItem.data('iscroll')) {
                activeItem.data('iscroll').refresh();
            }

            touchstart();

            typeof slideCallback === 'function' && slideCallback();
        }

        if (duration == 0) {
            callback();
        }

        transform(activeItem, 0, 0, duration);
        transform(prevItem, 0, -slideHeight, duration);
        transform(nextItem, 0, slideHeight, duration, callback);
    }

    function transform(el, x, y, duration, callback) {
        if (!el) {
            return;
        }

        var target = el[0];
        duration = duration || 0;

        if (!target) {
            return;
        }


        var rotateX = -45 * (y / slideHeight);

        if (y > 0 && target.getAttribute('origin') != transformOriginTopCenter) {
            target.setAttribute('origin', transformOriginTopCenter);
            target.style.transformOrigin = target.style.webkitTransformOrigin = transformOriginTopCenter;
        }
        if (y < 0 && target.getAttribute('origin') != transformOriginBottomCenter) {
            target.setAttribute('origin', transformOriginBottomCenter);
            target.style.transformOrigin = target.style.webkitTransformOrigin = transformOriginBottomCenter;
        }
        if (y == 0) {
            if (parseInt(target.getAttribute('data-y')) < 0) {
                target.style.transformOrigin = target.style.webkitTransformOrigin = transformOriginBottomCenter;
                target.setAttribute('origin', transformOriginBottomCenter);
            } else {
                target.style.transformOrigin = target.style.webkitTransformOrigin = transformOriginTopCenter;
                target.setAttribute('origin', transformOriginTopCenter);
            }

        }

        target.setAttribute('data-x', x);
        target.setAttribute('data-y', y);

        target.style.transition = 'transform ' + duration + 'ms ease';
        target.style.webkitTransition = '-webkit-transform ' + duration + 'ms ease';

        target.style.transform = target.style.webkitTransform = 'translateX(' + x + 'px) translateY(' + y + 'px) rotateX(' + rotateX + 'deg)';

        if (duration) {
            clearTimeout(el.attr('transition-st'));
            el.attr('transition-st', setTimeout(function(el) {
                el.unbind('transitionend webkitTransitionEnd');
                typeof callback === 'function' && callback();
            }, duration, el))
        } else {
            el.unbind('transitionend webkitTransitionEnd');
            typeof callback === 'function' && callback();
        }
    }

    function setTransition(ing) {
        transitioning = !!ing;
        window.transitioning = transitioning;
    }

    function getDuration() {
        return parseInt(changeDuration * (Math.abs(deltaY) / slideHeight));
    }

    function canLoop() {
        if (!loopDown && deltaY > 0 && !activeItem.prev().length) {
            deltaY = 0;
            return false;
        }
        if (!loopUp && deltaY < 0 && !activeItem.next().length) {
            deltaY = 0;
            return false;
        }
        return true;
    }



    function loadImage() {
        var slideItem = $('.slide .slide-item');
        var pageLoadMask = $('.page-load-mask');
        slideItem.each(function(i) {

            var _this = this;

            var images = $(this).find('img[data-src]');

            // $(this).attr('data-index', i).append(pageLoadMask.clone(true).removeClass('none').removeClass('hide'));

            if (i !== 0) {
                transform($(this), 0, slideHeight);
            }

            imageLoad.load(images, function(count, len) {

                $(_this).find('.page-load-mask-text').html(parseInt(count / len * 100) + '%');
            }, function(count, len) {
                // $(_this).find('.page-load-mask').addClass('hide').bind('transitionend webkitTransitionEnd', function() {
                //     $(this).remove();
                // })
                // setTimeout(function(img) {
                //     $(img).remove();

                // }, 300, $(_this).find('.page-load-mask'))
            })
        })
    }

    window.toPage = toPage;

    function toPage(target) {
        if (transitioning) {
            return;
        }

        if ($(target).hasClass('active')) {
            return;
        }

        var active = $('.slide-item.active');
        var cIndex = active.index();
        var index = $(target).index();
        var toggles = [];

        if (!active.length) {
            return;
        }


        if (cIndex - index > 0) {


            for (var i = 0; i < cIndex - index - 1; i++) {

                active = active.prev();

                if (!active.hasClass('skip')) {
                    toggles.push(active);
                    active.addClass('skip');
                    console.log(active.attr('class'))
                }

            }

            window.swipeDown();

        } else {


            for (var i = 0; i < index - cIndex - 1; i++) {

                active = active.next();

                if (!active.hasClass('skip')) {
                    toggles.push(active);
                    active.addClass('skip');
                }

            }

            window.swipeUp();

        }

        setTimeout(function() {
            $(toggles).each(function(i) {
                toggles[i].removeClass('skip');
            })
        }, 300);
    }



    //如果是安卓
    // if (isAndroid) {
    //     $('head').append('<style type="text/css">' +
    //         '.slide .slide-item { -webkit-transform: translateZ(0px); transform: translateZ(0px); }' +
    //         '.slide .slide-item > * { -webkit-transform: translateZ(0px); transform: translateZ(0px); }' +
    //         '</style>')
    // }


    module.exports = {
        init: init,
        swipeUp: swipeUp,
        swipeDown: swipeDown
    }



})
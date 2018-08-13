define(function(require, exports, module) {
	seajs.log('load index');

	var alert = require('./module/alert');


	//初始化音乐
	var audio = require('./module/audio');
	var autoplay = true;

	var music = audio.create({
		src: window.musicSrc,
		autoplay: autoplay
	});

	if (!autoplay) {
		$('.music').addClass('disabled');
	}
	$('.music').hammer().on('tap press', function(e) {
		if (e.type == 'press') {
			music.restart();
			return;
		}

		if ($(this).hasClass('disabled')) {
			music.play();
			$(this).removeClass('disabled');
		} else {
			music.pause();
			$(this).addClass('disabled');
		}
	})

	//初始化轮播
	var slide = require('./module/slide');

	slide.init({
		loopDown: false,
		loopUp: false,
		callback: function() {
			$('.slide-item.active.disabled').length ? $('.arrow').hide() : $('.arrow').show();
		}
	});

	//初始化分享
	var share = require('./module/share');
	share.init();

	//隐藏遮罩
	var loading = require('./module/loading')
	loading.hide();

	var info = require('./module/info');
	info.get();


	//所有礼品
	window.prizes = [{
		id: 1,
		name: 'IPAD MINI 一部'
	}, {
		id: 2,
		name: 'IPAD AIR 一部'
	}, {
		id: 3,
		name: 'APPLE WATCH SPORT 一部'
	}, {
		id: 4,
		name: '50元礼品兑换券一张'
	}, {
		id: 5,
		name: '150元礼品兑换券一张'
	}, {
		id: 6,
		name: '500元礼品兑换券一张'
	}, {
		id: 7,
		name: '800元礼品兑换券一张'
	}, {
		id: 8,
		name: '路卡斯汤蒸锅'
	}, {
		id: 9,
		name: '康佳煮蛋器'
	}, {
		id: 10,
		name: '康佳养生壶'
	}, {
		id: 11,
		name: '莱比锡套装锅具'
	}, {
		id: 12,
		name: '电水瓶'
	}, {
		id: 13,
		name: '烤箱'
	}, {
		id: 14,
		name: '康佳空气净化器'
	}, {
		id: 15,
		name: '康佳冷暖箱'
	}, {
		id: 16,
		name: '派克威雅宝珠笔'
	}, {
		id: 17,
		name: '卡通相机风扇'
	}, {
		id: 18,
		name: '时尚毛毯'
	}, {
		id: 19,
		name: '电子健康秤'
	}, {
		id: 20,
		name: '新秀丽双肩包'
	}, {
		id: 21,
		name: '迪士尼电炖锅'
	}];
})













define(function(require, exports, module) {
	seajs.log('load audio');

	window.AudioContext = window.AudioContext || window.webkitAudioContext || window.mozAudioContext || window.msAudioContext;

	var preloadFns = [];

	function Create(config) {

		var _this = this;

		_this.create(config);

		return {
			pause: function() {
				_this.pause.call(_this);
			},
			play: function() {
				_this.play.call(_this);
			},
			restart: function() {
				_this.restart.call(_this);
			},
			isEnd: function(){
				return _this._audio.ended
			}
		}

	}

	Create.prototype.play = function() {
		this._audio.play();
	}
	Create.prototype.pause = function() {
		this._audio.pause();
	}
	Create.prototype.restart = function() {
		if (this._audio.readyState == 4) {
			this._audio.currentTime = 0;
		}
		this._audio.play();
	}

	Create.prototype.create = function(config) {
		var _this = this;

		var src = typeof config === 'string' ? config : config.src;
		var autoplay = typeof config === 'string' ? true : config.autoplay !== false;
		var loop = typeof config === 'string' ? true : config.loop !== false;
		var preload = typeof config === 'string' ? true : config.preload !== false;

		var audio = new window.Audio;

		audio.autoplay = autoplay;
		audio.loop = loop;
		//auto - 当页面加载后载入整个音频 
		//meta - 当页面加载后只载入元数据 
		//none - 当页面加载后不载入音频
		audio.preload = preload ? 'auto' : 'none';


		audio.src = src;

		autoplay && audio.play();


		if (!/android/g.test(window.navigator.userAgent.toLowerCase())) {
			preloadFns.push([audio, function() {
				var audio = this;
				if (!audio._preload) {
					var isPause = audio.paused;
					var isPlay = audio.currentTime > 0;

					!isPlay && audio.play();
					isPause && audio.pause();
					// isPause && requestAnimationFrame(function() {
					// 	audio.pause();
					// })
					audio._preload = true;
				}
				return audio.readyState === 4;

			}])
		}
		_this._audio = audio;


	}

	$(document).bind('touchstart touchend', function(e) {
		$(preloadFns).each(function(i) {
			var audios = preloadFns[i];
			audios[1].call(audios[0]);
			delete preloadFns[i];
		})
		preloadFns.refresh();
	})



	module.exports = {
		create: function(config) {

			return new Create(config);
		}
	}



});

//数组刷新
(function(doc, win) {
	Array.prototype.refresh = function() {
		var nArray = [],
			_this = this;
		this.forEach(function(v, i, a) {
			v && nArray.push(v);
		})
		this.splice(0, this.length);
		nArray.forEach(function(v, i, a) {
			_this.push(v);
		})
		return this;
	}
})(document, window);
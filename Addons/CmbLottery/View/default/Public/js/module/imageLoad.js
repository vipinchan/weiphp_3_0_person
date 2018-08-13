define(function(require, exports, module) {
	seajs.log('load imageLoad');


	if (!window.imageCount && isNaN(window.imageCount)) {
		window._imageCount = $('.img-item[data-src]').length;
		window.imageCount = 0;
	}


	function load(images, update, callback) {
		var count = 0;
		var len = images.length;

		if (len === 0) {
			addCount()
			setTimeout(function(_this, count, len) {
				typeof callback === 'function' && callback.call(_this, count, len);
			}, 1000 / 60, null, count, len)
			return;
		}

		typeof update === 'function' && update.call(null, count, len);

		$(images).each(function() {
			var _this = $(this);
			loadImage(_this.attr('data-src'), function() {
				count++;

				var div = $('<div></div>');
				div.attr('class', _this.attr('class'));
				div.css({
					backgroundImage: 'url(' + this.src + ')'
				})
				_this.replaceWith(div);

				_this = div;

				typeof update === 'function' && update.call(_this, count, len);
				if (count === len) {
					setTimeout(function(_this, count, len) {
						typeof callback === 'function' && callback.call(_this, count, len);
					}, 1000 / 60, _this, count, len)
				}
				addCount()
			})
		})
	}

	function addCount() {

		var companyMask = $('.company-mask');

		window.imageCount++;

		if (window.imageCount >= window._imageCount) {
			window.imageCount = window._imageCount;
			setTimeout(function() {
				companyMask.addClass('hide');
				$('.slide .slide-item').eq(0).addClass('active');
					
				if(window.localStorage.getItem('toPageShake')){
					window.localStorage.removeItem('toPageShake');
					$('.s9, .s10').addClass('skip');
					window.swipeUp();
					
				}

				//如果为 disabled 就不显示箭头
				$('.slide-item.active.disabled').length ? $('.arrow').hide() : $('.arrow').show();
			}, 1000)
		}
		companyMask.find('.number span span').css('width', (window.imageCount / window._imageCount * 100) + '%');
	}


	function loadImage(src, callback) {
		var img = new Image();
		var loaded = function() {
			if (this.loaded) {
				return;
			}
			this.loaded = true;
			typeof callback === 'function' && callback.call(this);
		}
		img.onerror = img.onload = function() {
			loaded.call(this);
		}
		img.src = src;

		if (img.complete) {
			loaded.call(img);
		}

	}


	module.exports = {
		load: load
	}



})
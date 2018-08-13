define(function(require, exports, module) {
	seajs.log('load loading');


	function _show(show, text) {
		var _this = this;
		text ? $('body > .page-load-mask .page-load-mask-text').html(text) : $('body > .page-load-mask .page-load-mask-text').html('');
		show ? $('body > .page-load-mask').removeClass('hide') : $('body > .page-load-mask').addClass('hide');
	}

	function hide(text) {
		_show(false, text)
	}

	function show(text) {
		_show(true, text)
	}



	module.exports = {
		show: show,
		hide: hide
	}



})
define(function(require, exports, module) {
	seajs.log('load alert');


	function show(msg, callback) {

		if (!msg) {
			return;
		}
		var alert = $('.alert');
		alert.find('.alert-text').html(msg);
		alert.removeClass('hide');
		alert.bind('touchend', function() {
			alert.addClass('hide');
			alert.unbind('touchend');
			if (typeof callback == 'function') {
				callback();
			}
		})
	}



	module.exports = {
		show: show
	}



})
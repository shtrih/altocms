/**
 * Created by shtrih on 01.03.14.
 */
$(function () {
	var logo   = $('#logo'),
		portal = $('.portal,.portal-mask', logo),
		yukari = $('.yukari', logo),
		four   = $('.four', logo),
		height = '58px'
	;

	function _yukari(time, callback) {
		yukari.animate({
			top: '-=' + height
		}, time, callback);
	}

	function _portal(time, callback) {
		portal
			.first().fadeIn(time, callback)
			.end().last().fadeIn(time);
	}

	function _four(time, callback) {
		four
			.animate({ textIndent: '195px' }, {
				step: function(now,fx) {
					$(this).css({
						transform: 'rotate('+now+'deg)',
						WebkitTransform: 'rotate('+now+'deg)'
					});
				},
				queue: false,
				duration: 'normal'
			}, 'linear')
			.animate({
				top: '+=' + height
			}, time, callback);
	}

	logo.on('mouseenter', function () {
		_portal(300, function () {
			_four(300, function () {
				_yukari(600, function () {
					// console.log('stop');
				});
			});
		});
	})
	.on('mouseleave', function () {
		portal.stop();
		yukari.stop();
		four.stop();

		yukari.animate({top: '+=' + height}, 100, function () {
			portal.fadeOut('fast', function () {
				four.hide().fadeIn('fast').css({
					top: '0',
					transform: 'rotate(0)',
					WebkitTransform: 'rotate(0)',
					textIndent: '0'
				});
			});
			yukari.css('top', height);
		});
	});
});

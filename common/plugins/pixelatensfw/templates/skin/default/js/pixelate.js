/*
 * https://github.com/43081j/pixelate.js
 * pixelate.js
 * 43081j
 * Pixelate images with ease
 * License: MIT
 */
(function(window, $) {
	var pixelate = function() {
		var defaults = {
			value: 0.05,
			reveal: true,
			revealonclick: false
		};
		var options = arguments[0] || {};
		var element = this, //arguments[0],
			elementParent = element.parentNode;
		if(typeof options !== 'object') {
			options = { value: parseInt(arguments[0]) };
		}

		options = (function() {
			var opts = {};
			for(var k in defaults) {
				if(element.hasAttribute('data-' + k)) {
					opts[k] = element.getAttribute('data-' + k);
					continue;
				}
				if(k in options) {
					opts[k] = options[k];
					continue;
				}
				opts[k] = defaults[k];
			}
			return opts;
		})();
		var display = element.style.display,
			imgWidth = element.width,
			imgHeight = element.height,
			revealed = false;
		var canv = document.createElement('canvas');
		canv.width = imgWidth;
		canv.height = imgHeight;
		var ctx = canv.getContext('2d');
		ctx.mozImageSmoothingEnabled = false;
		ctx.webkitImageSmoothingEnabled = false;
		ctx.imageSmoothingEnabled = false;
		var width = imgWidth * options.value,
			height = imgHeight * options.value;

		var value_draft = options.value * 2,
			canv_draft = document.createElement('canvas'),
			width_draft = imgWidth * value_draft,
			height_draft = imgHeight * value_draft
		;
		canv_draft.width = width_draft;
		canv_draft.height = height_draft;

		var ctx_draft = canv_draft.getContext('2d');
		ctx_draft.mozImageSmoothingEnabled = false;
		ctx_draft.webkitImageSmoothingEnabled = false;
		ctx_draft.imageSmoothingEnabled = false;


		ctx_draft.drawImage(element, 0, 0, width, height);
		ctx.drawImage(canv_draft, 0, 0, width, height, 0, 0, canv.width, canv.height);

		ctx_draft.clearRect(0, 0, width_draft, height_draft);
		ctx_draft.globalAlpha = 0.6;
		ctx_draft.drawImage(element, 0, 0, width_draft, height_draft);
		ctx_draft.globalAlpha = 1;

		ctx.drawImage(canv_draft, 0, 0, width_draft, height_draft, 0, 0, canv.width, canv.height);
		canv_draft.remove();

		element.style.display = 'none';
		elementParent.insertBefore(canv, element);
		if(options.revealonclick !== false && options.revealonclick !== 'false') {
			/*
			 * Reveal on click
			 */
			canv.addEventListener('click', function(e) {
				revealed = !revealed;
				if(revealed) {
					ctx.drawImage(element, 0, 0, imgWidth, imgHeight);
				} else {
					ctx.drawImage(element, 0, 0, width, height);
					ctx.drawImage(canv, 0, 0, width, height, 0, 0, canv.width, canv.height);
				}
			});
		}
		if(options.reveal !== false && options.reveal !== 'false') {
			/*
			 * Reveal on hover
			 */
			canv.addEventListener('mouseenter', function(e) {
				if(revealed) return;
				ctx.drawImage(element, 0, 0, imgWidth, imgHeight);
			});
			canv.addEventListener('mouseleave', function(e) {
				if(revealed) return;
				ctx.drawImage(element, 0, 0, width, height);
				ctx.drawImage(canv, 0, 0, width, height, 0, 0, canv.width, canv.height);
			});
		}
	};
	window.HTMLImageElement.prototype.pixelate = pixelate;
	if(typeof $ === 'function') {
		$.fn.extend({
			pixelate: function() {
				var args = arguments;
				return this.each(function() {
					pixelate.apply(this, args);
				});
			}
		});
	}
	document.addEventListener('DOMContentLoaded', function(e) {
		var img = document.querySelectorAll('img[data-pixelate]');
		for(var i = 0; i < img.length; i++) {
			img[i].addEventListener('load', function() {
				this.pixelate();
			});
		}
	});
})(window, typeof jQuery === 'undefined' ? null : jQuery);

window.valstemarie.fancyScroll = function (sel) {
	var outer = $(sel);
	if (!outer.length) {
		return;
	}
	var inner = $("> div.text", outer);
	if (!inner.length) {
		return;
	}
	outer.css({
		overflow: 'hidden',
		position: 'relative'
	});
	inner.css('position', 'relative');
	var oheight = +(outer.css('height').replace(/[^0-9.-]/g, ''));
	var arrows = $('<div>').addClass('scroll-arrows').html('<div class="up u0"></div><div class="down d0"></div>');
	outer.append(arrows);
	var updateArrows = function (top, min, max) {
		if (min >= max) {
			$(arrows).hide();
			return;
		} else {
			$(arrows).show();
		}
		if (top < max) {
			$(".up", arrows).removeClass("u0").addClass("u1");
		} else {
			$(".up", arrows).removeClass("u1").addClass("u0");
		}
		if (top > min) {
			$(".down", arrows).removeClass("d0").addClass("d1");
		} else {
			$(".down", arrows).removeClass("d1").addClass("d0");
		}
	};
	var scrollHandler = function (ev, delta) {
		var del = (delta < 0 ? (-5) : 5);
		var iheight = +(inner.css('height').replace(/[^0-9.-]/g, '')) +
		              +(inner.css('padding-bottom').replace(/[^0-9.-]/g, ''));
		var top = +(inner.css('top').replace(/[^0-9.-]/g, ''));
		top += del;
		var min = oheight - iheight;
		top = Math.min(0, Math.max(min, top));
		inner.css('top', top + 'px');
		updateArrows(top, min, 0);
		return false;
	};
	var mouseUp = function (el) {
		if (el.data('interval')) {
			clearInterval(el.data('interval'));
			el.removeData('interval');
		}
	};
	var mouseDown = function (el) {
		mouseUp(el);
		var delta = el.hasClass('up') ? 1 : -1;
		el.data('interval', setInterval(function () {
			scrollHandler('click', delta);
		}, 25));
		scrollHandler('click', delta);
	};
	outer.bind('mousewheel', scrollHandler);
	$("> div", arrows).mousedown(function (ev) { return mouseDown($(this)); });
	$("> div", arrows).bind('touchstart', function (ev) { return mouseDown($(this)); });
	$("> div", arrows).mouseup(function (ev) { return mouseUp($(this)); });
	$("> div", arrows).bind('touchend', function (ev) { return mouseUp($(this)); });
	scrollHandler(null, 1);
};
$.each(['#left', '#center', '#right'], function (idx, sel) {
	window.valstemarie.fancyScroll(sel);
});

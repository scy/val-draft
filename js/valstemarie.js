window.valstemarie.fancyScroll = function (sel) {
	var outer = $(sel);
	if (!outer.length) {
		return;
	}
	var inner = $("> div.text", outer);
	if (!inner.length) {
		return;
	}
	outer.css('overflow', 'hidden');
	inner.css('position', 'relative');
	var oheight = +(outer.css('height').replace(/[^0-9.-]/g, ''));
	outer.bind('mousewheel', function (ev, delta) {
		var del = (delta < 0 ? (-3) : 3);
		var iheight = +(inner.css('height').replace(/[^0-9.-]/g, '')) +
		              +(inner.css('padding-bottom').replace(/[^0-9.-]/g, ''));
		var top = +(inner.css('top').replace(/[^0-9.-]/g, ''));
		top += del;
		top = Math.min(0, Math.max(oheight - iheight, top));
		inner.css('top', top + 'px');
		return false;
	});
};
$.each(['#left', '#center', '#right'], function (idx, sel) {
	window.valstemarie.fancyScroll(sel);
});

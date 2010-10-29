/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id$
*/

function previewLocalImg(obj, preview, width, height) {
	if(BROWSER.ie) {
		$(preview + '_hidden').filters.item("DXImageTransform.Microsoft.AlphaImageLoader").sizingMethod = 'image';
		try {
			$(preview + '_hidden').filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = obj.value;
		} catch (e) {
			showDialog('无效的图片文件。');
			return;
		}
		var wh = {'w' : $(preview + '_hidden').offsetWidth, 'h' : $(preview + '_hidden').offsetHeight};
		var aid = $(preview + '_hidden').alt;
		if(wh['w'] >= width || wh['h'] >= height) {
			wh = thumbImg(wh['w'], wh['h'], width, height);
		}
		$(preview + '_hidden').style.width = wh['w'];
		$(preview + '_hidden').style.height = wh['h'];
		$(preview + '_hidden').filters.item("DXImageTransform.Microsoft.AlphaImageLoader").sizingMethod = 'scale';
		$(preview).style.width = 'auto';
		$(preview).innerHTML = '<img style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=\'scale\',src=\'' + obj.value+'\');width:'+wh['w']+';height:'+wh['h']+'" src=\'images/common/none.gif\' border="0" alt="" />';
	}
}

function thumbImg(w, h, twidth, theight) {
	twidth = !twidth ? thumbwidth : twidth;
	theight = !theight ? thumbheight : theight;
	var x_ratio = twidth / w;
	var y_ratio = theight / h;
	var wh = [];
	if((x_ratio * h) < theight) {
		wh['h'] = Math.ceil(x_ratio * h);
		wh['w'] = twidth;
	} else {
		wh['w'] = Math.ceil(y_ratio * w);
		wh['h'] = theight;
	}
	return wh;
}
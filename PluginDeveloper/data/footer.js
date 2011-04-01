document.write('<br /><br /><br /><div id="footer">');
document.write('<div class="wrap">');
document.write('<p id="copyright">');
document.write("版权所有 &copy;2001-2011 <a href=\"http://www.comsenz.com\" style=\"color: #888888; text-decoration: none\">");
document.write("北京康盛新创科技有限责任公司 Comsenz Inc.</a>");
document.write('</p></div>');

titles = document.getElementsByTagName('A');
_attachEvent(window, 'resize', _resize, document);
_resize();
var sd = '<div class="title">导航</div>', an = 0;
titlecount = titles.length;
for(i = 0;i < titlecount;i++) {
	if(titles[i].name == 'title') {
		titles[i].name = 'title_' + an;
		titles[i].id = 'title_' + an;
		sd += '<a href="#' + titles[i].name + '" target="_self">&bull; ' + titles[i].innerHTML + '</a><br />';
		an++;
	}
}
document.getElementById('sd').innerHTML = sd;

function _resize() {
	w = document.body.clientWidth - 210;
	w = w < 760 ? 760 : w;
	document.getElementById('mn').style.width = w + 'px';
}

function _attachEvent(obj, evt, func, eventobj) {
	eventobj = !eventobj ? obj : eventobj;
	if(obj.addEventListener) {
		obj.addEventListener(evt, func, false);
	} else if(eventobj.attachEvent) {
		obj.attachEvent('on' + evt, func);
	}
}
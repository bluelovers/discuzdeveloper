/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: home_manage.js 10989 2010-05-19 05:55:54Z maruitao $
*/

function wall_add(id) {
	var obj = $('comment_ul');
	var newdl = document.createElement("dl");
	newdl.id = 'comment_'+id+'_li';
	newdl.className = 'bbda cl';
	var x = new Ajax();
	x.get('home.php?mod=misc&ac=ajax&op=comment&inajax=1&cid='+id, function(s){
		newdl.innerHTML = s;
	});
	obj.insertBefore(newdl, obj.firstChild);
	if($('comment_message')) {
		$('comment_message').value= '';
	}
	showCreditPrompt();
}

function share_add(sid) {
	var obj = $('share_ul');
	var newli = document.createElement("li");
	var x = new Ajax();
	x.get('home.php?mod=misc&ac=ajax&op=share&inajax=1&sid='+sid, function(s){
		newli.innerHTML = s;
	});
	obj.insertBefore(newli, obj.firstChild);
	$('share_link').value = 'http://';
	$('share_general').value = '';
	showCreditPrompt();
}

function comment_add(id) {
	var obj = $('comment_ul');
	var newdl = document.createElement("dl");
	newdl.id = 'comment_'+id+'_li';
	newdl.className = 'bbda cl';
	var x = new Ajax();
	x.get('home.php?mod=misc&ac=ajax&op=comment&inajax=1&cid='+id, function(s){
		newdl.innerHTML = s;
	});
	if($('comment_prepend')){
		obj = obj.firstChild;
		while (obj && obj.nodeType != 1){
			obj = obj.nextSibling;
		}
		obj.parentNode.insertBefore(newdl, obj);
	} else {
		obj.appendChild(newdl);
	}
	if($('comment_message')) {
		$('comment_message').value= '';
	}
	if($('comment_replynum')) {
		var a = parseInt($('comment_replynum').innerHTML);
		var b = a + 1;
		$('comment_replynum').innerHTML = b + '';
	}
	showCreditPrompt();
}
function comment_edit(cid) {
	var obj = $('comment_'+ cid +'_li');
	var x = new Ajax();
	x.get('home.php?mod=misc&ac=ajax&op=comment&inajax=1&cid='+ cid, function(s){
		obj.innerHTML = s;
	});
}
function comment_delete(cid) {
	var obj = $('comment_'+ cid +'_li');
	obj.style.display = "none";
	if($('comment_replynum')) {
		var a = parseInt($('comment_replynum').innerHTML);
		var b = a - 1;
		$('comment_replynum').innerHTML = b + '';
	}
}

function share_delete(sid) {
	var obj = $('share_'+ sid +'_li');
	obj.style.display = "none";
}
function friend_delete(uid) {
	var obj = $('friend_'+ uid +'_li');
	if(obj != null) obj.style.display = "none";
	var obj2 = $('friend_tbody_'+uid);
	if(obj2 != null) obj2.style.display = "none";
}
function friend_changegroup(id, result) {
	if(result) {
		var ids = explode('_', id);
		var uid = ids[1];
		var obj = $('friend_group_'+ uid);
		var x = new Ajax();
		x.get('home.php?mod=misc&ac=ajax&op=getfriendgroup&uid='+uid, function(s){
			obj.innerHTML = s;
		});
	}
}
function friend_changegroupname(group) {
	var obj = $('friend_groupname_'+ group);
	var x = new Ajax();
	x.get('home.php?mod=misc&ac=ajax&op=getfriendname&inajax=1&group='+group, function(s){
		obj.innerHTML = s;
	});
}
function post_add(pid, result) {
	if(result) {
		var obj = $('post_ul');
		var newli = document.createElement("div");
		var x = new Ajax();
		x.get('home.php?mod=misc&ac=ajax&op=post', function(s){
			newli.innerHTML = s;
		});
		obj.appendChild(newli);
		if($('message')) {
			$('message').value= '';
			newnode = $('quickpostimg').rows[0].cloneNode(true);
			tags = newnode.getElementsByTagName('input');
			for(i in tags) {
				if(tags[i].name == 'pics[]') {
					tags[i].value = 'http://';
				}
			}
			var allRows = $('quickpostimg').rows;
			while(allRows.length) {
				$('quickpostimg').removeChild(allRows[0]);
			}
			$('quickpostimg').appendChild(newnode);
		}
		if($('post_replynum')) {
			var a = parseInt($('post_replynum').innerHTML);
			var b = a + 1;
			$('post_replynum').innerHTML = b + '';
		}
		showCreditPrompt();
	}
}
function post_edit(id, result) {
	if(result) {
		var ids = explode('_', id);
		var pid = ids[1];

		var obj = $('post_'+ pid +'_li');
		var x = new Ajax();
		x.get('home.php?mod=misc&ac=ajax&op=post&pid='+ pid, function(s){
			obj.innerHTML = s;
		});
	}
}
function post_delete(id, result) {
	if(result) {
		var ids = explode('_', id);
		var pid = ids[1];

		var obj = $('post_'+ pid +'_li');
		obj.style.display = "none";
		if($('post_replynum')) {
			var a = parseInt($('post_replynum').innerHTML);
			var b = a - 1;
			$('post_replynum').innerHTML = b + '';
		}
	}
}
function poke_send(id, result) {
	if(result) {
		var ids = explode('_', id);
		var uid = ids[1];

		if($('poke_'+ uid)) {
			$('poke_'+ uid).style.display = "none";
		}
		showCreditPrompt();
	}
}
function myfriend_post(uid) {
	if($('friend_'+uid)) {
		$('friend_'+uid).innerHTML = '<p>你们现在是好友了，接下来，您还可以：<a href="home.php?mod=space&do=wall&uid='+uid+'" class="xi2" target="_blank">给TA留言</a> ，或者 <a href="home.php?mod=spacecp&ac=poke&op=send&uid='+uid+'&handlekey=propokehk_'+uid+'" id="a_poke_'+uid+'" class="xi2" onclick="showWindow(this.id, this.href, \'get\', 0, {\'ctrlid\':this.id,\'pos\':\'13\'});">打个招呼</a></p>';
	}
	showCreditPrompt();
}
function myfriend_ignore(id) {
	var ids = explode('_', id);
	var uid = ids[1];
	$('friend_tbody_'+uid).style.display = "none";
}

function mtag_join(tagid, result) {
	if(result) {
		location.reload();
	}
}

function picView(albumid) {
	if(albumid == 'none') {
		$('albumpic_body').innerHTML = '';
	} else {
		ajaxget('home.php?mod=misc&ac=ajax&op=album&id='+albumid+'&ajaxdiv=albumpic_body', 'albumpic_body');
	}
}
function resend_mail(mid) {
	if(mid) {
		var obj = $('sendmail_'+ mid +'_li');
		obj.style.display = "none";
	}
}

function userapp_delete(id, result) {
	if(result) {
		var ids = explode('_', id);
		var appid = ids[1];
		$('space_app_'+appid).style.display = "none";
	}
}

function docomment_get(doid, key) {
	var showid = key + '_' + doid;
	var opid = key + '_do_a_op_'+doid;
	$(showid).style.display = '';
	$(showid).className = 'cmt brm';
	ajaxget('home.php?mod=spacecp&ac=doing&op=getcomment&handlekey=msg_'+doid+'&doid='+doid+'&key='+key, showid);
	if($(opid)) {
		$(opid).innerHTML = '收起';
		$(opid).onclick = function() {
			docomment_colse(doid, key);
		}
	}
	showCreditPrompt();
}

function docomment_colse(doid, key) {
	var showid = key + '_' + doid;
	var opid = key + '_do_a_op_'+doid;

	$(showid).style.display = 'none';
	$(showid).style.className = '';

	$(opid).innerHTML = '回复';
	$(opid).onclick = function() {
		docomment_get(doid, key);
	}
}

function docomment_form(doid, id, key) {
	var showid = key + '_form_'+doid+'_'+id;
	var divid = key +'_'+ doid;
	ajaxget('home.php?mod=spacecp&ac=doing&op=docomment&handlekey=msg_'+id+'&doid='+doid+'&id='+id+'&key='+key, showid);
	if($(divid)) {
		$(divid).style.display = '';
	}
}

function docomment_form_close(doid, id, key) {
	var showid = key + '_form_'+doid+'_'+id;
	$(showid).innerHTML = '';
	$(showid).style.display = 'none';
	var liObj = $(key+'_'+doid).getElementsByTagName('li');
	if(!liObj.length) {
		$(key+'_'+doid).style.display = 'none';
	}
}

function feedcomment_get(feedid) {
	var showid = 'feedcomment_'+feedid;
	var opid = 'feedcomment_a_op_'+feedid;

	$(showid).style.display = '';
	ajaxget('home.php?mod=spacecp&ac=feed&op=getcomment&feedid='+feedid+'&handlekey=feedhk_'+feedid, showid);
	if($(opid) != null) {
		$(opid).innerHTML = '收起';
		$(opid).onclick = function() {
			feedcomment_close(feedid);
		}
	}
}

function feedcomment_add(cid, feedid) {
	var obj = $('comment_ol_'+feedid);
	var newdl = document.createElement("dl");
	newdl.id = 'comment_'+cid+'_li';
	newdl.className = 'bbda cl';
	var x = new Ajax();
	x.get('home.php?mod=misc&ac=ajax&op=comment&inajax=1&cid='+cid, function(s){
		newdl.innerHTML = s;
	});
	obj.appendChild(newdl);

	$('feedmessage_'+feedid).value= '';
	showCreditPrompt();
}

function feedcomment_close(feedid) {
	var showid = 'feedcomment_'+feedid;
	var opid = 'feedcomment_a_op_'+feedid;

	$(showid).style.display = 'none';
	$(showid).style.className = '';

	$(opid).innerHTML = '评论';
	$(opid).onclick = function() {
		feedcomment_get(feedid);
	}
}

function feed_post_result(feedid, result) {
	if(result) {
		location.reload();
	}
}

function feed_more_show(feedid) {
	var showid = 'feed_more_'+feedid;
	var opid = 'feed_a_more_'+feedid;

	$(showid).style.display = '';
	$(showid).className = 'sub_doing';

	$(opid).innerHTML = '&laquo; 收起列表';
	$(opid).onclick = function() {
		feed_more_close(feedid);
	}
}

function feed_more_close(feedid) {
	var showid = 'feed_more_'+feedid;
	var opid = 'feed_a_more_'+feedid;

	$(showid).style.display = 'none';

	$(opid).innerHTML = '&raquo; 更多动态';
	$(opid).onclick = function() {
		feed_more_show(feedid);
	}
}

function poll_post_result(id, result) {
	if(result) {
		var aObj = $('__'+id).getElementsByTagName("a");
		window.location.href = aObj[0].href;
	}
}

function show_click(idtype, id, clickid) {
	ajaxget('home.php?mod=spacecp&ac=click&op=show&clickid='+clickid+'&idtype='+idtype+'&id='+id, 'click_div');
	showCreditPrompt();
}

function feed_menu(feedid, show) {
	var obj = $('a_feed_menu_'+feedid);
	if(obj) {
		if(show) {
			obj.style.display='block';
		} else {
			obj.style.display='none';
		}
	}
	var obj = $('feedmagic_'+feedid);
	if(obj) {
		if(show) {
			obj.style.display='block';
		} else {
			obj.style.display='none';
		}
	}
}

function showbirthday(){
	var el = $('birthday');
	var birthday = el.value;
	el.length=0;
	el.options.add(new Option('日', ''));
	for(var i=0;i<28;i++){
		el.options.add(new Option(i+1, i+1));
	}
	if($('birthmonth').value!="2"){
		el.options.add(new Option(29, 29));
		el.options.add(new Option(30, 30));
		switch($('birthmonth').value){
			case "1":
			case "3":
			case "5":
			case "7":
			case "8":
			case "10":
			case "12":{
				el.options.add(new Option(31, 31));
			}
		}
	} else if($('birthyear').value!="") {
		var nbirthyear=$('birthyear').value;
		if(nbirthyear%400==0 || (nbirthyear%4==0 && nbirthyear%100!=0)) el.options.add(new Option(29, 29));
	}
	el.value = birthday;
}

function selCommentTab(hid, sid) {
	$(hid).style.display = 'none';
	$(sid).style.display = '';
}


function magicColor(elem, t) {
	t = t || 10;
	elem = (elem && elem.constructor == String) ? $(elem) : elem;
	if(!elem){
		setTimeout(function(){magicColor(elem, t-1);}, 400);
		return;
	}
	if(window.mcHandler == undefined) {
		window.mcHandler = {elements:[]};
		window.mcHandler.colorIndex = 0;
		window.mcHandler.run = function(){
			var color = ["#CC0000","#CC6D00","#CCCC00","#00CC00","#0000CC","#00CCCC","#CC00CC"][(window.mcHandler.colorIndex++) % 7];
			for(var i = 0, L=window.mcHandler.elements.length; i<L; i++)
				window.mcHandler.elements[i].style.color = color;
		}
	}
	window.mcHandler.elements.push(elem);
	if(window.mcHandler.timer == undefined) {
		window.mcHandler.timer = setInterval(window.mcHandler.run, 500);
	}
}

function passwordShow(value) {
	if(value==4) {
		$('span_password').style.display = '';
		$('tb_selectgroup').style.display = 'none';
	} else if(value==2) {
		$('span_password').style.display = 'none';
		$('tb_selectgroup').style.display = '';
	} else {
		$('span_password').style.display = 'none';
		$('tb_selectgroup').style.display = 'none';
	}
}

function getgroup(gid) {
	if(gid) {
		var x = new Ajax();
		x.get('home.php?mod=spacecp&ac=privacy&inajax=1&op=getgroup&gid='+gid, function(s){
			s = s + ' ';
			$('target_names').innerHTML += s;
		});
	}
}
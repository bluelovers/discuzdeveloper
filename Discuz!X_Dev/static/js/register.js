/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: register.js 21143 2011-03-16 07:04:24Z monkey $
*/

var lastusername = '', lastpassword = '', lastemail = '', lastinvitecode = '';

function errormessage(id, msg) {
	if($(id)) {
		showInputTip();
		msg = !msg ? '' : msg;
		if($('tip_' + id)) {
			if(msg == 'succeed') {
				msg = '';
                                $('tip_' + id).parentNode.className = $('tip_' + id).parentNode.className.replace(/ p_right/, '');
				$('tip_' + id).parentNode.className += ' p_right';
			} else if(msg !== '') {
				$('tip_' + id).parentNode.className = $('tip_' + id).parentNode.className.replace(/ p_right/, '');
			}
		}
		if($('chk_' + id)) {
			$('chk_' + id).innerHTML = msg;
		}
		$(id).className = !msg ? $(id).className.replace(/ er/, '') : $(id).className + ' er';
	}
}

function addFormEvent(formid){
	var formNode = $(formid).getElementsByTagName('input');
	for(i = 0;i < formNode.length;i++) {
		if(formNode[i].type == 'text' || formNode[i].type == 'password'){
			formNode[i].onfocus = function(){
				showInputTip(!this.id ? this.name : this.id);
			}
		}
	}
}

function showInputTip(id) {
	var p_tips = $('registerform').getElementsByTagName('i');
	for(i = 0;i < p_tips.length;i++){
		if(p_tips[i].className == 'p_tip'){
			p_tips[i].style.display = 'none';
		}
	}
	if($('tip_' + id)) {
		$('tip_' + id).style.display = 'block';
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

function trim(str) {
	return str.replace(/^\s*(.*?)[\s\n]*$/g, '$1');
}

var emailMenuST = null, emailMenui = 0, emaildomains = ['qq.com', '163.com', 'sina.com', 'sohu.com', 'yahoo.cn', 'gmail.com', 'hotmail.com'];
function emailMenuOp(op, e) {
	if(op == 3 && BROWSER.ie && BROWSER.ie < 7) {
		checkemail();
	}
	if(!$('emailmore_menu')) {
		return;
	}
	if(op == 1) {
		$('emailmore_menu').style.display = 'none';
	} else if(op == 2) {
		showMenu({'ctrlid':'emailmore','pos': '13!'});
	} else if(op == 3) {
		emailMenuST = setTimeout(function () {
			emailMenuOp(1);
			checkemail();
		}, 500);
	} else if(op == 4) {
	       	e = e ? e : window.event;
                var obj = $('email');
        	if(e.keyCode == 13) {
                        var v = obj.value.indexOf('@') != -1 ? obj.value.substring(0, obj.value.indexOf('@')) : obj.value;
                        obj.value = v + '@' + emaildomains[emailMenui];
                        doane(e);
        	}
	} else if(op == 5) {
                var as = $('emailmore_menu').getElementsByTagName('a');
                for(i = 0;i < as.length;i++){
                        as[i].className = '';
                }
	}
}

function emailMenu(e) {
	if(BROWSER.ie && BROWSER.ie < 7) {
		return;
	}
	e = e ? e : window.event;
        var obj = $('email');
	var value = e.keyCode;
	var v = obj.value.indexOf('@') != -1 ? obj.value.substring(0, obj.value.indexOf('@')) : obj.value;
	if(!obj.value.length) {
		emailMenuOp(1);
		return;
	}

        if(value == 40) {
		emailMenui++;
		if(emailMenui > emaildomains.length) {
			emailMenui = 0;
		}
	} else if(value == 38) {
		emailMenui--;
		if(emailMenui < 1) {
			emailMenui = emaildomains.length;
		}
	} else if(value == 13) {
  		$('emailmore_menu').style.display = 'none';
  		return;
 	}
        if(!$('emailmore_menu')) {
		menu = document.createElement('div');
		menu.id = 'emailmore_menu';
		menu.style.display = 'none';
		menu.className = 'p_pop';
		$('append_parent').appendChild(menu);
	}
	var s = '<ul>';
	for(var i = 0; i < emaildomains.length; i++) {
		s += '<li><a href="javascript:;" onmouseover="emailMenuOp(5)" ' + (emailMenui == i ? 'class="a" ' : '') + 'onclick="$(\'email\').value=this.innerHTML;display(\'emailmore_menu\');checkemail();">' + v + '@' + emaildomains[i] + '</a></li>';
	}
	s += '</ul>';
	$('emailmore_menu').innerHTML = s;
	emailMenuOp(2);
}

function checksubmit() {
	var p_chks = $('registerform').getElementsByTagName('kbd');
	for(i = 0;i < p_chks.length;i++){
		if(p_chks[i].className == 'p_chk'){
			p_chks[i].innerHTML = '';
		}
	}
	ajaxpost('registerform', 'returnmessage4', 'returnmessage4', 'onerror');
	return;
}

function checkusername() {
	errormessage('regusername');
	var username = trim($('regusername').value);
	if($('tip_regusername').parentNode.className.match(/ p_right/) && (username == '' || username == lastusername)) {
		return;
	} else {
		lastusername = username;
	}
	if(username.match(/<|"/ig)) {
		errormessage('regusername', '用户名包含敏感字符');
		return;
	}
	var unlen = username.replace(/[^\x00-\xff]/g, "**").length;
	if(unlen < 3 || unlen > 15) {
		errormessage('regusername', unlen < 3 ? '用户名小于 3 个字符' : '用户名超过 15 个字符');
		return;
	}
	var x = new Ajax();
	$('tip_regusername').parentNode.className = $('tip_regusername').parentNode.className.replace(/ p_right/, '');
	x.get('forum.php?mod=ajax&inajax=yes&infloat=register&handlekey=register&ajaxmenu=1&action=checkusername&username=' + (BROWSER.ie && document.charset == 'utf-8' ? encodeURIComponent(username) : username), function(s) {
		errormessage('regusername', s);
	});
}

function checkpassword() {
	if(!$('regpassword').value && !$('regpassword2').value) {
		return;
	}
	errormessage('regpassword2');
	if($('regpassword').value != $('regpassword2').value) {
		errormessage('regpassword2', '两次输入的密码不一致');
	} else {
		errormessage('regpassword2', 'succeed');
	}
}

function checkemail() {
	errormessage('email');
	var email = trim($('email').value);
	if($('email').parentNode.className.match(/ p_right/) && (email == '' || email == lastemail)) {
		return;
	} else {
		lastemail = email;
	}
	if(email.match(/<|"/ig)) {
		errormessage('email', 'Email 包含敏感字符');
		return;
	}
	var x = new Ajax();
	$('tip_email').parentNode.className = $('tip_email').parentNode.className.replace(/ p_right/, '');
	x.get('forum.php?mod=ajax&inajax=yes&infloat=register&handlekey=register&ajaxmenu=1&action=checkemail&email=' + email, function(s) {
		errormessage('email', s);
	});
}

function checkinvite() {
	errormessage('invitecode');
	var invitecode = trim($('invitecode').value);
	if(invitecode == '' || invitecode == lastinvitecode) {
		return;
	} else {
		lastinvitecode = invitecode;
	}
	if(invitecode.match(/<|"/ig)) {
		errormessage('invitecode', '邀请码包含敏感字符');
		return;
	}
	var x = new Ajax();
	$('tip_invitecode').parentNode.className = $('tip_invitecode').parentNode.className.replace(/ p_right/, '');
	x.get('forum.php?mod=ajax&inajax=yes&infloat=register&handlekey=register&ajaxmenu=1&action=checkinvitecode&invitecode=' + invitecode, function(s) {
		errormessage('invitecode', s);
	});
}
<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id$
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(!empty($_POST)) {
	if($result['member']['conisbind']) {
		showmessage('connect_register_bind_already');
	}
	if($result['member']['groupid'] == 8) {
		showmessage('connect_register_bind_need_inactive');
	}

	DB::query("UPDATE ".DB::table('common_member')." SET conisbind='1', conuin='$_G[gp_uin]', conispublishfeed='1', conisregister='0', conisqzoneavatar='0' WHERE uid='$uid'");
	DB::query("INSERT INTO ".DB::table('connect_memberbindlog')." (uid, uin, type, dateline) VALUES ('$uid', '$_G[gp_uin]', '1', '$_G[timestamp]')");

	dsetcookie('connect_js_name', 'user_bind', 86400);
	dsetcookie('connect_js_params', base64_encode(serialize(array('type' => 'registerbind'))), 86400);
	dsetcookie('connect_login', 1, 31536000);
}

?>
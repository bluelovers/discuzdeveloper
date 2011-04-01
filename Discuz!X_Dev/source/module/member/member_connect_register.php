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

$from_connect = $_G['setting']['connect']['allow'] ? 1 : 0;
$regname = 'connect';

if(empty($_POST)) {

	if(!connect_valid($_GET, $connect_params)) {
		showmessage('connect_register_bind_miss_uin');
	}
	$connect_usernames = base64_decode($connect_params['x_usernames']);
	$connect_nick = connect_filter_username(base64_decode($connect_params['x_nick']));
	$_G['qc']['connect_uin'] = $connect_params['uin'];
	$_G['qc']['connect_email'] = $connect_params['x_email'];
	$_G['qc']['dreferer'] = !$dreferer ? str_replace('&amp;', '&', $_G['referer']) : $dreferer;

	$_G['qc']['usernames'] = $available_usernames = $unavailable_usernames = array();
	$connect_usernames = array_unique(array_map('connect_filter_username', array_filter(explode(',', $connect_usernames))));

	$_G['qc']['first_available_username'] = '';
	$flag = false;
	$_G['qc']['available_username_count'] = 0;
	if($connect_usernames && is_array($connect_usernames)) {
		foreach($connect_usernames as $username) {
			$username = trim($username);
			$val = array('username' => $username, 'available' => true);
			$ucresult = uc_user_checkname($username);
			if($ucresult < 0) {
				$val['available'] = false;
				array_push($unavailable_usernames, $val);
			} else {
				if (!$flag) {
					$_G['qc']['first_available_username'] = $val['username'];
				}
				array_push($available_usernames, $val);
				$_G['qc']['available_username_count']++;
				$flag = true;
			}
		}
		$_G['qc']['usernames'] = array_merge($available_usernames, $unavailable_usernames);
	}

	$ucresult = uc_user_checkname($connect_nick);
	if($ucresult >= 0) {
		$_G['qc']['first_available_username'] = $connect_nick;
	}

	switch($connect_params['x_sex']) {
		case 'male':
			$connectdefault['gender'] = 1;
			break;
		case 'female':
			$connectdefault['gender'] = 2;
			break;
		default:
			$connectdefault['gender'] = 0;
	}
	list($connectdefault['birthyear'], $connectdefault['birthmonth'], $connectdefault['birthday']) = explode('-', $connect_params['x_birthday']);
	foreach($_G['cache']['fields_register'] as $field) {
		$fieldid = $field['fieldid'];
		$html = profile_setting($fieldid, $connectdefault);
		if($html) {
			$settings[$fieldid] = $_G['cache']['profilesetting'][$fieldid];
			$htmls[$fieldid] = $html;
		}
	}

} else {

	$userdata['conuin'] = $_G['gp_uin'];
	$userdata['conisbind'] = $userdata['conispublishfeed'] = $userdata['conispublisht'] = $userdata['conisregister'] = 1;
	$userdata['conisqzoneavatar'] = !empty($_G['gp_use_qzone_avatar']) ? 1 : 0;
	$userdata['avatarstatus'] = !empty($_G['gp_use_qzone_avatar']) ? 1 : 0;
	if($_G['setting']['connect']['register_groupid']) {
		$userdata['groupid'] = $groupinfo['groupid'] = $_G['setting']['connect']['register_groupid'];
	}

	dsetcookie('connect_js_name', 'user_bind', 86400);
	dsetcookie('connect_js_params', base64_encode(serialize(array('type' => 'register'))), 86400);
	dsetcookie('connect_login', 1, 31536000);

	manyoulog('user', $uid, 'add');

	DB::query("INSERT INTO ".DB::table('connect_memberbindlog')." (uid, uin, type, dateline) VALUES ('$uid', '$_G[gp_uin]', '1', '$_G[timestamp]')");
	if($_G['setting']['connect']['register_addcredit']) {
		updatemembercount($uid, array($_G['setting']['connect']['register_rewardcredit'] => $_G['setting']['connect']['register_addcredit']));
	}

}

function connect_filter_username($username) {
	$username = str_replace(' ', '_', trim($username));
	return cutstr($username, 15, '');
}

?>
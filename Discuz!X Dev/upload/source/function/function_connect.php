<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id$
 */

function connect_output_javascript($jsurl) {
	return '<script type="text/javascript">_attachEvent(window, \'load\', function () { appendscript(\''.$jsurl.'\', \'\', 1, \'utf-8\') }, document);</script>';
}

function connect_output_php($url) {
	$response = dfsockopen($url);
	$result = (array)unserialize($response);
	return $result;
}

function connect_user_bind_js($params) {
	global $_G;

	$jsname = $_G['cookie']['connect_js_name'];
	if($jsname != 'user_bind') {
		return false;
	}

	$jsparams = unserialize(base64_decode($_G['cookie']['connect_js_params']));
	$jsurl = $_G['connect']['api_url'].'/notify/user/bind';

	if($jsparams) {
		$params = array_merge($params, $jsparams);
		if(!empty($_G['cookie']['statreferer'])) {
			$params['statreferer'] = 'qzone';
		}
	}

	$func = 'connect_'.$jsname.'_params';
	$other_params = $func();
	$params = array_merge($other_params, $params);
	$jsurl .= '?'.http_build_query($params);

	dsetcookie('connect_js_name');
	dsetcookie('connect_js_params');
	return connect_output_javascript($jsurl);
}

function connect_user_unbind($uin, $isadmin = 0) {
	global $_G;
	if($isadmin) {
		$params = array(
			'uin' => $uin,
			's_id' => $_G['setting']['connectsiteid'],
			'response_type' => 'php'
		);
		$params['sig'] = connect_get_sig($params, $_G['setting']['connectsiteid'].'|'.$_G['setting']['connectsitekey']);
	} else {
		$params = array(
			'uin' => $uin,
			'access_token' => $_G['cookie']['client_token'],
			'response_type' => 'php'
		);
	}
	return connect_output_php($_G['connect']['api_url'].'/notify/user/unbind?'.http_build_query($params));
}

function connect_user_bind_params() {
	global $_G;

	getuserprofile('birthyear');
	getuserprofile('birthmonth');
	getuserprofile('birthday');
	switch($_G['member']['gender']) {
		case 1:
			$sex = 'male';
			break;
		case 2:
			$sex = 'female';
			break;
		default:
			$sex = 'unknown';
	}

	$is_public_email = 2;
	$is_use_qq_avatar = $_G['member']['conisqzoneavatar'] == 1 ? 1 : 2;
	$birthday = sprintf('%04d', $_G['member']['birthyear']).'-'.sprintf('%02d', $_G['member']['birthmonth']).'-'.sprintf('%02d', $_G['member']['birthday']);

	$agent = md5(time().rand().uniqid());
	$inputArray = array(
		'uid' => $_G['uid'],
		'agent' => $agent,
		'time' => TIMESTAMP
	);
	require_once DISCUZ_ROOT.'./config/config_ucenter.php';
	$input = 'uid='.$_G['uid'].'&agent='.$agent.'&time='.TIMESTAMP;
	$avatar_input = authcode($input, 'ENCODE', UC_KEY);

	$params = array(
		'u_id' => $_G['uid'],
		'uin' => $_G['member']['conuin'],
		'username' => $_G['member']['username'],
		'email' => $_G['member']['email'],
		'birthday' => $birthday,
		'sex' => $sex,
		'is_public_email' => $is_public_email,
		'is_use_qq_avatar' => $is_use_qq_avatar,
		's_id' => $_G['setting']['connectsiteid'],
		'avatar_input' => $avatar_input,
		'avatar_agent' => $agent,
		'site_ucenter_id' => UC_APPID,
	);

	return $params;
}

function connect_feed_remove($tid) {
	global $_G;

	$feedlog = DB::fetch_first("SELECT * FROM ".DB::table('connect_feedlog')." WHERE tid='$tid'");
	if(!$feedlog) {
		return false;
	}

	if($feedlog['status'] != 4) {
		DB::query("UPDATE ".DB::table('connect_feedlog')." SET status='4' WHERE tid='$tid'");
	}

	$params = array(
		'thread_id' => $tid,
		's_id' => $_G['setting']['connectsiteid'],
		'uin' => $_G['member']['conuin'],
		'ts' => TIMESTAMP,
	);
	$params['sig'] = connect_get_sig($params, connect_get_access_token('APP'));

	return sprintf('%s/notify/feed/remove?%s', $_G['connect']['api_url'], http_build_query($params));
}

?>
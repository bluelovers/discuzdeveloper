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

define('NOROBOT', TRUE);

if(!$_G['setting']['connect']['allow']) {
	showmessage('qqconnect_closed');
}

if($_G['gp_action'] == 'login') {

	$ctl_obj = new logging_ctl();
	$ctl_obj->setting = $_G['setting'];
	$ctl_obj->setting['seccodestatus'] = 0;

	$ctl_obj->extrafile = 'connect_logging';
	$ctl_obj->template = 'member/login';
	$ctl_obj->on_login();

} else {

	$ctl_obj = new register_ctl();
	$ctl_obj->setting = $_G['setting'];

	$ctl_obj->setting = $_G['setting'];
	$ctl_obj->setting['regclosed'] = $_G['setting']['regconnect'] && !$_G['setting']['regstatus'];
	if($_G['setting']['connect']['register_regverify']) {
		$ctl_obj->setting['regverify'] = 0;
	}
	$ctl_obj->setting['seccodestatus'] = 0;
	$ctl_obj->setting['secqaa']['status'] = 0;

	loadcache(array('fields_connect_register', 'profilesetting'));
	foreach($_G['cache']['fields_connect_register'] as $field => $data) {
		unset($_G['cache']['fields_register'][$field]);
	}
	$_G['cache']['profilesetting']['gender']['unchangeable'] = 0;
	$_G['cache']['profilesetting']['birthyear']['unchangeable'] = 0;
	$_G['cache']['profilesetting']['birthmonth']['unchangeable'] = 0;
	$_G['cache']['profilesetting']['birthday']['unchangeable'] = 0;
	$uin = $_G['gp_con_uin'] ? $_G['gp_con_uin'] : $_G['gp_uin'];
	$_G['cache']['fields_register'] = array_merge($_G['cache']['fields_connect_register'], $_G['cache']['fields_register']);
	$_G['qc']['uinlimit'] = $_G['setting']['connect']['register_uinlimit'] && DB::result_first("SELECT COUNT(DISTINCT uid) FROM ".DB::table('connect_memberbindlog')." WHERE uin='$uin' AND type='1'") >= $_G['setting']['connect']['register_uinlimit'];
	if($_G['setting']['connect']['register_invite']) {
		$ctl_obj->setting['regstatus'] = 1;
	}
	$ctl_obj->setting['ignorepassword'] = 1;
	$ctl_obj->setting['checkuinlimit'] = 1;

	$ctl_obj->extrafile = 'connect_register';
	$ctl_obj->template = 'member/register';
	$ctl_obj->on_register();

}

?>
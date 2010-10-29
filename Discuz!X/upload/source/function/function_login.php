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

function userlogin($username, $password, $questionid, $answer, $loginfield = 'username') {
	$return = array();

	if($loginfield == 'uid') {
		$isuid = 1;
	} elseif($loginfield == 'email') {
		$isuid = 2;
	} elseif($loginfield == 'auto') {
		$isuid = 3;
	} else {
		$isuid = 0;
	}

	if(!function_exists('uc_user_login')) {
		loaducenter();
	}
	if($isuid == 3) {
		if(preg_match('/^[1-9]\d*$/', $username)) {
			$return['ucresult'] = uc_user_login($username, $password, 1, 1, $questionid, $answer);
		} elseif(isemail($username)) {
			$return['ucresult'] = uc_user_login($username, $password, 2, 1, $questionid, $answer);
		}
		if($return['ucresult'][0] <= 0) {
			$return['ucresult'] = uc_user_login($username, $password, 0, 1, $questionid, $answer);
		}
	} else {
		$return['ucresult'] = uc_user_login($username, $password, $isuid, 1, $questionid, $answer);
	}
	list($tmp['uid'], $tmp['username'], $tmp['password'], $tmp['email'], $duplicate) = daddslashes($return['ucresult'], 1);
	$return['ucresult'] = $tmp;

	if($duplicate && $return['ucresult']['uid'] > 0) {
		if($olduid = DB::result_first("SELECT uid FROM ".DB::table('common_member')." WHERE username='".addslashes($return['ucresult']['username'])."'")) {
			require_once libfile('function/membermerge');
			if($olduid != $return['ucresult']['uid']) {
				membermerge($olduid, $return['ucresult']['uid']);
			}
			uc_user_merge_remove($return['ucresult']['username']);
		} else {
			$return['status'] = 0;
			return $return;
		}
	}

	if($return['ucresult']['uid'] <= 0) {
		$return['status'] = 0;
		return $return;
	}

	$member = DB::fetch_first("SELECT * FROM ".DB::table('common_member')." WHERE uid='".$return['ucresult']['uid']."'");
	if(!$member) {
		$return['status'] = -1;
		return $return;
	}
	$return['member'] = $member;
	$return['status'] = 1;

	if(addslashes($member['email']) != $return['ucresult']['email']) {
		DB::query("UPDATE ".DB::table('common_member')." SET email='".$return['ucresult']['email']."' WHERE uid='".$return['ucresult']['uid']."'");
	}

	return $return;
}

function setloginstatus($member, $cookietime) {
	global $_G;
	$_G['uid'] = $member['uid'];
	$_G['username'] = $member['username'];
	$_G['adminid'] = $member['adminid'];
	$_G['groupid'] = $member['groupid'];
	$_G['formhash'] = formhash();
	$_G['session']['invisible'] = getuserprofile('invisible');
	$_G['member'] = $member;
	$_G['core']->session->isnew = 1;

	dsetcookie('auth', authcode("{$member['password']}\t{$member['uid']}", 'ENCODE'), $cookietime, 1, true);
	dsetcookie('loginuser');
	dsetcookie('activationauth');
	dsetcookie('pmnum');
}

function logincheck() {
	global $_G;
	$return = 0;
	$login = DB::fetch_first("SELECT count, lastupdate FROM ".DB::table('common_failedlogin')." WHERE ip='$_G[clientip]'");
	$return = (!$login || (TIMESTAMP - $login['lastupdate'] > 900)) ? 4 : max(0, 5 - $login['count']);

	if(!$login) {
		DB::query("REPLACE INTO ".DB::table('common_failedlogin')." (ip, count, lastupdate) VALUES ('$_G[clientip]', '1', '$_G[timestamp]')");
	} elseif(TIMESTAMP - $login['lastupdate'] > 900) {
		DB::query("REPLACE INTO ".DB::table('common_failedlogin')." (ip, count, lastupdate) VALUES ('$_G[clientip]', '1', '$_G[timestamp]')");
		DB::query("DELETE FROM ".DB::table('common_failedlogin')." WHERE lastupdate<$_G[timestamp]-901", 'UNBUFFERED');
	}
	return $return;
}

function loginfailed() {
	global $_G;
	DB::query("UPDATE ".DB::table('common_failedlogin')." SET count=count+1, lastupdate='$_G[timestamp]' WHERE ip='$_G[clientip]'");
}

?>
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

require_once libfile('function/threadsort');
loadcache(array('threadsort_option_'.$sortid, 'threadsort_template_'.$sortid));
foreach($_G['cache']['threadsort_option_'.$sortid] AS $key => $val) {
	if($val['profile']) {
		$member_profile_sql = !$member_profile_sql ? $val['profile'] : ", ".$val['profile'];
	}
}
if($member_profile_sql) {
	$member_profile = DB::fetch_first("SELECT $member_profile_sql FROM ".DB::table('common_member_profile')." WHERE uid = '$_G[uid]' LIMIT 1");
	unset($member_profile_sql);
}
threadsort_optiondata($pid, $sortid, $_G['cache']['threadsort_option_'.$sortid], $_G['cache']['threadsort_template_'.$sortid]);
$template = intval($_G['gp_operate']) ? 'forum/search_sortoption' : 'forum/post_sortoption';
include template($template);
exit;
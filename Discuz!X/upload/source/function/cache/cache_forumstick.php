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

function build_cache_forumstick() {
	$data = array();
	$forumstickthreads = DB::result_first("SELECT svalue FROM ".DB::table('common_setting')." WHERE skey='forumstickthreads'");

	$forumstickthreads = unserialize($forumstickthreads);
	$forumstickcached = array();
	if($forumstickthreads) {
		foreach($forumstickthreads as $forumstickthread) {
			foreach($forumstickthread['forums'] as $fid) {
				$forumstickcached[$fid][] = $forumstickthread['tid'];
			}
		}
		$data = $forumstickcached;
	} else {
		$data = array();
	}

	save_syscache('forumstick', $data);
}

?>
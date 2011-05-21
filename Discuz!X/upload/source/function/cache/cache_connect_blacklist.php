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

function build_cache_connect_blacklist() {
	global $_G;
	$data = array();

	$query = DB::query("SELECT * FROM ".DB::table('common_uin_black'), 'SILENT');
	while($blacklist = DB::fetch($query)) {
		$data[] = $blacklist['uin'];
	}

	save_syscache('connect_blacklist', $data);
}

?>
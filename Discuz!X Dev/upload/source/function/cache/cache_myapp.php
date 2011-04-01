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

function build_cache_myapp() {
	$data = array();
	$query = DB::query("SELECT * FROM ".DB::table('common_myapp')." WHERE flag!='-1' ORDER BY displayorder");

	while($myapp = DB::fetch($query)) {
		$myapp['icon'] = getmyappiconpath($myapp['appid'], $myapp['iconstatus']);
		$data[$myapp['appid']] = $myapp;
	}

	save_syscache('myapp', $data);
}

?>
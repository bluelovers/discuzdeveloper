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

function build_cache_magics() {
	$data = array();
	$query = DB::query("SELECT * FROM ".DB::table('common_magic')." WHERE available='1'");

	while($magic = DB::fetch($query)) {
		$data[$magic['magicid']] = $magic;
	}

	save_syscache('magics', $data);
}

?>
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

function build_cache_modreasons() {
	$data = array();
	$query = DB::query("SELECT svalue FROM ".DB::table('common_setting')." WHERE skey='modreasons'");

	$modreasons = DB::result($query, 0);
	$modreasons = str_replace(array("\r\n", "\r"), array("\n", "\n"), $modreasons);
	$data = explode("\n", trim($modreasons));

	save_syscache('modreasons', $data);
}

?>
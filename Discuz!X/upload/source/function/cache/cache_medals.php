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

function build_cache_medals() {
	$data = array();
	$query = DB::query("SELECT medalid, name, image FROM ".DB::table('forum_medal')." WHERE available='1'");

	while($medal = DB::fetch($query)) {
		$data[$medal['medalid']] = array('name' => $medal['name'], 'image' => $medal['image']);
	}

	save_syscache('medals', $data);
}

?>
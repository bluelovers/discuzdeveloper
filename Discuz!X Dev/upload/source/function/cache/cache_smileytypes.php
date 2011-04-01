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

function build_cache_smileytypes() {
	$data = array();
	$query = DB::query("SELECT typeid, name, directory FROM ".DB::table('forum_imagetype')." WHERE type='smiley' AND available='1' ORDER BY displayorder");

	while($type = DB::fetch($query)) {
		$typeid = $type['typeid'];
		unset($type['typeid']);
		if(DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_smiley')." WHERE type='smiley' AND code<>'' AND typeid='$typeid'")) {
			$data[$typeid] = $type;
		}
	}

	save_syscache('smileytypes', $data);
}

?>
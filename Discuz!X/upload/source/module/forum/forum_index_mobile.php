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
if($_G['uid']) {
	$query = DB::query("SELECT * FROM ".DB::table('home_favorite')." WHERE uid = '{$_G['uid']}' AND idtype = 'fid' ORDER BY dateline DESC");
	while($result = DB::fetch($query)) {
		$result['title'] = strip_tags($result['title']);
		$forum_favlist[$result['id']] = $result;
	}
}
?>
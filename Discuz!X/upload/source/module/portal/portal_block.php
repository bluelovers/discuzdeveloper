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

$bid = empty($_GET['bid'])?0:intval($_GET['bid']);
if(empty($bid)) {
	showmessage("block_no_choice");
}
$block = DB::fetch_first("SELECT * FROM ".DB::table('common_block')." WHERE bid='$bid'");
if(empty($block)) {
	showmessage("block_noexist");
}

$perpage = 5;
$page = intval($_GET['page']);
$start = ($page-1)*$perpage;
if($start<0) $start = 0;

$list = array();
$multi = '';

$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_block_item_archive')." WHERE bid='$bid'"), 0);
if($count) {
	$query = DB::query("SELECT * FROM ".DB::table('common_block_item_archive')." ORDER BY startdate DESC
		LIMIT $start,$perpage");
	while ($value = DB::fetch($query)) {
		if($value['pic']) $value['pic'] = pic_cover_get($value['pic'], $value['picflag']);
		$list[] = $value;
	}

	$multi = multi($count, $perpage, $page, "portal.php?mod=block&bid=$bid");
}

include_once template("portal/block");

?>
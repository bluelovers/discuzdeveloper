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

$perpage = 20;
$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1) $page=1;
$start = ($page-1)*$perpage;
$multi_mode = false;

ckstart($start, $perpage);

$creditkey = $cache_file = '';
$cache_time = $_G['setting']['topcachetime'];
if($cache_time<5) $cache_time = 5;
$cache_time = $cache_time*60;
$fuids = array();
$count = 0;
$now_pos = 0;

if(empty($_GET['view'])) $_GET['view'] = 'show';

$memcol = 'm.uid,m.username,m.videophotostatus,m.groupid,m.credits';

if ($_GET['view'] == 'show') {
	$creditid = 0;
	if($_G['setting']['creditstransextra'][6]) {
		$creditid = intval($_G['setting']['creditstransextra'][6]);
		$creditkey = 'extcredits'.$creditid;
	} elseif ($_G['setting']['creditstrans']) {
		$creditid = intval($_G['setting']['creditstrans']);
		$creditkey = 'extcredits'.$creditid;
	}
	$extcredits = $_G['setting']['extcredits'];
	$c_sql = "SELECT COUNT(*) FROM ".DB::table('home_show');
	$sql = "SELECT s.credit AS show_credit,s.note AS show_note,$memcol
		FROM ".DB::table('home_show')." s
		LEFT JOIN ".DB::table('common_member')." m ON m.uid=s.uid
		ORDER BY s.credit DESC";

	if(substr($_G['timestamp'], -1) == '0') {
		DB::query("DELETE FROM ".DB::table('home_show')." WHERE credit<1");
	}

	space_merge($space, 'count');
	$space['credit'] = empty($creditkey) ? 0 : $space[$creditkey];
	$space['showcredit'] = DB::result(DB::query("SELECT credit FROM ".DB::table('home_show')." WHERE uid='$space[uid]'"));
	$space['showcredit'] = intval($space['showcredit']);

	$now_pos = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('home_show')." WHERE credit>='$space[showcredit]'"), 0);

} elseif ($_GET['view'] == 'credit') {

	$count = 100;
	$cache_file = DISCUZ_ROOT.'./data/sysdata/home_top_credit.txt';

	$sql = "SELECT $memcol,field.spacenote FROM ".DB::table('common_member')." m
		LEFT JOIN ".DB::table('common_member_field_home')." field ON field.uid=m.uid
		ORDER BY m.credits DESC";

	$cookie_name = 'space_top_'.$_GET['view'];
	if($_G['cookie'][$cookie_name]) {
		$now_pos = $_G['cookie'][$cookie_name];
	} else {
		$pos_sql = "SELECT COUNT(*) FROM ".DB::table('common_member')." s WHERE s.credits>'$space[credits]'";
		$now_pos = DB::result(DB::query($pos_sql), 0);
		$now_pos++;
		dsetcookie($cookie_name, $now_pos);
	}

} elseif ($_GET['view'] == 'friendnum') {
	if($multi_mode) {
		$c_sql = "SELECT COUNT(*) FROM ".DB::table('common_member');
	} else {
		$count = 100;
		$cache_file = DISCUZ_ROOT.'./data/sysdata/home_top_friendnum.txt';
	}
	space_merge($space, 'count');
	$sql = "SELECT main.friends, $memcol, field.spacenote FROM ".DB::table('common_member_count')." main
		LEFT JOIN ".DB::table('common_member')." m ON m.uid=main.uid
		LEFT JOIN ".DB::table('common_member_field_home')." field ON field.uid=main.uid
		ORDER BY main.friends DESC";

	$cookie_name = 'space_top_'.$_GET['view'];
	if($_G['cookie'][$cookie_name]) {
		$now_pos = $_G['cookie'][$cookie_name];
	} else {
		space_merge($space, 'count');
		$pos_sql = "SELECT COUNT(*) FROM ".DB::table('common_member_count')." s WHERE s.friends>'$space[friends]'";
		$now_pos = DB::result(DB::query($pos_sql), 0);
		$now_pos++;
		dsetcookie($cookie_name, $now_pos);
	}
} else {
	$multi_mode = true;
	$_GET['view'] = 'online';
	$c_sql = "SELECT COUNT(*) FROM ".DB::table('common_session')." WHERE uid>0";
	$sql = "SELECT field.spacenote, $memcol, main.lastactivity
		FROM ".DB::table('common_session')." main
		LEFT JOIN ".DB::table('common_member')." m ON m.uid=main.uid
		LEFT JOIN ".DB::table('common_member_field_home')." field ON field.uid=main.uid
		WHERE main.uid>0 AND main.invisible=0";
	$now_pos = -1;
}

$lockfile = DISCUZ_ROOT.'./data/sysdata/home_top_cache.lock';

$list = array();
if(empty($count)) {
	$cache_mode = false;
	$filecachetime = '';

	$count = empty($multi_mode)?1:DB::result(DB::query($c_sql),0);
	$multi = multi($count, $perpage, $page, "home.php?mod=space&do=top&view=$_GET[view]");
} else {
	$cache_mode = true;
	$multi = '';
	$start = 0;
	$perpage = $count;

	$locktime = @filemtime($lockfile);
	$filecachetime = @filemtime($cache_file);

	if($cache_file && file_exists($cache_file) && ($_G['timestamp'] - $filecachetime < $cache_time || $_G['timestamp'] - $locktime < 60)) {
		$list_cache = file_get_contents($cache_file);
		$list = unserialize($list_cache);
	}

	$filecachetime = empty($filecachetime)?'':dgmdate($filecachetime);
}
if($count && empty($list)) {

	touch($lockfile);

	$query = DB::query("$sql LIMIT $start,$perpage");
	while ($value = DB::fetch($query)) {
		$list[$value['uid']] = $value;
	}
	if($cache_mode && $cache_file) {
		file_put_contents($cache_file, serialize($list));
	}

	@unlink($lockfile);
}

$myfuids =array();
$query = DB::query("SELECT fuid, fusername FROM ".DB::table('home_friend')." WHERE uid='$_G[uid]'");
while ($value = DB::fetch($query)) {
	$myfuids[$value['fuid']] = $value['fuid'];
}
$myfuids[$_G['uid']] = $_G['uid'];

foreach($list as $key => $value) {
	$fuids[] = $value['uid'];
	if(isset($value['lastactivity'])) $value['lastactivity'] = dgmdate($value['lastactivity'], 't');
	$value['isfriend'] = empty($myfuids[$value['uid']])?0:1;
	$list[$key] = $value;
}

$ols = array();
if($fuids && $_GET['view'] != 'online') {
	$query = DB::query("SELECT * FROM ".DB::table('common_session')." WHERE uid IN (".dimplode($fuids).")");
	while ($value = DB::fetch($query)) {
		if(!$value['magichidden']) {
			$ols[$value['uid']] = $value['lastactivity'];
		} elseif ($_GET['view'] == 'online' && $list[$value['uid']]) {
			unset($list[$value['uid']]);
		}
	}
}

$a_actives = array($_GET['view'] => ' class="a"');

include_once template("diy:home/space_top");

?>
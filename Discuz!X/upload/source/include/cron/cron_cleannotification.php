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

$deltime = $_G['timestamp'] - 2*3600*24;
DB::query("DELETE FROM ".DB::table('home_notification')." WHERE dateline < '$deltime' AND new='0'");

$deltime = $_G['timestamp'] - 7*3600*24;
DB::query("DELETE FROM ".DB::table('home_pokearchive')." WHERE dateline < '$deltime'");

DB::query("OPTIMIZE TABLE ".DB::table('home_notification'), 'SILENT');

?>
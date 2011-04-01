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

$myrecordtimes = TIMESTAMP - $_G['setting']['myrecorddays'] * 86400;

DB::query("DELETE FROM ".DB::table('common_mytask')." WHERE status='-1' AND dateline<'$_G[timestamp]'-2592000", 'UNBUFFERED');

?>
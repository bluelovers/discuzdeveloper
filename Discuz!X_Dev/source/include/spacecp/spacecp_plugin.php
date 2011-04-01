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

$pluginkey = 'spacecp'.($op ? '_'.$op : '');
$navtitle = $_G['setting']['plugins'][$pluginkey][$_G['gp_id']]['name'];

include pluginmodule($_G['gp_id'], $pluginkey);
if(!$op || $op == 'credit') {
	include template('home/spacecp_plugin');
} elseif($op == 'profile') {
	$operation = 'plugin';
	include template('home/spacecp_profile');
}

?>
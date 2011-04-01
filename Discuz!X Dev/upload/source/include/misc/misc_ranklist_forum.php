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

$forumsrank = '';
$view = 'threads';
$navname = $_G['setting']['navs'][8]['navname'];
switch($_G['gp_view']) {
	case 'posts':
		$gettype = 'post';
		break;
	case 'thismonth':
		$gettype = 'post_30';
		break;
	case 'today':
		$gettype = 'post_24';
		break;
	case 'threads':
		$gettype = 'thread';
		break;
	default: $_G['gp_view'] = 'threads';
}
$view = $_G['gp_view'];
$forumsrank = getranklistdata($type, $view);
$lastupdate = $_G['lastupdate'];
$nextupdate = $_G['nextupdate'];

$navtitle = lang('ranklist/navtitle', 'ranklist_title_forum_'.$gettype).' - '.$navname;
$metakeywords = lang('ranklist/navtitle', 'ranklist_title_forum_'.$gettype);
$metadescription = lang('ranklist/navtitle', 'ranklist_title_forum_'.$gettype);

include template('diy:ranklist/forum');

?>
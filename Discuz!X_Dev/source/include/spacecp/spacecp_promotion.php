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

if(!($_G['setting']['creditspolicy']['promotion_visit'] || $_G['setting']['creditspolicy']['promotion_register'])) {
	showmessage('action_closed', NULL);
}

include_once template("home/spacecp_promotion");
?>
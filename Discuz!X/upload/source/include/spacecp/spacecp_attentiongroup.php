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

$_G['gp_handlekey'] = 'attentiongroup';
$usergroups = getuserprofile('groups');
if(!empty($usergroups)) {
	$usergroups = unserialize($usergroups);
}
$attentiongroup = explode(',', $_G['member']['attentiongroup']);
$counttype = count($attentiongroup);
if(submitcheck('attentionsubmit')) {
	if(is_array($_G['gp_attentiontypeid'])) {
		$_G['gp_attentiontypeid'] = array_slice($_G['gp_attentiontypeid'], 0, 5);
		DB::query("UPDATE ".DB::table('common_member_field_forum')." SET attentiongroup='".implode(',', daddslashes($_G['gp_attentiontypeid']))."' WHERE uid='$_G[uid]'");
	} else {
		DB::query("UPDATE ".DB::table('common_member_field_forum')." SET attentiongroup='' WHERE uid='$_G[uid]'");
	}
	showmessage('setup_finished', 'home.php?mod=space&do=group&view=groupthread');
}
include template('home/spacecp_attentiongroup');

?>
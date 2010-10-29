<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id$
 */
function getuidfields() {
	return array(
		'common_credit_log',
		'common_credit_rule_log',
		'common_credit_rule_log_field',
		'common_invite|uid,fuid',
		'common_mailcron|touid',
		'common_member',
		'common_member_count',
		'common_member_field_forum',
		'common_member_field_home',
		'common_member_log',
		'common_member_profile',
		'common_member_security',
		'common_member_status',
		'common_member_validate',
		'common_myinvite|fromuid,touid',
		'forum_access',
		'forum_activity',
		'forum_activityapply',
		'forum_attachment',
		'forum_attachmentfield',
		'forum_creditslog',
		'forum_debate',
		'forum_debatepost',
		'home_favorite',
		'forum_medallog',
		'common_member_magic',
		'forum_memberrecommend|recommenduid',
		'forum_moderator',
		'forum_modwork',
		'common_mytask',
		'forum_order',
		'forum_groupinvite',
		'forum_groupuser',
		'forum_pollvoter',
		'forum_post|authorid',
		'forum_thread|authorid',
		'forum_threadmod',
		'forum_tradecomment|raterid,rateeid',
		'forum_tradelog|sellerid,buyerid',
		'home_album',
		'home_appcreditlog',
		'home_blacklist|uid,buid',
		'home_blog',
		'home_blogfield',
		'home_class',
		'home_clickuser',
		'home_comment|uid,authorid',
		'home_docomment',
		'home_doing',
		'home_feed',
		'home_feed_app',
		'home_friend|uid,fuid',
		'home_friendlog|uid,fuid',
		'home_pic',
		'home_share',
		'home_userapp',
		'home_userappfield',
		'common_admincp_member'
	);
}

function membermerge($olduid, $newuid) {
	$uidfields = getuidfields();
	foreach($uidfields as $value) {
		list($table, $field, $stepfield) = explode('|', $value);
		$fields = !$field ? array('uid') : explode(',', $field);
		foreach($fields as $field) {
			DB::query("UPDATE `".DB::table($table)."` SET `$field`='$newuid' WHERE `$field`='$olduid'");
		}
	}
}

?>
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

$blockclass = array(
	'forum' => array(
		'name' => lang('blockclass', 'blockclass_forum'),
		'subs' => array(
			'forum_thread' => array(
				'name' => lang('blockclass', 'blockclass_forum_thread'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_thread_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_thread_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'pic' => array('name' => lang('blockclass', 'blockclass_thread_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
					'summary' => array('name' => lang('blockclass', 'blockclass_thread_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'author' => array('name' => lang('blockclass', 'blockclass_thread_field_author'), 'formtype' => 'text', 'datatype' => 'string'),
					'authorid' => array('name' => lang('blockclass', 'blockclass_thread_field_authorid'), 'formtype' => 'text', 'datatype' => 'int'),
					'avatar' => array('name' => lang('blockclass', 'blockclass_thread_field_avatar'), 'formtype' => 'text', 'datatype' => 'string'),
					'avatar_big' => array('name' => lang('blockclass', 'blockclass_thread_field_avatar_big'), 'formtype' => 'text', 'datatype' => 'string'),
					'icon' => array('name' => lang('blockclass', 'blockclass_thread_field_icon'), 'formtype' => 'text', 'datatype' => 'string'),
					'forumurl' => array('name' => lang('blockclass', 'blockclass_thread_field_forumurl'), 'formtype' => 'text', 'datatype' => 'string'),
					'forumname' => array('name' => lang('blockclass', 'blockclass_thread_field_forumname'), 'formtype' => 'text', 'datatype' => 'string'),
					'posts' => array('name' => lang('blockclass', 'blockclass_thread_field_posts'), 'formtype' => 'text', 'datatype' => 'int'),
					'todayposts' => array('name' => lang('blockclass', 'blockclass_thread_field_todayposts'), 'formtype' => 'text', 'datatype' => 'int'),
					'lastpost' => array('name' => lang('blockclass', 'blockclass_thread_field_lastpost'), 'formtype' => 'date', 'datatype' => 'date'),
					'dateline' => array('name' => lang('blockclass', 'blockclass_thread_field_dateline'), 'formtype' => 'date', 'datatype' => 'date'),
					'replies' => array('name' => lang('blockclass', 'blockclass_thread_field_replies'), 'formtype' => 'text', 'datatype' => 'int'),
					'views' => array('name' => lang('blockclass', 'blockclass_thread_field_views'), 'formtype' => 'text', 'datatype' => 'int'),
					'heats' => array('name' => lang('blockclass', 'blockclass_thread_field_heats'), 'formtype' => 'text', 'datatype' => 'int'),
					'recomments' => array('name' => lang('blockclass', 'blockclass_thread_field_recomments'), 'formtype' => 'text', 'datatype' => 'int'),
					'hourviews' => array('name' => lang('blockclass', 'blockclass_thread_field_hourviews'), 'formtype' => 'text', 'datatype' => 'int'),
					'todayviews' => array('name' => lang('blockclass', 'blockclass_thread_field_todayviews'), 'formtype' => 'text', 'datatype' => 'int'),
					'weekviews' => array('name' => lang('blockclass', 'blockclass_thread_field_weekviews'), 'formtype' => 'text', 'datatype' => 'int'),
					'monthviews' => array('name' => lang('blockclass', 'blockclass_thread_field_monthviews'), 'formtype' => 'text', 'datatype' => 'int'),
					),
				'script' => array(
					'threadnew' => lang('blockclass', 'blockclass_thread_script_threadnew'),
					'threadhot' => lang('blockclass', 'blockclass_thread_script_threadhot'),
					'threadspecial' => lang('blockclass', 'blockclass_thread_script_threadspecial'),
					'threaddigest' => lang('blockclass', 'blockclass_thread_script_threaddigest'),
					'threadstick' => lang('blockclass', 'blockclass_thread_script_threadstick'),
					'threadspecified' => lang('blockclass', 'blockclass_thread_script_threadspecified'),
					'thread' => lang('blockclass', 'blockclass_thread_script_thread'),
					)
			),
			'forum_attachment' => array(
				'name' => lang('blockclass', 'blockclass_forum_attachment'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_attachment_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_attachment_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'pic' => array('name' => lang('blockclass', 'blockclass_attachment_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
					'summary' => array('name' => lang('blockclass', 'blockclass_attachment_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'threadurl' => array('name' => lang('blockclass', 'blockclass_attachment_field_threadurl'), 'formtype' => 'text', 'datatype' => 'text'),
					'threadsubject' => array('name' => lang('blockclass', 'blockclass_attachment_field_threadsubject'), 'formtype' => 'text', 'datatype' => 'text'),
					'threadsummary' => array('name' => lang('blockclass', 'blockclass_attachment_field_threadsummary'), 'formtype' => 'text', 'datatype' => 'text'),
					'filesize' => array('name' => lang('blockclass', 'blockclass_attachment_field_filesize'), 'formtype' => 'text', 'datatype' => 'string'),
					'author' => array('name' => lang('blockclass', 'blockclass_attachment_field_author'), 'formtype' => 'text', 'datatype' => 'string'),
					'authorid' => array('name' => lang('blockclass', 'blockclass_attachment_field_authorid'), 'formtype' => 'text', 'datatype' => 'int'),
					'dateline' => array('name'=>lang('blockclass', 'blockclass_attachment_field_dateline'), 'formtype' => 'date', 'datatype'=>'date'),
					'downloads' => array('name'=>lang('blockclass', 'blockclass_attachment_field_downloads'), 'formtype' => 'text', 'datatype'=>'int'),
					'hourdownloads' => array('name'=>lang('blockclass', 'blockclass_attachment_field_hourdownloads'), 'formtype' => 'text', 'datatype'=>'int'),
					'todaydownloads' => array('name'=>lang('blockclass', 'blockclass_attachment_field_todaydownloads'), 'formtype' => 'text', 'datatype'=>'int'),
					'weekdownloads' => array('name'=>lang('blockclass', 'blockclass_attachment_field_weekdownloads'), 'formtype' => 'text', 'datatype'=>'int'),
					'monthdownloads' => array('name'=>lang('blockclass', 'blockclass_attachment_field_monthdownloads'), 'formtype' => 'text', 'datatype'=>'int'),
					),
				'script' => array(
					'attachmentnew' => lang('blockclass', 'blockclass_attachment_script_attachmentnew'),
					'attachmentdigest' => lang('blockclass', 'blockclass_attachment_script_attachmentdigest'),
					'attachmentpic' => lang('blockclass', 'blockclass_attachment_script_attachmentpic'),
					'attachment' => lang('blockclass', 'blockclass_attachment_script_attachment'),
					)
			),
			'forum_forum' => array(
				'name' => lang('blockclass', 'blockclass_forum_forum'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_forum_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_forum_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'summary' => array('name' => lang('blockclass', 'blockclass_forum_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'icon' => array('name' => lang('blockclass', 'blockclass_forum_field_icon'), 'formtype' => 'text', 'datatype' => 'string'),
					'posts' => array('name'=>lang('blockclass', 'blockclass_forum_field_posts'), 'formtype' => 'text', 'datatype'=>'int'),
					'threads' => array('name'=>lang('blockclass', 'blockclass_forum_field_threads'), 'formtype' => 'text', 'datatype'=>'int'),
					'todayposts' => array('name'=>lang('blockclass', 'blockclass_forum_field_todayposts'), 'formtype' => 'text', 'datatype'=>'int'),
					),
				'script' => array(
					'forum' => lang('blockclass', 'blockclass_forum_script_forum'),
					)
			),
			'forum_trade' => array(
				'name' => lang('blockclass', 'blockclass_trade_trade'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_trade_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_trade_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'pic' => array('name' => lang('blockclass', 'blockclass_trade_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
					'summary' => array('name' => lang('blockclass', 'blockclass_trade_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'totalitems' => array('name' => lang('blockclass', 'blockclass_trade_field_totalitems'), 'formtype' => 'text', 'datatype' => 'int'),
					'author' => array('name' => lang('blockclass', 'blockclass_trade_field_author'), 'formtype' => 'text', 'datatype' => 'text'),
					'authorid' => array('name' => lang('blockclass', 'blockclass_trade_field_authorid'), 'formtype' => 'text', 'datatype' => 'int'),
					'price' => array('name' => lang('blockclass', 'blockclass_trade_field_price'), 'formtype' => 'text', 'datatype' => 'text'),
					),
				'script' => array(
					'tradenew' => lang('blockclass', 'blockclass_trade_script_tradenew'),
					'tradehot' => lang('blockclass', 'blockclass_trade_script_tradehot'),
					'tradespecified' => lang('blockclass', 'blockclass_trade_script_tradespecified'),
					'trade' => lang('blockclass', 'blockclass_trade_script_trade'),
					)
			),
			'forum_activity' => array(
				'name' => lang('blockclass', 'blockclass_activity_activity'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_activity_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_activity_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'pic' => array('name' => lang('blockclass', 'blockclass_activity_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
					'summary' => array('name' => lang('blockclass', 'blockclass_activity_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'time' => array('name' => lang('blockclass', 'blockclass_activity_field_time'), 'formtype' => 'text', 'datatype' => 'text'),
					'expiration' => array('name' => lang('blockclass', 'blockclass_activity_field_expiration'), 'formtype' => 'text', 'datatype' => 'text'),
					'author' => array('name' => lang('blockclass', 'blockclass_activity_field_author'), 'formtype' => 'text', 'datatype' => 'text'),
					'authorid' => array('name' => lang('blockclass', 'blockclass_activity_field_authorid'), 'formtype' => 'text', 'datatype' => 'int'),
					'cost' => array('name' => lang('blockclass', 'blockclass_activity_field_cost'), 'formtype' => 'text', 'datatype' => 'int'),
					'place' => array('name' => lang('blockclass', 'blockclass_activity_field_place'), 'formtype' => 'text', 'datatype' => 'text'),
					'class' => array('name' => lang('blockclass', 'blockclass_activity_field_class'), 'formtype' => 'text', 'datatype' => 'text'),
					'gender' => array('name' => lang('blockclass', 'blockclass_activity_field_gender'), 'formtype' => 'text', 'datatype' => 'text'),
					'number' => array('name' => lang('blockclass', 'blockclass_activity_field_number'), 'formtype' => 'text', 'datatype' => 'int'),
					'applynumber' => array('name' => lang('blockclass', 'blockclass_activity_field_applynumber'), 'formtype' => 'text', 'datatype' => 'int'),
					),
				'script' => array(
					'activitynew' => lang('blockclass', 'blockclass_activity_script_activitynew'),
					'activitycity' => lang('blockclass', 'blockclass_activity_script_activitycity'),
					'activity' => lang('blockclass', 'blockclass_activity_script_activity'),
					)
			),
		)
	),
	'group' => array(
		'name' => lang('blockclass', 'blockclass_group'),
		'subs' => array(
			'group_group' => array(
				'name' => lang('blockclass', 'blockclass_group_group'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_group_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_group_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'pic' => array('name' => lang('blockclass', 'blockclass_group_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
					'summary' => array('name' => lang('blockclass', 'blockclass_group_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'icon' => array('name' => lang('blockclass', 'blockclass_group_field_icon'), 'formtype' => 'text', 'datatype' => 'string'),
					'foundername' => array('name' => lang('blockclass', 'blockclass_group_field_foundername'), 'formtype' => 'text', 'datatype' => 'string'),
					'founderuid' => array('name' => lang('blockclass', 'blockclass_group_field_founderuid'), 'formtype' => 'text', 'datatype' => 'int'),
					'posts' => array('name' => lang('blockclass', 'blockclass_group_field_posts'), 'formtype' => 'text', 'datatype' => 'int'),
					'todayposts' => array('name' => lang('blockclass', 'blockclass_group_field_todayposts'), 'formtype' => 'text', 'datatype' => 'int'),
					'threads' => array('name' => lang('blockclass', 'blockclass_group_field_threads'), 'formtype' => 'date', 'datatype' => 'int'),
					'membernum' => array('name' => lang('blockclass', 'blockclass_group_field_membernum'), 'formtype' => 'text', 'datatype' => 'int'),
					'dateline ' => array('name' => lang('blockclass', 'blockclass_group_field_dateline'), 'formtype' => 'date', 'datatype' => 'date'),
					'level' => array('name' => lang('blockclass', 'blockclass_group_field_level'), 'formtype' => 'text', 'datatype' => 'int'),
					'commoncredits' => array('name' => lang('blockclass', 'blockclass_group_field_commoncredits'), 'formtype' => 'text', 'datatype' => 'int'),
					'activity' => array('name' => lang('blockclass', 'blockclass_group_field_activity'), 'formtype' => 'text', 'datatype' => 'int'),
					),
				'script' => array(
					'groupnew' => lang('blockclass', 'blockclass_group_script_groupnew'),
					'grouphot' => lang('blockclass', 'blockclass_group_script_grouphot'),
					'groupspecified' => lang('blockclass', 'blockclass_group_script_groupspecified'),
					'group' => lang('blockclass', 'blockclass_group_script_group')
					)
			),
			'group_thread' => array(
				'name' => lang('blockclass', 'blockclass_group_thread'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_groupthread_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_groupthread_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'pic' => array('name' => lang('blockclass', 'blockclass_groupthread_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
					'summary' => array('name' => lang('blockclass', 'blockclass_groupthread_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'author' => array('name' => lang('blockclass', 'blockclass_groupthread_field_author'), 'formtype' => 'text', 'datatype' => 'string'),
					'authorid' => array('name' => lang('blockclass', 'blockclass_groupthread_field_authorid'), 'formtype' => 'text', 'datatype' => 'int'),
					'avatar' => array('name' => lang('blockclass', 'blockclass_groupthread_field_avatar'), 'formtype' => 'text', 'datatype' => 'string'),
					'avatar_big' => array('name' => lang('blockclass', 'blockclass_groupthread_field_avatar_big'), 'formtype' => 'text', 'datatype' => 'string'),
					'posts' => array('name' => lang('blockclass', 'blockclass_groupthread_field_posts'), 'formtype' => 'text', 'datatype' => 'int'),
					'todayposts' => array('name' => lang('blockclass', 'blockclass_groupthread_field_todayposts'), 'formtype' => 'text', 'datatype' => 'int'),
					'lastpost' => array('name' => lang('blockclass', 'blockclass_groupthread_field_lastpost'), 'formtype' => 'date', 'datatype' => 'date'),
					'dateline' => array('name' => lang('blockclass', 'blockclass_groupthread_field_dateline'), 'formtype' => 'date', 'datatype' => 'date'),
					'replies' => array('name' => lang('blockclass', 'blockclass_groupthread_field_replies'), 'formtype' => 'text', 'datatype' => 'int'),
					'views' => array('name' => lang('blockclass', 'blockclass_groupthread_field_views'), 'formtype' => 'text', 'datatype' => 'int'),
					'heats' => array('name' => lang('blockclass', 'blockclass_groupthread_field_heats'), 'formtype' => 'text', 'datatype' => 'int'),
					'recommends' => array('name' => lang('blockclass', 'blockclass_groupthread_field_recommends'), 'formtype' => 'text', 'datatype' => 'int'),
					'groupname' => array('name' => lang('blockclass', 'blockclass_groupthread_field_groupname'), 'formtype' => 'text', 'datatype' => 'string'),
					'groupurl' => array('name' => lang('blockclass', 'blockclass_groupthread_field_groupurl'), 'formtype' => 'text', 'datatype' => 'string'),
					),
				'script' => array(
					'groupthreadnew' => lang('blockclass', 'blockclass_groupthread_script_groupthreadnew'),
					'groupthreadhot' => lang('blockclass', 'blockclass_groupthread_script_groupthreadhot'),
					'groupthreadspecial' => lang('blockclass', 'blockclass_groupthread_script_groupthreadspecial'),
					'groupthreadspecified' => lang('blockclass', 'blockclass_groupthread_script_groupthreadspecified'),
					'groupthread' => lang('blockclass', 'blockclass_groupthread_script_groupthread'),
					)
			),
			'group_attachment' => array(
				'name' => lang('blockclass', 'blockclass_group_attachment'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'pic' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
					'summary' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'threadurl' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_threadurl'), 'formtype' => 'text', 'datatype' => 'text'),
					'threadsubject' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_threadsubject'), 'formtype' => 'text', 'datatype' => 'text'),
					'threadsummary' => array('name' => lang('blockclass', 'blockclass_attachment_field_threadsummary'), 'formtype' => 'text', 'datatype' => 'text'),
					'filesize' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_filesize'), 'formtype' => 'text', 'datatype' => 'string'),
					'author' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_author'), 'formtype' => 'text', 'datatype' => 'string'),
					'authorid' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_authorid'), 'formtype' => 'text', 'datatype' => 'int'),
					'dateline' => array('name'=>lang('blockclass', 'blockclass_groupattachment_field_dateline'), 'formtype' => 'date', 'datatype'=>'date'),
					'downloads' => array('name'=>lang('blockclass', 'blockclass_groupattachment_field_downloads'), 'formtype' => 'text', 'datatype'=>'int'),
					'hourdownloads' => array('name'=>lang('blockclass', 'blockclass_groupattachment_field_hourdownloads'), 'formtype' => 'text', 'datatype'=>'int'),
					'todaydownloads' => array('name'=>lang('blockclass', 'blockclass_groupattachment_field_todaydownloads'), 'formtype' => 'text', 'datatype'=>'int'),
					'weekdownloads' => array('name'=>lang('blockclass', 'blockclass_groupattachment_field_weekdownloads'), 'formtype' => 'text', 'datatype'=>'int'),
					'monthdownloads' => array('name'=>lang('blockclass', 'blockclass_groupattachment_field_monthdownloads'), 'formtype' => 'text', 'datatype'=>'int'),
					),
				'script' => array(
					'groupattachmentnew' => lang('blockclass', 'blockclass_groupattachment_script_groupattachmentnew'),
					'groupattachmentdigest' => lang('blockclass', 'blockclass_groupattachment_script_groupattachmentdigest'),
					'groupattachmentpic' => lang('blockclass', 'blockclass_groupattachment_script_groupattachmentpic'),
					'groupattachment' => lang('blockclass', 'blockclass_groupattachment_script_groupattachment'),
					)
			),
			'group_trade' => array(
				'name' => lang('blockclass', 'blockclass_group_trade'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_grouptrade_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_grouptrade_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'pic' => array('name' => lang('blockclass', 'blockclass_grouptrade_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
					'summary' => array('name' => lang('blockclass', 'blockclass_grouptrade_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'totalitems' => array('name' => lang('blockclass', 'blockclass_grouptrade_field_totalitems'), 'formtype' => 'text', 'datatype' => 'int'),
					'author' => array('name' => lang('blockclass', 'blockclass_grouptrade_field_author'), 'formtype' => 'text', 'datatype' => 'text'),
					'authorid' => array('name' => lang('blockclass', 'blockclass_grouptrade_field_authorid'), 'formtype' => 'text', 'datatype' => 'int'),
					'price' => array('name' => lang('blockclass', 'blockclass_grouptrade_field_price'), 'formtype' => 'text', 'datatype' => 'text'),
					),
				'script' => array(
					'grouptradenew' => lang('blockclass', 'blockclass_grouptrade_script_grouptradenew'),
					'grouptradehot' => lang('blockclass', 'blockclass_grouptrade_script_grouptradehot'),
					'grouptradespecified' => lang('blockclass', 'blockclass_grouptrade_script_grouptradespecified'),
					'grouptrade' => lang('blockclass', 'blockclass_grouptrade_script_grouptrade'),
					)
			),
			'group_activity' => array(
				'name' => lang('blockclass', 'blockclass_group_activity'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_groupactivity_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_groupactivity_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'pic' => array('name' => lang('blockclass', 'blockclass_groupactivity_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
					'summary' => array('name' => lang('blockclass', 'blockclass_groupactivity_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'time' => array('name' => lang('blockclass', 'blockclass_groupactivity_field_time'), 'formtype' => 'text', 'datatype' => 'text'),
					'expiration' => array('name' => lang('blockclass', 'blockclass_groupactivity_field_expiration'), 'formtype' => 'text', 'datatype' => 'text'),
					'author' => array('name' => lang('blockclass', 'blockclass_groupactivity_field_author'), 'formtype' => 'text', 'datatype' => 'text'),
					'authorid' => array('name' => lang('blockclass', 'blockclass_groupactivity_field_authorid'), 'formtype' => 'text', 'datatype' => 'int'),
					'cost' => array('name' => lang('blockclass', 'blockclass_groupactivity_field_cost'), 'formtype' => 'text', 'datatype' => 'int'),
					'place' => array('name' => lang('blockclass', 'blockclass_groupactivity_field_place'), 'formtype' => 'text', 'datatype' => 'text'),
					'class' => array('name' => lang('blockclass', 'blockclass_groupactivity_field_class'), 'formtype' => 'text', 'datatype' => 'text'),
					'gender' => array('name' => lang('blockclass', 'blockclass_groupactivity_field_gender'), 'formtype' => 'text', 'datatype' => 'text'),
					'number' => array('name' => lang('blockclass', 'blockclass_groupactivity_field_number'), 'formtype' => 'text', 'datatype' => 'int'),
					'applynumber' => array('name' => lang('blockclass', 'blockclass_groupactivity_field_applynumber'), 'formtype' => 'text', 'datatype' => 'int'),
					),
				'script' => array(
					'groupactivitynew' => lang('blockclass', 'blockclass_groupactivity_script_groupactivitynew'),
					'groupactivitycity' => lang('blockclass', 'blockclass_groupactivity_script_groupactivitycity'),
					'groupactivity' => lang('blockclass', 'blockclass_groupactivity_script_groupactivity'),
					)
			),
		)
	),
	'portal' => array(
		'name' => lang('blockclass', 'blockclass_group'),
		'subs' => array(
			'portal_article' => array(
				'name' => lang('blockclass', 'blockclass_portal_article'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_article_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_article_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'pic' => array('name' => lang('blockclass', 'blockclass_article_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
					'summary' => array('name' => lang('blockclass', 'blockclass_article_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'dateline' => array('name' => lang('blockclass', 'blockclass_article_field_dateline'), 'formtype' => 'date', 'datatype' => 'date'),
					'caturl' => array('name' => lang('blockclass', 'blockclass_article_field_caturl'), 'formtype' => 'text', 'datatype' => 'string'),
					'catname' => array('name' => lang('blockclass', 'blockclass_article_field_catname'), 'formtype' => 'text', 'datatype' => 'string'),
					'viewnum' => array('name' => lang('blockclass', 'blockclass_article_field_viewnum'), 'formtype' => 'text', 'datatype' => 'int'),
					'commentnum' => array('name' => lang('blockclass', 'blockclass_article_field_commentnum'), 'formtype' => 'text', 'datatype' => 'int'),
					),
				'script' => array(
					'articlenew' => lang('blockclass', 'blockclass_article_script_articlenew'),
					'articlehot' => lang('blockclass', 'blockclass_article_script_articlehot'),
					'articlespecified' => lang('blockclass', 'blockclass_article_script_articlespecified'),
					'article' => lang('blockclass', 'blockclass_article_script_article'),
					),
			),
			'portal_topic' => array(
				'name' => lang('blockclass', 'blockclass_portal_topic'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_topic_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_topic_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'pic' => array('name' => lang('blockclass', 'blockclass_topic_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
					'summary' => array('name' => lang('blockclass', 'blockclass_topic_field_title'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'uid' => array('name' => lang('blockclass', 'blockclass_topic_field_uid'), 'formtype' => 'text', 'datatype' => 'int'),
					'username' => array('name' => lang('blockclass', 'blockclass_topic_field_username'), 'formtype' => 'text', 'datatype' => 'string'),
					'dateline' => array('name' => lang('blockclass', 'blockclass_topic_field_dateline'), 'formtype' => 'date', 'datatype' => 'date'),
					'viewnum' => array('name' => lang('blockclass', 'blockclass_topic_field_viewnum'), 'formtype' => 'text', 'datatype' => 'int'),
					),
				'script' => array(
					'topicnew' => lang('blockclass', 'blockclass_topic_script_topicnew'),
					'topichot' => lang('blockclass', 'blockclass_topic_script_topichot'),
					'topicspecified' => lang('blockclass', 'blockclass_topic_script_topicspecified'),
					'topic' => lang('blockclass', 'blockclass_topic_script_topic'),
					),
			),
			'portal_category' => array(
				'name' => lang('blockclass', 'blockclass_portal_category'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_category_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_category_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'articles' => array('name' => lang('blockclass', 'blockclass_category_field_articles'), 'formtype' => 'text', 'datatype' => 'int'),
					),
				'script' => array(
					'portalcategory' => lang('blockclass', 'blockclass_category_script_portalcategory'),
					),
			),
		)
	),
	'space' => array(
		'name' => lang('blockclass', 'blockclass_space'),
		'subs' => array(
			'space_doing' => array(
				'name' => lang('blockclass', 'blockclass_space_doing'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_doing_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_doing_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'uid' => array('name' => lang('blockclass', 'blockclass_doing_field_uid'), 'formtype' => 'text', 'datatype' => 'pic'),
					'username' => array('name' => lang('blockclass', 'blockclass_doing_field_username'), 'formtype' => 'text', 'datatype' => 'string'),
					'avatar' => array('name' => lang('blockclass', 'blockclass_doing_field_avatar'), 'formtype' => 'text', 'datatype' => 'string'),
					'avatar_big' => array('name' => lang('blockclass', 'blockclass_doing_field_avatar_big'), 'formtype' => 'text', 'datatype' => 'string'),
					'dateline' => array('name' => lang('blockclass', 'blockclass_doing_field_dateline'), 'formtype' => 'date', 'datatype' => 'date'),
					'replynum' => array('name' => lang('blockclass', 'blockclass_doing_field_replynum'), 'formtype' => 'text', 'datatype' => 'int'),
					),
				'script' => array(
					'doingnew' => lang('blockclass', 'blockclass_doing_script_doingnew'),
					'doinghot' => lang('blockclass', 'blockclass_doing_script_doinghot'),
					'doing' => lang('blockclass', 'blockclass_doing_script_doing'),
					),
			),
			'space_blog' => array(
				'name' => lang('blockclass', 'blockclass_space_blog'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_blog_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_blog_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'summary' => array('name' => lang('blockclass', 'blockclass_blog_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'pic' => array('name' => lang('blockclass', 'blockclass_blog_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
					'dateline' => array('name' => lang('blockclass', 'blockclass_blog_field_dateline'), 'formtype' => 'date', 'datatype' => 'date'),
					'uid' => array('name' => lang('blockclass', 'blockclass_blog_field_uid'), 'formtype' => 'text', 'datatype' => 'int'),
					'username' => array('name' => lang('blockclass', 'blockclass_blog_field_username'), 'formtype' => 'text', 'datatype' => 'string'),
					'replynum' => array('name' => lang('blockclass', 'blockclass_blog_field_replynum'), 'formtype' => 'text', 'datatype' => 'int'),
					'viewnum' => array('name' => lang('blockclass', 'blockclass_blog_field_viewnum'), 'formtype' => 'text', 'datatype' => 'int'),
					'click1' => array('name' => lang('blockclass', 'blockclass_blog_field_click1'), 'formtype' => 'text', 'datatype' => 'int'),
					'click2' => array('name' => lang('blockclass', 'blockclass_blog_field_click2'), 'formtype' => 'text', 'datatype' => 'int'),
					'click3' => array('name' => lang('blockclass', 'blockclass_blog_field_click3'), 'formtype' => 'text', 'datatype' => 'int'),
					'click4' => array('name' => lang('blockclass', 'blockclass_blog_field_click4'), 'formtype' => 'text', 'datatype' => 'int'),
					'click5' => array('name' => lang('blockclass', 'blockclass_blog_field_click5'), 'formtype' => 'text', 'datatype' => 'int'),
					'click6' => array('name' => lang('blockclass', 'blockclass_blog_field_click6'), 'formtype' => 'text', 'datatype' => 'int'),
					'click7' => array('name' => lang('blockclass', 'blockclass_blog_field_click7'), 'formtype' => 'text', 'datatype' => 'int'),
					'click8' => array('name' => lang('blockclass', 'blockclass_blog_field_click8'), 'formtype' => 'text', 'datatype' => 'int'),
					),
				'script' => array(
					'blognew' => lang('blockclass', 'blockclass_blog_script_blognew'),
					'bloghot' => lang('blockclass', 'blockclass_blog_script_bloghot'),
					'blogspecified' => lang('blockclass', 'blockclass_blog_script_blogspecified'),
					'blog' => lang('blockclass', 'blockclass_blog_script_blog'),
					),
			),
			'space_album' => array(
				'name' => lang('blockclass', 'blockclass_space_album'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_album_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_album_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'pic' => array('name' => lang('blockclass', 'blockclass_album_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
					'uid' => array('name' => lang('blockclass', 'blockclass_album_field_uid'), 'formtype' => 'text', 'datatype' => 'int'),
					'username' => array('name' => lang('blockclass', 'blockclass_album_field_username'), 'formtype' => 'text', 'datatype' => 'string'),
					'dateline' => array('name' => lang('blockclass', 'blockclass_album_field_dateline'), 'formtype' => 'date', 'datatype' => 'date'),
					'updatetime' => array('name' => lang('blockclass', 'blockclass_album_field_updatetime'), 'formtype' => 'date', 'datatype' => 'date'),
					'picnum' => array('name' => lang('blockclass', 'blockclass_album_field_picnum'), 'formtype' => 'text', 'datatype' => 'int'),
					),
				'script' => array(
					'albumnew' => lang('blockclass', 'blockclass_album_script_albumnew'),
					'albumspecified' => lang('blockclass', 'blockclass_album_script_albumspecified'),
					'album' => lang('blockclass', 'blockclass_album_script_album'),
					),
			),
			'space_pic' => array(
				'name' => lang('blockclass', 'blockclass_space_pic'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_pic_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_pic_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'pic' => array('name' => lang('blockclass', 'blockclass_pic_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
					'summary' => array('name' => lang('blockclass', 'blockclass_pic_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'uid' => array('name' => lang('blockclass', 'blockclass_pic_field_uid'), 'formtype' => 'text', 'datatype' => 'int'),
					'username' => array('name' => lang('blockclass', 'blockclass_pic_field_username'), 'formtype' => 'text', 'datatype' => 'string'),
					'dateline' => array('name' => lang('blockclass', 'blockclass_pic_field_dateline'), 'formtype' => 'date', 'datatype' => 'date'),
					'viewnum' => array('name' => lang('blockclass', 'blockclass_pic_field_viewnum'), 'formtype' => 'text', 'datatype' => 'int'),
					'click1' => array('name' => lang('blockclass', 'blockclass_pic_field_click1'), 'formtype' => 'text', 'datatype' => 'int'),
					'click2' => array('name' => lang('blockclass', 'blockclass_pic_field_click2'), 'formtype' => 'text', 'datatype' => 'int'),
					'click3' => array('name' => lang('blockclass', 'blockclass_pic_field_click3'), 'formtype' => 'text', 'datatype' => 'int'),
					'click4' => array('name' => lang('blockclass', 'blockclass_pic_field_click4'), 'formtype' => 'text', 'datatype' => 'int'),
					'click5' => array('name' => lang('blockclass', 'blockclass_pic_field_click5'), 'formtype' => 'text', 'datatype' => 'int'),
					'click6' => array('name' => lang('blockclass', 'blockclass_pic_field_click6'), 'formtype' => 'text', 'datatype' => 'int'),
					'click7' => array('name' => lang('blockclass', 'blockclass_pic_field_click7'), 'formtype' => 'text', 'datatype' => 'int'),
					'click8' => array('name' => lang('blockclass', 'blockclass_pic_field_click8'), 'formtype' => 'text', 'datatype' => 'int'),
					),
				'script' => array(
					'picnew' => lang('blockclass', 'blockclass_pic_script_picnew'),
					'pichot' => lang('blockclass', 'blockclass_pic_script_pichot'),
					'picspecified' => lang('blockclass', 'blockclass_pic_script_picspecified'),
					'pic' => lang('blockclass', 'blockclass_pic_script_pic'),
					),
			),
		)
	),
	'member' => array(
		'name' => lang('blockclass', 'blockclass_member'),
		'subs' => array(
			'member_member' => array(
				'name' => lang('blockclass', 'blockclass_member_member'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_member_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_member_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'avatar' => array('name' => lang('blockclass', 'blockclass_member_field_avatar'), 'formtype' => 'text', 'datatype' => 'string'),
					'avatar_big' => array('name' => lang('blockclass', 'blockclass_member_field_avatar_big'), 'formtype' => 'text', 'datatype' => 'string'),
					'regdate' => array('name' => lang('blockclass', 'blockclass_member_field_regdate'), 'formtype' => 'date', 'datatype' => 'date'),
					'posts' => array('name' => lang('blockclass', 'blockclass_member_field_posts'), 'formtype' => 'text', 'datatype' => 'int'),
					'digestposts' => array('name' => lang('blockclass', 'blockclass_member_field_digestposts'), 'formtype' => 'text', 'datatype' => 'int'),
					'credits' => array('name' => lang('blockclass', 'blockclass_member_field_credits'), 'formtype' => 'text', 'datatype' => 'int'),
					'extcredits1' => array('name' => lang('blockclass', 'blockclass_member_field_extcredits1'), 'formtype' => 'text', 'datatype' => 'int'),
					'extcredits2' => array('name' => lang('blockclass', 'blockclass_member_field_extcredits2'), 'formtype' => 'text', 'datatype' => 'int'),
					'extcredits3' => array('name' => lang('blockclass', 'blockclass_member_field_extcredits3'), 'formtype' => 'text', 'datatype' => 'int'),
					'extcredits4' => array('name' => lang('blockclass', 'blockclass_member_field_extcredits4'), 'formtype' => 'text', 'datatype' => 'int'),
					'extcredits5' => array('name' => lang('blockclass', 'blockclass_member_field_extcredits5'), 'formtype' => 'text', 'datatype' => 'int'),
					'extcredits6' => array('name' => lang('blockclass', 'blockclass_member_field_extcredits6'), 'formtype' => 'text', 'datatype' => 'int'),
					'extcredits7' => array('name' => lang('blockclass', 'blockclass_member_field_extcredits7'), 'formtype' => 'text', 'datatype' => 'int'),
					'extcredits8' => array('name' => lang('blockclass', 'blockclass_member_field_extcredits8'), 'formtype' => 'text', 'datatype' => 'int'),
					'gender' => array('name' => lang('blockclass', 'blockclass_member_field_gender'), 'formtype' => 'text', 'datatype' => 'text'),
					'birthday' => array('name' => lang('blockclass', 'blockclass_member_field_birthday'), 'formtype' => 'text', 'datatype' => 'text'),
					'constellation' => array('name' => lang('blockclass', 'blockclass_member_field_constellation'), 'formtype' => 'text', 'datatype' => 'text'),
					'zodiac' => array('name' => lang('blockclass', 'blockclass_member_field_zodiac'), 'formtype' => 'text', 'datatype' => 'text'),
					'telephone' => array('name' => lang('blockclass', 'blockclass_member_field_telephone'), 'formtype' => 'text', 'datatype' => 'text'),
					'mobile' => array('name' => lang('blockclass', 'blockclass_member_field_mobile'), 'formtype' => 'text', 'datatype' => 'text'),
					'idcardtype' => array('name' => lang('blockclass', 'blockclass_member_field_idcardtype'), 'formtype' => 'text', 'datatype' => 'text'),
					'idcard' => array('name' => lang('blockclass', 'blockclass_member_field_idcard'), 'formtype' => 'text', 'datatype' => 'text'),
					'address' => array('name' => lang('blockclass', 'blockclass_member_field_address'), 'formtype' => 'text', 'datatype' => 'text'),
					'zipcode' => array('name' => lang('blockclass', 'blockclass_member_field_zipcode'), 'formtype' => 'text', 'datatype' => 'text'),
					'nationality' => array('name' => lang('blockclass', 'blockclass_member_field_nationality'), 'formtype' => 'text', 'datatype' => 'text'),
					'birthcity' => array('name' => lang('blockclass', 'blockclass_member_field_birthcity'), 'formtype' => 'text', 'datatype' => 'text'),
					'residecity' => array('name' => lang('blockclass', 'blockclass_member_field_residecity'), 'formtype' => 'text', 'datatype' => 'text'),
					'residedist' => array('name' => lang('blockclass', 'blockclass_member_field_residedist'), 'formtype' => 'text', 'datatype' => 'text'),
					'residecommunity' => array('name' => lang('blockclass', 'blockclass_member_field_residecommunity'), 'formtype' => 'text', 'datatype' => 'text'),
					'residesuite' => array('name' => lang('blockclass', 'blockclass_member_field_residesuite'), 'formtype' => 'text', 'datatype' => 'text'),
					'graduateschool' => array('name' => lang('blockclass', 'blockclass_member_field_graduateschool'), 'formtype' => 'text', 'datatype' => 'text'),
					'education' => array('name' => lang('blockclass', 'blockclass_member_field_education'), 'formtype' => 'text', 'datatype' => 'text'),
					'occupation' => array('name' => lang('blockclass', 'blockclass_member_field_occupation'), 'formtype' => 'text', 'datatype' => 'text'),
					'company' => array('name' => lang('blockclass', 'blockclass_member_field_company'), 'formtype' => 'text', 'datatype' => 'text'),
					'position' => array('name' => lang('blockclass', 'blockclass_member_field_position'), 'formtype' => 'text', 'datatype' => 'text'),
					'revenue' => array('name' => lang('blockclass', 'blockclass_member_field_revenue'), 'formtype' => 'text', 'datatype' => 'text'),
					'affectivestatus' => array('name' => lang('blockclass', 'blockclass_member_field_affectivestatus'), 'formtype' => 'text', 'datatype' => 'text'),
					'lookingfor' => array('name' => lang('blockclass', 'blockclass_member_field_lookingfor'), 'formtype' => 'text', 'datatype' => 'text'),
					'bloodtype' => array('name' => lang('blockclass', 'blockclass_member_field_bloodtype'), 'formtype' => 'text', 'datatype' => 'text'),
					'height' => array('name' => lang('blockclass', 'blockclass_member_field_height'), 'formtype' => 'text', 'datatype' => 'text'),
					'weight' => array('name' => lang('blockclass', 'blockclass_member_field_weight'), 'formtype' => 'text', 'datatype' => 'text'),
					'alipay' => array('name' => lang('blockclass', 'blockclass_member_field_alipay'), 'formtype' => 'text', 'datatype' => 'text'),
					'icq' => array('name' => lang('blockclass', 'blockclass_member_field_icq'), 'formtype' => 'text', 'datatype' => 'text'),
					'qq' => array('name' => lang('blockclass', 'blockclass_member_field_qq'), 'formtype' => 'text', 'datatype' => 'text'),
					'yahoo' => array('name' => lang('blockclass', 'blockclass_member_field_yahoo'), 'formtype' => 'text', 'datatype' => 'text'),
					'msn' => array('name' => lang('blockclass', 'blockclass_member_field_msn'), 'formtype' => 'text', 'datatype' => 'text'),
					'taobao' => array('name' => lang('blockclass', 'blockclass_member_field_taobao'), 'formtype' => 'text', 'datatype' => 'text'),
					'site' => array('name' => lang('blockclass', 'blockclass_member_field_site'), 'formtype' => 'text', 'datatype' => 'text'),
					'bio' => array('name' => lang('blockclass', 'blockclass_member_field_bio'), 'formtype' => 'text', 'datatype' => 'text'),
					'interest' => array('name' => lang('blockclass', 'blockclass_member_field_interest'), 'formtype' => 'text', 'datatype' => 'text'),
					'field1' => array('name' => lang('blockclass', 'blockclass_member_field_field1'), 'formtype' => 'text', 'datatype' => 'text'),
					'field2' => array('name' => lang('blockclass', 'blockclass_member_field_field2'), 'formtype' => 'text', 'datatype' => 'text'),
					'field3' => array('name' => lang('blockclass', 'blockclass_member_field_field3'), 'formtype' => 'text', 'datatype' => 'text'),
					'field4' => array('name' => lang('blockclass', 'blockclass_member_field_field4'), 'formtype' => 'text', 'datatype' => 'text'),
					'field5' => array('name' => lang('blockclass', 'blockclass_member_field_field5'), 'formtype' => 'text', 'datatype' => 'text'),
					'field6' => array('name' => lang('blockclass', 'blockclass_member_field_field6'), 'formtype' => 'text', 'datatype' => 'text'),
					'field7' => array('name' => lang('blockclass', 'blockclass_member_field_field7'), 'formtype' => 'text', 'datatype' => 'text'),
					'field8' => array('name' => lang('blockclass', 'blockclass_member_field_field8'), 'formtype' => 'text', 'datatype' => 'text'),
					),
				'script' => array(
					'membernew' => lang('blockclass', 'blockclass_member_script_membernew'),
					'membercredit' => lang('blockclass', 'blockclass_member_script_membercredit'),
					'membershow' => lang('blockclass', 'blockclass_member_script_membershow'),
					'memberposts' => lang('blockclass', 'blockclass_member_script_memberposts'),
					'memberspecified' => lang('blockclass', 'blockclass_member_script_memberspecified'),
					'member' => lang('blockclass', 'blockclass_member_script_member'),
					),
			),
		)
	),
	'html' => array(
		'name' => lang('blockclass', 'blockclass_html'),
		'subs' => array(
			'html_html' => array(
				'name' => lang('blockclass', 'blockclass_html_html'),
				'fields' => array(
					),
				'script' => array(
					'blank' => lang('blockclass', 'blockclass_html_script_blank'),
					'line' => lang('blockclass', 'blockclass_html_script_line'),
					'banner' => lang('blockclass', 'blockclass_html_script_banner'),
					'vedio' => lang('blockclass', 'blockclass_html_script_vedio'),
					'google' => lang('blockclass', 'blockclass_html_script_google'),
					'stat' => lang('blockclass', 'blockclass_html_script_stat'),
					'forumtree' => lang('blockclass', 'blockclass_html_script_forumtree'),
					'friendlink' => lang('blockclass', 'blockclass_html_script_friendlink'),
					'adv' => lang('blockclass', 'blockclass_html_script_adv'),
					'sort' => lang('blockclass', 'blockclass_html_script_sort'),
					),
			),
			'html_announcement' => array(
				'name' => lang('blockclass', 'blockclass_html_announcement'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_announcement_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_announcement_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'summary' => array('name' => lang('blockclass', 'blockclass_announcement_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'starttime' => array('name' => lang('blockclass', 'blockclass_announcement_field_starttime'), 'formtype' => 'text', 'datatype' => 'string'),
					'endtime' => array('name' => lang('blockclass', 'blockclass_announcement_field_endtime'), 'formtype' => 'text', 'datatype' => 'string'),
					),
				'script' => array(
					'announcement' => lang('blockclass', 'blockclass_announcement_script_announcement'),
					),
			),
			'html_myapp' => array(
				'name' => lang('blockclass', 'blockclass_html_myapp'),
				'fields' => array(
					'url' => array('name' => lang('blockclass', 'blockclass_myapp_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_myapp_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'icon' => array('name' => lang('blockclass', 'blockclass_myapp_field_icon'), 'formtype' => 'text', 'datatype' => 'string'),
					'icon_small' => array('name' => lang('blockclass', 'blockclass_myapp_field_icon_small'), 'formtype' => 'text', 'datatype' => 'string'),
					),
				'script' => array(
					'myapp' => lang('blockclass', 'blockclass_myapp_script_myapp'),
					),
			)
		)
	),
);

$extradir = DISCUZ_ROOT.'./source/include/portal';
$extradirhandle = dir($extradir);
while($entry = $extradirhandle->read()) {
	if(!in_array($entry, array('.', '..')) && preg_match("/^portal_blockclass\_([\w\.]+)$/", $entry, $entryr) && substr($entry, -4) == '.php' && strlen($entry) < 30 && is_file($extradir.'/'.$entry)) {
		@include_once $extradir.'/'.$entry;
	}
}

?>
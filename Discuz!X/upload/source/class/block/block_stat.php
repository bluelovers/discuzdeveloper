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

class block_stat {

	function getsetting() {
		global $_G;
		$settings = array(
			'option' => array(
				'title' => 'stat_option',
				'type' => 'mcheckbox',
				'value' => array(
					array('posts', 'stat_option_posts'),
					array('groups', 'stat_option_groups'),
					array('members', 'stat_option_members'),
					array('groupmembers', 'stat_option_groupmembers'),
					array('groupnewposts', 'stat_option_groupnewposts'),
					array('bbsnewposts', 'stat_option_bbsnewposts'),
					array('bbslastposts', 'stat_option_bbslastposts'),
				),
				'default' => array('posts', 'groups', 'members')
			)
		);
		return $settings;
	}

	function getdata($style, $parameter) {
		global $_G;
		if(in_array('posts', $parameter['option']) || in_array('bbsnewposts', $parameter['option'])) {
			$sql = !empty($_G['member']['accessmasks']) ?
			"SELECT  sum(f.posts) AS posts, sum(f.todayposts) AS todayposts FROM ".DB::table('forum_forum')." f
				LEFT JOIN ".DB::table('forum_access')." a ON a.uid='$_G[uid]' AND a.fid=f.fid
				WHERE f.status IN ('1','2')"
			: "SELECT sum(f.posts) AS posts, sum(f.todayposts) AS todayposts FROM ".DB::table('forum_forum')." f
				WHERE f.status IN ('1','2')";
			$forum = DB::fetch_first($sql);
		}
		if(in_array('groups', $parameter['option']) || in_array('groupmembers', $parameter['option']) || in_array('groupnewposts', $parameter['option'])) {
			loadcache('groupindex');
		}
		$index = count($parameter['option']) - 1;
		$html = '<div class="tns"><table cellspacing="0" cellpadding="4" border="0"><tbody><tr>';
		if(in_array('posts', $parameter['option'])) {
			$class = ($index-- == 0) ? ' class="bbn"' : '';
			$html .= "<th$class><p>".intval($forum['posts']).'</p>'.lang('block/stat', 'stat_posts').'</th>';
		}
		if(in_array('groups', $parameter['option'])) {
			$class = ($index-- == 0) ? ' class="bbn"' : '';
		    $html .= "<th$class><p>".intval($_G['cache']['groupindex']['groupnum']).'</p>'.lang('block/stat', 'stat_groups').'</th>';
		}
		if(in_array('members', $parameter['option'])) {
			loadcache('userstats');
			$class = ($index-- == 0) ? ' class="bbn"' : '';
			$html .= "<th$class><p>".intval($_G['cache']['userstats']['totalmembers']).'</p>'.lang('block/stat', 'stat_members').'</th>';
		}
		if(in_array('groupmembers', $parameter['option'])) {
			$class = ($index-- == 0) ? ' class="bbn"' : '';
			$html .= "<th$class><p>".intval($_G['cache']['groupindex']['joinnum']).'</p>'.lang('block/stat', 'stat_groupmembers').'</th>';
		}
		if(in_array('groupnewposts', $parameter['option'])) {
			$class = ($index-- == 0) ? ' class="bbn"' : '';
			$html .= "<th$class><p>".intval($_G['cache']['groupindex']['todayposts']).'</p>'.lang('block/stat', 'stat_groupnewposts').'</th>';
		}
		if(in_array('bbsnewposts', $parameter['option'])) {
			$class = ($index-- == 0) ? ' class="bbn"' : '';
			$html .= "<th$class><p>".intval($forum['todayposts']).'</p>'.lang('block/stat', 'stat_bbsnewposts').'</th>';
		}
		if(in_array('bbslastposts', $parameter['option'])) {
			loadcache('historyposts');
			$postdata = $_G['cache']['historyposts'] ? explode("\t", $_G['cache']['historyposts']) : array();
			$class = ($index-- == 0) ? ' class="bbn"' : '';
			$html .= "<th$class><p>".intval($postdata[0]).'</p>'.lang('block/stat', 'stat_bbslastposts').'</th>';
		}
		$html .= '</tr></tbody></table></div>';
		return array('html' => $html, 'data' => null);
	}
}

?>
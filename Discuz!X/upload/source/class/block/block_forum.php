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
class block_forum {
	var $setting = array();
	function block_forum() {
		$this->setting = array(
			'fups'	=> array(
				'title' => 'forumlist_fups',
				'type' => 'mselect',
				'value' => array()
			),
			'titlelength' => array(
				'title' => 'forumlist_titlelength',
				'type' => 'text',
				'default' => 40
			),
			'summarylength' => array(
				'title' => 'forumlist_summarylength',
				'type' => 'text',
				'default' => 80
			),
			'orderby' => array(
				'title' => 'forumlist_orderby',
				'type' => 'mradio',
				'value' => array(
					array('displayorder', 'forumlist_orderby_displayorder'),
					array('threads', 'forumlist_orderby_threads'),
					array('todayposts', 'forumlist_orderby_todayposts'),
					array('posts', 'forumlist_orderby_posts')
				),
				'default' => 'displayorder'
			)
		);
	}

	function getsetting() {
		global $_G;

		$settings = $this->setting;
		loadcache('forums');
		$settings['fups']['value'][] = array(0, lang('portalcp', 'block_all_forum'));
		if(empty($_G['cache']['forums'])) $_G['cache']['forums'] = array();
		foreach($_G['cache']['forums'] as $fid => $forum) {
			$settings['fups']['value'][] = array($fid, ($forum['type'] == 'forum' ? str_repeat('&nbsp;', 4) : ($forum['type'] == 'sub' ? str_repeat('&nbsp;', 8) : '')).$forum['name']);
		}
		return $settings;
	}

	function cookparameter($parameter) {
		return $parameter;
	}

	function getdata($style, $parameter) {
		global $_G;

		$parameter = $this->cookparameter($parameter);
		$fups		= isset($parameter['fups']) && !in_array(0, (array)$parameter['fups']) ? $parameter['fups'] : '';
		$orderby	= isset($parameter['orderby']) ? (in_array($parameter['orderby'],array('displayorder','threads','posts', 'todayposts')) ? $parameter['orderby'] : 'displayorder') : 'displayorder';
		$titlelength = isset($parameter['titlelength']) ? intval($parameter['titlelength']) : 40;
		$summarylength = isset($parameter['summarylength']) ? intval($parameter['summarylength']) : 80;

		$bannedids = !empty($parameter['bannedids']) ? explode(',', $parameter['bannedids']) : array();
		$sqlban = !empty($bannedids) ? ' AND f.fid NOT IN ('.dimplode($bannedids).')' : '';

		$ffadd1 = ", ff.icon, ff.description";
		$ffadd2 = "LEFT JOIN `".DB::table('forum_forumfield')."` ff ON f.`fid`=ff.`fid`";
		$query = DB::query("SELECT f.* $ffadd1
			FROM `".DB::table('forum_forum')."` f $ffadd2
			WHERE f.`type`!='group'
			".($fups ? "AND f.`fup` IN (".dimplode($fups).") " : "")."
			AND f.`status`='1'
			$sqlban
			ORDER BY ".($orderby == 'f.displayorder' ? " `f.displayorder` ASC " : " `$orderby` DESC")
		);
		$datalist = $list = array();
		$attachurl = preg_match('/^(http|ftp|ftps|https):\/\//', $_G['setting']['attachurl']) ? $_G['setting']['attachurl'] : $_G['setting']['attachurl'];
		while($data = DB::fetch($query)) {
			$data['icon'] = $data['icon'] ? $data['icon'] : 'static/image/common/forum_new.gif';
			$list[] = array(
				'id' => $data['fid'],
				'idtype' => 'fid',
				'title' => cutstr($data['name'], $titlelength),
				'url' => 'forum.php?mod=forumdisplay&fid='.$data['fid'],
				'pic' => '',
				'summary' => cutstr($data['description'], $summarylength),
				'fields' => array(
					'icon' => preg_match('/^(http|ftp|ftps|https):\/\//', $data['icon']) ? $data['icon'] : $attachurl.'common/'.$data['icon'],
					'threads' => intval($data['threads']),
					'posts' => intval($data['posts']),
					'todayposts' => intval($data['todayposts'])
				)
			);
		}
		return array('html' => '', 'data' => $list);
	}
}

?>
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
class block_doing {
	var $setting = array();
	function block_doing() {
		$this->setting = array(
			'uids'	=> array(
				'title' => 'doinglist_uids',
				'type' => 'text',
				'value' => ''
			),
			'titlelength' => array(
				'title' => 'doinglist_titlelength',
				'type' => 'text',
				'default' => 40
			),
			'orderby' => array(
				'title' => 'doinglist_orderby',
				'type' => 'mradio',
				'value' => array(
					array('dateline', 'doinglist_orderby_dateline'),
					array('replynum', 'doinglist_orderby_replynum')
				),
				'default' => 'dateline'
			),
			'startrow' => array(
				'title' => 'doinglist_startrow',
				'type' => 'text',
				'default' => 0
			),
		);
	}

	function getsetting() {
		global $_G;
		$settings = $this->setting;

		return $settings;
	}

	function cookparameter($parameter) {
		return $parameter;
	}

	function getdata($style, $parameter) {
		global $_G;

		$parameter = $this->cookparameter($parameter);
		$uids		= isset($parameter['uids']) && !in_array(0, (array)$parameter['uids']) ? $parameter['uids'] : '';
		$startrow	= isset($parameter['startrow']) ? intval($parameter['startrow']) : 0;
		$items		= isset($parameter['items']) ? intval($parameter['items']) : 10;
		$titlelength = $parameter['titlelength'] ? intval($parameter['titlelength']) : 40;
		$orderby	= isset($parameter['orderby']) && in_array($parameter['orderby'],array('dateline', 'replynum')) ? $parameter['orderby'] : 'dateline';

		$bannedids = !empty($parameter['bannedids']) ? explode(',', $parameter['bannedids']) : array();

		$list = array();
		$wheres = array();
		if($uids) {
			$wheres[] = 'uid IN ('.dimplode($uids).')';
		}
		if($bannedids) {
			$wheres[] = 'doid NOT IN ('.dimplode($bannedids).')';
		}
		$wheresql = $wheres ? implode(' AND ', $wheres) : '1';
		$query = DB::query("SELECT * FROM ".DB::table('home_doing')." WHERE $wheresql ORDER BY $orderby DESC LIMIT $startrow,$items");
		while($data = DB::fetch($query)) {
			$list[] = array(
				'id' => $data['doid'],
				'idtype' => 'doid',
				'title' => cutstr(strip_tags($data['message']), $titlelength),
				'url' => 'home.php?mod=space&do=doing&uid='.$data['uid'],
				'pic' => '',
				'summary' => '',
				'fields' => array(
					'uid' => $data['uid'],
					'username' => $data['username'],
					'avatar' => avatar($data['uid'], 'small', true, false, $_G['setting']['avatarmethod'], $_G['setting']['ucenterurl']),
					'avatar_big' => avatar($data['uid'], 'middle', true, false, $_G['setting']['avatarmethod'], $_G['setting']['ucenterurl']),
					'dateline'=>$data['dateline'],
					'replynum'=>$data['replynum'],
				)
			);
		}
		return array('html' => '', 'data' => $list);
	}
}

?>
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
class block_pic {
	var $setting = array();
	function block_pic() {
		$this->setting = array(
			'picids'	=> array(
				'title' => 'piclist_picids',
				'type' => 'text',
				'value' => ''
			),
			'uids'	=> array(
				'title' => 'piclist_uids',
				'type' => 'text',
				'value' => ''
			),
			'aids'	=> array(
				'title' => 'piclist_aids',
				'type' => 'text',
				'value' => ''
			),
			'titlelength' => array(
				'title' => 'piclist_titlelength',
				'type' => 'text',
				'default' => 40
			),
			'orderby' => array(
				'title' => 'piclist_orderby',
				'type' => 'mradio',
				'value' => array(
					array('dateline', 'piclist_orderby_dateline'),
					array('hot', 'piclist_orderby_hot')
				),
				'default' => 'dateline'
			),
			'hours' => array(
				'title' => 'piclist_hours',
				'type' => 'mradio',
				'value' => array(
					array('', 'piclist_hours_nolimit'),
					array('1', 'piclist_hours_hour'),
					array('24', 'piclist_hours_day'),
					array('168', 'piclist_hours_week'),
					array('720', 'piclist_hours_month'),
					array('8760', 'piclist_hours_year'),
				),
				'default' => ''
			),
			'startrow' => array(
				'title' => 'piclist_startrow',
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
		$picids		= !empty($parameter['picids']) ? explode(',', $parameter['picids']) : array();
		$uids		= !empty($parameter['uids']) ? explode(',', $parameter['uids']) : array();
		$aids		= !empty($parameter['aids']) ? explode(',', $parameter['aids']) : array();
		$startrow	= isset($parameter['startrow']) ? intval($parameter['startrow']) : 0;
		$items		= isset($parameter['items']) ? intval($parameter['items']) : 10;
		$hours		= isset($parameter['hours']) ? intval($parameter['hours']) : '';
		$titlelength = isset($parameter['titlelength']) ? intval($parameter['titlelength']) : 40;
		$orderby	= isset($parameter['orderby']) && in_array($parameter['orderby'],array('dateline', 'viewnum', 'replynum', 'hot')) ? $parameter['orderby'] : 'dateline';

		$bannedids = !empty($parameter['bannedids']) ? explode(',', $parameter['bannedids']) : array();

		$list = array();
		$wheres = array();
		if($picids) {
			$wheres[] = 'pic.picid IN ('.dimplode($picids).')';
		}
		if($uids) {
			$wheres[] = 'pic.uid IN ('.dimplode($uids).')';
		}
		if($aids) {
			$wheres[] = 'pic.albumid IN ('.dimplode($aids).')';
		}
		if($hours) {
			$timestamp = TIMESTAMP - 3600 * $hours;
			$wheres[] = "pic.dateline >= '$timestamp'";
		}
		if($bannedids) {
			$wheres[] = 'pic.picid NOT IN ('.dimplode($bannedids).')';
		}
		$wheresql = $wheres ? implode(' AND ', $wheres) : '1';
		$sql = "SELECT pic.* FROM ".DB::table('home_pic')." pic LEFT JOIN ".DB::table('home_album')." album ON pic.albumid=album.albumid WHERE $wheresql AND album.friend='0' ORDER BY pic.$orderby DESC";
		$query = DB::query($sql." LIMIT $startrow,$items;");
		while($data = DB::fetch($query)) {
			$list[] = array(
				'id' => $data['picid'],
				'idtype' => 'picid',
				'title' => cutstr($data['title'], $titlelength),
				'url' => "home.php?mod=space&do=album&uid=$data[uid]&picid=$data[picid]",
				'pic' => 'album/'.$data['filepath'],
				'picflag' => $data['remote'] ? '2' : '1',
				'summary' => '',
				'fields' => array(
					'uid'=>$data['uid'],
					'username'=>$data['username'],
					'dateline'=>$data['dateline'],
					'replynum'=>$data['replynum'],
					'click1'=>$data['click1'],
					'click2'=>$data['click2'],
					'click3'=>$data['click3'],
					'click4'=>$data['click4'],
					'click5'=>$data['click5'],
					'click6'=>$data['click6'],
					'click7'=>$data['click7'],
					'click8'=>$data['click8'],
				)
			);
		}
		return array('html' => '', 'data' => $list);
	}
}

?>
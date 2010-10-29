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
class block_groupactivity {
	var $setting = array();

	function block_groupactivity(){
		$this->setting = array(
			'tids' => array(
				'title' => 'groupactivity_tids',
				'type' => 'text'
			),
			'uids' => array(
				'title' => 'groupactivity_uids',
				'type' => 'text'
			),
			'keyword' => array(
				'title' => 'groupactivity_keyword',
				'type' => 'text'
			),
			'fids'	=> array(
				'title' => 'groupactivity_fids',
				'type' => 'text'
			),
			'gtids' => array(
				'title' => 'groupactivity_gtids',
				'type' => 'mselect',
				'value' => array(
				),
			),
			'digest' => array(
				'title' => 'groupactivity_digest',
				'type' => 'mcheckbox',
				'value' => array(
					array(1, 'groupactivity_digest_1'),
					array(2, 'groupactivity_digest_2'),
					array(3, 'groupactivity_digest_3'),
					array(0, 'groupactivity_digest_0')
				),
			),
			'stick' => array(
				'title' => 'groupactivity_stick',
				'type' => 'mcheckbox',
				'value' => array(
					array(1, 'groupactivity_stick_1'),
					array(2, 'groupactivity_stick_2'),
					array(3, 'groupactivity_stick_3'),
					array(0, 'groupactivity_stick_0')
				),
			),
			'recommend' => array(
				'title' => 'groupactivity_recommend',
				'type' => 'radio'
			),
			'place' => array(
				'title' => 'groupactivity_place',
				'type' => 'text'
			),
			'class' => array(
				'title' => 'groupactivity_class',
				'type' => 'select',
				'value' => array()
			),
			'gender' => array(
				'title' => 'groupactivity_gender',
				'type' => 'mradio',
				'value' => array(
					array('', 'groupactivity_gender_0'),
					array('1', 'groupactivity_gender_1'),
					array('2', 'groupactivity_gender_2'),
				),
				'default' => ''
			),
			'orderby' => array(
				'title' => 'groupactivity_orderby',
				'type'=> 'mradio',
				'value' => array(
					array('dateline', 'groupactivity_orderby_dateline'),
					array('weekstart', 'groupactivity_orderby_weekstart'),
					array('monthstart', 'groupactivity_orderby_monthstart'),
					array('weekexp', 'groupactivity_orderby_weekexp'),
					array('monthexp', 'groupactivity_orderby_monthexp'),
				),
				'default' => 'dateline'
			),
			'titlelength' => array(
				'title' => 'groupactivity_titlelength',
				'type' => 'text',
				'default' => 40
			),
			'summarylength' => array(
				'title' => 'groupactivity_summarylength',
				'type' => 'text',
				'default' => 80
			),
			'startrow' => array(
				'title' => 'groupactivity_startrow',
				'type' => 'text',
				'default' => 0
			),
		);
	}

	function getsetting() {
		global $_G;
		$settings = $this->setting;

		if($settings['gtids']) {
			loadcache('grouptype');
			$settings['gtids']['value'][] = array(0, lang('portalcp', 'block_all_type'));
			foreach($_G['cache']['grouptype']['first'] as $gid=>$group) {
				$settings['gtids']['value'][] = array($gid, $group['name']);
				if($group['secondlist']) {
					foreach($group['secondlist'] as $subgid) {
						$settings['gtids']['value'][] = array($subgid, '&nbsp;&nbsp;'.$_G['cache']['grouptype']['second'][$subgid]['name']);
					}
				}
			}
		}
		$activitytype = explode("\n", $_G['setting']['activitytype']);
		$settings['class']['value'][] = array('', 'groupactivity_class_all');
		foreach($activitytype as $item) {
			$settings['class']['value'][] = array($item, $item);
		}
		return $settings;
	}

	function cookparameter($parameter) {
		return $parameter;
	}

	function getdata($style, $parameter) {
		global $_G;

		$parameter = $this->cookparameter($parameter);

		loadcache('grouptype');
		$typeids	= !empty($parameter['gtids']) && !in_array('0', $parameter['gtids']) ? $parameter['gtids'] : array_keys($_G['cache']['grouptype']['first']);
		$tids		= !empty($parameter['tids']) ? explode(',', $parameter['tids']) : array();
		$fids		= !empty($parameter['fids']) ? explode(',', $parameter['fids']) : array();
		$uids		= !empty($parameter['uids']) ? explode(',', $parameter['uids']) : array();
		$startrow	= !empty($parameter['startrow']) ? intval($parameter['startrow']) : 0;
		$items		= !empty($parameter['items']) ? intval($parameter['items']) : 10;
		$digest		= isset($parameter['digest']) ? $parameter['digest'] : 0;
		$stick		= isset($parameter['stick']) ? $parameter['stick'] : 0;
		$orderby	= isset($parameter['orderby']) ? (in_array($parameter['orderby'],array('dateline','weekstart','monthstart','weekexp','monthexp')) ? $parameter['orderby'] : 'dateline') : 'dateline';
		$titlelength	= !empty($parameter['titlelength']) ? intval($parameter['titlelength']) : 40;
		$summarylength	= !empty($parameter['summarylength']) ? intval($parameter['summarylength']) : 80;
		$recommend	= !empty($parameter['recommend']) ? 1 : 0;
		$keyword	= !empty($parameter['keyword']) ? $parameter['keyword'] : '';
		$place		= !empty($parameter['place']) ? $parameter['place'] : '';
		$class		= !empty($parameter['class']) ? $parameter['class'] : '';
		$gender		= !empty($parameter['gender']) ? intval($parameter['gender']) : '';

		$bannedids = !empty($parameter['bannedids']) ? explode(',', $parameter['bannedids']) : array();

		if(empty($fids)) {
			$plusids = $typeids ? array(0) : array();
			foreach($typeids as $typeid) {
				if(!empty($_G['cache']['grouptype']['first'][$typeid]['secondlist'])) {
					$plusids = array_merge($plusids, $_G['cache']['grouptype']['first'][$typeid]['secondlist']);
				}
			}
			$typeids = array_merge($typeids, $plusids);
			$groups = array();
			if($typeids) {
				$query = DB::query('SELECT f.fid, f.name, ff.description FROM '.DB::table('forum_forum')." f LEFT JOIN ".DB::table('forum_forumfield')." ff ON f.fid = ff.fid WHERE f.fup IN (".dimplode($typeids).")");
				while($value = DB::fetch($query)) {
					$groups[$value['fid']] = $value;
					$fids[] = intval($value['fid']);
				}
			}
		}
		require_once libfile('function/post');
		$datalist = $list = array();
		if($keyword) {
			if(preg_match("(AND|\+|&|\s)", $keyword) && !preg_match("(OR|\|)", $keyword)) {
				$andor = ' AND ';
				$keywordsrch = '1';
				$keyword = preg_replace("/( AND |&| )/is", "+", $keyword);
			} else {
				$andor = ' OR ';
				$keywordsrch = '0';
				$keyword = preg_replace("/( OR |\|)/is", "+", $keyword);
			}
			$keyword = str_replace('*', '%', addcslashes($keyword, '%_'));
			foreach(explode('+', $keyword) as $text) {
				$text = trim($text);
				if($text) {
					$keywordsrch .= $andor;
					$keywordsrch .= "t.subject LIKE '%$text%'";
				}
			}
			$keyword = " AND ($keywordsrch)";
		} else {
			$keyword = '';
		}
		$sql = ($fids ? ' AND t.fid IN ('.dimplode($fids).')' : '')
			.$keyword
			.($tids ? ' AND t.tid IN ('.dimplode($tids).')' : '')
			.($bannedids ? ' AND t.tid NOT IN ('.dimplode($bannedids).')' : '')
			.($digest ? ' AND t.digest IN ('.dimplode($digest).')' : '')
			.($stick ? ' AND t.displayorder IN ('.dimplode($stick).')' : '')
			." AND t.closed='0' AND t.isgroup='1'";
		$where = '';
		if(in_array($orderby, array('weekstart','monthstart'))) {
			$historytime = 0;
			switch($orderby) {
				case 'weekstart':
					$historytime = TIMESTAMP + 86400 * 7;
				break;
				case 'monthstart':
					$historytime = TIMESTAMP + 86400 * 30;
				break;
			}
			$where = ' WHERE a.starttimefrom >= '.TIMESTAMP.' AND a.starttimefrom<='.$historytime;
			$orderby = 'a.starttimefrom ASC';
		} elseif(in_array($orderby, array('weekexp','monthexp'))) {
			$historytime = 0;
			switch($orderby) {
				case 'weekexp':
					$historytime = TIMESTAMP + 86400 * 7;
				break;
				case 'monthexp':
					$historytime = TIMESTAMP + 86400 * 30;
				break;
			}
			$where = ' WHERE a.expiration >= '.TIMESTAMP.' AND a.expiration<='.$historytime;
			$orderby = 'a.expiration ASC';
		} else {
			$orderby = 't.dateline DESC';
		}
		$where .= $uids ? ' AND t.authorid IN ('.dimplode($uids).')' : '';
		if($gender) {
			$where .= " AND a.gender='$gender'";
		}
		$sqlfrom = " INNER JOIN `".DB::table('forum_thread')."` t ON t.tid=a.tid $sql AND t.displayorder>='0'";
		if($recommend) {
			$sqlfrom .= " INNER JOIN `".DB::table('forum_forumrecommend')."` fc ON fc.tid=tr.tid";
		}
		$query = DB::query("SELECT a.*, t.tid, t.subject, t.authorid, t.author
			FROM ".DB::table('forum_activity')." a $sqlfrom $where
			ORDER BY $orderby
			LIMIT $startrow,$items;"
			);
		include_once libfile('block/thread', 'class');
		$bt = new block_thread();
		while($data = DB::fetch($query)) {
			$data['time'] = dgmdate($data['starttimefrom']);
			if($data['starttimeto']) {
				$data['time'] .= ' - '.dgmdate($data['starttimeto']);
			}
			$list[] = array(
				'id' => $data['pid'],
				'idtype' => 'pid',
				'title' => cutstr(str_replace('\\\'', '&#39;', addslashes($data['subject'])), $titlelength),
				'url' => 'forum.php?mod=viewthread&tid='.$data['tid'],
				'pic' => ($data['aid'] ? getforumimg($data['aid']) : IMGDIR.'/nophoto.gif'),
				'picflag' => '0',
				'summary' => !empty($style['getsummary']) ? $bt->getthread($data['tid'], $summarylength, true) : '',
				'fields' => array(
					'time' => $data['time'],
					'expiration' => $data['expiration'] ? dgmdate($data['expiration']) : 'N/A',
					'author' => $data['author'] ? $data['author'] : 'Anonymous',
					'authorid' => $data['authorid'] ? $data['authorid'] : 0,
					'cost' => $data['cost'],
					'place' => $data['place'],
					'class' => $data['class'],
					'gender' => $data['gender'],
					'number' => $data['number'],
					'applynumber' => $data['applynumber'],
				)
			);
		}
		return array('html' => '', 'data' => $list);
	}
}


?>
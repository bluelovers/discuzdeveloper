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
class block_groupthread {
	var $setting = array();
	function block_groupthread(){
		$this->setting = array(
			'tids' => array(
				'title' => 'groupthread_tids',
				'type' => 'text'
			),
			'fids'	=> array(
				'title' => 'groupthread_fids',
				'type' => 'text'
			),
			'gtids' => array(
				'title' => 'groupthread_gtids',
				'type' => 'mselect',
				'value' => array(
				),
			),
			'uids' => array(
				'title' => 'groupthread_uids',
				'type' => 'text'
			),
			'special' => array(
				'title' => 'groupthread_special',
				'type' => 'mcheckbox',
				'value' => array(
					array(1, 'groupthread_special_1'),
					array(2, 'groupthread_special_2'),
					array(3, 'groupthread_special_3'),
					array(4, 'groupthread_special_4'),
					array(5, 'groupthread_special_5'),
					array(0, 'groupthread_special_0'),
				),
				'default' => array('0')
			),
			'rewardstatus' => array(
				'title' => 'groupthread_special_reward',
				'type' => 'mradio',
				'value' => array(
					array(0, 'groupthread_special_reward_0'),
					array(1, 'groupthread_special_reward_1'),
					array(2, 'groupthread_special_reward_2')
				),
				'default' => 0,
			),
			'picrequired' => array(
				'title' => 'groupthread_picrequired',
				'type' => 'radio',
				'value' => '0'
			),
			'orderby' => array(
				'title' => 'groupthread_orderby',
				'type'=> 'mradio',
				'value' => array(
					array('lastpost', 'groupthread_orderby_lastpost'),
					array('dateline', 'groupthread_orderby_dateline'),
					array('replies', 'groupthread_orderby_replies'),
					array('views', 'groupthread_orderby_views'),
					array('heats', 'groupthread_orderby_heats'),
					array('recommends', 'groupthread_orderby_recommends'),
					array('hourviews', 'groupthread_orderby_hourviews'),
					array('todayviews', 'groupthread_orderby_todayviews'),
					array('weekviews', 'groupthread_orderby_weekviews'),
					array('monthviews', 'groupthread_orderby_monthviews'),
				),
				'default' => 'lastpost'
			),
			'titlelength' => array(
				'title' => 'groupthread_titlelength',
				'type' => 'text',
				'default' => 40
			),
			'summarylength' => array(
				'title' => 'groupthread_summarylength',
				'type' => 'text',
				'default' => 80
			),
			'startrow' => array(
				'title' => 'groupthread_startrow',
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
		$startrow	= isset($parameter['startrow']) ? intval($parameter['startrow']) : 0;
		$items		= isset($parameter['items']) ? intval($parameter['items']) : 10;
		$special	= isset($parameter['special']) ? $parameter['special'] : array();
		$rewardstatus	= isset($parameter['rewardstatus']) ? intval($parameter['rewardstatus']) : 0;
		$titlelength	= !empty($parameter['titlelength']) ? intval($parameter['titlelength']) : 40;
		$summarylength	= !empty($parameter['summarylength']) ? intval($parameter['summarylength']) : 80;
		$orderby	= in_array($parameter['orderby'], array('dateline','replies','views','threads', 'heats', 'recommends', 'hourviews', 'todayviews', 'weekviews', 'monthviews')) ? $parameter['orderby'] : 'lastpost';
		$picrequired = !empty($parameter['picrequired']) ? 1 : 0;

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
		$threadtypeids = array();
		$sql = ($fids ? ' AND t.fid IN ('.dimplode($fids).')' : '')
			.($tids ? ' AND t.tid IN ('.dimplode($tids).')' : '')
			.($bannedids ? ' AND t.tid NOT IN ('.dimplode($bannedids).')' : '')
			.($uids ? ' AND t.authorid IN ('.dimplode($uids).')' : '')
			.($special ? ' AND t.special IN ('.dimplode($special).')' : '')
			.((in_array(3, $special) && $rewardstatus) ? ($rewardstatus == 1 ? ' AND t.price < 0' : ' AND t.price > 0') : '')
			.($picrequired ? ' AND t.attachment = 2' : '')
			." AND t.isgroup='1'";
		if(in_array($orderby, array('hourviews','todayviews','weekviews','monthviews'))) {
			$historytime = 0;
			switch($orderby) {
				case 'hourviews':
					$historytime = TIMESTAMP - 3600;
				break;
				case 'todayviews':
					$historytime = mktime(0, 0, 0, date('m', TIMESTAMP), date('d', TIMESTAMP), date('Y', TIMESTAMP));
				break;
				case 'weekviews':
					$week = gmdate('w', TIMESTAMP) - 1;
					$week = $week != -1 ? $week : 6;
					$historytime = mktime(0, 0, 0, date('m', TIMESTAMP), date('d', TIMESTAMP) - $week, date('Y', TIMESTAMP));
				break;
				case 'monthviews':
					$historytime = mktime(0, 0, 0, date('m', TIMESTAMP), 1, date('Y', TIMESTAMP));
				break;
			}
			$sql .= ' AND t.dateline>='.$historytime;
			$orderby = 'views';
		} elseif($orderby == 'heats') {
			$heatdateline = TIMESTAMP - 86400 * $_G['setting']['indexhot']['days'];
			$sql .= " AND t.dateline>'$heatdateline' AND t.heats>'0'";
		}
		$sqlfrom = "FROM `".DB::table('forum_thread')."` t";

		$query = DB::query("SELECT t.*
			$sqlfrom WHERE t.readperm='0'
			$sql
			AND t.displayorder>='0'
			ORDER BY t.$orderby DESC
			LIMIT $startrow,$items;"
			);

		include_once libfile('block/thread', 'class');
		$bt = new block_thread();
		while($data = DB::fetch($query)) {
			$_G['thread'][$data['tid']] = $data;
			if($style['getpic'] && $data['attachment']=='2') {
				$pic = $bt->getpic($data['tid']);
				$data['attachment'] = $pic['attachment'];
				$data['remote'] = $pic['remote'];
			}
			$list[] = array(
				'id' => $data['tid'],
				'idtype' => 'tid',
				'title' => cutstr(str_replace('\\\'', '&#39;', $data['subject']), $titlelength),
				'url' => 'forum.php?mod=viewthread&tid='.$data['tid'],
				'pic' => $data['attachment'] ? 'forum/'.$data['attachment'] : STATICURL.'image/common/nophoto.gif',
				'picflag' => $data['attachment'] ? ($data['remote'] ? '2' : '1') : '0',
				'summary' => $style['getsummary'] ? $bt->getthread($data['tid'], $summarylength) : '',
				'fields' => array(
					'icon' => 'forum/'.$data['icon'],
					'author' => $data['author'] ? $data['author'] : 'Anonymous',
					'authorid' => $data['author'] ? $data['authorid'] : 0,
					'avatar' => avatar(($data['author'] ? $data['authorid'] : 0), 'small', true),
					'avatar_big' => avatar(($data['author'] ? $data['authorid'] : 0), 'middle', true),
					'dateline' => $data['dateline'],
					'lastpost' => $data['lastpost'],
					'posts' => $data['posts'],
					'todayposts' => $data['todayposts'],
					'replies' => $data['replies'],
					'views' => $data['views'],
					'heats' => $data['heats'],
					'recommends' => $data['recommends'],
					'groupname' => $groups[$data['fid']]['name'],
					'groupurl' => 'forum.php?mod=group&fid='.$data['fid'],
				)
			);
		}
		return array('html' => '', 'data' => $list);
	}
}


?>
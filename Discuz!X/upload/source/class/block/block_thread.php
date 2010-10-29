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
class block_thread {
	var $setting = array();

	function block_thread(){
		$this->setting = array(
			'tids' => array(
				'title' => 'threadlist_tids',
				'type' => 'text'
			),
			'uids' => array(
				'title' => 'threadlist_uids',
				'type' => 'text'
			),
			'keyword' => array(
				'title' => 'threadlist_keyword',
				'type' => 'text'
			),
			'fids'	=> array(
				'title' => 'threadlist_fids',
				'type' => 'mselect',
				'value' => array()
			),
			'sortids' => array(
				'title' => 'threadlist_sortids',
				'type' => 'mselect',
				'value' => array()
			),
			'digest' => array(
				'title' => 'threadlist_digest',
				'type' => 'mcheckbox',
				'value' => array(
					array(1, 'threadlist_digest_1'),
					array(2, 'threadlist_digest_2'),
					array(3, 'threadlist_digest_3'),
					array(0, 'threadlist_digest_0')
				),
			),
			'stick' => array(
				'title' => 'threadlist_stick',
				'type' => 'mcheckbox',
				'value' => array(
					array(1, 'threadlist_stick_1'),
					array(2, 'threadlist_stick_2'),
					array(3, 'threadlist_stick_3'),
					array(0, 'threadlist_stick_0')
				),
			),
			'recommend' => array(
				'title' => 'threadlist_recommend',
				'type' => 'radio'
			),
			'special' => array(
				'title' => 'threadlist_special',
				'type' => 'mcheckbox',
				'value' => array(
					array(1, 'threadlist_special_1'),
					array(2, 'threadlist_special_2'),
					array(3, 'threadlist_special_3'),
					array(4, 'threadlist_special_4'),
					array(5, 'threadlist_special_5'),
					array(0, 'threadlist_special_0'),
				),
				'default' => array('0')
			),
			'viewmod' => array(
				'title' => 'threadlist_viewmod',
				'type' => 'radio'
			),
			'rewardstatus' => array(
				'title' => 'threadlist_special_reward',
				'type' => 'mradio',
				'value' => array(
					array(0, 'threadlist_special_reward_0'),
					array(1, 'threadlist_special_reward_1'),
					array(2, 'threadlist_special_reward_2')
				),
				'default' => 0,
			),
			'picrequired' => array(
				'title' => 'threadlist_picrequired',
				'type' => 'radio',
				'value' => '0'
			),
			'orderby' => array(
				'title' => 'threadlist_orderby',
				'type'=> 'mradio',
				'value' => array(
					array('lastpost', 'threadlist_orderby_lastpost'),
					array('dateline', 'threadlist_orderby_dateline'),
					array('replies', 'threadlist_orderby_replies'),
					array('views', 'threadlist_orderby_views'),
					array('heats', 'threadlist_orderby_heats'),
					array('recommends', 'threadlist_orderby_recommends'),
					array('hourviews', 'threadlist_orderby_hourviews'),
					array('todayviews', 'threadlist_orderby_todayviews'),
					array('weekviews', 'threadlist_orderby_weekviews'),
					array('monthviews', 'threadlist_orderby_monthviews'),
				),
				'default' => 'lastpost'
			),
			'hours' => array(
				'title' => 'threadlist_orderby_hours',
				'type' => 'text'
			),
			'titlelength' => array(
				'title' => 'threadlist_titlelength',
				'type' => 'text',
				'default' => 40
			),
			'summarylength' => array(
				'title' => 'threadlist_summarylength',
				'type' => 'text',
				'default' => 80
			),
			'startrow' => array(
				'title' => 'threadlist_startrow',
				'type' => 'text',
				'default' => 0
			),
		);
	}

	function getsetting() {
		global $_G;
		$settings = $this->setting;

		if($settings['fids']) {
			loadcache('forums');
			$settings['fids']['value'][] = array(0, lang('portalcp', 'block_all_forum'));
			foreach($_G['cache']['forums'] as $fid => $forum) {
				$settings['fids']['value'][] = array($fid, ($forum['type'] == 'forum' ? str_repeat('&nbsp;', 4) : ($forum['type'] == 'sub' ? str_repeat('&nbsp;', 8) : '')).$forum['name']);
			}
		}
		if($settings['sortids']) {
			$settings['sortids']['value'][] = array('all', 'threadlist_sortids_all');
			$query = DB::query("SELECT typeid, name, special FROM ".DB::table('forum_threadtype')." ORDER BY typeid DESC");
			while($threadtype = DB::fetch($query)) {
				if($threadtype['special']) {
					$settings['sortids']['value'][] = array($threadtype['typeid'], $threadtype['name']);
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

		loadcache('forums');
		$tids		= !empty($parameter['tids']) ? explode(',', $parameter['tids']) : array();
		$uids		= !empty($parameter['uids']) ? explode(',', $parameter['uids']) : array();
		$fids		= isset($parameter['fids']) && !in_array(0, (array)$parameter['fids']) ? $parameter['fids'] : array_keys($_G['cache']['forums']);
		$startrow	= isset($parameter['startrow']) ? intval($parameter['startrow']) : 0;
		$items		= !empty($parameter['items']) ? intval($parameter['items']) : 10;
		$digest		= isset($parameter['digest']) ? $parameter['digest'] : 0;
		$stick		= isset($parameter['stick']) ? $parameter['stick'] : 0;
		$orderby	= isset($parameter['orderby']) ? (in_array($parameter['orderby'],array('lastpost','dateline','replies','views','heats','recommends','hourviews','todayviews','weekviews','monthviews')) ? $parameter['orderby'] : 'lastpost') : 'lastpost';
		$hours		= isset($parameter['hours']) ? intval($parameter['hours']) : 0;
		$titlelength	= !empty($parameter['titlelength']) ? intval($parameter['titlelength']) : 40;
		$summarylength	= !empty($parameter['summarylength']) ? intval($parameter['summarylength']) : 80;
		$recommend	= !empty($parameter['recommend']) ? 1 : 0;
		$keyword	= !empty($parameter['keyword']) ? $parameter['keyword'] : '';
		$typeids	= isset($parameter['typeids']) ? $parameter['typeids'] : '';
		$sortids	= isset($parameter['sortids']) ? $parameter['sortids'] : '';
		$special	= isset($parameter['special']) ? $parameter['special'] : array();
		$rewardstatus	= isset($parameter['rewardstatus']) ? intval($parameter['rewardstatus']) : 0;
		$picrequired	= !empty($parameter['picrequired']) ? 1 : 0;
		$viewmod	= !empty($parameter['viewmod']) ? 1 : 0;

		if($fids) {
			$thefids = array();
			foreach($fids as $fid) {
				if($_G['cache']['forums'][$fid]['type']=='group') {
					$thefids[] = $fid;
				}
			}
			if($thefids) {
				foreach($_G['cache']['forums'] as $value) {
					if($value['fup'] && in_array($value['fup'], $thefids)) {
						$fids[] = intval($value['fid']);
					}
				}
			}
			$fids = array_unique($fids);
		}

		$bannedids = !empty($parameter['bannedids']) ? explode(',', $parameter['bannedids']) : array();

		require_once libfile('function/post');

		$datalist = $list = array();
		$threadtypeids = array();
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
			.($tids ? ' AND t.tid IN ('.dimplode($tids).')' : '')
			.($uids ? ' AND t.authorid IN ('.dimplode($uids).')' : '')
			.($bannedids ? ' AND t.tid NOT IN ('.dimplode($bannedids).')' : '')
			.($typeids ? ' AND t.typeid IN ('.dimplode($typeids).')' : '')
			.($sortids ? ' AND t.sortid IN ('.dimplode($sortids).')' : '')
			.($special ? ' AND t.special IN ('.dimplode($special).')' : '')
			.((in_array(3, $special) && $rewardstatus) ? ($rewardstatus == 1 ? ' AND t.price < 0' : ' AND t.price > 0') : '')
			.($digest ? ' AND t.digest IN ('.dimplode($digest).')' : '')
			.($stick ? ' AND t.displayorder IN ('.dimplode($stick).')' : '')
			.($picrequired ? ' AND t.attachment = 2' : '')
			.$keyword
			." AND t.closed='0' AND t.isgroup='0'";
		if(in_array($orderby, array('hourviews','todayviews','weekviews','monthviews'))) {
			$historytime = 0;
			switch($orderby) {
				case 'hourviews':
					$historytime = TIMESTAMP - 3600 * $hours;
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
			$_G['setting']['indexhot']['days'] = !empty($_G['setting']['indexhot']['days']) ? intval($_G['setting']['indexhot']['days']) : 8;
			$heatdateline = TIMESTAMP - 86400 * $_G['setting']['indexhot']['days'];
			$sql .= " AND t.dateline>'$heatdateline' AND t.heats>'0'";
		}
		$sqlfrom = "FROM `".DB::table('forum_thread')."` t";
		if($recommend) {
			$sqlfrom .= " INNER JOIN `".DB::table('forum_forumrecommend')."` fc ON fc.tid=t.tid";
		}
		$query = DB::query("SELECT t.*
			$sqlfrom WHERE t.readperm='0'
			$sql
			AND t.displayorder>='0'
			ORDER BY t.$orderby DESC
			LIMIT $startrow,$items;"
			);
		while($data = DB::fetch($query)) {
			$_G['thread'][$data['tid']] = $data;
			if($style['getpic'] && $data['attachment']=='2') {
				$pic = $this->getpic($data['tid']);
				$data['attachment'] = $pic['attachment'];
				$data['remote'] = $pic['remote'];
			}
			$list[] = array(
				'id' => $data['tid'],
				'idtype' => 'tid',
				'title' => cutstr(str_replace('\\\'', '&#39;', addslashes($data['subject'])), $titlelength),
				'url' => 'forum.php?mod=viewthread&tid='.$data['tid'].($viewmod ? '&from=portal' : ''),
				'pic' => $data['attachment'] ? 'forum/'.$data['attachment'] : STATICURL.'image/common/nophoto.gif',
				'picflag' => $data['attachment'] ? ($data['remote'] ? '2' : '1') : '0',
				'summary' => $style['getsummary'] ? $this->getthread($data['tid'], $summarylength) : '',
				'fields' => array(
					'threads' => $data['threads'],
					'author' => $data['author'] ? $data['author'] : 'Anonymous',
					'authorid' => $data['author'] ? $data['authorid'] : 0,
					'avatar' => avatar(($data['author'] ? $data['authorid'] : 0), 'small', true),
					'avatar_big' => avatar(($data['author'] ? $data['authorid'] : 0), 'middle', true),
					'posts' => $data['posts'],
					'todayposts' => $data['todayposts'],
					'lastpost' => $data['lastpost'],
					'dateline' => $data['dateline'],
					'replies' => $data['replies'],
					'forumurl' => 'forum.php?fid='.$data['fid'],
					'forumname' => $_G['cache']['forums'][$data['fid']]['name'],
					'views' => $data['views'],
					'heats' => $data['heats'],
					'recommends' => $data['recommends'],
					'hourviews' => $data['views'],
					'todayviews' => $data['views'],
					'weekviews' => $data['views'],
					'monthviews' => $data['views']
				)
			);
		}
		return array('html' => '', 'data' => $list);
	}

	function getthread($tid, $messagelength = 80, $nospecial=false) {
		global $_G;
		if(!$tid) {
			return '';
		}
		require_once libfile('function/post');
		if(empty($_G['thread'][$tid])) {
			$thread = DB::fetch_first("SELECT subject, fid, special, price, posttableid FROM ".DB::table('forum_thread')." WHERE tid='$tid'");
			$_G['thread'][$tid] = $thread;
		} else {
			$thread = $_G['thread'][$tid];
		}
		if($thread['posttableid'] == 0) {
			$posttable = 'forum_post';
		} else {
			$posttable = "forum_post_{$thread['posttableid']}";
		}
		$fid = $thread['fid'];
		if($nospecial) {
			$thread['special'] = 0;
		}
		if($thread['special'] == 1) {
			$multiple = DB::result_first("SELECT multiple FROM ".DB::table('forum_poll')." WHERE tid='$tid'");
			$optiontype = $multiple ? 'checkbox' : 'radio';
			$query = DB::query("SELECT polloptionid, polloption FROM ".DB::table('forum_polloption')." WHERE tid='$tid' ORDER BY displayorder");
			while($polloption = DB::fetch($query)) {
				$polloption['polloption'] = preg_replace("/\[url=(https?|ftp|gopher|news|telnet|rtsp|mms|callto|bctp|ed2k|thunder|synacast){1}:\/\/([^\[\"']+?)\](.+?)\[\/url\]/i",
					"<a href=\"\\1://\\2\" target=\"_blank\">\\3</a>", $polloption['polloption']);
				$polloptions[] = $polloption;
			}
		} elseif($thread['special'] == 2) {
			$trade = DB::fetch_first("SELECT subject, price, credit, aid, pid FROM ".DB::table('forum_trade')." WHERE tid='$tid' ORDER BY displayorder DESC LIMIT 1");
			$trade['aid'] = $trade['aid'] ? getforumimg($trade['aid']) : '';
			$trades[] = $trade;
		} elseif($thread['special'] == 3) {
			$extcredits = $_G['settings']['extcredits'];
			$creditstransextra = $_G['settings']['creditstransextra'];
			$rewardend = $thread['price'] < 0;
			$rewardprice = abs($thread['price']);
			$message = messagecutstr(DB::result_first("SELECT message FROM ".DB::table($posttable)." WHERE tid='$tid' AND first=1"), $messagelength);
		} elseif($thread['special'] == 4) {
			$message = messagecutstr(DB::result_first("SELECT message FROM ".DB::table($posttable)." WHERE tid='$tid' AND first=1"), $messagelength);
			$activity = DB::fetch_first("SELECT aid, number, applynumber FROM ".DB::table('forum_activity')." WHERE tid='$tid'");
			$activity['aid'] = $activity['aid'] ? getforumimg($activity['aid']) : '';
			$activity['aboutmember'] = $activity['number'] - $activity['applynumber'];
		} elseif($thread['special'] == 5) {
			$message = messagecutstr(DB::result_first("SELECT message FROM ".DB::table($posttable)." WHERE tid='$tid' AND first=1"), $messagelength);
			$debate = DB::fetch_first("SELECT affirmdebaters, negadebaters, affirmvotes, negavotes, affirmpoint, negapoint FROM ".DB::table('forum_debate')." WHERE tid='$tid'");
			$debate['affirmvoteswidth'] = $debate['affirmvotes']  ? intval(80 * (($debate['affirmvotes'] + 1) / ($debate['affirmvotes'] + $debate['negavotes'] + 1))) : 1;
			$debate['negavoteswidth'] = $debate['negavotes']  ? intval(80 * (($debate['negavotes'] + 1) / ($debate['affirmvotes'] + $debate['negavotes'] + 1))) : 1;
			@require_once libfile('function/discuzcode');
			$debate['affirmpoint'] = discuzcode($debate['affirmpoint'], 0, 0, 0, 1, 1, 0, 0, 0, 0, 0);
			$debate['negapoint'] = discuzcode($debate['negapoint'], 0, 0, 0, 1, 1, 0, 0, 0, 0, 0);
		} else {
			$message = messagecutstr(DB::result_first("SELECT message FROM ".DB::table($posttable)." WHERE tid='$tid' AND first=1"), $messagelength);
		}

		include template('common/block_thread');
		return $return;
	}

	function getpic($tid) {
		global $_G;
		if(!$tid) {
			return '';
		}
		require_once libfile('function/post');
		if(empty($_G['thread'][$tid])) {
			$thread = DB::fetch_first("SELECT subject, fid, special, price, posttableid FROM ".DB::table('forum_thread')." WHERE tid='$tid'");
			$_G['thread'][$tid] = $thread;
		} else {
			$thread = $_G['thread'][$tid];
		}
		if($thread['posttableid'] == 0) {
			$posttable = 'forum_post';
		} else {
			$posttable = "forum_post_{$thread['posttableid']}";
		}
		$pic = DB::fetch_first("SELECT fa.attachment, fa.remote FROM ".DB::table($posttable)." fp LEFT JOIN ".DB::table('forum_attachment')." fa ON fp.tid=fa.tid AND fp.pid=fa.pid WHERE fp.tid='$tid' AND fp.first=1 AND fa.isimage IN (1, -1) LIMIT 0,1");
		return $pic;
	}
}


?>
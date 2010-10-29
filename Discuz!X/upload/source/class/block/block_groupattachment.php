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

class block_groupattachment {

	var $settings = array();

	function block_groupattachment() {
		$this->settings = array(
			'gtids' => array(
				'title' => 'groupattachment_gtids',
				'type' => 'mselect',
				'value' => array(
				),
			),
			'tids'	=> array(
				'title' => 'groupattachment_tids',
				'type' => 'text'
			),
			'special' => array(
				'title' => 'groupattachment_special',
				'type' => 'mcheckbox',
				'value' => array(
					array(1, 'groupattachment_special_1'),
					array(2, 'groupattachment_special_2'),
					array(3, 'groupattachment_special_3'),
					array(4, 'groupattachment_special_4'),
					array(5, 'groupattachment_special_5'),
					array(0, 'groupattachment_special_0'),
				)
			),
			'rewardstatus' => array(
				'title' => 'groupattachment_special_reward',
				'type' => 'mradio',
				'value' => array(
					array(0, 'groupattachment_special_reward_0'),
					array(1, 'groupattachment_special_reward_1'),
					array(2, 'groupattachment_special_reward_2')
				),
				'default' => 0,
			),
			'isimage' => array(
				'title' => 'groupattachment_isimage',
				'type' => 'mradio',
				'value' => array(
					array(0, 'groupattachment_isimage_0'),
					array(1, 'groupattachment_isimage_1'),
					array(2, 'groupattachment_isimage_2')
				),
				'default' => 0
			),
			'threadmethod' => array(
				'title' => 'groupattachment_threadmethod',
				'type' => 'radio',
				'default' => 0
			),
			'digest' => array(
				'title' => 'groupattachment_digest',
				'type' => 'mcheckbox',
				'value' => array(
					array(1, 'groupattachment_digest_1'),
					array(2, 'groupattachment_digest_2'),
					array(3, 'groupattachment_digest_3'),
					array(0, 'groupattachment_digest_0')
				),
			),
			'orderby' => array(
				'title' => 'groupattachment_orderby',
				'type' => 'mradio',
				'value' => array(
					array('dateline', 'groupattachment_orderby_dateline'),
					array('downloads', 'groupattachment_orderby_downloads'),
				),
				'default' => 'dateline'
			),
			'dateline' => array(
				'title' => 'groupattachment_dateline',
				'type' => 'mradio',
				'value' => array(
					array('', 'groupattachment_dateline_nolimit'),
					array('3600', 'groupattachment_dateline_hour'),
					array('86400', 'groupattachment_dateline_day'),
					array('604800', 'groupattachment_dateline_week'),
					array('2592000', 'groupattachment_dateline_month'),
				),
				'default' => ''
			),
			'titlelength' => array(
				'title' => 'groupattachment_titlelength',
				'type' => 'text',
				'default' => 40
			),
			'summarylength' => array(
				'title' => 'groupattachment_summarylength',
				'type' => 'text',
				'default' => 80
			),
			'startrow' => array(
				'title' => 'groupattachment_startrow',
				'type' => 'text',
				'default' => 0
			),
		);
	}

	function getsetting() {
		global $_G;
		$settings = $this->settings;

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
		$startrow	= isset($parameter['startrow']) ? intval($parameter['startrow']) : 0;
		$items		= isset($parameter['items']) ? intval($parameter['items']) : 10;
		$titlelength	= !empty($parameter['titlelength']) ? intval($parameter['titlelength']) : 40;
		$summarylength	= !empty($parameter['summarylength']) ? intval($parameter['summarylength']) : 80;
		$digest		= isset($parameter['digest']) ? $parameter['digest'] : 0;
		$special	= isset($parameter['special']) ? $parameter['special'] : array();
		$rewardstatus	= isset($parameter['rewardstatus']) ? intval($parameter['rewardstatus']) : 0;
		$orderby = isset($parameter['orderby']) ? (in_array($parameter['orderby'],array('dateline','downloads')) ? $parameter['orderby'] : 'dateline') : 'dateline';
		$dateline = isset($parameter['dateline']) ? intval($parameter['dateline']) : '8640000';
		$threadmethod = !empty($parameter['threadmethod']) ? 1 : 0;
		$isimage = isset($parameter['isimage']) ? intval($parameter['isimage']) : '';

		$bannedids = !empty($parameter['bannedids']) ? explode(',', $parameter['bannedids']) : array();

		$plusids = array();
		foreach($typeids as $typeid) {
			if(!empty($_G['cache']['grouptype']['first'][$typeid]['secondlist'])) {
				$plusids = array_merge($plusids, $_G['cache']['grouptype']['first'][$typeid]['secondlist']);
			}
		}
		$typeids = array_merge($typeids, $plusids);
		$fids = array();
		if($typeids) {
			$query = DB::query('SELECT f.fid, f.name, ff.description FROM '.DB::table('forum_forum')." f LEFT JOIN ".DB::table('forum_forumfield')." ff ON f.fid = ff.fid WHERE f.fup IN (".dimplode($typeids).")");
			while($value = DB::fetch($query)) {
				$groups[$value['fid']] = $value;
				$fids[] = intval($value['fid']);
			}
		}
		$datalist = $list = array();
		$sql = ($fids ? ' AND t.fid IN ('.dimplode($fids).')' : '')
			.($tids ? ' AND t.tid IN ('.dimplode($tids).')' : '')
			.($digest ? ' AND t.digest IN ('.dimplode($digest).')' : '')
			.($special ? ' AND t.special IN ('.dimplode($special).')' : '')
			.((in_array(3, $special) && $rewardstatus) ? ($rewardstatus == 1 ? ' AND t.price < 0' : ' AND t.price > 0') : '')
			. " AND t.closed='0' AND t.isgroup='1'";
		$orderbysql = $historytime = '';
		switch($orderby) {
			case 'dateline':
				$orderbysql = "ORDER BY `attach`.`dateline` DESC";
			break;
			case 'downloads':
				$orderbysql = "ORDER BY `attach`.`downloads` DESC";
			break;
		}
		$htsql = '';
		$dateline = !empty($dateline) ? intval($dateline) : 8640000;
		$historytime = TIMESTAMP - $dateline;
		$htsql = "`attach`.`dateline`>='$historytime'";
		$sqlfield = $sqljoin = '';
		if($style['getsummary']) {
			$sqlfield = ',af.description';
			$sqljoin = "LEFT JOIN `".DB::table('forum_attachmentfield')."` af ON attach.aid=af.aid";
		}
		if($isimage) {
			$sql .= $isimage == 1 ? "AND `attach`.`isimage` IN ('1', '-1')" : "AND `attach`.`isimage`='0'";
		}
		$sqlgroupby = '';
		if($threadmethod) {
			if($isimage==1) {
				$sql .= ' AND t.attachment=2';
			} elseif($isimage==2) {
				$sql .= ' AND t.attachment=1';
			} else {
				$sql .= ' AND t.attachment>0';
			}
			$sqlgroupby = ' GROUP BY t.tid';
		}
		$sqlban = !empty($bannedids) ? ' AND attach.tid NOT IN ('.dimplode($bannedids).')' : '';
		$query = DB::query("SELECT attach.*,t.tid,t.author,t.authorid,t.subject $sqlfield
			FROM `".DB::table('forum_attachment')."` attach
			$sqljoin
			INNER JOIN `".DB::table('forum_thread')."` t
			ON `t`.`tid`=`attach`.`tid` AND `displayorder`>='0'
			WHERE $htsql AND `attach`.`readperm`='0' AND `attach`.`price`='0'
			$sql
			$sqlban
			$sqlgroupby
			$orderbysql
			LIMIT $startrow,$items;"
		);
		include_once libfile('block/thread', 'class');
		$bt = new block_thread();
		while($data = DB::fetch($query)) {
			$list[] = array(
				'id' => $data['aid'],
				'idtype' => 'aid',
				'title' => cutstr(str_replace('\\\'', '&#39;', $data['filename']), $titlelength),
				'url' => 'forum.php?mod=attachment&aid='.aidencode($data['aid']),
				'pic' => $data['isimage'] == 1 || $data['isimage'] == -1 ? 'forum/'.$data['attachment'] : '',
				'picflag' => $data['remote'] ? '2' : '1',
				'summary' => $data['description'] ? cutstr(str_replace('\\\'', '&#39;', $data['description']), $summarylength) : '',
				'fields' => array(
					'author' => $data['author'],
					'authorid' => $data['authorid'],
					'filesize' => sizecount($data['filesize']),
					'dateline' => $data['dateline'],
					'downloads' => $data['downloads'],
					'hourdownloads' => $data['downloads'],
					'todaydownloads' => $data['downloads'],
					'weekdownloads' => $data['downloads'],
					'monthdownloads' => $data['downloads'],
					'threadurl' => 'forum.php?mod=viewthread&tid='.$data['tid'],
					'threadsubject' => cutstr(str_replace('\\\'', '&#39;', $data['subject']), $titlelength),
					'threadsummary' => $bt->getthread($data['tid'], $summarylength),
				)
			);
		}
		return array('html' => '', 'data' => $list);
	}
}

?>
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
			'gviewperm' => array(
				'title' => 'groupattachment_gviewperm',
				'type' => 'mradio',
				'value' => array(
					array('-1', 'groupattachment_gviewperm_nolimit'),
					array('0', 'groupattachment_gviewperm_only_member'),
					array('1', 'groupattachment_gviewperm_all_member')
				),
				'default' => '-1'
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
			'highlight' => array(
				'title' => 'groupattachment_highlight',
				'type' => 'radio',
				'default' => 0,
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

	function name() {
		return lang('blockclass', 'blockclass_groupattachment_script_groupattachment');
	}

	function blockclass() {
		return array('attachment', lang('blockclass', 'blockclass_group_attachment'));
	}

	function fields() {
		return array(
				'url' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
				'title' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
				'pic' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
			'summary' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
				'threadurl' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_threadurl'), 'formtype' => 'text', 'datatype' => 'text'),
				'threadsubject' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_threadsubject'), 'formtype' => 'text', 'datatype' => 'text'),
				'threadsummary' => array('name' => lang('blockclass', 'blockclass_attachment_field_threadsummary'), 'formtype' => 'text', 'datatype' => 'text'),
				'author' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_author'), 'formtype' => 'text', 'datatype' => 'string'),
				'authorid' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_authorid'), 'formtype' => 'text', 'datatype' => 'int'),
				'dateline' => array('name'=>lang('blockclass', 'blockclass_groupattachment_field_dateline'), 'formtype' => 'date', 'datatype'=>'date'),
					'filesize' => array('name' => lang('blockclass', 'blockclass_groupattachment_field_filesize'), 'formtype' => 'text', 'datatype' => 'string'),
					'downloads' => array('name'=>lang('blockclass', 'blockclass_groupattachment_field_downloads'), 'formtype' => 'text', 'datatype'=>'int'),
			);
	}

	function fieldsconvert() {
		return array(
				'forum_attachment' => array(
					'name' => lang('blockclass', 'blockclass_forum_attachment'),
					'script' => 'attachment',
					'searchkeys' => array(),
					'replacekeys' => array(),
				),
				'space_pic' => array(
					'name' => lang('blockclass', 'blockclass_space_pic'),
					'script' => 'pic',
					'searchkeys' => array('author', 'authorid'),
					'replacekeys' => array('username', 'uid'),
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
		$typeids = array();
		if(!empty($parameter['gtids'])) {
			if($parameter['gtids'][0] == '0') {
				unset($parameter['gtids'][0]);
			}
			$typeids = $parameter['gtids'];
		}
		$startrow	= isset($parameter['startrow']) ? intval($parameter['startrow']) : 0;
		$items		= isset($parameter['items']) ? intval($parameter['items']) : 10;
		$titlelength	= !empty($parameter['titlelength']) ? intval($parameter['titlelength']) : 40;
		$summarylength	= !empty($parameter['summarylength']) ? intval($parameter['summarylength']) : 80;
		$digest		= isset($parameter['digest']) ? $parameter['digest'] : 0;
		$special	= isset($parameter['special']) ? $parameter['special'] : array();
		$rewardstatus	= isset($parameter['rewardstatus']) ? intval($parameter['rewardstatus']) : 0;
		$dateline = isset($parameter['dateline']) ? intval($parameter['dateline']) : 8640000;
		$threadmethod = !empty($parameter['threadmethod']) ? 1 : 0;
		$gviewperm = isset($parameter['gviewperm']) ? intval($parameter['gviewperm']) : -1;
		$highlight = !empty($parameter['highlight']) ? 1 : 0;

		$bannedids = !empty($parameter['bannedids']) ? explode(',', $parameter['bannedids']) : array();

		$gviewwhere = $gviewperm == -1 ? '' : " AND ff.gviewperm='$gviewperm'";

		$groups = array();
		if(empty($fids) && $typeids) {
			$query = DB::query('SELECT f.fid, f.name, ff.description FROM '.DB::table('forum_forum')." f LEFT JOIN ".DB::table('forum_forumfield')." ff ON f.fid = ff.fid WHERE f.fup IN (".dimplode($typeids).") AND threads > 0$gviewwhere");
			while($value = DB::fetch($query)) {
				$groups[$value['fid']] = $value;
				$fids[] = intval($value['fid']);
			}
			if(empty($fids)){
				return array('html' => '', 'data' => '');
			}
		}
		$datalist = $list = $listtids = $threadtids = $threads = array();
		$sql = ($fids ? ' AND t.fid IN ('.dimplode($fids).')' : '')
			.($tids ? ' AND t.tid IN ('.dimplode($tids).')' : '')
			.($digest ? ' AND t.digest IN ('.dimplode($digest).')' : '')
			.($special ? ' AND t.special IN ('.dimplode($special).')' : '')
			.((in_array(3, $special) && $rewardstatus) ? ($rewardstatus == 1 ? ' AND t.price < 0' : ' AND t.price > 0') : '');

		if(empty($fids)) {
			$sql .= " AND t.isgroup='1'";
			if($gviewwhere) {
				$sql .= $gviewwhere;
			}
		}

		$orderbysql = $historytime = '';
		$orderbysql = "ORDER BY `t`.`dateline` DESC";
		$htsql = '1';
		if($dateline) {
			$historytime = TIMESTAMP - $dateline;
		$htsql = "`t`.`dateline`>='$historytime'";
		}
		$sqlgroupby = '';
		if($threadmethod) {
			$sqlgroupby = ' GROUP BY t.tid';
		}
		$sqlban = !empty($bannedids) ? ' AND attach.aid NOT IN ('.dimplode($bannedids).')' : '';

		$sqlfield = '';
		if(empty($fids)) {
			$sqlfield = ', f.name groupname';
			$sqlfrom .= ' LEFT JOIN '.DB::table('forum_forum').' f ON t.fid=f.fid LEFT JOIN '.DB::table('forum_forumfield').' ff ON f.fid = ff.fid';
		}
		$sqlfield = $highlight ? ', t.highlight' : '';

		$query = DB::query("SELECT attach.*,t.tid,t.author,t.authorid,t.subject$sqlfield
			FROM `".DB::table('forum_attachment')."` attach
			INNER JOIN `".DB::table('forum_thread')."` t
			ON `t`.`tid`=`attach`.`tid` AND `displayorder`>='0'
			$sqlfrom
			WHERE $htsql
			$sql
			$sqlban
			$sqlgroupby
			$orderbysql
			LIMIT $startrow,$items;"
		);
		require_once libfile('block_thread', 'class/block/forum');
		$bt = new block_thread();
		while($data = DB::fetch($query)) {
			$threadtids[$data['posttableid']][] = $data['tid'];
			$listtids[] = $data['tid'];
			$tables[$data['tableid']][] = $data['aid'];
			$datalist[] = $data;
			$list[$data['aid']] = array(
				'tid' => $data['tid'],
				'id' => $data['aid'],
				'idtype' => 'aid',
				'title' => cutstr(str_replace('\\\'', '&#39;', $data['attachment']), $titlelength, ''),
				'url' => 'forum.php?mod=attachment&aid='.aidencode($data['aid']),
				'pic' => 'forum/'.$data['attachment'],
				'picflag' => $data['remote'] ? '2' : '1',
				'summary' => '',
				'fields' => array(
					'fulltitle' => str_replace('\\\'', '&#39;', addslashes($data['subject'])),
					'author' => $data['author'],
					'authorid' => $data['authorid'],
					'dateline' => $data['dateline'],
					'threadurl' => 'forum.php?mod=viewthread&tid='.$data['tid'],
					'threadsubject' => cutstr(str_replace('\\\'', '&#39;', $data['subject']), $titlelength, ''),
				)
			);
			if($highlight && $data['highlight']) {
				$list[$data['aid']]['fields']['showstyle'] = $bt->getthreadstyle($data['highlight']);
			}
		}
		if(!empty($listtids)) {
			$threads = $bt->getthread($threadtids, $summarylength, true);
			if($threads) {
				foreach($list as $laid => $lvalue) {
					if(isset($threads[$lvalue['tid']])) {
						$list[$laid]['fields']['threadsummary'] = cutstr(str_replace('\\\'', '&#39;', $threads[$lvalue['tid']]), $summarylength, '');
					}
				}
			}
		}
		foreach($tables as $tableid => $taids) {
			$query = DB::query('SELECT * FROM '.DB::table('forum_attachment_'.$tableid).' WHERE aid IN ('.dimplode($taids).')');
			while($avalue = DB::fetch($query)) {
				$list[$avalue['aid']]['title'] = cutstr(str_replace('\\\'', '&#39;', $avalue['filename']), $titlelength, '');
				$list[$avalue['aid']]['pic'] = 'forum/'.$avalue['attachment'];
				$list[$avalue['aid']]['picflag'] = $avalue['remote'] ? '2' : '1';
				$list[$avalue['aid']]['summary'] = $avalue['description'];
				$list[$avalue['aid']]['fields']['filesize'] = $avalue['filesize'];
				$list[$avalue['aid']]['fields']['downloads'] = $avalue['downloads'];
				$list[$avalue['aid']]['fields']['dateline'] = $avalue['dateline'];
			}
		}
		foreach($datalist as $key => $value) {
			$datalist[$key] = $list[$value['aid']];
		}
		return array('html' => '', 'data' => $datalist);
	}
}

?>
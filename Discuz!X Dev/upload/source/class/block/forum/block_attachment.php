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

class block_attachment {

	var $settings = array();

	function block_attachment() {
		$this->settings = array(
			'fids'	=> array(
				'title' => 'attachmentlist_fids',
				'type' => 'mselect',
				'value' => array()
			),
			'tids'	=> array(
				'title' => 'attachmentlist_tids',
				'type' => 'text'
			),
			'special' => array(
				'title' => 'attachmentlist_special',
				'type' => 'mcheckbox',
				'value' => array(
					array(1, 'attachmentlist_special_1'),
					array(2, 'attachmentlist_special_2'),
					array(3, 'attachmentlist_special_3'),
					array(4, 'attachmentlist_special_4'),
					array(5, 'attachmentlist_special_5'),
					array(0, 'attachmentlist_special_0'),
				)
			),
			'rewardstatus' => array(
				'title' => 'attachmentlist_special_reward',
				'type' => 'mradio',
				'value' => array(
					array(0, 'attachmentlist_special_reward_0'),
					array(1, 'attachmentlist_special_reward_1'),
					array(2, 'attachmentlist_special_reward_2')
				),
				'default' => 0,
			),
			'digest' => array(
				'title' => 'attachmentlist_digest',
				'type' => 'mcheckbox',
				'value' => array(
					array(1, 'attachmentlist_digest_1'),
					array(2, 'attachmentlist_digest_2'),
					array(3, 'attachmentlist_digest_3'),
					array(0, 'attachmentlist_digest_0')
				),
			),
			'dateline' => array(
				'title' => 'attachmentlist_dateline',
				'type' => 'mradio',
				'value' => array(
					array('', 'attachmentlist_dateline_nolimit'),
					array('3600', 'attachmentlist_dateline_hour'),
					array('86400', 'attachmentlist_dateline_day'),
					array('604800', 'attachmentlist_dateline_week'),
					array('2592000', 'attachmentlist_dateline_month'),
				),
				'default' => ''
			),
			'highlight' => array(
				'title' => 'attachmentlist_highlight',
				'type' => 'radio',
				'default' => 0,
			),
			'titlelength' => array(
				'title' => 'attachmentlist_titlelength',
				'type' => 'text',
				'default' => 40
			),
			'summarylength' => array(
				'title' => 'attachmentlist_summarylength',
				'type' => 'text',
				'default' => 80
			),
			'startrow' => array(
				'title' => 'attachmentlist_startrow',
				'type' => 'text',
				'default' => 0
			),
		);
	}

	function name() {
		return lang('blockclass', 'blockclass_attachment_script_attachment');
	}

	function blockclass() {
		return array('attachment',  lang('blockclass', 'blockclass_forum_attachment'));
	}

	function fields() {
		return array(
					'url' => array('name' => lang('blockclass', 'blockclass_attachment_field_url'), 'formtype' => 'text', 'datatype' => 'string'),
					'title' => array('name' => lang('blockclass', 'blockclass_attachment_field_title'), 'formtype' => 'title', 'datatype' => 'title'),
					'pic' => array('name' => lang('blockclass', 'blockclass_attachment_field_pic'), 'formtype' => 'pic', 'datatype' => 'pic'),
					'summary' => array('name' => lang('blockclass', 'blockclass_attachment_field_summary'), 'formtype' => 'summary', 'datatype' => 'summary'),
					'threadurl' => array('name' => lang('blockclass', 'blockclass_attachment_field_threadurl'), 'formtype' => 'text', 'datatype' => 'text'),
					'threadsubject' => array('name' => lang('blockclass', 'blockclass_attachment_field_threadsubject'), 'formtype' => 'text', 'datatype' => 'text'),
					'threadsummary' => array('name' => lang('blockclass', 'blockclass_attachment_field_threadsummary'), 'formtype' => 'textarea', 'datatype' => 'text'),
					'author' => array('name' => lang('blockclass', 'blockclass_attachment_field_author'), 'formtype' => 'text', 'datatype' => 'string'),
					'authorid' => array('name' => lang('blockclass', 'blockclass_attachment_field_authorid'), 'formtype' => 'text', 'datatype' => 'int'),
					'dateline' => array('name'=>lang('blockclass', 'blockclass_attachment_field_dateline'), 'formtype' => 'date', 'datatype'=>'date'),
					'filesize' => array('name' => lang('blockclass', 'blockclass_attachment_field_filesize'), 'formtype' => 'text', 'datatype' => 'string'),
					'downloads' => array('name'=>lang('blockclass', 'blockclass_attachment_field_downloads'), 'formtype' => 'text', 'datatype'=>'int'),
				);
	}

	function fieldsconvert() {
		return array(
				'group_attachment' => array(
					'name' => lang('blockclass', 'blockclass_group_attachment'),
					'script' => 'groupattachment',
					'searchkeys' => array(),
					'replacekeys' => array(),
				),
				'space_pic' => array(
					'name' => lang('blockclass', 'blockclass_space_pic'),
					'script' => 'pic',
					'searchkeys' => array('author', 'authorid', 'downloads'),
					'replacekeys' => array('username', 'uid', 'viewnum'),
				),
			);
	}

	function getsetting() {
		global $_G;
		$settings = $this->settings;

		loadcache('forums');
		$settings['fids']['value'][] = array(0, lang('portalcp', 'block_all_forum'));
		foreach($_G['cache']['forums'] as $fid => $forum) {
			$settings['fids']['value'][] = array($fid, ($forum['type'] == 'forum' ? str_repeat('&nbsp;', 4) : ($forum['type'] == 'sub' ? str_repeat('&nbsp;', 8) : '')).$forum['name']);
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
		$startrow	= isset($parameter['startrow']) ? intval($parameter['startrow']) : 0;
		$items		= isset($parameter['items']) ? intval($parameter['items']) : 10;
		$digest		= isset($parameter['digest']) ? $parameter['digest'] : 0;
		$special	= isset($parameter['special']) ? $parameter['special'] : array();
		$rewardstatus	= isset($parameter['rewardstatus']) ? intval($parameter['rewardstatus']) : 0;
		$titlelength	= !empty($parameter['titlelength']) ? intval($parameter['titlelength']) : 40;
		$summarylength	= !empty($parameter['summarylength']) ? intval($parameter['summarylength']) : 80;
		$dateline = isset($parameter['dateline']) ? intval($parameter['dateline']) : 8640000;
		$highlight = !empty($parameter['highlight']) ? 1 : 0;

		$fids = array();
		if(!empty($parameter['fids'])) {
			if($parameter['fids'][0] == '0') {
				unset($parameter['fids'][0]);
			}
			$fids = $parameter['fids'];
		}

		$bannedids = !empty($parameter['bannedids']) ? explode(',', $parameter['bannedids']) : array();

		$datalist = $list = $listtids = $threadtids = $threads = $tables = array();
		$sql = ($fids ? ' AND t.fid IN ('.dimplode($fids).')' : '')
			.($tids ? ' AND t.tid IN ('.dimplode($tids).')' : '')
			.($digest ? ' AND t.digest IN ('.dimplode($digest).')' : '')
			.($special ? ' AND t.special IN ('.dimplode($special).')' : '')
			.((in_array(3, $special) && $rewardstatus) ? ($rewardstatus == 1 ? ' AND t.price < 0' : ' AND t.price > 0') : '')
			. " AND t.isgroup='0'";
		$orderbysql = $historytime = '';
		$orderbysql = "ORDER BY `t`.`dateline` DESC";
		$htsql = '1';
		$dateline = !empty($dateline) ? intval($dateline) : 8640000;
		if($dateline) {
			$historytime = TIMESTAMP - $dateline;
			$htsql = "`t`.`dateline`>='$historytime'";
		}
		$sqlban = !empty($bannedids) ? ' AND attach.aid NOT IN ('.dimplode($bannedids).')' : '';
		$sqlfield = $highlight ? ', t.highlight' : '';
		$query = DB::query("SELECT attach.*,t.tid,t.author,t.authorid,t.subject$sqlfield
			FROM `".DB::table('forum_attachment')."` attach
			INNER JOIN `".DB::table('forum_thread')."` t
			ON `t`.`tid`=`attach`.`tid` AND `displayorder`>='0'
			WHERE $htsql
			$sql
			$sqlban
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
			$threads = $bt->getthread($threadtids, $summarylength);
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
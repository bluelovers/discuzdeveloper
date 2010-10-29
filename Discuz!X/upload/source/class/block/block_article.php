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
class block_article {
	var $setting = array();
	function block_article() {
		$this->setting = array(
			'aids'	=> array(
				'title' => 'articlelist_aids',
				'type' => 'text',
				'value' => ''
			),
			'uids'	=> array(
				'title' => 'articlelist_uids',
				'type' => 'text',
				'value' => ''
			),
			'catid' => array(
				'title' => 'articlelist_catid',
				'type' => 'mselect',
				'value' => array(
				),
			),
			'picrequired' => array(
				'title' => 'articlelist_picrequired',
				'type' => 'radio',
				'default' => '0'
			),
			'orderby' => array(
				'title' => 'articlelist_orderby',
				'type' => 'mradio',
				'value' => array(
					array('dateline', 'articlelist_orderby_dateline'),
					array('viewnum', 'articlelist_orderby_viewnum'),
					array('replynum', 'articlelist_orderby_commentnum'),
				),
				'default' => 'dateline'
			),
			'titlelength' => array(
				'title' => 'articlelist_titlelength',
				'type' => 'text',
				'default' => 40
			),
			'summarylength'	=> array(
				'title' => 'articlelist_summarylength',
				'type' => 'text',
				'default' => 80
			),
			'startrow' => array(
				'title' => 'articlelist_startrow',
				'type' => 'text',
				'default' => 0
			),
		);
	}

	function getsetting() {
		global $_G;
		$settings = $this->setting;

		if($settings['catid']) {
			$settings['catid']['value'][] = array(0, lang('portalcp', 'block_all_category'));
			loadcache('portalcategory');
			foreach($_G['cache']['portalcategory'] as $value) {
				if($value['level'] == 0) {
					$settings['catid']['value'][] = array($value['catid'], $value['catname']);
					if($value['children']) {
						foreach($value['children'] as $catid2) {
							$value2 = $_G['cache']['portalcategory'][$catid2];
							$settings['catid']['value'][] = array($value2['catid'], '-- '.$value2['catname']);
							if($value2['children']) {
								foreach($value2['children'] as $catid3) {
									$value3 = $_G['cache']['portalcategory'][$catid3];
									$settings['catid']['value'][] = array($value3['catid'], '---- '.$value3['catname']);
								}
							}
						}
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
		$aids		= !empty($parameter['aids']) ? explode(',', $parameter['aids']) : array();
		$uids		= !empty($parameter['uids']) ? explode(',', $parameter['uids']) : array();
		$startrow	= isset($parameter['startrow']) ? intval($parameter['startrow']) : 0;
		$items		= isset($parameter['items']) ? intval($parameter['items']) : 10;
		$titlelength = isset($parameter['titlelength']) ? intval($parameter['titlelength']) : 40;
		$summarylength = isset($parameter['summarylength']) ? intval($parameter['summarylength']) : 80;
		$orderby	= in_array($parameter['orderby'], array('dateline', 'viewnum', 'commentnum')) ? $parameter['orderby'] : 'dateline';
		$catid = !empty($parameter['catid']) && !in_array('0', $parameter['catid']) ? $parameter['catid'] : array();
		$picrequired = !empty($parameter['picrequired']) ? 1 : 0;

		$bannedids = !empty($parameter['bannedids']) ? explode(',', $parameter['bannedids']) : array();

		loadcache('portalcategory');

		$list = array();
		$wheres = array();
		if($aids) {
			$wheres[] = 'at.aid IN ('.dimplode($aids).')';
		}
		if($uids) {
			$wheres[] = 'at.uid IN ('.dimplode($uids).')';
		}
		if($catid) {
			$wheres[] = 'at.catid IN ('.dimplode($catid).')';
		}
		if($style['getpic'] && $picrequired) {
			$wheres[] = "pic != ''";
		}
		if($bannedids) {
			$wheres[] = 'at.aid NOT IN ('.dimplode($bannedids).')';
		}
		$wheresql = $wheres ? implode(' AND ', $wheres) : '1';
		$orderby = ($orderby == 'dateline') ? 'at.dateline DESC ' : "ac.$orderby DESC";
		$query = DB::query("SELECT at.*, ac.viewnum, ac.commentnum FROM ".DB::table('portal_article_title')." at LEFT JOIN ".DB::table('portal_article_count')." ac ON at.aid=ac.aid WHERE $wheresql ORDER BY $orderby LIMIT $startrow, $items");
		while($data = DB::fetch($query)) {
			if(empty($data['pic'])) {
				$data['pic'] = STATICURL.'image/common/nophoto.gif';
				$data['picflag'] = '0';
			} else {
				$data['pic'] = 'portal/'.$data['pic'];
				$data['picflag'] = $data['remote'] == '1' ? '2' : '1';
			}
			$list[] = array(
				'id' => $data['aid'],
				'idtype' => 'aid',
				'title' => cutstr($data['title'], $titlelength),
				'url' => 'portal.php?mod=view&aid='.$data['aid'],
				'pic' => $data['pic'],
				'picflag' => $data['picflag'],
				'summary' => cutstr(strip_tags($data['summary']), $summarylength),
				'fields' => array(
					'dateline'=>$data['dateline'],
					'caturl'=> 'portal.php?mod=list&catid='.$data['catid'],
					'catname' => $_G['cache']['portalcategory'][$data['catid']]['catname'],
					'viewnum' => intval($data['viewnum']),
					'commentnum' => intval($data['commentnum'])
				)
			);
		}
		return array('html' => '', 'data' => $list);
	}
}

?>
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
class block_portalcategory {
	var $setting = array();
	function block_portalcategory() {
		$this->setting = array(
			'catid' => array(
				'title' => 'portalcategory_catid',
				'type' => 'mselect',
				'value' => array(
				),
			),
			'orderby' => array(
				'title' => 'portalcategory_orderby',
				'type' => 'mradio',
				'value' => array(
					array('displayorder', 'portalcategory_orderby_displayorder'),
					array('articles', 'portalcategory_orderby_articles')
				),
				'default' => 'displayorder'
			)
		);
	}

	function getsetting() {
		global $_G;
		$settings = $this->setting;

		if($settings['catid']) {
			$settings['catid']['value'][] = array(0, lang('portalcp', 'block_first_category'));
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
		loadcache('portalcategory');
		$catid = !empty($parameter['catid']) ? $parameter['catid'] : array_keys($_G['cache']['portalcategory']);
		$orderby = $parameter['orderby'] == 'articles' ? ' ORDER BY articles DESC' : ' ORDER BY displayorder';

		$list = array();
		$query = DB::query('SELECT * FROM '.DB::table('portal_category')." WHERE upid IN (".dimplode($catid).") $orderby");
		while($data = DB::fetch($query)) {
			$list[] = array(
				'id' => $data['catid'],
				'idtype' => 'catid',
				'title' => $data['catname'],
				'url' => 'portal.php?mod=list&catid='.$data['catid'],
				'pic' => '',
				'picflag' => '0',
				'summary' => '',
				'fields' => array(
					'dateline'=>$data['dateline'],
					'articles' => $data['articles']
				)
			);
		}
		return array('html' => '', 'data' => $list);
	}
}

?>
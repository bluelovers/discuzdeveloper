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

class block_friendlink {

	function getsetting() {
		global $_G;
		$settings = array(
			'content' => array(
				'title' => 'friendlink_content',
				'type' => 'mradio',
				'value' => array(
					array('both', 'friendlink_content_both'),
					array('logo', 'friendlink_content_logo'),
					array('text', 'friendlink_content_text')
				),
				'default' => 'both'
			)
		);
		return $settings;
	}

	function getdata($style, $parameter) {
		$query = DB::query('SELECT * FROM '.DB::table('common_friendlink')." ORDER BY displayorder");
		$group1 = $group2 = $group3 = array();
		while($value=DB::fetch($query)) {
			if($parameter['content']=='logo') {
				$group2[] = $value;
			} elseif($parameter['content']=='text') {
				$group3[] = $value;
			} else {
				if($value['description']) {
					$group1[] = $value;
				} elseif($value['logo']) {
					$group2[] = $value;
				} else {
					$group3[] = $value;
				}
			}
		}
		$return = '<div class="bn lk">';
		if($group1) {
			$return .= '<ul class="m cl">';
			foreach($group1 as $value) {
				$return .= '<li>'
					. '<div class="forumlogo"><a target="_blank" href="'.$value['url'].'"><img border="0" alt="'.$value['name'].'" src="'.$value['logo'].'"></a></div>'
					. '<div class="forumcontent"><h5><a target="_blank" href="'.$value['url'].'">'.$value['name'].'</a></h5><p>'.$value['description'].'</p></div>'
					. '</li>';
			}
			$return .= '</ul>';
		}
		if($group2) {
			$return .= '<div class="cl mbm">';
			foreach($group2 as $value) {
				$return .= '<a target="_blank" href="'.$value['url'].'"><img border="0" alt="'.$value['name'].'" src="'.$value['logo'].'"></a>';
			}
			$return .= '</div>';
		}
		if($group3) {
			$return .= '<ul class="x cl">';
			foreach($group3 as $value) {
				$return .= '<li><a target="_blank" href="'.$value['url'].'">'.$value['name'].'</a></li>';
			}
			$return .= '</ul>';
		}
		$return .= '</div>';
		return array('html' => $return, 'data' => null);
	}
}

?>
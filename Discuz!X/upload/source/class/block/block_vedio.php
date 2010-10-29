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

class block_vedio {

	function block_vedio() {}

	function getsetting() {
		global $_G;
		$settings = array(
			'url' => array(
				'title' => 'vedio_url',
				'type' => 'text',
				'default' => 'http://'
			),
			'width' => array(
				'title' => 'vedio_width',
				'type' => 'text',
				'default' => ''
			),
			'height' => array(
				'title' => 'vedio_height',
				'type' => 'text',
				'default' => ''
			),
		);

		return $settings;
	}

	function getdata($style, $parameter) {
		require_once libfile('function/discuzcode');
		$return = parseflv($parameter['url'], $parameter['width'], $parameter['height']);
		if($return == false) {
			$return = $parameter['url'];
		}
		return array('html' => $return, 'data' => null);
	}
}

?>
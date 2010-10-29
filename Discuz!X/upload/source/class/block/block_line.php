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

class block_line {

	function getsetting() {
		global $_G;
		$settings = array(
			'style' => array(
				'title' => 'line_style',
				'type' => 'mradio',
				'value' => array(
					array('dash', 'line_style_dash'),
					array('line', 'line_style_line'),
				),
				'default' => 'dash'
			)
		);

		return $settings;
	}

	function getdata($style, $parameter) {
		$class = $parameter['style'] == 'line' ? 'l' : 'da';
		$return = "<hr class='$class' />";
		return array('html' => $return, 'data' => null);
	}
}

?>
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

class block_banner {

	function block_banner() {}

	function getsetting() {
		global $_G;
		$settings = array(
			'pic' => array(
				'title' => 'banner_pic',
				'type' => 'text',
				'default' => 'http://'
			),
			'url' => array(
				'title' => 'banner_url',
				'type' => 'text',
				'default' => ''
			),
			'width' => array(
				'title' => 'banner_width',
				'type' => 'text',
				'default' => '100%'
			),
			'height' => array(
				'title' => 'banner_height',
				'type' => 'text',
				'default' => ''
			),
			'text' => array(
				'title' => 'banner_text',
				'type' => 'text',
				'default' => ''
			),
		);

		return $settings;
	}

	function getdata($style, $parameter) {
		$return = '<img src="'.$parameter['pic'].'"'
			.($parameter['width'] ? ' width="'.$parameter['width'].'"' : '')
			.($parameter['height'] ? ' height="'.$parameter['height'].'"' : '')
			.($parameter['text'] ? ' alt="'.$parameter['text'].'"' : '')
			.' />';
		if($parameter['url']) {
			$return = "<a href=\"$parameter[url]\">$return</a>";
		}
		if($parameter['text']) {
			$return .= '<center><p>';
			$return .= $parameter['url'] ? "<a href=\"$parameter[url]\">$parameter[text]</a>" : $parameter['text'];
			$re5turn .= '</p></center>';
		}
		return array('html' => $return, 'data' => null);
	}

}

?>
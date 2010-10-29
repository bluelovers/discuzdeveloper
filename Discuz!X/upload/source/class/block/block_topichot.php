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
require_once libfile('block/topic', 'class');
class block_topichot extends block_topic {
	function block_topichot() {
		$this->setting = array(
			'picrequired' => array(
				'title' => 'topiclist_picrequired',
				'type' => 'radio',
				'default' => '0'
			),
			'titlelength' => array(
				'title' => 'topiclist_titlelength',
				'type' => 'text',
				'default' => 40
			),
			'summarylength' => array(
				'summary' => 'topiclist_summarylength',
				'type' => 'text',
				'default' => 80
			),
			'startrow' => array(
				'title' => 'topiclist_startrow',
				'type' => 'text',
				'default' => 0
			),
		);
	}

	function cookeparameter($parameter) {
		$parameter['orderby'] = 'viewnum';
		return $parameter;
	}
}

?>
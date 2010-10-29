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
require_once libfile('block/grouptrade', 'class');
class block_grouptradehot extends block_grouptrade {
	function block_grouptradehot() {
		$this->setting = array(
			'gtids' => array(
				'title' => 'grouptrade_gtids',
				'type' => 'mselect',
				'value' => array(
				),
			),
			'orderby' => array(
				'title' => 'grouptrade_orderby',
				'type'=> 'mradio',
				'value' => array(
					array('todayhots', 'grouptrade_orderby_todayhots'),
					array('weekhots', 'grouptrade_orderby_weekhots'),
					array('monthhots', 'grouptrade_orderby_monthhots'),
				),
				'default' => 'weekhots'
			),
			'titlelength' => array(
				'title' => 'grouptrade_titlelength',
				'type' => 'text',
				'default' => 40
			),
			'summarylength' => array(
				'title' => 'grouptrade_summarylength',
				'type' => 'text',
				'default' => 80
			),
			'startrow' => array(
				'title' => 'grouptrade_startrow',
				'type' => 'text',
				'default' => 0
			),
		);
	}
}

?>
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

class commonblock_html {

	function fields() {
		return array();
	}

	function blockclass() {
		return array('html', lang('blockclass', 'blockclass_html_html'));
	}

}
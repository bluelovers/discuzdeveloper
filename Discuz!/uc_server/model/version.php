<?php

/*
	[UCenter] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id$
*/

!defined('IN_UC') && exit('Access Denied');

class versionmodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->versionmodel($base);
	}

	function versionmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function check() {
		$data = $this->db->result_first("SELECT v FROM ".UC_DBTABLEPRE."settings WHERE k='version'");
		return $data;
	}

}

?>
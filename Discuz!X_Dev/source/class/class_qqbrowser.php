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

class qqbrowser {

	var $version = '1.0';
	var $params = array();
	var $safevariables = array('config', 'setting', 'setting/my_sitekey', 'setting/connectsitekey');

	function validator() {
		global $_G;
		if(empty($_G['gp_qqbrowser'])) {
			return false;
		}
		$qqbrowser = $_G['gp_qqbrowser'];
		$p = strpos($qqbrowser, '|');
		if($p === FALSE) {
			return false;
		}
		$authcode = substr($qqbrowser, 0, $p);
		$params = substr($qqbrowser, $p + 1);
		if(md5(md5($params).$_G['setting']['my_sitekey']) !== $authcode) {
			return false;
		}
		$this->params = array_merge($this->params, explode('|', $params));
		return true;
	}

	function outputvariables() {
		global $_G;
		$variables = array();
		foreach($this->params as $param) {
			if(substr($param, 0, 1) == '$') {
				if($param == '$_G') {
					continue;
				}
				$var = substr($param, 1);
				if(preg_match("/^[a-zA-Z_][a-zA-Z0-9_]*$/", $var)) {
					$variables[$param] = $GLOBALS[$var];
				}
			} else {
				if(in_array($param, $this->safevariables)) {
					continue;
				}
				$variables[$param] = getglobal($param);
			}
		}
		$xml = array(
			'Version' => $this->version,
			'Charset' => strtoupper($_G['charset']),
			'Variables' => $variables,
		);
		if(!empty($_G['messageparam'])) {
			$xml['Message'] = $_G['messageparam'];
		}
		require_once libfile('class/xml');
		echo array2xml($xml);
		exit;
	}
}

?>
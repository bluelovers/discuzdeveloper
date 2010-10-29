<?php

/*
	[Comsenz Stat!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id$
*/

define('DISCUZ_ROOT', '../');

if(empty($_GET['action'])) {
	exit('Access Denied');
}

if($_GET['action'] == 'send') {

	include_once DISCUZ_ROOT.'./include/common.inc.php';
	$statlogfile = DISCUZ_ROOT.'./forumdata/stat.log';
	$script = $_GET['script'] ? $_GET['script'] : $indexname;
	if($fp = @fopen($statlogfile, 'a')) {
		@flock($fp, 2);
		fwrite($fp, stat_query($_GET['message'], $_GET['query'], $_GET['referer'], '', $script)."\n");
		fclose($fp);
	}

} elseif($_GET['action'] == 'query') {

	$rows = array();
	include_once DISCUZ_ROOT.'./include/common.inc.php';
	if(!defined('STAT_KEY') || strlen(STAT_KEY) < 16) {
		exit;
	}
	$sql = authcode($_GET['sql'], 'DECODE', STAT_KEY);
	if(!$sql) {
		exit;
	}
	$sql = str_replace(' cdb_', ' '.$tablepre, $sql);
	$query = $db->query("SELECT $sql", 'SILENT');
	if($db->error()) {
		$rows[] = $db->error();
	} else {
		while($row = $db->fetch_array($query)) {
			$rows[] = $row;
		}
	}
	$data = serialize($rows);
	$md5 = md5($data);
	exit($md5.chr(0).$data);

} elseif($_GET['action'] == 'version') {

	define('IN_DISCUZ', TRUE);
	include_once DISCUZ_ROOT.'./config.inc.php';
	include_once DISCUZ_ROOT.'./include/global.func.php';
	include_once DISCUZ_ROOT.'./discuz_version.php';
	@include_once DISCUZ_ROOT.'./forumdata/cache/cache_settings.php';
	$statkey = STAT_KEY;
	if(empty($statkey) && $_DCACHE['settings']['statkey']) {
		$statkey = $_DCACHE['settings']['statkey'];
	}
	exit(authcode(DISCUZ_VERSION.' '.DISCUZ_RELEASE, 'ENCODE', $statkey));

}

?>
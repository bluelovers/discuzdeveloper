<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id$
*/

if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
}

if($requestrun) {

	$limit = !empty($settings['limit']) ? intval($settings['limit']) : 10;

	$cachefile = DISCUZ_ROOT.'./forumdata/cache/requestscript_sofa.php';
	if((@!include($cachefile)) || $timestamp - $today > 300 || $limit != $limitcache) {
		$query = $db->query("SELECT tid, fid, subject FROM {$tablepre}threads WHERE displayorder>='0' AND dateline>$timestamp-86400 AND replies='0' ORDER BY dateline DESC LIMIT $limit");

		$sofathreads = array();
		while($thread = $db->fetch_array($query)) {
			$thread['cutsubject'] = cutstr($thread['subject'], 16, '');
			$sofathreads[] = $thread;
		}
		$cachefile = DISCUZ_ROOT.'./forumdata/cache/requestscript_sofa.php';
		writetorequestcache($cachefile, 0, "\$limitcache = $limit;\n\$todaycache = '".$timestamp."';\n\$sofathreads = ".var_export($sofathreads, 1).';');
	}

	include template('request_sofa');

} else {

	$request_version = '1.0';
	$request_name = '抢沙发';
	$request_description = '在首页显示没有回复的主题';
	$request_copyright = '<a href="http://www.comsenz.com" target="_blank">Comsenz Inc.</a>';	

}

?>
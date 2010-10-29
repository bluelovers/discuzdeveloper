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

	$cachefile = DISCUZ_ROOT.'./forumdata/cache/requestscript_task.php';
	if((@!include($cachefile)) || $limit != $limitcache) {
		$query = $db->query("SELECT taskid, name, name, icon, reward, prize, bonus FROM {$tablepre}tasks WHERE available='2' AND newbietask='0' ORDER BY displayorder DESC LIMIT $limit");

		$tasklist = array();
		while($task = $db->fetch_array($query)) {
			$task['icon'] = $task['icon'] ? $task['icon'] : 'task.gif';
			$task['icon'] = strtolower(substr($task['icon'], 0, 7)) == 'http://' ? $task['icon'] : "images/tasks/$task[icon]";
			if($task['reward'] == 'magic') {
				$magicids[] = $task['prize'];
			} elseif($task['reward'] == 'medal') {
				$medalids[] = $task['prize'];
			} elseif($task['reward'] == 'group') {
				$groupids[] = $task['prize'];
			}		
			$tasklist[] = $task;
		}		
		writetorequestcache($cachefile, 0, "\$limitcache = $limit;\n\$tasklist = ".var_export($tasklist, 1).';');
	}
	
	$randtask = $tasklist[array_rand($tasklist)];

	include template('request_task');

} else {

	$request_version = '1.0';
	$request_name = '社区任务';
	$request_description = '在首页展示社区任务';
	$request_copyright = '<a href="http://www.comsenz.com" target="_blank">Comsenz Inc.</a>';

}

?>
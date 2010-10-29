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

function task_apply($task = array()) {
	global $_G;

	require_once libfile('task/'.$task['scriptname'], 'class');
	$taskclassname = 'task_'.$task['scriptname'];
	$taskclass = new $taskclassname;
	if(method_exists($taskclass, 'condition')) {
		$taskclass->condition();
	}
	DB::query("REPLACE INTO ".DB::table('common_mytask')." (uid, username, taskid, csc, dateline)
		VALUES ('$_G[uid]', '$_G[username]', '$task[taskid]', '0\$_G[timestamp]', '$_G[timestamp]')");
	DB::query("UPDATE ".DB::table('common_task')." SET applicants=applicants+1 WHERE taskid='$task[taskid]'", 'UNBUFFERED');
	if(method_exists($taskclass, 'preprocess')) {
		$taskclass->preprocess($task);
	}
}

function task_reward($task = array()) {
	switch($task['reward']) {
		case 'credit': return task_reward_credit($task['prize'], $task['bonus']); break;
		case 'magic': return task_reward_magic($task['prize'], $task['bonus']); break;
		case 'medal': return task_reward_medal($task['prize'], $task['bonus']); break;
		case 'invite': return task_reward_invite($task['bonus'], $task['prize']); break;
		case 'group': return task_reward_group($task['prize'], $task['bonus']); break;
	}
}

function task_reward_credit($extcreditid, $credits) {
	global $_G;

	$creditsarray[$extcreditid] = $credits;
	updatemembercount($_G['uid'], $creditsarray, 1, 'TRC', $taskid);

}

function task_reward_magic($magicid, $num) {
	global $_G;

	if(DB::result_first("SELECT COUNT(*) FROM ".DB::table('forum_membermagic')." WHERE magicid='$magicid' AND uid='$_G[uid]'")) {
		DB::query("UPDATE ".DB::table('forum_membermagic')." SET num=num+'$num' WHERE magicid='$magicid' AND uid='$_G[uid]'", 'UNBUFFERED');
	} else {
		DB::query("INSERT INTO ".DB::table('forum_membermagic')." (uid, magicid, num) VALUES ('$_G[uid]', '$magicid', '$num')");
	}
}

function task_reward_medal($medalid, $day) {
	global $_G;

	$medals = DB::result_first("SELECT medals FROM ".DB::table('common_member_field_forum')." WHERE uid='$_G[uid]'");
	$medalsnew = $medals ? $medals."\t".$medalid : $medalid;
	DB::query("UPDATE ".DB::table('common_member_field_forum')." SET medals='$medalsnew' WHERE uid='$_G[uid]'", 'UNBUFFERED');
	DB::query("INSERT INTO ".DB::table('forum_medallog')." (uid, medalid, type, dateline, expiration, status) VALUES ('$_G[uid]', '$medalid', '0', '$_G[timestamp]', '".($day ? TIMESTAMP + $day * 86400 : '')."', '1')");
}

function task_reward_invite($day, $num) {
	global $_G;

	$expiration = TIMESTAMP + $day * 86400;
	$invitecodes = '';
	$comma = '<br />';
	for($i = 1; $i <= $num; $i++) {
		$invitecode = substr(md5($_G['uid'].TIMESTAMP.random(6)), 0, 10).random(6);
		DB::query("INSERT INTO ".DB::table('common_invite')." (uid, dateline, expiration, inviteip, invitecode) VALUES ('$_G[uid]', '$_G[timestamp]', '$expiration', '$_G[clientip]', '$invitecode')", 'UNBUFFERED');
		$invitecodes .= $comma.'<b>'.$invitecode.'</b>';
	}
	return $invitecodes;
}

function task_reward_group($gid, $day = 0) {
	global $_G;

	$exists = FALSE;
	if($_G['forum_extgroupids']) {
		$_G['forum_extgroupids'] = explode("\t", $_G['forum_extgroupids']);
		if(in_array($gid, $_G['forum_extgroupids'])) {
			$exists = TRUE;
		} else {
			$_G['forum_extgroupids'][] = $gid;
		}
		$_G['forum_extgroupids'] = implode("\t", $_G['forum_extgroupids']);
	} else {
		$_G['forum_extgroupids'] = $gid;
	}

	DB::query("UPDATE ".DB::table('common_member')." SET extgroupids='".$_G['forum_extgroupids']."' WHERE uid='$_G[uid]'", 'UNBUFFERED');

	if($day) {
		$groupterms = DB::result_first("SELECT groupterms FROM ".DB::table('common_member_field_forum')." WHERE uid='$_G[uid]'");
		$groupterms = $groupterms ? unserialize($groupterms) : array();
		$groupterms['ext'][$gid] = $exists && $groupterms['ext'][$gid] ? max($groupterms['ext'][$gid], TIMESTAMP + $day * 86400) : TIMESTAMP + $day * 86400;
		DB::query("UPDATE ".DB::table('common_member_field_forum')." SET groupterms='".addslashes(serialize($groupterms))."' WHERE uid='$_G[uid]'", 'UNBUFFERED');
	}
}

?>
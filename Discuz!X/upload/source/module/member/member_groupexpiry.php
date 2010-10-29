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

define('NOROBOT', TRUE);

if($_G['uid']) {

	if(!$_G['member']['groupexpiry']) {
		showmessage('group_expiry_disabled');
	}

	$groupterms = unserialize(DB::result_first("SELECT groupterms FROM ".DB::table('common_member_field_forum')." WHERE uid='$_G[uid]'"));

	$expgrouparray = $expirylist = $termsarray = array();

	if(!empty($groupterms['ext']) && is_array($groupterms['ext'])) {
		$termsarray = $groupterms['ext'];
	}
	if(!empty($groupterms['main']['time']) && (empty($termsarray[$_G['groupid']]) || $termsarray[$_G['groupid']] > $groupterm['main']['time'])) {
		$termsarray[$_G['groupid']] = $groupterms['main']['time'];
	}

	foreach($termsarray as $expgroupid => $expiry) {
		if($expiry <= TIMESTAMP) {
			$expgrouparray[] = $expgroupid;
		}
	}

	if(!empty($groupterms['ext'])) {
		foreach($groupterms['ext'] as $extgroupid => $time) {
			$expirylist[$extgroupid] = array('time' => dgmdate($time, 'd'), 'type' => 'ext');
		}
	}

	if(!empty($groupterms['main'])) {
		$expirylist[$_G['groupid']] = array('time' => dgmdate($groupterms['main']['time'], 'd'), 'type' => 'main');
	}

	if($expirylist) {
		$query = DB::query("SELECT groupid, grouptitle FROM ".DB::table('common_usergroup')." WHERE groupid IN (".implode(',', array_keys($expirylist)).")");
		while($group = DB::fetch($query)) {
			$expirylist[$group['groupid']]['grouptitle'] = in_array($group['groupid'], $expgrouparray) ? '<s>'.$group['grouptitle'].'</s>' : $group['grouptitle'];
		}
	} else {
		DB::query("UPDATE ".DB::table('common_member')." SET groupexpiry='0' WHERE uid='$_G[uid]'");
	}

	if($expgrouparray) {

		$extgroupidarray = array();
		foreach(explode("\t", $_G['forum_extgroupids']) as $extgroupid) {
			if(($extgroupid = intval($extgroupid)) && !in_array($extgroupid, $expgrouparray)) {
				$extgroupidarray[] = $extgroupid;
			}
		}

		$groupidnew = $_G['groupid'];
		$adminidnew = $_G['adminid'];
		foreach($expgrouparray as $expgroupid) {
			if($expgroupid == $_G['groupid']) {
				if(!empty($groupterms['main']['groupid'])) {
					$groupidnew = $groupterms['main']['groupid'];
					$adminidnew = $groupterms['main']['adminid'];
				} else {
					$groupidnew = DB::result_first("SELECT groupid FROM ".DB::table('common_usergroup')." WHERE type='member' AND '".$_G['member']['credits']."'>=creditshigher AND '$credits'<creditslower LIMIT 1");
					if(in_array($_G['adminid'], array(1, 2, 3))) {
						$query = DB::query("SELECT groupid FROM ".DB::table('common_usergroup')." WHERE groupid IN ('".implode('\',\'', $extgroupidarray)."') AND radminid='$_G[adminid]' LIMIT 1");
						$adminidnew = (DB::num_rows($query)) ? $_G['adminid'] : 0;
					} else {
						$adminidnew = 0;
					}
				}
				unset($groupterms['main']);
			}
			unset($groupterms['ext'][$expgroupid]);
		}

		require_once libfile('function/forum');
		$groupexpirynew = groupexpiry($groupterms);
		$extgroupidsnew = implode("\t", $extgroupidarray);
		$grouptermsnew = addslashes(serialize($groupterms));

		DB::query("UPDATE ".DB::table('common_member')." SET adminid='$adminidnew', groupid='$groupidnew', extgroupids='$extgroupidsnew', groupexpiry='$groupexpirynew' WHERE uid='$_G[uid]'");
		DB::query("UPDATE ".DB::table('common_member_field_forum')." SET groupterms='$grouptermsnew' WHERE uid='$_G[uid]'");

	}

	include template('member/groupexpiry');

} else {

	showmessage('undefined_action');

}

?>
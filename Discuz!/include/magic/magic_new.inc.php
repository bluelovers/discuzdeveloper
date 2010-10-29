<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id$
*/

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$getmagicdata = $magicperm['presentcontent'];

if(submitcheck('usesubmit')) {
	
	foreach($getmagicdata as $getmagicid => $magicdata) {
		$totalweight = $magicdata['num'] * $magicdata['weight'];
		getmagic($getmagicid, $magicdata['num'], $magicdata['weight'], $totalweight, $discuz_uid, 0, 1);
	}

	usemagic($magicid, $magic['num']);
	updatemagiclog($magicid, '2', '1', '0', $tid);
	updatemagicthreadlog($tid, $magicid, $magic['identifier']);
	showmessage('magics_operation_succeed', '', 1);

}

function showmagic() {
	global $lang;
	magicshowtips($lang['NEW_info'], $lang['option']);
}

?>
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

$do = in_array($_G['gp_do'], array('buy', 'exit', 'switch', 'list')) ? trim($_G['gp_do']) : '';
$extgroupids = $_G['member']['extgroupids'] ? explode("\t", $_G['member']['extgroupids']) : array();
space_merge($space, 'count');
$credits = $space['credits'];
$forumselect = '';

if(in_array($do, array('buy', 'exit'))) {
	$groupid = intval($_G['gp_groupid']);

	$group = DB::fetch_first("SELECT groupid, type, system, grouptitle FROM ".DB::table('common_usergroup')." WHERE groupid='$groupid' AND type='special' AND system<>'private' AND radminid='0'");
	if(empty($group)) {
		showmessage('undefined_action');
	}
	$join = !in_array($group['groupid'], $extgroupids);
	$group['dailyprice'] = $group['minspan'] = 0;

	if($group['system'] != 'private') {
		list($group['dailyprice'], $group['minspan']) = explode("\t", $group['system']);
		if($group['dailyprice'] > -1 && $group['minspan'] == 0) {
			 $group['minspan'] = 1;
		}
	}
	$creditstrans = $_G['setting']['creditstrans'];
	if(!isset($_G['setting']['creditstrans'])) {
		showmessage('credits_transaction_disabled');
	}

	if(!submitcheck('buysubmit')) {
		$usermoney = $space['extcredits'.$creditstrans];
		$usermaxdays = $group['dailyprice'] > 0 ? round($usermoney / $group['dailyprice']) : 0;
		$group['minamount'] = $group['dailyprice'] * $group['minspan'];
	} else {
		$groupterms = unserialize(DB::result_first("SELECT groupterms FROM ".DB::table('common_member_field_forum')." WHERE uid='$_G[uid]'"));
		require_once libfile('function/forum');
		if($join) {
			$extgroupidsarray = array();
			foreach(array_unique(array_merge($extgroupids, array($groupid))) as $extgroupid) {
				if($extgroupid) {
					$extgroupidsarray[] = $extgroupid;
				}
			}
			$extgroupidsnew = implode("\t", $extgroupidsarray);
			if($group['dailyprice']) {
				if(($days = intval($_G['gp_days'])) < $group['minspan']) {
					showmessage('usergroups_span_invalid', '', array('minspan' => $group['minspan']));
				}

				if($space['extcredits'.$creditstrans] - ($amount = $days * $group['dailyprice']) < ($minbalance = 0)) {
					showmessage('credits_balance_insufficient', '', array('minbalance' => $minbalance));
				}

				$groupexpirynew = TIMESTAMP + $days * 86400;
				$groupterms['ext'][$groupid] = $groupexpirynew;

				$groupexpirynew = groupexpiry($groupterms);

				DB::query("UPDATE ".DB::table('common_member')." SET groupexpiry='$groupexpirynew', extgroupids='$extgroupidsnew' WHERE uid='$_G[uid]'");
				updatemembercount($_G['uid'], array('extcredits'.$creditstrans => "-$amount"));

				DB::query("UPDATE ".DB::table('common_member_field_forum')." SET groupterms='".addslashes(serialize($groupterms))."' WHERE uid='$_G[uid]'");

			} else {
				DB::query("UPDATE ".DB::table('common_member')." SET extgroupids='$extgroupidsnew' WHERE uid='$_G[uid]'");
			}

			showmessage('usergroups_join_succeed', "home.php?mod=spacecp&ac=credit&op=usergroup&perms=$_G[gp_perms]&tab=$_G[gp_tab]", array('group' => $group['grouptitle']), array('showdialog' => 3, 'showmsg' => true, 'locationtime' => 2));

		} else {

			if($groupid != $_G['groupid']) {
				if(isset($groupterms['ext'][$groupid])) {
					unset($groupterms['ext'][$groupid]);
				}
				$groupexpirynew = groupexpiry($groupterms);
				DB::query("UPDATE ".DB::table('common_member_field_forum')." SET groupterms='".addslashes(serialize($groupterms))."' WHERE uid='$_G[uid]'");
			} else {
				$groupexpirynew = 'groupexpiry';
			}

			$extgroupidsarray = array();
			foreach($extgroupids as $extgroupid) {
				if($extgroupid && $extgroupid != $groupid) {
					$extgroupidsarray[] = $extgroupid;
				}
			}
			$extgroupidsnew = implode("\t", array_unique($extgroupidsarray));
			DB::query("UPDATE ".DB::table('common_member')." SET groupexpiry='$groupexpirynew', extgroupids='$extgroupidsnew' WHERE uid='$_G[uid]'");

			showmessage('usergroups_exit_succeed', "home.php?mod=spacecp&ac=credit&op=usergroup&perms=$_G[gp_perms]&tab=$_G[gp_tab]", array('group' => $group['grouptitle']), array('showdialog' => 3, 'showmsg' => true, 'locationtime' => 2));

		}

	}

} elseif($do == 'switch') {

	$groupid = intval($_G['gp_groupid']);
	if(!in_array($groupid, $extgroupids)) {
		showmessage('undefined_action', NULL, 'HALTED');
	}
	$group = DB::fetch_first("SELECT * FROM ".DB::table('common_usergroup')." WHERE groupid='$groupid'");
	if(submitcheck('groupsubmit')) {
		$extgroupidsnew = $_G['groupid'];
		foreach($extgroupids as $extgroupid) {
			if($extgroupid && $extgroupid != $groupid) {
				$extgroupidsnew .= "\t".$extgroupid;
			}
		}

		DB::query("UPDATE ".DB::table('common_member')." SET groupid='$groupid', adminid='$group[radminid]', extgroupids='$extgroupidsnew' WHERE uid='$_G[uid]'");
		showmessage('usergroups_switch_succeed', "home.php?mod=spacecp&ac=credit&op=usergroup&perms=$_G[gp_perms]&tab=$_G[gp_tab]", array('group' => $group['grouptitle']), array('showdialog' => 3, 'showmsg' => true, 'locationtime' => 2));
	}

} else {

	$language = lang('forum/misc');
	$permlang = $language;
	unset($language);
	$maingroup = $_G['group'];
	$ptype = in_array($_G['gp_ptype'], array(0, 1, 2)) ? intval($_G['gp_ptype']) : 0;
	foreach($_G['cache']['usergroups'] as $gid => $value) {
		$cachekey[] = 'usergroup_'.$gid;
	}
	loadcache($cachekey);
	$_G['group'] = $maingroup;
	$grouplist = array();
	$groupterms = unserialize(DB::result_first("SELECT groupterms FROM ".DB::table('common_member_field_forum')." WHERE uid='$_G[uid]'"));

	$switchmaingroup = $_G['group']['grouppublic'] || isset($groupterms['ext']) ? 1 : 0;
	foreach($_G['cache']['usergroups'] as $gid => $group) {
		$group['type'] = $group['type'] == 'special' && $_G['cache']['usergroup_'.$gid]['radminid'] ? 'specialadmin' : $group['type'];
		$grouplist[$group['type']][$gid] = $_G['cache']['usergroup_'.$gid];
		if($_G['cache']['usergroup_'.$gid]['radminid']) {
			$admingids[] = $gid;
		}
	}

	if($ptype == 1) {
		require_once libfile('function/forum');
		require_once libfile('function/forumlist');
		$perms = array('viewperm', 'postperm', 'replyperm', 'getattachperm', 'postattachperm', 'postimageperm');
		$fid = intval($_G['gp_fid']);
		if(!$fid) {
			$fid = DB::result(DB::query("SELECT fid FROM ".DB::table('forum_forum')." WHERE status='1' AND type='forum'"), 0);
		}
		$query = DB::query("SELECT fid, viewperm, postperm, replyperm, getattachperm, postattachperm, postimageperm FROM ".DB::table('forum_forumfield')." WHERE fid='$fid'");
		while($forum = DB::fetch($query)) {
			foreach($perms as $perm) {
				if($forum[$perm]) {
					$groupids = explode("\t", $forum[$perm]);
					foreach($groupids as $id) {
						if($id) {
							$forumperm[$id][$perm] = 1;
						}
					}
				} else {
					foreach($_G['cache']['usergroups'] as $id => $data) {
						if($id == 7 && ($perm == 'viewperm' || $perm == 'getattachperm') || $id != 7) {
							$forumperm[$id][$perm] = 1;
						}
					}
				}
			}
		}
		$forumselect = "<select name=\"forumid\" onchange=\"showForumPerm(this.value)\" class=\"ps\" style=\"width:155px\">\n".str_replace('%', '%%', forumselect(FALSE, 0, $fid, TRUE, FALSE)).'</select>';

	} elseif($ptype == 2) {
		$aperms = array('allowstickthread', 'allowdigestthread', 'allowbumpthread', 'allowhighlightthread', 'allowrecommendthread', 'allowstampthread', 'allowclosethread', 'allowmovethread', 'allowedittypethread', 'allowcopythread', 'allowmergethread', 'allowsplitthread', 'allowrepairthread', 'allowrefund', 'alloweditpoll', 'allowremovereward', 'alloweditactivity', 'allowedittrade', 'alloweditpost', 'allowwarnpost', 'allowbanpost', 'allowdelpost', 'allowviewreport', 'allowmodpost', 'allowmoduser', 'allowbanuser', 'allowbanip', 'allowedituser', 'allowmassprune', 'allowpostannounce', 'disablepostctrl', 'allowviewip');

		$query = DB::query("SELECT * FROM ".DB::table('common_admingroup')." WHERE admingid IN (".dimplode($admingids).")");
		while($group = DB::fetch($query)) {
			foreach($aperms as $perm) {
				$adminperm[$group['admingid']][$perm] = $group[$perm];
			}
		}
		if($adminperm[$maingroup['groupid']]) {
			$maingroup = array_merge($maingroup, $adminperm[$maingroup['groupid']]);
		}
	} else {

		$bperms = array('allowvisit','readaccess','allowinvisible','allowsearch','allowcstatus');
		$pperms = array('allowpost','allowreply','allowpostpoll','allowvote','allowpostreward','allowpostactivity','allowpostdebate','allowposttrade','maxsigsize','allowsigbbcode','allowsigimgcode','allowrecommend');
		$aperms = array('allowgetattach', 'allowpostattach', 'allowsetattachperm', 'maxspacesize', 'maxattachsize', 'maxsizeperday', 'maxattachnum', 'attachextensions');
		$sperms = array('allowblog', 'allowdoing', 'allowupload', 'allowshare', 'allowpoke', 'allowfriend', 'allowclick', 'allowmyop', 'allowcomment', 'allowstat', 'allowpostarticle');

		$allperms = array();
		$allkey = array_merge($bperms, $pperms, $aperms, $sperms);
		foreach($grouplist as $key => $groups) {
			foreach($groups as $gid => $group) {
				foreach($allkey as $id => $pkey) {
					if(in_array($pkey, array('maxattachsize', 'maxsizeperday', 'maxspacesize'))) {
						$group[$pkey] = sizecount($group[$pkey]);
					}
					$allperms[$pkey][$group['groupid']] = $group[$pkey];
				}
			}
		}

	}
	$publicgroup = array();
	$extgroupids[] = $_G['groupid'];
	$query = DB::query("SELECT * FROM ".DB::table('common_usergroup')." WHERE (type='special' AND system<>'private' AND radminid='0') OR groupid IN (".dimplode(array_unique($extgroupids)).") ORDER BY type, system");
	while($group = DB::fetch($query)) {
		$group['allowsetmain'] = in_array($group['groupid'], $extgroupids);
		$publicgroup[$group['groupid']] = $group;
	}
	$_G['gp_perms'] = 'member';
}

include_once template("home/spacecp_credit_usergroup");

?>
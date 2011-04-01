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

if(!$_G['group']['allowdelpost']) {
	showmessage('no_privilege_delpost');
}

$topiclist = $_G['gp_topiclist'];
$modpostsnum = count($topiclist);
if(!($deletepids = dimplode($topiclist))) {
	showmessage('admin_delpost_invalid');
} elseif(!$_G['group']['allowdelpost'] || !$_G['tid']) {
	showmessage('admin_nopermission');
}  else {
	$posttable = getposttablebytid($_G['tid']);
	$query = DB::query("SELECT pid FROM ".DB::table($posttable)." WHERE pid IN ($deletepids) AND first='1'");
	if(DB::num_rows($query)) {
		dheader("location: $_G[siteurl]forum.php?mod=topicadmin&action=moderate&operation=delete&optgroup=3&fid=$_G[fid]&moderate[]=$thread[tid]&inajax=yes".($_G['gp_infloat'] ? "&infloat=yes&handlekey={$_G['gp_handlekey']}" : ''));
	}
}

if(!submitcheck('modsubmit')) {

	$deleteid = '';
	foreach($topiclist as $id) {
		$deleteid .= '<input type="hidden" name="topiclist[]" value="'.$id.'" />';
	}

	include template('forum/topicadmin_action');

} else {

	$reason = checkreasonpm();

	$comma = '';
	$pids = $posts = $uidarray = $puidarray = $auidarray = array();
	$losslessdel = $_G['setting']['losslessdel'] > 0 ? TIMESTAMP - $_G['setting']['losslessdel'] * 86400 : 0;
	$query = DB::query("SELECT pid, authorid, dateline, message, first FROM ".DB::table($posttable)." WHERE pid IN ($deletepids) AND tid='$_G[tid]'");
	while($post = DB::fetch($query)) {
		if(!$post['first']) {
			$pids[] = $post['pid'];
			$modpostsnum++;
		}
	}

	if($pids) {
		$pidimplode = dimplode($pids);
		if($_G['forum']['recyclebin']) {
			DB::query("UPDATE ".DB::table($posttable)." SET invisible='-5' WHERE pid IN ($pidimplode)");
			DB::delete('forum_postcomment', "rpid IN ($pidimplode)");
			manage_addnotify('verifyrecyclepost', $modpostsnum);
		} else {
			$logs = array();
			$query = DB::query("SELECT r.extcredits, r.score, p.authorid, p.author FROM ".DB::table('forum_ratelog')." r LEFT JOIN ".DB::table($posttable)." p ON r.pid=p.pid WHERE r.pid IN ($pidimplode)");
			while($author = DB::fetch($query)) {
				if($author['score'] > 0) {
					updatemembercount($author['authorid'], array($author['extcredits'] => -$author['score']));
					$author['score'] = $_G['setting']['extcredits'][$id]['title'].' '.-$author['score'].' '.$_G['setting']['extcredits'][$id]['unit'];
					$logs[] = dhtmlspecialchars("$_G[timestamp]\t{$_G[member][username]}\t$_G[adminid]\t$author[author]\t$author[extcredits]\t$author[score]\t$thread[tid]\t$thread[subject]\t$delpostsubmit");
				}
			}
			if(!empty($logs)) {
				writelog('ratelog', $logs);
				unset($logs);
			}

			if($have_replycredit = DB::fetch_first("SELECT * FROM ".DB::table('forum_replycredit')." WHERE tid = '$thread[tid]' LIMIT 1")) {
				$replycredit_del = array();
				$query = DB::query("SELECT authorid, replycredit FROM ".DB::table($posttable)." WHERE pid IN ($pidimplode)");
				while($result = DB::fetch($query)) {
					if($result['replycredit'] > 0 && $result['authorid'] > 0) {
						$replycredit_del[$result['authorid']] += $result['replycredit'];
					}
				}
				if(!empty($replycredit_del)) {
					foreach($replycredit_del AS $uid => $credit) {
						updatemembercount($uid, array($_G['setting']['creditstransextra'][10] => '-'.$credit), true);
					}
				}
			}

			require_once libfile('function/delete');
			deletepost($pids);
		}
	}

	updatethreadcount($_G['tid'], 1);
	updateforumcount($_G['fid']);

	$_G['forum']['threadcaches'] && deletethreadcaches($thread['tid']);

	$modaction = 'DLP';

	$resultarray = array(
	'redirect'	=> "forum.php?mod=viewthread&tid=$_G[tid]&page=$_G[gp_page]",
	'reasonpm'	=> ($sendreasonpm ? array('data' => $posts, 'var' => 'post', 'item' => 'reason_delete_post') : array()),
	'reasonvar'	=> array('tid' => $thread['tid'], 'subject' => $thread['subject'], 'modaction' => $modaction, 'reason' => stripslashes($reason)),
	'modtids'	=> 0,
	'modlog'	=> $thread
	);

}

?>
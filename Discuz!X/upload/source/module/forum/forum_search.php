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

require_once libfile('function/forumlist');
loadcache(array('forums', 'icons'));

$cachelife_time = 300;		// Life span for cache of searching in specified range of time
$cachelife_text = 3600;		// Life span for cache of text searching

$sdb = loadmultiserver('search');

$srchtype = empty($_G['gp_srchtype']) ? '' : trim($_G['gp_srchtype']);
$checkarray = array('posts' => '', 'trade' => '', 'threadsort' => '');

$searchid = isset($_G['gp_searchid']) ? intval($_G['gp_searchid']) : 0;

if($srchtype == 'trade' || $srchtype == 'threadsort') {
	$checkarray[$srchtype] = 'checked';
} elseif($srchtype == 'title' || $srchtype == 'fulltext') {
	$checkarray['posts'] = 'checked';
} else {
	$srchtype = '';
	$checkarray['posts'] = 'checked';
}

$srchtxt = $_G['gp_srchtxt'];
$srchuid = intval($_G['gp_srchuid']);
$srchuname = $_G['gp_srchuname'];
$srchfrom = intval($_G['gp_srchfrom']);
$before = intval($_G['gp_before']);
$srchfid = $_G['gp_srchfid'];

$keyword = isset($srchtxt) ? htmlspecialchars(trim($srchtxt)) : '';

$threadsorts = '';
if($srchtype == 'threadsort') {
	$query = DB::query("SELECT * FROM ".DB::table('forum_threadtype')." WHERE special='1' ORDER BY displayorder");
	while($type = DB::fetch($query)) {
		$threadsorts .= '<option value="'.$type['typeid'].'" '.($type['typeid'] == intval($sortid) ? 'selected=selected' : '').'>'.$type['name'].'</option>';
	}
}

$forumselect = forumselect('', '', '', TRUE);
if(!empty($srchfid) && !is_numeric($srchfid)) {
	$forumselect = str_replace('<option value="'.$srchfid.'">', '<option value="'.$srchfid.'" selected="selected">', $forumselect);
}

$disabled = array();
$disabled['title'] = !$_G['group']['allowsearch'] ? 'disabled' : '';
$disabled['fulltext'] = $_G['group']['allowsearch'] != 2 ? 'disabled' : '';

if(!submitcheck('searchsubmit', 1)) {

	include template('forum/search');

} else {

	if(!$_G['group']['allowsearch']) {

		showmessage('group_nopermission', NULL, array('grouptitle' => $_G['group']['grouptitle']), array('login' => 1));

	} elseif($srchtype == 'trade') {

		require_once libfile('search/trade', 'include');
		exit;

	} elseif($srchtype == 'threadsort' && $sortid) {

		require_once libfile('search/sort', 'include');
		exit;

	}

	$orderby = in_array($_G['gp_orderby'], array('dateline', 'replies', 'views')) ? $_G['gp_orderby'] : 'lastpost';
	$ascdesc = isset($_G['gp_ascdesc']) && $_G['gp_ascdesc'] == 'asc' ? 'asc' : 'desc';

	if(!empty($searchid)) {

		require_once libfile('function/misc');

		$page = max(1, intval($_G['gp_page']));
		$start_limit = ($page - 1) * $_G['tpp'];

		$index = $sdb->fetch_first("SELECT searchstring, keywords, threads, tids FROM ".DB::table('common_searchindex')." WHERE searchid='$searchid'");
		if(!$index) {
			showmessage('search_id_invalid');
		}

		$keyword = htmlspecialchars($index['keywords']);
		$keyword = $keyword != '' ? str_replace('+', ' ', $keyword) : '';

		$index['keywords'] = rawurlencode($index['keywords']);
		$searchstring = explode('|', $index['searchstring']);
		$index['searchtype'] = $searchstring[0];//preg_replace("/^([a-z]+)\|.*/", "\\1", $index['searchstring']);
		$srchuname = $searchstring[3];

		$threadlist = array();
		$query = $sdb->query("SELECT * FROM ".DB::table('forum_thread')." WHERE tid IN ($index[tids]) AND displayorder>='0' ORDER BY $orderby $ascdesc LIMIT $start_limit, $_G[tpp]");
		while($thread = $sdb->fetch_array($query)) {
			$threadlist[] = procthread($thread);
		}

		$multipage = multi($index['threads'], $_G['tpp'], $page, "forum.php?mod=search&searchid=$searchid&orderby=$orderby&ascdesc=$ascdesc&searchsubmit=yes");

		$url_forward = 'forum.php?mod=search&'.$_SERVER['QUERY_STRING'];

		include template('forum/search');

	} else {

		!($_G['group']['exempt'] & 2) && checklowerlimit('getattach');

		$srchuname = isset($_G['gp_srchuname']) ? trim($_G['gp_srchuname']) : '';

		if($_G['group']['allowsearch'] == 2 && $srchtype == 'fulltext') {
			periodscheck('searchbanperiods');
		} elseif($srchtype != 'title') {
			$srchtype = 'title';
		}

		$forumsarray = array();
		if(!empty($srchfid)) {
			foreach((is_array($srchfid) ? $srchfid : explode('_', $srchfid)) as $forum) {
				if($forum = intval(trim($forum))) {
					$forumsarray[] = $forum;
				}
			}
		}

		$fids = $comma = '';
		foreach($_G['cache']['forums'] as $fid => $forum) {
			if($forum['type'] != 'group' && (!$forum['viewperm'] && $_G['group']['readaccess']) || ($forum['viewperm'] && forumperm($forum['viewperm']))) {
				if(!$forumsarray || in_array($fid, $forumsarray)) {
					$fids .= "$comma'$fid'";
					$comma = ',';
				}
			}
		}

		$specials = $special ? implode(',', $special) : '';
		$srchfilter = in_array($_G['gp_srchfilter'], array('all', 'digest', 'top')) ? $_G['gp_srchfilter'] : 'all';

		$searchstring = $srchtype.'|'.addslashes($srchtxt).'|'.intval($srchuid).'|'.$srchuname.'|'.addslashes($fids).'|'.intval($srchfrom).'|'.intval($before).'|'.$srchfilter.'|'.$specials;
		$searchindex = array('id' => 0, 'dateline' => '0');

		$query = $sdb->query("SELECT searchid, dateline,
			('".$_G['setting']['searchctrl']."'<>'0' AND ".(empty($_G['uid']) ? "useip='$_G[clientip]'" : "uid='$_G[uid]'")." AND $_G[timestamp]-dateline<".$_G['setting']['searchctrl'].") AS flood,
			(searchstring='$searchstring' AND expiration>'$_G[timestamp]') AS indexvalid
			FROM ".DB::table('common_searchindex')."
			WHERE ('".$_G['setting']['searchctrl']."'<>'0' AND ".(empty($_G['uid']) ? "useip='$_G[clientip]'" : "uid='$_G[uid]'")." AND $_G[timestamp]-dateline<".$_G['setting']['searchctrl'].") OR (searchstring='$searchstring' AND expiration>'$_G[timestamp]')
			ORDER BY flood");

		while($index = $sdb->fetch_array($query)) {
			if($index['indexvalid'] && $index['dateline'] > $searchindex['dateline']) {
				$searchindex = array('id' => $index['searchid'], 'dateline' => $index['dateline']);
				break;
			} elseif($_G['adminid'] != '1' && $index['flood']) {
				showmessage('search_ctrl', 'forum.php?mod=search', array('searchctrl' => $_G['setting']['searchctrl']));
			}
		}

		if($searchindex['id']) {

			$searchid = $searchindex['id'];

		} else {

			if(!$srchtxt && !$srchuid && !$srchuname && !$srchfrom && !in_array($srchfilter, array('digest', 'top')) && !is_array($special)) {
				showmessage('search_invalid', 'forum.php?mod=search');
			} elseif(isset($srchfid) && $srchfid != 'all' && !(is_array($srchfid) && in_array('all', $srchfid)) && empty($forumsarray)) {
				showmessage('search_forum_invalid', 'forum.php?mod=search');
			} elseif(!$fids) {
				showmessage('group_nopermission', NULL, array('grouptitle' => $_G['group']['grouptitle']), array('login' => 1));
			}

			if($_G['adminid'] != '1' && $_G['setting']['maxspm']) {
				if(($sdb->result_first("SELECT COUNT(*) FROM ".DB::table('common_searchindex')." WHERE dateline>'$_G[timestamp]'-60")) >= $_G['setting']['maxspm']) {
					showmessage('search_toomany', 'forum.php?mod=search', array('maxspm' => $_G['setting']['maxspm']));
				}
			}

			$digestltd = $srchfilter == 'digest' ? "t.digest>'0' AND" : '';
			$topltd = $srchfilter == 'top' ? "AND t.displayorder>'0'" : "AND t.displayorder>='0'";

			if(!empty($srchfrom) && empty($srchtxt) && empty($srchuid) && empty($srchuname)) {

				$searchfrom = $before ? '<=' : '>=';
				$searchfrom .= TIMESTAMP - $srchfrom;
				$sqlsrch = "FROM ".DB::table('forum_thread')." t WHERE $digestltd t.fid IN ($fids) $topltd AND t.lastpost$searchfrom";
				$expiration = TIMESTAMP + $cachelife_time;
				$keywords = '';

			} else {

				$sqlsrch = $srchtype == 'fulltext' ?
				"FROM ".DB::table('forum_post')." p, ".DB::table('forum_thread')." t WHERE $digestltd t.fid IN ($fids) $topltd AND p.tid=t.tid AND p.invisible='0'" :
				"FROM ".DB::table('forum_thread')." t WHERE $digestltd t.fid IN ($fids) $topltd";

				if($srchuname) {
					$srchuid = $comma = '';
					$srchuname = str_replace('*', '%', addcslashes($srchuname, '%_'));
					$query = DB::query("SELECT uid FROM ".DB::table('common_member')." WHERE username LIKE '".str_replace('_', '\_', $srchuname)."' LIMIT 50");
					while($member = DB::fetch($query)) {
						$srchuid .= "$comma'$member[uid]'";
						$comma = ', ';
					}
					if(!$srchuid) {
						$sqlsrch .= ' AND 0';
					}
				} elseif($srchuid) {
					$srchuid = "'$srchuid'";
				}

				if($srchtxt) {
					if(preg_match("(AND|\+|&|\s)", $srchtxt) && !preg_match("(OR|\|)", $srchtxt)) {
						$andor = ' AND ';
						$sqltxtsrch = '1';
						$srchtxt = preg_replace("/( AND |&| )/is", "+", $srchtxt);
					} else {
						$andor = ' OR ';
						$sqltxtsrch = '0';
						$srchtxt = preg_replace("/( OR |\|)/is", "+", $srchtxt);
					}
					$srchtxt = str_replace('*', '%', addcslashes($srchtxt, '%_'));
					foreach(explode('+', $srchtxt) as $text) {
						$text = trim($text);
						if($text) {
							$sqltxtsrch .= $andor;
							$sqltxtsrch .= $srchtype == 'fulltext' ? "(p.message LIKE '%".str_replace('_', '\_', $text)."%' OR p.subject LIKE '%$text%')" : "t.subject LIKE '%$text%'";
						}
					}
					$sqlsrch .= " AND ($sqltxtsrch)";
				}

				if($srchuid) {
					$sqlsrch .= ' AND '.($srchtype == 'fulltext' ? 'p' : 't').".authorid IN ($srchuid)";
				}

				if(!empty($srchfrom)) {
					$searchfrom = ($before ? '<=' : '>=').(TIMESTAMP - $srchfrom);
					$sqlsrch .= " AND t.lastpost$searchfrom";
				}

				if(!empty($specials)) {
					$sqlsrch .=  " AND special IN (".dimplode($special).")";
				}

				$keywords = str_replace('%', '+', $srchtxt);
				$expiration = TIMESTAMP + $cachelife_text;

			}

			$threads = $tids = 0;
			$_G['setting']['maxsearchresults'] = $_G['setting']['maxsearchresults'] ? intval($_G['setting']['maxsearchresults']) : 500;
			$query = $sdb->query("SELECT ".($srchtype == 'fulltext' ? 'DISTINCT' : '')." t.tid, t.closed, t.author $sqlsrch ORDER BY tid DESC LIMIT ".$_G['setting']['maxsearchresults']);
			while($thread = $sdb->fetch_array($query)) {
				if($thread['closed'] <= 1 && $thread['author']) {
					$tids .= ','.$thread['tid'];
					$threads++;
				}
			}
			DB::free_result($query);

			DB::query("INSERT INTO ".DB::table('common_searchindex')." (keywords, searchstring, useip, uid, dateline, expiration, threads, tids)
					VALUES ('$keywords', '$searchstring', '$_G[clientip]', '$_G[uid]', '$_G[timestamp]', '$expiration', '$threads', '$tids')");
			$searchid = DB::insert_id();

			!($_G['group']['exempt'] & 2) && updatecreditbyaction('search');
		}

		showmessage('search_redirect', "forum.php?mod=search&searchid=$searchid&orderby=$orderby&ascdesc=$ascdesc&searchsubmit=yes");

	}

}

?>
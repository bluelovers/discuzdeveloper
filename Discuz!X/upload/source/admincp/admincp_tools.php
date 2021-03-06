<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id$
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

cpheader();

if($operation == 'updatecache') {

	$step = max(1, intval($_G['gp_step']));
	shownav('tools', 'nav_updatecache');
	showsubmenusteps('nav_updatecache', array(
		array('nav_updatecache_confirm', $step == 1),
		array('nav_updatecache_verify', $step == 2),
		array('nav_updatecache_completed', $step == 3)
	));

	showtips('tools_updatecache_tips');

	if($step == 1) {
		cpmsg("<input type=\"checkbox\" name=\"type[]\" value=\"data\" id=\"datacache\" class=\"checkbox\" checked /><label for=\"datacache\">".$lang[tools_updatecache_data]."</label><input type=\"checkbox\" name=\"type[]\" value=\"tpl\" id=\"tplcache\" class=\"checkbox\" checked /><label for=\"tplcache\">".$lang[tools_updatecache_tpl]."</label><input type=\"checkbox\" name=\"type[]\" value=\"blockclass\" id=\"blockclasscache\" class=\"checkbox\" /><label for=\"blockclasscache\">".$lang[tools_updatecache_blockclass].'</label>', 'action=tools&operation=updatecache&step=2', 'form', '', FALSE);
	} elseif($step == 2) {
		$type = implode('_', (array)$_G['gp_type']);
		cpmsg(cplang('tools_updatecache_waiting'), "action=tools&operation=updatecache&step=3&type=$type", 'loading', '', FALSE);
	} elseif($step == 3) {
		$type = explode('_', $_G['gp_type']);
		if(in_array('data', $type)) {
			updatecache();
			require_once libfile('function/group');
			$groupindex['randgroupdata'] = $randgroupdata = grouplist('lastupdate', array('ff.membernum', 'ff.icon'), 80);
			$groupindex['topgrouplist'] = $topgrouplist = grouplist('activity', array('f.commoncredits', 'ff.membernum', 'ff.icon'), 10);
			$groupindex['updateline'] = TIMESTAMP;
			$groupdata = DB::fetch_first("SELECT SUM(todayposts) AS todayposts, COUNT(fid) AS groupnum FROM ".DB::table('forum_forum')." WHERE status='3' AND type='sub'");
			$groupindex['todayposts'] = $groupdata['todayposts'];
			$groupindex['groupnum'] = $groupdata['groupnum'];
			save_syscache('groupindex', $groupindex);
			DB::query("TRUNCATE ".DB::table('forum_groupfield'));
		}
		if(in_array('tpl', $type) && $_G['config']['output']['tplrefresh']) {
			$tpl = dir(DISCUZ_ROOT.'./data/template');
			while($entry = $tpl->read()) {
				if(preg_match("/\.tpl\.php$/", $entry)) {
					@unlink(DISCUZ_ROOT.'./data/template/'.$entry);
				}
			}
			$tpl->close();
		}
		if(in_array('blockclass', $type)) {
			include_once libfile('function/block');
			blockclass_cache();
		}
		cpmsg('update_cache_succeed', '', 'succeed', '', FALSE);
	}

} elseif($operation == 'fileperms') {

	$step = max(1, intval($_G['gp_step']));

	shownav('tools', 'nav_fileperms');
	showsubmenusteps('nav_fileperms', array(
		array('nav_fileperms_confirm', $step == 1),
		array('nav_fileperms_verify', $step == 2),
		array('nav_fileperms_completed', $step == 3)
	));

	if($step == 1) {
		cpmsg(cplang('fileperms_check_note'), 'action=tools&operation=fileperms&step=2', 'button', '', FALSE);
	} elseif($step == 2) {
		cpmsg(cplang('fileperms_check_waiting'), 'action=tools&operation=fileperms&step=3', 'loading', '', FALSE);
	} elseif($step == 3) {

		showtips('fileperms_tips');

		$entryarray = array(
			'data',
			'data/attachment',
			'data/cache',
			'data/log',
			'data/template',
			'data/threadcache',
			'data/diy'
		);

		foreach(array('data/cache', 'data/log', 'data/template', 'data/attachment', 'data/diy') as $directory) {
			getdirentry($directory);
		}

		$result = '';
		foreach($entryarray as $entry) {
			$fullentry = DISCUZ_ROOT.'./'.$entry;
			if(!is_dir($fullentry) && !file_exists($fullentry)) {
				continue;
			} else {
				if(!is_writeable($fullentry)) {
					$result .= '<li class="error">'.(is_dir($fullentry) ? $lang['dir'] : $lang['file'])." ./$entry $lang[fileperms_unwritable]</li>";
				}
			}
		}
		$result = $result ? $result : '<li>'.$lang['fileperms_check_ok'].'</li>';
		echo '<div class="colorbox"><ul class="fileperms">'.$result.'</ul></div>';
	}
}

function getdirentry($directory) {
	global $entryarray;
	if(!file_exists(DISCUZ_ROOT.'./'.$directory)) {
		return;
	}
	$dir = dir(DISCUZ_ROOT.'./'.$directory);
	while($entry = $dir->read()) {
		if(!in_array($entry, array('.', '..', 'index.htm'))) {
			if(is_dir(DISCUZ_ROOT.'./'.$directory.'/'.$entry)) {
				getdirentry($directory."/".$entry);
			}
			$entryarray[] = $directory.'/'.$entry;
		}
	}
	$dir->close();
}

function jsinsertunit() {

?>
<script type="text/JavaScript">
function isUndefined(variable) {
	return typeof variable == 'undefined' ? true : false;
}

function insertunit(text, obj) {
	if(!obj) {
		obj = 'jstemplate';
	}
	$(obj).focus();
	if(!isUndefined($(obj).selectionStart)) {
		var opn = $(obj).selectionStart + 0;
		$(obj).value = $(obj).value.substr(0, $(obj).selectionStart) + text + $(obj).value.substr($(obj).selectionEnd);
	} else if(document.selection && document.selection.createRange) {
		var sel = document.selection.createRange();
		sel.text = text.replace(/\r?\n/g, '\r\n');
		sel.moveStart('character', -strlen(text));
	} else {
		$(obj).value += text;
	}
}
</script>
<?php

}

?>
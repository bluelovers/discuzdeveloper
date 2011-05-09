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

if($_G['adminid'] != 1 && $_G['setting']) {
	exit('Access Denied');
}

require_once libfile('function/cache');
updatecache();

require_once libfile('function/block');
blockclass_cache();

if($_G['config']['output']['tplrefresh']) {
	$tpl = dir(DISCUZ_ROOT.'./data/template');
	while($entry = $tpl->read()) {
		if(preg_match("/\.tpl\.php$/", $entry)) {
			@unlink(DISCUZ_ROOT.'./data/template/'.$entry);
		}
	}
	$tpl->close();
}

$plugins = array('qqconnect', 'cloudstat', 'soso_smilies');

require_once libfile('function/plugin');
require_once libfile('function/admincp');

foreach($plugins as $pluginid) {
	$func = 'plugininstall';
	$importfile = DISCUZ_ROOT.'./source/plugin/'.$pluginid.'/discuz_plugin_'.$pluginid.'.xml';
	if(!file_exists($importfile)) {
		continue;
	}
	$importtxt = @implode('', file($importfile));
	$pluginarray = getimportdata('Discuz! Plugin', $importtxt);
	$plugin = DB::fetch_first("SELECT identifier, modules, version FROM ".DB::table('common_plugin')." WHERE identifier='$pluginid' LIMIT 1");
	if($plugin) {
		$modules = unserialize($plugin['modules']);
		if($modules['system'] != 2) {
			DB::delete('common_plugin', "identifier='$pluginid'");
		} elseif($pluginarray['plugin']['version'] != $plugin['version']) {
			$func = 'pluginupgrade';
		} else {
			continue;
		}
	}
	$pluginarray['plugin']['modules'] = unserialize(dstripslashes($pluginarray['plugin']['modules']));
	$pluginarray['plugin']['modules']['system'] = 2;
	$pluginarray['plugin']['modules'] = addslashes(serialize($pluginarray['plugin']['modules']));
	$func($pluginarray);
}

?>
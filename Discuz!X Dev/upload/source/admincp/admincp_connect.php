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

$query = DB::query("SELECT * FROM ".DB::table('common_setting')." WHERE skey IN ('connect', 'connectsiteid', 'connectsitekey', 'regconnect')");
while($row = DB::fetch($query)) {
	$setting[$row['skey']] = $row['svalue'];
}
$setting['connect'] = (array)unserialize($setting['connect']);

$params = array(
	's_id' => $setting['connectsiteid'],
	'response_type' => 'php'
);
$params['sig'] = connect_get_sig($params, $setting['connectsiteid'].'|'.$setting['connectsitekey']);
$staturl = $setting['connectsiteid'] ? 'http://connect.manyou.com/site/stats/index?'.http_build_query($params) : '';

if(!submitcheck('connectsubmit')) {

	shownav('founder', 'menu_setting_qqconnect');
	showsubmenu('menu_setting_qqconnect');

	include_once libfile('function/forumlist');
	$forumselect = array();
	foreach(array('feed', 't') as $k) {
		$forumselect[$k] = '<select name="connectnew['.$k.'][fids][]" multiple="multiple" size="10">'.forumselect(FALSE, 0, 0, TRUE).'</select>';
		if($setting['connect'][$k]['fids']) {
			foreach($setting['connect'][$k]['fids'] as $v) {
				$forumselect[$k] = str_replace('<option value="'.$v.'">', '<option value="'.$v.'" selected>', $forumselect[$k]);
			}
		}
	}

	showformheader('connect&operation=setting');
	showtableheader();
	showsetting('connect_setting_allow', 'connectnew[allow]', $setting['connect']['allow'], 'radio', 0, 1);
	showsetting('connect_setting_siteid', 'connectsiteidnew', $setting['connectsiteid'], 'text');
	showsetting('connect_setting_sitekey', 'connectsitekeynew', $setting['connectsitekey'], 'text');

	showtagfooter('tbody');
	showsetting('connect_setting_feed_allow', 'connectnew[feed][allow]', $setting['connect']['feed']['allow'], 'radio', 0, 1);
	showsetting('connect_setting_feed_fids', '', '', $forumselect['feed']);
	showsetting('connect_setting_feed_group', 'connectnew[feed][group]', $setting['connect']['feed']['group'], 'radio');
	showtagfooter('tbody');
	showsetting('connect_setting_t_allow', 'connectnew[t][allow]', $setting['connect']['t']['allow'], 'radio', 0, 1);
	showsetting('connect_setting_t_fids', '', '', $forumselect['t']);
	showsetting('connect_setting_t_group', 'connectnew[t][group]', $setting['connect']['t']['group'], 'radio');
	showtagfooter('tbody');
	showsetting('connect_setting_like_allow', 'connectnew[like_allow]', $setting['connect']['like_allow'], 'radio', 0, 1);
	showsetting('connect_setting_like_url', 'connectnew[like_qq]', $setting['connect']['like_qq'], 'text');
	showtagfooter('tbody');
	showsetting('connect_setting_turl_allow', 'connectnew[turl_allow]', $setting['connect']['turl_allow'], 'radio', 0, 1);
	showsetting('connect_setting_turl_code', 'connectnew[turl_code]', $setting['connect']['turl_code'], 'textarea');
	showtagfooter('tbody');
	showsubmit('connectsubmit');
	showtablefooter();
	showformfooter();

} else {

	if($_G['gp_connectnew']['like_url']) {
		$url = parse_url($_G['gp_connectnew']['like_url']);
		if(!preg_match('/\.qq\.com$/i', $url['host'])) {
			cpmsg('connect_like_url_error', '', 'error');
		}
	}
	if($_G['gp_connectnew']['like_allow'] && $_G['gp_connectnew']['like_url'] === '') {
		cpmsg('connect_like_url_miss', '', 'error');
	}
	$_G['gp_connectnew'] = array_merge($setting['connect'], $_G['gp_connectnew']);
	$_G['gp_connectnew']['like_url'] = $_G['gp_connectnew']['like_qq'] ? 'http://open.qzone.qq.com/like?url=http%3A%2F%2Fuser.qzone.qq.com%2F'.$_G['gp_connectnew']['like_qq'].'&width=100&height=21&type=button_num' : '';
	$connectnew = addslashes(serialize(dstripslashes($_G['gp_connectnew'])));
	$regconnectnew = !$setting['connect']['allow'] && $_G['gp_connectnew']['allow'] ? 1 : $setting['regconnect'];
	DB::query("REPLACE INTO ".DB::table('common_setting')." (`skey`, `svalue`) VALUES
		('regconnect', '$regconnectnew'),
		('connect', '$connectnew'),
		('connectsiteid', '$_G[gp_connectsiteidnew]'),
		('connectsitekey', '$_G[gp_connectsitekeynew]')");

	$available = intval($_G['gp_connectnew']['allow']);
	$identifier = 'qqconnect';
	$pluginId = DB::result_first("SELECT pluginid FROM ".DB::table('common_plugin')." WHERE identifier='$identifier'");
	if($pluginId) {
		DB::update('common_plugin', array('available' => $available), array('pluginid' => $pluginId));
	}

	updatecache(array('setting', 'fields_connect_register', 'plugin', 'styles'));

	cpmsg('connect_update_succeed', 'action=connect&operation=setting', 'succeed');

}

?>
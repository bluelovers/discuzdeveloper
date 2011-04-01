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

require_once libfile('commonblock_html', 'class/block/html');

class block_search extends commonblock_html {

	function block_search() {}

	function name() {
		return lang('blockclass', 'blockclass_html_script_search');
	}

	function getsetting() {
		global $_G;
		$settings = array();
		return $settings;
	}

	function getdata($style, $parameter) {
		global $_G;
		$lang = lang('template');
		$slist = array();
		$checked = ' checked="checked"';
		if($_G['setting']['search']) {
			if($_G['setting']['search']['portal']['status']) {
				$slist['portal'] = '<label for="mod_article" title="'.$lang['search'].$lang['article'].'"><input type="radio"name="mod" id="mod_article" class="pr"  value="portal"'.$checked.' /> '.$lang['article'].'</label>';
				$checked = '';
			}
			if($_G['setting']['search']['forum']['status']) {
				$slist['forum'] = '<label for="mod_thread" title="'.$lang['search'].$_G['setting']['navs'][2]['navname'].'"><input type="radio" name="mod" id="mod_thread" class="pr" value="forum"'.$checked.' /> '.$_G['setting']['navs'][2]['navname'].'</label>';
				$checked = '';
			}
			if ($_G['setting']['search']['blog']['status']) {
				$slist['blog'] = '<label for="mod_blog" title="'.$lang['search'].$lang['blog'].'"><input type="radio" name="mod" id="mod_blog" class="pr" value="blog"'.$checked.' /> '.$lang['blog'].'</label>';
				$checked = '';
			}
			if ($_G['setting']['search']['album']['status']) {
				$slist['album'] = '<label for="mod_album" title="'.$lang['search'].$lang['album'].'"><input type="radio" name="mod" id="mod_album" class="pr" value="album"'.$checked.' /> '.$lang['album'].'</label>';
				$checked = '';
			}
			if ($_G['setting']['groupstatus'] && $_G['setting']['search']['group']['status']) {
				$slist['group'] = '<label for="mod_group" title="'.$lang['search'].$_G['setting']['navs'][3]['navname'].'"><input type="radio" name="mod" id="mod_group" class="pr" value="group"'.$checked.' /> '.$_G['setting']['navs'][3]['navname'].'</label>';
				$checked = '';
			}
			$slist['user'] = '<label for="mod_user" title="'.$lang['users'].'"><input type="radio" name="mod" id="mod_user" class="pr" value="user"'.$checked.' /> '.$lang['users'].'</label>';
		}
		if($slist) {
			$slist = implode('', $slist);
			$hotsearch = '';
			if ($_G['setting']['srchhotkeywords']) {
				$hotsearch = '<strong class="xw1 xi1">'.$lang['hot_search'].': </strong>';
				foreach($_G['setting']['srchhotkeywords'] as $val) {
					$val = trim($val);
					if($val) {
						$hotsearch .= '<a href="search.php?mod=forum&srchtxt='.rawurlencode($val).'&formhash={FORMHASH}&searchsubmit=true" target="_blank" class="xi2">'.$val.'</a><span class="pipe">|</span>';
					}
				}
			}
			$html = <<<EOT
				<div id="scbar" class="bm cl">
					<form id="scbar_form" class="z" method="post" autocomplete="off" onsubmit="searchFocus($('srchtxt'))" action="search.php?searchsubmit=yes" target="_blank">
						<input type="hidden" name="mod" value="search" />
						<input type="hidden" name="formhash" value="{FORMHASH}" />
						<input type="hidden" name="srchtype" value="title" />
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td><input type="text" name="srchtxt" id="scbar_srchtxt" class="xg1" value="{$lang['enter_content']}" autocomplete="off" onfocus="if(this.value=='{$lang['enter_content']}'){this.value='';this.className=''}" onblur="if(this.value==''){this.value='{$lang['enter_content']}';this.className='xg1'}" /></td>
								<td>&nbsp;$slist</td>
								<td><button type="submit" id="scbar_submit" name="searchsubmit" class="pn pnc" value="true"><strong>{$lang['search']}</strong></button></td>
								<td><a href="search.php?mod=search&adv=yes" target="_blank" class="xi2">{$lang['advanced_search']}</a></td>
								<td>
									<div id="scbar_hot">
										$hotsearch
									</div>
								</td>
							</tr>
						</table>
					</form>
					<script type="text/javascript">initSearchmenu('srchtxt');</script>
				</div>
EOT;
		}
		return array('html' => $html, 'data' => null);
	}
}

?>
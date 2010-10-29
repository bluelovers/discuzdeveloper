<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id$
*/


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
        exit('Access Denied');
}

include_once DISCUZ_ROOT.'./discuz_version.php';
include_once DISCUZ_ROOT.'./api/upgradeapi.php';
include_once DISCUZ_ROOT.'./include/ftp.func.php';

cpheader();
if(!isfounder()) cpmsg('noaccess_isfounder', '', 'error');

if(!($operation)) {
	showtips('upgrade_tips');
	if(!submitcheck('upgradesubmit', 1) && !submitcheck('deletesubmit')) {
		?>
		<script src="<?=$upgradeurl?>?release=<?=DISCUZ_RELEASE?>&charset=<?=$charset?>&time=<?=time()?>"></script>
		<?
		showformheader('upgrade');
		showtableheader();
		showtitle('upgrade_wizard');
		showsetting('upgrade_ftp_host', 'ftphost', '', 'text');
		showsetting('upgrade_ftp_port', 'ftpport', '21', 'text');
		showsetting('upgrade_ftp_user', 'ftpuser', '', 'text');
		showsetting('upgrade_ftp_pass', 'ftppass', '', 'password');
		showsetting('upgrade_ftp_path', 'ftppath', '/', 'text');
		showsetting('upgrade_ftp_ssl', 'ftpssl', '', 'radio');

		$backupinfo = '';
		$dh = opendir($backuppath);
		while(($file = readdir($dh)) != false) {
			if($file != '.' && $file != '..' && preg_match('/^[0-9]+$/', $file) && is_dir($backuppath.'/'.$file)) {
				$backupinfo .= "<tr><td><input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$file\"></td>\n".
					"<td>$file</a></td>\n".
					"<td>".gmdate("$dateformat $timeformat", filemtime($backuppath.'/'.$file) + $timeoffset * 3600)."</td>\n";
			}
		}
		closedir($dh);

		?>
		</table><br />

		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb tb2 nobdb">
		<tr class="partition"><td colspan="4"><?=$lang['upgrade_upgradelist']?></td></tr>
		<tr>
			<td width="20"></td>
			<td width="120"><?=$lang['upgrade_name']?></td>
			<td width="100"><?=$lang['upgrade_version']?></td>
			<td><?=$lang['upgrade_description']?></td>
		</tr>

		<script type="text/JavaScript">

			if( typeof(upgradelist) == 'undefined') {
				document.write('<tr><td colspan="4"><?=$lang['upgrade_noconnent']?></td></tr>');
			} else if( typeof(upgradelist)!= 'undefined' && upgradelist.length < 1 ) {
				document.write('<tr><td colspan="4"><?=$lang['upgrade_noupdate']?></td></tr>');
			} else {
				for(i = 0; i < upgradelist.length; i++) {
					document.write('<tr>');
					document.write('<td algin="center"><input class="checkbox" onclick="setcheck(' + i + ')" id="upgradelist' + i + '" name="upgradelist[' + i + ']" type="checkbox" checked="checked" value="'+upgradelist[i].release+'"');
					document.write('"></td>');
					document.write('<td>' + upgradelist[i].name + '</td>');
					document.write('<td>' + upgradelist[i].release + '</td>');
					document.write('<td><br />' + upgradelist[i].description);
					document.write('<br /><br /><?=$lang['upgrade_filelist']?>');
					for(j = 0; j < upgradelist[i].update.length; j++) {
						document.write('<br /> &raquo; ' + upgradelist[i].update[j]);
					}
					document.write('<br /><br /></td>');
					document.write('</tr>');
				}
			}

		</script>
		</table>
		<br /><center><input type="submit" class="btn" name="upgradesubmit" value="<?=$lang['submit']?>"></center>
		</form>

		<br />
		<form method="post" action="<?=$BASESCRIPT?>?action=upgrade">
		<input type="hidden" name="formhash" value="<?=FORMHASH?>">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tb tb2 nobdb">
		<tr class="partition"><td colspan="3"><?=$lang['upgrade_oldbackup']?></td></tr>
		<tr ><td class="td25"><input class="checkbox" type="checkbox" name="chkall" onclick="checkAll('prefix', this.form)"><?=$lang['del']?></td>
		<td><?=$lang['upgrade_name']?></td><td><?=$lang['upgrade_time']?></td></tr>
		<?=$backupinfo?>
		</table><br /><center>
		<input type="submit" class="btn" name="deletesubmit" value="<?=$lang['submit']?>"></center></form>


		<script type="text/JavaScript">
			function setcheck(num) {
				if($('upgradelist'+num).checked) {
					for( i = 0; i < num; i++ ) {
						$('upgradelist'+i).checked = true;
					}
				} else {
					if($('upgradelist'+(num+1)).checked) {
						alert('<?=$lang['upgrade_noskip']?>')
						$('upgradelist'+num).checked = true;
					}
				}
			}
		</script>
		<?

	} elseif(submitcheck('deletesubmit')) {
		if(is_array($delete)) {
			foreach($delete as $filename) {
				if(!preg_match('/^[0-9]+$/', $filename)) {
					cpmsg('upgrade_deleteerror', '', 'error');
				}
				removedir($backuppath.'/'.$filename);
			}
			cpmsg('upgrade_backupdeleted', '', 'succeed');
		} else {
			cpmsg('upgrade_nofiles', '', 'error');
		}
	}

	else {
		if(empty($ftphost) || empty($ftpuser) || empty($ftppass) || empty($ftppath)) {
			cpmsg('upgrade_configerror', '', 'error');
		}

		$ftpport = empty($ftpport) ? 21 : intval($ftpport);
		$ftpssl = empty($ftpssl) ? 0 : intval($ftpssl);

		if(!($ftpid = dftp_connect($ftphost, $ftpuser, $ftppass, $ftppath, $ftpport, $ftpssl))) {
			cpmsg('upgrade_configerror', '', 'error');
		}

		dsetcookie("ftp_auth", authcode("$ftphost\t$ftpport\t$ftpuser\t$ftppass\t$ftppath\t$ftpssl", 'ENCODE'), 0);

		?>
		<script src="<?=$upgradeurl?>?release=<?=DISCUZ_RELEASE?>&time=<?=time()?>"></script>

		<table id="" width="100%" border="0" cellpadding="0" cellspacing="0" class="tb tb2 nobdb">
		<tr class="partition"><td colspan="2"><?=$lang['upgrade_wizard']?></td></tr>

		<tr><td width="20%"><?=$lang['upgrade_name']?>:</td>
		<td align="right" id="name"></td></tr>

		<tr><td><?=$lang['upgrade_version']?>:</td>
		<td align="right" id="release"></td></tr>

		<tr><td><?=$lang['upgrade_time']?>:</td>
		<td align="right" id="dateline"></td></tr>

		<tr><td><?=$lang['upgrade_type']?>:</td>
		<td align="right" id="run"></td></tr>

		<tr><td><?=$lang['upgrade_description']?>:</td>
		<td align="right" id="description"></td></tr>

		<tr><td><?=$lang['upgrade_currentinfo']?>:</td>
		<td align="right" id="msg"></td></tr>



		<tr>
		<td colspan="2" style="padding-left: 4px;padding-right: 4px;padding-top: 2px;padding-bottom: 2px;">
		<div id="percent" style="background:#1590BE;color:#FFFFFF;">0%</div>
		</td></tr>
		</table>

		<script type="text/JavaScript">
			var steptotals = 0;
			var stepprocess = 0;
			var percent;
			var upgradepackage = new Array();
			<?
			$i = 0;
			foreach($upgradelist as $key => $val) {
			?>
				upgradepackage[<?=$i++?>] = upgradelist[<?=$key?>];
			<?
			}
			?>
			var i = 0;
			var j = 0;
			upgrade();
			function upgrade() {
				document.getElementById('percent').style.width = '0px';
				document.getElementById('percent').innerHTML = '0%';
				$('name').innerHTML = upgradepackage[i].name;
				$('release').innerHTML = upgradepackage[i].release;
				$('dateline').innerHTML = upgradepackage[i].dateline;
				$('description').innerHTML = upgradepackage[i].description;
				$('run').innerHTML = (upgradepackage[i].run != 'none') ? "<?=$lang['upgrade_upgrade']?>" : "<?=$lang['upgrade_update']?>";

				if(upgradepackage[i].run != 'none') {
					upgradepackage[i].update[upgradepackage[i].update.length] = upgradepackage[i].run;
				}


				steptotals = upgradepackage[i].update.length;
				stepprocess = 0;
				j = 0;

				$('msg').innerHTML = '<?=$lang['upgrade_closesite']?>';
					x = new Ajax('HTML', '');
					x.get($BASESCRIPT.'?action=upgrade&operation=openbbs&bbc=1', function(s) {
					$('msg').innerHTML = '<?=$lang['upgrade_closesite_ok']?>';
				});
				upgradefile();
			}

			function upgradefile() {
				var run = '';
				var x = new Ajax('HTML', '');

				if(upgradepackage[i].update[j] == upgradepackage[i].run) {
					run = '&run=1';
					$('msg').innerHTML = '<?=$lang['upgrade_upgrade_running']?>: '+ upgradepackage[i].update[j];
				} else {
					$('msg').innerHTML = '<?=$lang['upgrade_update_running']?>: '+ upgradepackage[i].update[j];
				}

				x.get($BASESCRIPT.'?action=upgrade&operation=down&release='+upgradelist[i].release+'&id='+j+run, function(s) {

					s = s.charAt(s.length - 1);

					if(s != '1') {
						$('msg').innerHTML = '<?=$lang['upgrade_error']?>';
						return;
					}
					stepprocess++;
					percent = ((stepprocess / steptotals) * 100).toFixed(0);
					percent = percent > 100 ? 100 : percent;
					document.getElementById('percent').style.width = percent+'%';
					document.getElementById('percent').innerHTML = percent+'%';
					j++;
					if( j < upgradepackage[i].update.length) {
						upgradefile();
					} else {
						i++;
						if( i < upgradepackage.length) {
							$('msg').innerHTML = '<?=$lang['upgrade_waiting']?>';
							upgrade();
						} else {
							$('msg').innerHTML = '<?=$lang['upgrade_succeed']?>';
							x = new Ajax('HTML', '');
							x.get($BASESCRIPT.'?action=upgrade&operation=openbbs&bbc=0', function(s) {
								$('msg').innerHTML += '(<?=$lang['upgrade_open_succeed']?>)';
							});
						}
					}
				});
			}
		</script>
		<?
	}
} elseif($operation == 'down') {
	$upgradeurl = str_replace('upgrade.php', '', $upgradeurl);

	$id = intval($id);

	//读取远程文件列表
	$file = array();
	$lines = file($upgradeurl.$release.'/info.txt');
	foreach($lines as $line) {
		list($file['script'], $file['type'], $file['chmod'], $file['run']) = explode("\t", trim($line));
		$files[] = $file;
	}


	//初始化文件信息
	$mod = strval($files[$id]['chmod']);
	$type = $files[$id]['type'] == 1 ? 1 : 0;
	$run = $files[$id]['run'] == 1 ? 1 : 0;
	$script = $files[$id]['script'];

	@list($ftphost, $ftpport, $ftpuser, $ftppass, $ftppath, $ftpssl) = explode("\t", authcode($cdb_ftp_auth, 'DECODE'));

	if(!($ftpid = dftp_connect($ftphost, $ftpuser, $ftppass, $ftppath, $ftpport, $ftpssl))) {

		exit(false);

	} else {

		if(!file_exists($packagepath)) {
			mkdir($packagepath, 0777);
		}

		if(!file_exists($backuppath)) {
			mkdir($backuppath, 0777);
		}

		$packagepath .= '/'.$release;
		$backuppath .= '/'.$release;

		if(!file_exists($packagepath)) {
			mkdir($packagepath, 0777);
		}

		if(!file_exists($backuppath)) {
			mkdir($backuppath, 0777);
		}

		if(!$type) {
			//建立更新文件临时目录
			if(!file_exists($packagepath.'/'.$script)) {
				mkdir($packagepath.'/'.$script, 0777);
			}

			//建立备份目录
			if(file_exists(DISCUZ_ROOT.'./'.$script) && !file_exists($backuppath.'/'.$script)) {
				mkdir($backuppath.'/'.$script, 0777);
			}

			//建立新目录
			//if(!file_exists(DISCUZ_ROOT.'./'.$file)) {
				dftp_mkdir($ftpid, $script);
				if(!empty($mod)) {
					dftp_chmod($ftpid, $mod, '/'.$script);
				}
			//}

		} else {

			if(!($fp = fopen($upgradeurl.$release.'/package/'.$script.'.txt', 'rb'))) {
				exit(false);
			}

			$data = '';
			while(($c=fgetc($fp)) !== false) {
				$data .= $c;
			}
			fclose($fp);

			if(file_exists(DISCUZ_ROOT.'./'.$script) && !file_exists($backuppath.'/'.$script.'.txt')) {
				copy(DISCUZ_ROOT.'./'.$script, $backuppath.'/'.$script.'.txt');
			}

			$fp = fopen($packagepath.'/'.$script.'.txt', 'wb');
			fwrite($fp, $data);
			fclose($fp);

			dftp_put($ftpid, './'.$script, $packagepath.'/'.$script.'.txt', FTP_BINARY);
			if(!empty($mod)) {
				dftp_chmod($ftpid, $mod, '/'.$script);
			}

			$md5 = md5_file(DISCUZ_ROOT.'./'.$script);
			$discuzfiles = file(DISCUZ_ROOT.'./forumdata/discuzfiles.md5');

			$md5datanew = array();

			foreach($discuzfiles as $line) {
				$md5file = trim(substr($line, 34));
				$md5str = trim(substr($line, 0, 32));
				$md5datanew[$md5file] = $md5str;
			}

			$md5datanew['./'.$script] = $md5;

			$fp = fopen(DISCUZ_ROOT.'./forumdata/discuzfiles.md5', 'wb');
			foreach($md5datanew as $key => $val) {
				fwrite($fp, $val.' *'.$key."\r\n");
			}
			fclose($fp);

			if($run) {
				include_once $script;
				dftp_delete($ftpid, $script);
			}

			dftp_close($ftpid);

		}
		exit(true);
	}
} elseif($operation == 'openbbs') {

	if(!($bbc = intval($bbc))) {
		removedir($packagepath);
	}

	$db->query("UPDATE {$tablepre}settings SET value='$bbc' WHERE variable='bbclosed'");

	updatecache('settings');

	exit(true);
}
?>

<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id$
 */

include_once('../source/class/class_core.php');
include_once('../source/function/function_core.php');

@set_time_limit(0);

$cachelist = array();
$discuz = & discuz_core::instance();

$discuz->cachelist = $cachelist;
$discuz->init_cron = false;
$discuz->init_setting = false;
$discuz->init_user = false;
$discuz->init_session = false;
$discuz->init_misc = false;

$discuz->init();
//����
$config = array(
	'dbcharset' => $_G['config']['db']['1']['dbcharset'],
	'charset' => $_G['config']['output']['charset'],
	'tablepre' => $_G['config']['db']['1']['tablepre']
);
$theurl = 'update.php';

$lockfile = DISCUZ_ROOT.'./data/update.lock';
if(file_exists($lockfile)) {
	show_msg('�����ȵ�¼������ftp���ֹ�ɾ�� ./data/update.lock �ļ����ٴ����б��ļ�����������');
}

//��SQL, ����ʹ�ÿ��������е� install_dev.sql
$devmode = file_exists(DISCUZ_ROOT.'./install/data/install_dev.sql');
$sqlfile = DISCUZ_ROOT.($devmode ? './install/data/install_dev.sql' : './install/data/install.sql');

if(!file_exists($sqlfile)) {
	show_msg('SQL�ļ� '.$sqlfile.' ������');
}

//�ύ����
if($_POST['delsubmit']) {
	//ɾ����
	if(!empty($_POST['deltables'])) {
		foreach ($_POST['deltables'] as $tname => $value) {
			DB::query("DROP TABLE `".DB::table($tname)."`");
		}
	}
	//ɾ���ֶ�
	if(!empty($_POST['delcols'])) {
		foreach ($_POST['delcols'] as $tname => $cols) {
			foreach ($cols as $col => $indexs) {
				if($col == 'PRIMARY') {
					DB::query("ALTER TABLE ".DB::table($tname)." DROP PRIMARY KEY", 'SILENT');//���δ���
				} elseif($col == 'KEY' || $col == 'UNIQUE') {
					foreach ($indexs as $index => $value) {
						DB::query("ALTER TABLE ".DB::table($tname)." DROP INDEX `$index`", 'SILENT');//���δ���
					}
				} else {
					DB::query("ALTER TABLE ".DB::table($tname)." DROP `$col`");
				}
			}
		}
	}

	show_msg('ɾ�������ֶβ��������', $theurl.'?step=style');
}

if(empty($_GET['step'])) $_GET['step'] = 'start';

//������ʼ
if($_GET['step'] == 'start') {
	//��ʼ
	show_msg('˵����<br>�����������������µ�SQL�ļ��������ݿ����ͬ��������<br>
		��ȷ����ǰĿ¼�� ./data/install.sql �ļ�Ϊ���°汾��<br><br>
		<a href="'.$theurl.'?step=prepare">׼����ϣ�������ʼ</a>');

} elseif ($_GET['step'] == 'prepare') {
	//Ԥ������Ϊ����Ӱ�����������ı��䶯��׼��
	if(!DB::result_first('SELECT skey FROM '.DB::table('common_setting')." WHERE skey='group_recommend' LIMIT 1")) {
		DB::query("TRUNCATE ".DB::table('forum_groupinvite'));
	}
	if(DB::fetch_first("SHOW COLUMNS FROM ".DB::table('forum_activityapply')." LIKE 'contact'")) {
		$query = DB::query("UPDATE ".DB::table('forum_activityapply')." SET message=CONCAT_WS(' ��ϵ��ʽ:', message, contact) WHERE contact<>''");
		DB::query("ALTER TABLE ".DB::table('forum_activityapply')." DROP contact");
	}
	//�������
	show_msg('׼����ϣ�������һ�����ݿ�ṹ����', $theurl.'?step=sql');
} elseif ($_GET['step'] == 'sql') {

	//�µ�SQL
	$sql = implode('', file($sqlfile));
	preg_match_all("/CREATE\s+TABLE.+?pre\_(.+?)\s*\((.+?)\)\s*(ENGINE|TYPE)\s*\=/is", $sql, $matches);
	$newtables = empty($matches[1])?array():$matches[1];
	$newsqls = empty($matches[0])?array():$matches[0];
	if(empty($newtables) || empty($newsqls)) {
		show_msg('SQL�ļ�����Ϊ�գ���ȷ��');
	}

	//������
	$i = empty($_GET['i'])?0:intval($_GET['i']);
	$count_i = count($newtables);
	if($i>=$count_i) {
		//�������
		show_msg('���ݿ�ṹ������ϣ�������һ��������������', $theurl.'?step=data');
	}
	//��ǰ������
	$newtable = $newtables[$i];

	// forum_post, forum_thread �ֱ����⴦��
	$specid = intval($_GET['specid']);
	if($specid && in_array($newtable, array('forum_post', 'forum_thread'))) {
		$spectable = $newtable;
		$newtable = get_special_table_by_num($newtable, $specid);
	}

	$newcols = getcolumn($newsqls[$i]);

	//��ȡ��ǰSQL
	if(!$query = DB::query("SHOW CREATE TABLE ".DB::table($newtable), 'SILENT')) {
		//���ӱ�
		preg_match("/(CREATE TABLE .+?)\s*(ENGINE|TYPE)\s*\=/is", $newsqls[$i], $maths);

		if(strpos($newtable, 'common_session')) {
			$type = mysql_get_server_info() > '4.1' ? " ENGINE=MEMORY".(empty($config['dbcharset'])?'':" DEFAULT CHARSET=$config[dbcharset]" ): " TYPE=HEAP";
		} else {
			$type = mysql_get_server_info() > '4.1' ? " ENGINE=MYISAM".(empty($config['dbcharset'])?'':" DEFAULT CHARSET=$config[dbcharset]" ): " TYPE=MYISAM";
		}
		$usql = $maths[1].$type;

		$usql = str_replace("CREATE TABLE IF NOT EXISTS pre_", 'CREATE TABLE IF NOT EXISTS '.$config['tablepre'], $usql);
		$usql = str_replace("CREATE TABLE pre_", 'CREATE TABLE '.$config['tablepre'], $usql);
		if(!DB::query($usql, 'SILENT')) {
			show_msg('���ӱ� '.DB::table($newtable).' ����,���ֹ�ִ������SQL����,���������б���������:<br><br>'.dhtmlspecialchars($usql));
		} else {
			$msg = '���ӱ� '.DB::table($newtable).' ���';
		}
	} else {
		$value = DB::fetch($query);
		$oldcols = getcolumn($value['Create Table']);

		//��ȡ����SQL��
		$updates = array();
		foreach ($newcols as $key => $value) {
			if($key == 'PRIMARY') {
				if($value != $oldcols[$key]) {
					if(!empty($oldcols[$key])) {
						$usql = "RENAME TABLE ".DB::table($newtable)." TO ".DB::table($newtable.'_bak');
						if(!DB::query($usql, 'SILENT')) {
							show_msg('������ '.DB::table($newtable).' ����,���ֹ�ִ��������������,���������б���������:<br><br><b>����SQL���</b>:<div style=\"position:absolute;font-size:11px;font-family:verdana,arial;background:#EBEBEB;padding:0.5em;\">'.dhtmlspecialchars($usql)."</div><br><b>Error</b>: ".DB::error()."<br><b>Errno.</b>: ".DB::errno());
						} else {
							$msg = '������ '.DB::table($newtable).' ��ɣ�';
							show_msg($msg, $theurl.'?step=sql&i='.$_GET['i']);
						}
					}
					$updates[] = "ADD PRIMARY KEY $value";
				}
			} elseif ($key == 'KEY') {
				foreach ($value as $subkey => $subvalue) {
					if(!empty($oldcols['KEY'][$subkey])) {
						if($subvalue != $oldcols['KEY'][$subkey]) {
							$updates[] = "DROP INDEX `$subkey`";
							$updates[] = "ADD INDEX `$subkey` $subvalue";
						}
					} else {
						$updates[] = "ADD INDEX `$subkey` $subvalue";
					}
				}
			} elseif ($key == 'UNIQUE') {
				foreach ($value as $subkey => $subvalue) {
					if(!empty($oldcols['UNIQUE'][$subkey])) {
						if($subvalue != $oldcols['UNIQUE'][$subkey]) {
							$updates[] = "DROP INDEX `$subkey`";
							$updates[] = "ADD UNIQUE INDEX `$subkey` $subvalue";
						}
					} else {
						$usql = "ALTER TABLE  ".DB::table($newtable)." DROP INDEX `$subkey`";
						DB::query($usql, 'SILENT');
						$updates[] = "ADD UNIQUE INDEX `$subkey` $subvalue";
					}
				}
			} else {
				if(!empty($oldcols[$key])) {
					if(strtolower($value) != strtolower($oldcols[$key])) {
						$updates[] = "CHANGE `$key` `$key` $value";
					}
				} else {
					$updates[] = "ADD `$key` $value";
				}
			}
		}

		//��������
		if(!empty($updates)) {
			$usql = "ALTER TABLE ".DB::table($newtable)." ".implode(', ', $updates);
			if(!DB::query($usql, 'SILENT')) {
				show_msg('������ '.DB::table($newtable).' ����,���ֹ�ִ��������������,���������б���������:<br><br><b>����SQL���</b>:<div style=\"position:absolute;font-size:11px;font-family:verdana,arial;background:#EBEBEB;padding:0.5em;\">'.dhtmlspecialchars($usql)."</div><br><b>Error</b>: ".DB::error()."<br><b>Errno.</b>: ".DB::errno());
			} else {
				$msg = '������ '.DB::table($newtable).' ��ɣ�';
			}
		} else {
			$msg = '���� '.DB::table($newtable).' ��ɣ���������������';
		}
	}

	//������һ��
	if($specid) {
		$newtable = $spectable;
	}

	if(get_special_table_by_num($newtable, $specid+1)) {
		$next = $theurl . '?step=sql&i='.($_GET['i']).'&specid='.($specid + 1);
	} else {
		$next = $theurl.'?step=sql&i='.($_GET['i']+1);
	}
	show_msg("[ $i / $count_i ] ".$msg, $next);

} elseif ($_GET['step'] == 'data') {// ��������


	if(empty($_GET['op']) || $_GET['op'] == 'realname') {// ʵ������

		$nextop = 'setting';// ��һ�������Ĺ���

		// һ�δ��� 1000 ��
		$p = 1000;
		$i = !empty($_GET['i']) ? intval($_GET['i']) : 0;
		$n = 0;
		if($i==0) {
			// profile_setting ���в��� realname ��
			$value = DB::fetch_first('SELECT * FROM '.DB::table('common_member_profile_setting')." WHERE fieldid = 'realname'");
			if(!empty($value)) {
				show_msg("ʵ�������������", "$theurl?step=data&op=$nextop");
			}
			DB::query("INSERT INTO ".DB::table('common_member_profile_setting')." VALUES ('realname', '1', '0', '1', '��ʵ����', '', '0', '0', '0', '0', '1', 'text', '0', '', '', '0', '0')");
		}
		$t = DB::result_first('SELECT uid FROM '.DB::table('common_member')." ORDER BY uid DESC LIMIT 1");
		$names = $uids = array();
		$query = DB::query('SELECT * FROM '.DB::table('common_member')." WHERE uid>'$i' AND realname != '' LIMIT $p");
		while($value=DB::fetch($query)) {
			$n = intval($value['uid']);
			// ���������ʵ�������õ� profile ��
			$value['uid'] = intval($value['uid']);
			$value['realname'] = addslashes($value['realname']);
			DB::update('common_member_profile', array('realname'=>$value['realname']), array('uid'=>$value['uid']));
			DB::update('common_member', array('realname'=>''), array('uid'=>$value['uid']));
			$names[$value['uid']] = $value['realname'];
		}

		// ��һ��
		if($n>0) {
			show_msg("ʵ������������[$n/$t]", "$theurl?step=data&op=realname&i=$n");
		} else {
			show_msg("ʵ�������������", "$theurl?step=data&op=$nextop");
		}

	} elseif($_GET['op'] == 'setting') { //��̳������� setting
		$nextop = 'admingroup';// ��һ�������Ĺ���
		$settings = $newsettings = array();
		$query = DB::query('SELECT * FROM '.DB::table('common_setting')." WHERE 1");
		while($value=DB::fetch($query)) {
			$settings[$value[skey]] = $value['svalue'];
		}
		//note ��̳ SEO ����
		if($settings['seotitle'] && unserialize($settings['seotitle']) === FALSE) {
			$rownew = array('forum' => $settings['seotitle']);
			DB::insert('common_setting', array(
				'skey' => 'seotitle',
				'svalue' => addslashes(serialize($rownew)),
			), false, true);
		}
		if($settings['seokeywords'] && unserialize($settings['seokeywords']) === FALSE) {
			$rownew = array('forum' => $settings['seokeywords']);
			DB::insert('common_setting', array(
				'skey' => 'seokeywords',
				'svalue' => addslashes(serialize($rownew)),
			), false, true);
		}
		if($settings['seodescription'] && unserialize($settings['seodescription']) === FALSE) {
			$rownew = array('forum' => $settings['seodescription']);
			DB::insert('common_setting', array(
				'skey' => 'seodescription',
				'svalue' => addslashes(serialize($rownew)),
			), false, true);
		}	
		//note ˮӡ����
		if($settings['watermarkminheight'] && unserialize($settings['watermarkminheight']) === FALSE) {
			$rownew = array('portal' => $settings['watermarkminheight'], 'forum' => $settings['watermarkminheight'], 'album' => $settings['watermarkminheight']);
			DB::insert('common_setting', array(
				'skey' => 'watermarkminheight',
				'svalue' => addslashes(serialize($rownew)),
			), false, true);
		}
		if($settings['watermarkminwidth'] && unserialize($settings['watermarkminwidth']) === FALSE) {
			$rownew = array('portal' => $settings['watermarkminwidth'], 'forum' => $settings['watermarkminwidth'], 'album' => $settings['watermarkminwidth']);
			DB::insert('common_setting', array(
				'skey' => 'watermarkminwidth',
				'svalue' => addslashes(serialize($rownew)),
			), false, true);
		}
		if($settings['watermarkquality'] && unserialize($settings['watermarkquality']) === FALSE) {
			$rownew = array('portal' => $settings['watermarkquality'], 'forum' => $settings['watermarkquality'], 'album' => $settings['watermarkquality']);
			DB::insert('common_setting', array(
				'skey' => 'watermarkquality',
				'svalue' => addslashes(serialize($rownew)),
			), false, true);
		}
		if($settings['watermarkstatus'] && unserialize($settings['watermarkstatus']) === FALSE) {
			$rownew = array('portal' => $settings['watermarkstatus'], 'forum' => $settings['watermarkstatus'], 'album' => $settings['watermarkstatus']);
			DB::insert('common_setting', array(
				'skey' => 'watermarkstatus',
				'svalue' => addslashes(serialize($rownew)),
			), false, true);
		}
		if($settings['watermarktrans'] && unserialize($settings['watermarktrans']) === FALSE) {
			$rownew = array('portal' => $settings['watermarktrans'], 'forum' => $settings['watermarktrans'], 'album' => $settings['watermarktrans']);
			DB::insert('common_setting', array(
				'skey' => 'watermarktrans',
				'svalue' => addslashes(serialize($rownew)),
			), false, true);
		}
		if($settings['watermarktype'] && unserialize($settings['watermarktype']) === FALSE) {
			$watermarktype_map = array(
				0 => 'gif',
				1 => 'png',
				2 => 'text',
			);
			$rownew = array('portal' => $watermarktype_map[$settings['watermarktype']], 'forum' => $watermarktype_map[$settings['watermarktype']], 'album' => $watermarktype_map[$settings['watermarktype']]);
			DB::insert('common_setting', array(
				'skey' => 'watermarktype',
				'svalue' => addslashes(serialize($rownew)),
			), false, true);
		}
		if($settings['watermarktext'] && unserialize($settings['watermarktext']) === FALSE) {
			$rownew = array();
			$watermarktext = (array)unserialize($settings['watermarktext']);
			foreach($watermarktext as $data_k => $data_v) {
				$rownew[$data_k]['portal'] = $data_v;
				$rownew[$data_k]['forum'] = $data_v;
				$rownew[$data_k]['album'] = $data_v;
			}
			DB::insert('common_setting', array(
				'skey' => 'watermarktext',
				'svalue' => addslashes(serialize($rownew)),
			), false, true);
		}
		//Ⱥ�鶯̬����
		DB::query("REPLACE INTO ".DB::table('common_setting')." VALUES ('group_allowfeed', '1')");

		//���а�Ĭ�Ͽ���
		if(!isset($settings['ranklist'])) {
			DB::query("REPLACE INTO ".DB::table('common_setting')." VALUES ('ranklist', '".'a:11:{s:6:"status";s:1:"1";s:10:"cache_time";s:1:"1";s:12:"index_select";s:8:"thisweek";s:6:"member";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}s:6:"thread";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}s:4:"blog";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}s:4:"poll";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}s:8:"activity";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}s:7:"picture";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}s:5:"forum";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}s:5:"group";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}}'."')");
		}
		//ע���ַ������
		DB::query("REPLACE INTO ".DB::table('common_setting')." VALUES ('regname', 'register')");
		if(empty($settings['reglinkname'])) {
			DB::query("REPLACE INTO ".DB::table('common_setting')." VALUES ('reglinkname', 'ע��')");
		}

		if(empty($settings['domain'])) {//note �жϵ�һ������setting���domain
			DB::query("REPLACE INTO ".DB::table('common_setting')." VALUES ('domain', '".'a:5:{s:12:"defaultindex";s:9:"forum.php";s:10:"holddomain";s:18:"www|*blog*|*space*";s:4:"list";a:0:{}s:3:"app";a:5:{s:6:"portal";s:0:"";s:5:"forum";s:0:"";s:5:"group";s:0:"";s:4:"home";s:0:"";s:7:"default";s:0:"";}s:4:"root";a:5:{s:4:"home";s:0:"";s:5:"group";s:0:"";s:5:"forum";s:0:"";s:5:"topic";s:0:"";s:7:"channel";s:0:"";}}'."')");
		}
		if(empty($settings['group_recommend'])) {//note �ж��ǲ��ǵ�һ������setting
			//�������ּ�ϰʱ����Сʱ��Ϊ�Է���Ϊ��λ
			if($settings['newbiespan'] > 0) {
				$newsettings['newbiespan'] = round($settings['newbiespan'] * 60);
			}
			//�������˹�ע��Ⱥ�����
			DB::query("UPDATE ".DB::table('common_member_field_forum')." SET attentiongroup=''");

			//��ʼ��Ⱥ����ҳ�Ƽ�Ⱥ��
			$query = DB::query("SELECT f.fid, f.name, ff.description, ff.icon FROM ".DB::table('forum_forum')." f LEFT JOIN ".DB::table('forum_forumfield')." ff USING(fid) WHERE f.status='3' AND f.type='sub' ORDER BY f.commoncredits desc LIMIT 8");
			while($row = DB::fetch($query)) {
				$row['name'] = addslashes($row['name']);
				$settings['attachurl'] .= substr($settings['attachurl'], -1, 1) != '/' ? '/' : '';
				if($row['icon']) {
					$row['icon'] = $settings['attachurl'].'group/'.$row['icon'];
				} else {
					$row['icon'] = 'static/image/common/groupicon.gif';
				}
				$row['description'] = addslashes($row['description']);
				$group_recommend[$row[fid]] = $row;
			}
			$newsettings['group_recommend'] = serialize($group_recommend);
			if($newsettings) {
				foreach($newsettings as $skey => $svalue) {
					DB::query("REPLACE INTO ".DB::table('common_setting')." VALUES ('$skey', '$svalue')");
				}
			}
		}

		if(!DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_magic')." WHERE credit>'0'")) {
			$creditstranssi = explode(',', $settings['creditstrans']);
			$creditstran = $creditstranssi[3] ? $creditstranssi[3] : $creditstranssi[0];
			DB::update('common_magic', array('credit' => $creditstran));
		}
		//���������鿴�û������������
		if(!isset($settings['allowviewuserthread'])) {
			//Ĭ�Ͽ���
			$allowviewuserthread = array('allow'=>'1','fids'=>array());
			$query = DB::query('SELECT ff.fid,ff.viewperm FROM '.DB::table('forum_forum').' f LEFT JOIN '.DB::table('forum_forumfield')." ff ON f.fid = ff.fid WHERE f.status='1' AND f.type IN ('forum','sub')");
			while($value = DB::fetch($query)) {
				$arr = !empty($value['viewperm']) ? explode("\t", $value['viewperm']) : array();
				//û������Ȩ�޻�����Ϊ�οͻ����ֿɼ��İ��Ĭ��ѡ��
				if(empty($value['viewperm']) || in_array('7', $arr) ||  in_array($settings['newusergroupid'], $arr) ) {
					$allowviewuserthread['fids'][] = $value['fid'];
				}
			}
			DB::query("INSERT INTO ".DB::table('common_setting')." VALUES ('allowviewuserthread', '".addslashes(serialize($allowviewuserthread))."')");
		}
		show_msg("�������������", "$theurl?step=data&op=$nextop");
	} elseif($_GET['op'] == 'admingroup') { //��̳������ admingroup
		$nextop = 'updatecron';// ��һ�������Ĺ���
		DB::query('UPDATE '.DB::table('common_admingroup')." SET allowclearrecycle='1' WHERE admingid='1' OR admingid='2'");
		//����Ⱥ�����ݹ���Ȩ��
		if(DB::result_first("SELECT cpgroupid FROM ".DB::table('common_admincp_group')." WHERE cpgroupid='3'")) {
			if(!DB::result_first("SELECT cpgroupid FROM ".DB::table('common_admincp_perm')." WHERE cpgroupid='3' AND perm='threads_group'")) {
				DB::query("INSERT INTO ".DB::table('common_admincp_perm')." VALUES ('3', 'threads_group')");
				DB::query("INSERT INTO ".DB::table('common_admincp_perm')." VALUES ('3', 'prune_group')");
				DB::query("INSERT INTO ".DB::table('common_admincp_perm')." VALUES ('3', 'attach_group')");
				//ɾ������Ȩ�ޱ��еĶ����ֶ�
				DB::query("ALTER TABLE ".DB::table('common_admingroup')." DROP `disablepostctrl`");
				//Ĭ�������û�Ⱥ�鷢������Ҫ���
				DB::query("UPDATE ".DB::table('common_usergroup_field')." SET allowgroupdirectpost='3'");
				DB::query("UPDATE ".DB::table('common_usergroup_field')." SET allowgroupposturl='3' WHERE groupid='1'");
			}
		}
		show_msg("�����������������", "$theurl?step=data&op=$nextop");
	} elseif($_GET['op'] == 'updatecron') { //�����ƻ�����
		$nextop = 'updatereport';// ��һ�������Ĺ���
		if(!DB::result_first("SELECT filename FROM ".DB::table('common_cron')." WHERE filename='cron_cleanfeed.php'")) {
			DB::query("INSERT INTO ".DB::table('common_cron')." VALUES ('', '1','system','�������ڶ�̬','cron_cleanfeed.php','1269746634','1269792000','-1','-1','0','0')");
		}
		
		//����û�õļƻ�����
		if(DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_cron')." WHERE filename IN('cron_birthday_daily.php')")) {
			DB::query("DELETE FROM ".DB::table('common_cron')." WHERE filename IN('cron_birthday_daily.php')");
		}
		
		show_msg("�ƻ������������", "$theurl?step=data&op=$nextop");
	} elseif($_GET['op'] == 'updatereport') {
		$nextop = 'myappcount';// ��һ�������Ĺ���
		$report_uids = array();
		$founders = $_G['config']['admincp']['founder'] !== '' ? explode(',', str_replace(' ', '', addslashes($_G['config']['admincp']['founder']))) : array();
		if($founders) {
			$founderexists = true;
			$fuid = $fuser = array();
			foreach($founders as $founder) {
				if(is_numeric($founder)) {
					$fuid[] = $founder;
				} else {
					$fuser[] = $founder;
				}
			}
			$query = DB::query("SELECT uid, username FROM ".DB::table('common_member')." WHERE ".($fuid ? "uid IN (".dimplode($fuid).")" : '0')." OR ".($fuser ? "username IN (".dimplode($fuser).")" : '0'));
			while($founder = DB::fetch($query)) {
				$report_uids[] = $founder['uid'];
			}
		}
		$query = DB::query("SELECT uid FROM ".DB::table('common_admincp_perm')." ap LEFT JOIN ".DB::table('common_admincp_member')." am ON am.cpgroupid=ap.cpgroupid where perm='report'");
		while($user = DB::fetch($query)) {
			if(empty($users[$user[uid]])) {
				$report_uids[] = $user['uid'];
			}
		}
		if($report_uids) {
			$report_uids = implode(',', $report_uids);
			DB::query("REPLACE INTO ".DB::table('common_setting')." (skey, svalue) VALUES ('report_receive', '$report_uids')");
		}
		show_msg("�ٱ��������", "$theurl?step=data&op=$nextop");
	} elseif($_GET['op'] == 'myappcount') {

		$nextop = 'nav';// ��һ�������Ĺ���
		$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_myapp_count')),0);
		if(!$count) {
			DB::query('INSERT INTO '.DB::table('common_myapp_count').' (appid) SELECT appid FROM '.DB::table('common_myapp'));
		}
		show_msg("����Ӧ��ͳ���������", "$theurl?step=data&op=$nextop");

	} elseif($_GET['op'] == 'nav') {

		$nextop = 'forumstatus';// ��һ�������Ĺ���
		$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_nav')." WHERE navtype='0' AND type='0' AND identifier=''"),0);
		if($count) {
			DB::delete('common_nav', "navtype='0' AND type='0' AND identifier=''");
			$sql = implode('', file(DISCUZ_ROOT.'./install/data/install_data.sql'));
			preg_match("/\[update\_nav\](.+?)\[\/update\_nav\]/is", $sql, $a);
			runquery($a[1]);
		}

		show_msg("���������������", "$theurl?step=data&op=$nextop");

	} elseif($_GET['op'] == 'forumstatus') {

		$nextop = 'poststick';// ��һ�������Ĺ���
		$query = DB::query("SELECT fid FROM ".DB::table('forum_forum')." WHERE status='2'");
		if(DB::num_rows($query)) {
			while($row = DB::fetch($query)) {
				$fids[] = $row['fid'];
			}
			DB::update('forum_forumfield', array('hidemenu' => 1), "fid IN (".dimplode($fids).")");
			DB::update('forum_forum', array('status' => 1), "status='2'");
		}

		show_msg("���״̬�������", "$theurl?step=data&op=$nextop");

	} elseif($_GET['op'] == 'poststick') {

		$nextop = 'usergroup_allowvisit';// ȫ���������
		$query = DB::query("SELECT * FROM ".DB::table('forum_postposition')." WHERE stick='1'", 'SILENT');
		if(DB::num_rows($query)) {
			while($row = DB::fetch($query)) {
				DB::query("REPLACE INTO ".DB::table('forum_poststick')." SET tid='$row[tid]', pid='$row[pid]', position='$row[position]', dateline='$row[dateline]'");
			}
			DB::query("DELETE FROM ".DB::table('forum_postposition')." WHERE stick='1'");
		}

		show_msg("�����Ƽ��������", "$theurl?step=data&op=$nextop");

	//������Ա�� allowvisit ����Ϊ 2
	} elseif($_GET['op'] == 'usergroup_allowvisit') {
		$nextop = 'creditrule';
		DB::update('common_usergroup', array('allowvisit' => 2), "groupid='1'");
		show_msg("�û��������", "$theurl?step=data&op=$nextop");
	} elseif($_GET['op'] == 'creditrule') {
		$nextop = 'bbcode';
		$delrule = array('register', 'realname', 'invitefriend', 'report', 'uploadimage', 'editrealname', 'editrealemail', 'delavatar');
		$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_credit_rule')." WHERE action IN(".dimplode($delrule).")"),0);
		if($count) {
			DB::query("DELETE FROM ".DB::table('common_credit_rule')." WHERE action IN(".dimplode($delrule).")");
		}
		show_msg("���ֹ����������", "$theurl?step=data&op=$nextop");
	} elseif($_GET['op'] == 'bbcode') {
		$nextop = 'stamp';
		$allowcusbbcodes = array();
		$query = DB::query("SELECT * FROM ".DB::table('common_usergroup_field'));
		while($row = DB::fetch($query)) {
			if($row['allowcusbbcode']) {
				$allowcusbbcodes[] = $row['groupid'];
			}
		}
		if($allowcusbbcodes) {
			DB::query("UPDATE ".DB::table('forum_bbcode')." SET perm='".implode("\t", $allowcusbbcodes)."' WHERE perm=''");
		}
		show_msg("�Զ������Ȩ���������", "$theurl?step=data&op=$nextop");
	} elseif($_GET['op'] == 'stamp') {
		$nextop = 'block_premission';
		$stampnew = DB::result_first("SELECT COUNT(*) FROM ".DB::table('forum_thread')." WHERE stamp>'0'");
		if(!$stampnew) {
			$query = DB::query("SELECT t.tid, tm.stamp FROM ".DB::table('forum_thread')." t
				INNER JOIN ".DB::table('forum_threadmod')." tm ON t.tid=tm.tid AND tm.action='SPA'
				WHERE t.status|16=t.status");
			while($row = DB::fetch($query)) {
				DB::query("UPDATE ".DB::table('forum_thread')." SET stamp='$row[stamp]' WHERE tid='$row[tid]'", 'UNBUFFERED');
			}
		}
		show_msg("����ͼ���������", "$theurl?step=data&op=$nextop");
	} elseif($_GET['op'] == 'block_permission') { //����ģ��Ȩ�ޱ�
		$nextop = 'common_usergroup_field';
		if(!DB::result_first('SELECT skey FROM '.DB::table('common_setting')." WHERE skey='group_recommend' LIMIT 1")) {
			DB::query("UPDATE ".DB::table('common_block_permission')." SET allowmanage=allowsetting,allowrecomment=allowdata");
		}
		show_msg("ģ��Ȩ���������", "$theurl?step=data&op=$nextop");

	} elseif($_GET['op'] == 'common_usergroup_field') { //�û���Ȩ�ޱ�
		$nextop = 'group_index';
		if(!DB::result_first('SELECT skey FROM '.DB::table('common_setting')." WHERE skey='group_recommend' LIMIT 1")) {
			DB::query("UPDATE ".DB::table('common_usergroup_field')."
				SET allowcommentarticle=allowcomment,allowblogmod=allowblog,allowdoingmod=allowdoing,allowuploadmod=allowupload,allowsharemod=allowshare,allowdownlocalimg=allowpostarticle");
		}
		show_msg("�û���Ȩ���������", "$theurl?step=data&op=$nextop");

	} elseif($_GET['op'] == 'group_index') { //Ⱥ����ҳ
		$nextop = 'domain';
		if(!DB::result_first('SELECT skey FROM '.DB::table('common_setting')." WHERE skey='group_recommend' LIMIT 1")) {
			//�����ļ�
			$arr = array(
				0 => array('importfile'=>'./data/group_index.xml','primaltplname'=>'group/index', 'targettplname'=>'group/index'), //Ⱥ����ҳDIYģ��
			);
			foreach ($arr as $v) {
				import_diy($v['importfile'], $v['primaltplname'], $v['targettplname']);
			}
		}
		show_msg("Ⱥ����ҳ�������", "$theurl?step=data&op=$nextop");

	} elseif($_GET['op'] == 'domain') { //Ⱥ����ҳ
		$nextop = 'end';
		if(!empty($_G['config']['app']['domain'])) {
			$update = 0;
			foreach($_G['config']['app']['domain'] as $key => $value) {
				if($value && !$_G['setting']['domain']['app'][$key]) {
					$update = 1;
				}
			}
			if($update) {
				$domain = array(
					'defaultindex' => !empty($_G['config']['app']['default']) ? $_G['config']['app']['default'].'.php' : '',
					'app' => $_G['config']['app']['domain'],
				);
				DB::insert('common_setting', array('skey' => 'domain', 'svalue' => addslashes(serialize($domain))), false, true);
			}
		}
		if(!empty($_G['config']['app']['default']) && !$_G['setting']['defaultindex']) {
			DB::insert('common_setting', array('skey' => 'defaultindex', 'svalue' => $_G['config']['app']['default'].'.php'), 0, 1);
		}
		if(!empty($_G['config']['home']['holddomain']) && !$_G['setting']['holddomain']) {
			$holddomain = implode('|', explode(',', $_G['config']['home']['holddomain']));
			DB::insert('common_setting', array('skey' => 'holddomain', 'svalue' => $holddomain), 0, 1);
		}
		if(!empty($_G['config']['home']['allowdomain']) && !$_G['setting']['allowspacedomain']) {
			DB::insert('common_setting', array('skey' => 'allowspacedomain', 'svalue' => 1), 0, 1);
		}
		
		if(!DB::result_first("SELECT domain FROM ".DB::table('common_domain')." WHERE idtype='home'")) {
			$domainroot = $_G['config']['home']['domainroot'] ? $_G['config']['home']['domainroot'] : '';
			DB::query("INSERT INTO ".DB::table('common_domain')." (domain, domainroot, id, idtype) SELECT domain, '$domainroot', uid, 'home' FROM ".DB::table('common_member_field_home')." WHERE domain<>''");
		}
		show_msg("���������������", "$theurl?step=data&op=$nextop");

	} else {//���������������

		$deletevar = array('app', 'home');//config����Ҫɾ������Ŀ
		$default_config = $_config = array();
		$default_configfile = DISCUZ_ROOT.'./config/config_global_default.php';
		if(!file_exists($default_configfile)) {
			exit('config_global_default.php was lost, please reupload this  file.');
		} else {
			include $default_configfile;
			$default_config = $_config;
		}
		$configfile = DISCUZ_ROOT.'./config/config_global.php';
		include $configfile;
		if(save_config_file($configfile, $_config, $default_config, $deletevar)) {
			show_msg("���ݴ������", "$theurl?step=delete");
		} else {
			show_msg('"config/config_global.php" �ļ��Ѹ��£����� "config/" Ŀ¼����д�룬�����ѽ����µ��ļ����浽 "data/" Ŀ¼�£���ͨ�� FTP ��������ת�Ƶ� "config/" Ŀ¼�¸���Դ�ļ���<br /><br /><a href="'.$theurl.'?step=delete">��������������������������</a>');
		}
	}

}elseif ($_GET['step'] == 'delete') {

	if(!$devmode) {
		show_msg("����ɾ����������������һ��", "$theurl?step=style");
	}

	//�����Ҫɾ�����ֶ�
	//�ϱ�����
	$oldtables = array();
	$query = DB::query("SHOW TABLES LIKE '$config[tablepre]%'");
	while ($value = DB::fetch($query)) {
		$values = array_values($value);
		$oldtables[] = $values[0];//�ֱ�������
	}

	//�±�����
	$sql = implode('', file($sqlfile));
	preg_match_all("/CREATE\s+TABLE.+?pre\_(.+?)\s+\((.+?)\)\s*(ENGINE|TYPE)\s*\=/is", $sql, $matches);
	$newtables = empty($matches[1])?array():$matches[1];
	$newsqls = empty($matches[0])?array():$matches[0];

	//��Ҫɾ���ı�
	$deltables = array();
	$delcolumns = array();

	//�ϵ��У��µ�û��
	foreach ($oldtables as $tname) {
		$tname = substr($tname, strlen($config['tablepre']));
		if(in_array($tname, $newtables)) {
			//�Ƚ��ֶ��Ƿ����
			$query = DB::query("SHOW CREATE TABLE ".DB::table($tname));
			$cvalue = DB::fetch($query);
			$oldcolumns = getcolumn($cvalue['Create Table']);

			//�µ�
			$i = array_search($tname, $newtables);
			$newcolumns = getcolumn($newsqls[$i]);

			//�ϵ��У��µ�û�е��ֶ�
			foreach ($oldcolumns as $colname => $colstruct) {
				if($colname == 'UNIQUE' || $colname == 'KEY') {
					foreach ($colstruct as $key_index => $key_value) {
						if(empty($newcolumns[$colname][$key_index])) {
							$delcolumns[$tname][$colname][$key_index] = $key_value;
						}
					}
				} else {
					//��ͨ�ֶ�
					if(empty($newcolumns[$colname])) {
						$delcolumns[$tname][] = $colname;
					}
				}
			}
		} else {
			if(!strexists($tname, 'uc_') && !strexists($tname, 'ucenter_') && !preg_match('/forum_(thread|post)_(\d+)$/i', $tname)) {
				$deltables[] = $tname;
			}
		}
	}

	//��ʾ
	show_header();
	echo '<form method="post" autocomplete="off" action="'.$theurl.'?step=delete">';

	//ɾ����
	$deltablehtml = '';
	if($deltables) {
		$deltablehtml .= '<table>';
		foreach ($deltables as $tablename) {
			$deltablehtml .= "<tr><td><input type=\"checkbox\" name=\"deltables[$tablename]\" value=\"1\"></td><td>{$config['tablepre']}$tablename</td></tr>";
		}
		$deltablehtml .= '</table>';
		echo "<p>���� <strong>���ݱ�</strong> ���׼���ݿ�����Ƕ����:<br>�����Ը�����Ҫ���о����Ƿ�ɾ��</p>$deltablehtml";
	}

	//ɾ���ֶ�
	$delcolumnhtml = '';
	if($delcolumns) {
		$delcolumnhtml .= '<table>';
		foreach ($delcolumns as $tablename => $cols) {
			foreach ($cols as $coltype => $col) {
				if (is_array($col)) {
					foreach ($col as $index => $indexvalue) {
						$delcolumnhtml .= "<tr><td><input type=\"checkbox\" name=\"delcols[$tablename][$coltype][$index]\" value=\"1\"></td><td>{$config['tablepre']}$tablename</td><td>����($coltype) $index $indexvalue</td></tr>";
					}
				} else {
					$delcolumnhtml .= "<tr><td><input type=\"checkbox\" name=\"delcols[$tablename][$col]\" value=\"1\"></td><td>{$config['tablepre']}$tablename</td><td>�ֶ� $col</td></tr>";
				}
			}
		}
		$delcolumnhtml .= '</table>';

		echo "<p>���� <strong>�ֶ�</strong> ���׼���ݿ�����Ƕ����:<br>�����Ը�����Ҫ���о����Ƿ�ɾ��</p>$delcolumnhtml";
	}

	if(empty($deltables) && empty($delcolumns)) {
		echo "<p>���׼���ݿ���ȣ�û����Ҫɾ�������ݱ����ֶ�</p><a href=\"$theurl?step=style\">����������һ��</a></p>";
	} else {
		echo "<p><input type=\"submit\" name=\"delsubmit\" value=\"�ύɾ��\"></p><p>��Ҳ���Ժ��Զ���ı����ֶ�<br><a href=\"$theurl?step=style\">ֱ�ӽ�����һ��</a></p>";
	}
	echo '</form>';

	show_footer();
	exit();

} elseif ($_GET['step'] == 'style') {
	//�ָ����
	if(empty($_GET['confirm'])) {
		show_msg("��ȷ���Ƿ�Ҫ�ָ�Ĭ�Ϸ��<br /><br /><a href=\"$theurl?step=style&confirm=yes\">[ �� ]</a>&nbsp;&nbsp;<a href=\"$theurl?step=cache\">[ �� ]</a>", '');
	}

	define('IN_ADMINCP', true);
	require_once libfile('function/admincp');
	require_once libfile('function/importdata');
	$dir = DB::result_first("SELECT t.directory FROM ".DB::table('common_style')." s LEFT JOIN ".DB::table('common_template')." t ON t.templateid=s.templateid WHERE s.styleid='1'");
	import_styles(1, $dir, 1, 0);
	DB::update('common_setting', array('svalue' => 1), "skey='styleid'");

	show_msg("Ĭ�Ϸ���ѻָ���������һ��", "$theurl?step=cache");

} elseif ($_GET['step'] == 'cache') {

	//дlog
	if(!$devmode && @$fp = fopen($lockfile, 'w')) {
		fwrite($fp, ' ');
		fclose($fp);
	}

	dir_clear(ROOT_PATH.'./data/template');
	dir_clear(ROOT_PATH.'./data/cache');
	dir_clear(ROOT_PATH.'./data/threadcache');
	dir_clear(ROOT_PATH.'./uc_client/data');
	dir_clear(ROOT_PATH.'./uc_client/data/cache');

	//�������
	show_msg('��ϲ�����ݿ�ṹ������ɣ�Ϊ�����ݰ�ȫ����ɾ�����ļ���<iframe src="../misc.php?mod=initsys" style="display:none;"></iframe>');

}

function has_another_special_table($tablename, $key) {
	if(!$key) {
		return $tablename;
	}

	$tables_array = get_special_tables_array($tablename);

	if($key > count($tables_array)) {
		return FALSE;
	} else {
		return TRUE;
	}
}

function get_special_tables_array($tablename) {
	$tablename = DB::table($tablename);
	$tablename = str_replace('_', '\_', $tablename);
	$query = DB::query("SHOW TABLES LIKE '{$tablename}\_%'");
	$dbo = DB::object();
	$tables_array = array();
	while($row = $dbo->fetch_array($query, MYSQL_NUM)) {
		if(preg_match("/^{$tablename}_(\\d+)$/i", $row[0])) {
			$prefix_len = strlen($dbo->tablepre);
			$row[0] = substr($row[0], $prefix_len);
			$tables_array[] = $row[0];
		}
	}
	return $tables_array;
}

function get_special_table_by_num($tablename, $num) {
	$tables_array = get_special_tables_array($tablename);

	$num --;
	return isset($tables_array[$num]) ? $tables_array[$num] : FALSE;
}

//����ƥ��,��ȡ�ֶ�/����/�ؼ�����Ϣ
function getcolumn($creatsql) {

	$creatsql = preg_replace("/ COMMENT '.*?'/i", '', $creatsql);
	preg_match("/\((.+)\)\s*(ENGINE|TYPE)\s*\=/is", $creatsql, $matchs);

	$cols = explode("\n", $matchs[1]);
	$newcols = array();
	foreach ($cols as $value) {
		$value = trim($value);
		if(empty($value)) continue;
		$value = remakesql($value);//��ʹ�ַ��滻
		if(substr($value, -1) == ',') $value = substr($value, 0, -1);//ȥ��ĩβ����

		$vs = explode(' ', $value);
		$cname = $vs[0];

		if($cname == 'KEY' || $cname == 'INDEX' || $cname == 'UNIQUE') {

			$name_length = strlen($cname);
			if($cname == 'UNIQUE') $name_length = $name_length + 4;

			$subvalue = trim(substr($value, $name_length));
			$subvs = explode(' ', $subvalue);
			$subcname = $subvs[0];
			$newcols[$cname][$subcname] = trim(substr($value, ($name_length+2+strlen($subcname))));

		}  elseif($cname == 'PRIMARY') {

			$newcols[$cname] = trim(substr($value, 11));

		}  else {

			$newcols[$cname] = trim(substr($value, strlen($cname)));
		}
	}
	return $newcols;
}

//����sql��
function remakesql($value) {
	$value = trim(preg_replace("/\s+/", ' ', $value));//�ո��׼��
	$value = str_replace(array('`',', ', ' ,', '( ' ,' )'), array('', ',', ',','(',')'), $value);//ȥ�����÷���
	return $value;
}

//��ʾ
function show_msg($message, $url_forward='') {

	if($url_forward) {
		$message = "<a href=\"$url_forward\">$message (��ת��...)</a><script>setTimeout(\"window.location.href ='$url_forward';\", 1);</script>";
	}

	show_header();
	print<<<END
	<table>
	<tr><td>$message</td></tr>
	</table>
END;
	show_footer();
	exit();
}


//ҳ��ͷ��
function show_header() {
	global $config;

	$nowarr = array($_GET['step'] => ' class="current"');

	print<<<END
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=$config[charset]" />
	<title> ���ݿ��������� </title>
	<style type="text/css">
	* {font-size:12px; font-family: Verdana, Arial, Helvetica, sans-serif; line-height: 1.5em; word-break: break-all; }
	body { text-align:center; margin: 0; padding: 0; background: #F5FBFF; }
	.bodydiv { margin: 40px auto 0; width:720px; text-align:left; border: solid #86B9D6; border-width: 5px 1px 1px; background: #FFF; }
	h1 { font-size: 18px; margin: 1px 0 0; line-height: 50px; height: 50px; background: #E8F7FC; color: #5086A5; padding-left: 10px; }
	#menu {width: 100%; margin: 10px auto; text-align: center; }
	#menu td { height: 30px; line-height: 30px; color: #999; border-bottom: 3px solid #EEE; }
	.current { font-weight: bold; color: #090 !important; border-bottom-color: #F90 !important; }
	input { border: 1px solid #B2C9D3; padding: 5px; background: #F5FCFF; }
	#footer { font-size: 10px; line-height: 40px; background: #E8F7FC; text-align: center; height: 38px; overflow: hidden; color: #5086A5; margin-top: 20px; }
	</style>
	</head>
	<body>
	<div class="bodydiv">
	<h1>���ݿ���������</h1>
	<div style="width:90%;margin:0 auto;">
	<table id="menu">
	<tr>
	<td{$nowarr[start]}>������ʼ</td>
	<td{$nowarr[sql]}>���ݿ�ṹ���������</td>
	<td{$nowarr[data]}>���ݸ���</td>
	<td{$nowarr[delete]}>���ݿ�ṹɾ��</td>
	<td{$nowarr[cache]}>�������</td>
	</tr>
	</table>
	<br>
END;
}

//ҳ�涥��
function show_footer() {
	print<<<END
	</div>
	<div id="footer">&copy; Comsenz Inc. 2001-2010 http://www.comsenz.com</div>
	</div>
	<br>
	</body>
	</html>
END;
}

function runquery($sql) {
	global $_G;
	$tablepre = $_G['config']['db'][1]['tablepre'];
	$dbcharset = $_G['config']['db'][1]['dbcharset'];

	$sql = str_replace("\r", "\n", str_replace(array(' {tablepre}', ' cdb_', ' `cdb_', ' pre_', ' `pre_'), array(' '.$tablepre, ' '.$tablepre, ' `'.$tablepre, ' '.$tablepre, ' `'.$tablepre), $sql));
	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query;
		}
		$num++;
	}
	unset($sql);

	foreach($ret as $query) {
		$query = trim($query);
		if($query) {

			if(substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $query);
				DB::query(createtable($query, $dbcharset));

			} else {
				DB::query($query);
			}

		}
	}
}


//����DIY�ļ�
function import_diy($importfile, $primaltplname, $targettplname) {
	global $_G;

	$css = $html = '';
	$arr = array();

	$content = file_get_contents(realpath($importfile));
	if (empty($content)) return $arr;
	require_once DISCUZ_ROOT.'./source/class/class_xml.php';
	$diycontent = xml2array($content);

	if ($diycontent) {

		//�õ�ԭframeID����frameID
		foreach ($diycontent['layoutdata'] as $key => $value) {
			if (!empty($value)) getframeblock($value);
		}
		$newframe = array();
		foreach ($_G['curtplframe'] as $value) {
			$newframe[] = $value['type'].random(6);
		}

		//����block����
		$mapping = array();
		if (!empty($diycontent['blockdata'])) {
			$mapping = block_import($diycontent['blockdata']);
			unset($diycontent['bockdata']);
		}

		//�õ�ԭblockID����blockID
		$oldbids = $newbids = array();
		if (!empty($mapping)) {
			foreach($mapping as $obid=>$nbid) {
				$oldbids[] = 'portal_block_'.$obid;
				$newbids[] = 'portal_block_'.$nbid;
			}
		}

		//�滻�������ģ����ʽ��
		require_once DISCUZ_ROOT.'./source/class/class_xml.php';
		$xml = array2xml($diycontent['layoutdata'],true);
		$xml = str_replace($oldbids, $newbids, $xml);
		$xml = str_replace((array)array_keys($_G['curtplframe']), $newframe, $xml);
		$diycontent['layoutdata'] = xml2array($xml);

		//�滻css��ʽ��
		$css = str_replace($oldbids, $newbids, $diycontent['spacecss']);
		$css = str_replace((array)array_keys($_G['curtplframe']), $newframe, $css);

		//DIY ���������ļ������
		$arr['spacecss'] = $css;
		$arr['layoutdata'] = $diycontent['layoutdata'];
		$arr['style'] = $diycontent['style'];
		save_diy_data($primaltplname, $targettplname, $arr, true);
	}
	return $arr;
}

function save_config_file($filename, $config, $default, $deletevar) {
	$config = setdefault($config, $default, $deletevar);
	$date = gmdate("Y-m-d H:i:s", time() + 3600 * 8);
	$content = <<<EOT
<?php

/**
	[Discuz!] (C) 2001-2099 Comsenz Inc.

	config_global.php Build at $date
*/

\$_config = array();

EOT;
	$content .= getvars(array('_config' => $config));
	$content .= "\r\n// ".str_pad('  THE END  ', 50, '-', STR_PAD_BOTH)." //\r\n\r\n?>";
	if(!is_writable($filename) || !($len = file_put_contents($filename, $content))) {
		file_put_contents(DISCUZ_ROOT.'./data/config_global.php', $content);
		return 0;
	}
	return 1;
}

function setdefault($var, $default, $deletevar) {
	foreach ($default as $k => $v) {
		if(!isset($var[$k])) {
			$var[$k] = $default[$k];
		} elseif(is_array($v)) {
			$var[$k] = setdefault($var[$k], $default[$k]);
		}
	}
	foreach ($deletevar as $k) {
		unset($var[$k]);
	}
	return $var;
}

function getvars($data, $type = 'VAR') {
	$evaluate = '';
	foreach($data as $key => $val) {
		if(!preg_match("/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/", $key)) {
			continue;
		}
		if(is_array($val)) {
			$evaluate .= buildarray($val, 0, "\${$key}")."\r\n";
		} else {
			$val = addcslashes($val, '\'\\');
			$evaluate .= $type == 'VAR' ? "\$$key = '$val';\n" : "define('".strtoupper($key)."', '$val');\n";
		}
	}
	return $evaluate;
}

function buildarray($array, $level = 0, $pre = '$_config') {
	static $ks;
	if($level == 0) {
		$ks = array();
		$return = '';
	}

	foreach ($array as $key => $val) {
		if($level == 0) {
			$newline = str_pad('  CONFIG '.strtoupper($key).'  ', 70, '-', STR_PAD_BOTH);
			$return .= "\r\n// $newline //\r\n";
			if($key == 'admincp') {
				$newline = str_pad(' Founders: $_config[\'admincp\'][\'founder\'] = \'1,2,3\'; ', 70, '-', STR_PAD_BOTH);
				$return .= "// $newline //\r\n";
			}
		}

		$ks[$level] = $ks[$level - 1]."['$key']";
		if(is_array($val)) {
			$ks[$level] = $ks[$level - 1]."['$key']";
			$return .= buildarray($val, $level + 1, $pre);
		} else {
			$val =  is_string($val) || strlen($val) > 12 || !preg_match("/^\-?[1-9]\d*$/", $val) ? '\''.addcslashes($val, '\'\\').'\'' : $val;
			$return .= $pre.$ks[$level - 1]."['$key']"." = $val;\r\n";
		}
	}
	return $return;
}

function dir_clear($dir) {
	global $lang;
	if($directory = @dir($dir)) {
		while($entry = $directory->read()) {
			$filename = $dir.'/'.$entry;
			if(is_file($filename)) {
				@unlink($filename);
			}
		}
		$directory->close();
		@touch($dir.'/index.htm');
	}
}

?>
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
//配置
$config = array(
	'dbcharset' => $_G['config']['db']['1']['dbcharset'],
	'charset' => $_G['config']['output']['charset'],
	'tablepre' => $_G['config']['db']['1']['tablepre']
);
$theurl = 'update.php';

$lockfile = DISCUZ_ROOT.'./data/update.lock';
if(file_exists($lockfile)) {
	show_msg('请您先登录服务器ftp，手工删除 ./data/update.lock 文件，再次运行本文件进行升级。');
}

//新SQL, 优先使用开发过程中的 install_dev.sql
$devmode = file_exists(DISCUZ_ROOT.'./install/data/install_dev.sql');
$sqlfile = DISCUZ_ROOT.($devmode ? './install/data/install_dev.sql' : './install/data/install.sql');

if(!file_exists($sqlfile)) {
	show_msg('SQL文件 '.$sqlfile.' 不存在');
}

//提交处理
if($_POST['delsubmit']) {
	//删除表
	if(!empty($_POST['deltables'])) {
		foreach ($_POST['deltables'] as $tname => $value) {
			DB::query("DROP TABLE `".DB::table($tname)."`");
		}
	}
	//删除字段
	if(!empty($_POST['delcols'])) {
		foreach ($_POST['delcols'] as $tname => $cols) {
			foreach ($cols as $col => $indexs) {
				if($col == 'PRIMARY') {
					DB::query("ALTER TABLE ".DB::table($tname)." DROP PRIMARY KEY", 'SILENT');//屏蔽错误
				} elseif($col == 'KEY' || $col == 'UNIQUE') {
					foreach ($indexs as $index => $value) {
						DB::query("ALTER TABLE ".DB::table($tname)." DROP INDEX `$index`", 'SILENT');//屏蔽错误
					}
				} else {
					DB::query("ALTER TABLE ".DB::table($tname)." DROP `$col`");
				}
			}
		}
	}

	show_msg('删除表和字段操作完成了', $theurl.'?step=style');
}

if(empty($_GET['step'])) $_GET['step'] = 'start';

//处理开始
if($_GET['step'] == 'start') {
	//开始
	show_msg('说明：<br>本升级程序会参照最新的SQL文件，对数据库进行同步升级。<br>
		请确保当前目录下 ./data/install.sql 文件为最新版本。<br><br>
		<a href="'.$theurl.'?step=prepare">准备完毕，升级开始</a>');

} elseif ($_GET['step'] == 'prepare') {
	//预处理，为可能影响数据升级的表变动做准备
	if(!DB::result_first('SELECT skey FROM '.DB::table('common_setting')." WHERE skey='group_recommend' LIMIT 1")) {
		DB::query("TRUNCATE ".DB::table('forum_groupinvite'));
	}
	if(DB::fetch_first("SHOW COLUMNS FROM ".DB::table('forum_activityapply')." LIKE 'contact'")) {
		$query = DB::query("UPDATE ".DB::table('forum_activityapply')." SET message=CONCAT_WS(' 联系方式:', message, contact) WHERE contact<>''");
		DB::query("ALTER TABLE ".DB::table('forum_activityapply')." DROP contact");
	}
	//处理完毕
	show_msg('准备完毕，进入下一步数据库结构升级', $theurl.'?step=sql');
} elseif ($_GET['step'] == 'sql') {

	//新的SQL
	$sql = implode('', file($sqlfile));
	preg_match_all("/CREATE\s+TABLE.+?pre\_(.+?)\s*\((.+?)\)\s*(ENGINE|TYPE)\s*\=/is", $sql, $matches);
	$newtables = empty($matches[1])?array():$matches[1];
	$newsqls = empty($matches[0])?array():$matches[0];
	if(empty($newtables) || empty($newsqls)) {
		show_msg('SQL文件内容为空，请确认');
	}

	//升级表
	$i = empty($_GET['i'])?0:intval($_GET['i']);
	$count_i = count($newtables);
	if($i>=$count_i) {
		//处理完毕
		show_msg('数据库结构升级完毕，进入下一步数据升级操作', $theurl.'?step=data');
	}
	//当前处理表
	$newtable = $newtables[$i];

	// forum_post, forum_thread 分表特殊处理
	$specid = intval($_GET['specid']);
	if($specid && in_array($newtable, array('forum_post', 'forum_thread'))) {
		$spectable = $newtable;
		$newtable = get_special_table_by_num($newtable, $specid);
	}

	$newcols = getcolumn($newsqls[$i]);

	//获取当前SQL
	if(!$query = DB::query("SHOW CREATE TABLE ".DB::table($newtable), 'SILENT')) {
		//添加表
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
			show_msg('添加表 '.DB::table($newtable).' 出错,请手工执行以下SQL语句后,再重新运行本升级程序:<br><br>'.dhtmlspecialchars($usql));
		} else {
			$msg = '添加表 '.DB::table($newtable).' 完成';
		}
	} else {
		$value = DB::fetch($query);
		$oldcols = getcolumn($value['Create Table']);

		//获取升级SQL文
		$updates = array();
		foreach ($newcols as $key => $value) {
			if($key == 'PRIMARY') {
				if($value != $oldcols[$key]) {
					if(!empty($oldcols[$key])) {
						$usql = "RENAME TABLE ".DB::table($newtable)." TO ".DB::table($newtable.'_bak');
						if(!DB::query($usql, 'SILENT')) {
							show_msg('升级表 '.DB::table($newtable).' 出错,请手工执行以下升级语句后,再重新运行本升级程序:<br><br><b>升级SQL语句</b>:<div style=\"position:absolute;font-size:11px;font-family:verdana,arial;background:#EBEBEB;padding:0.5em;\">'.dhtmlspecialchars($usql)."</div><br><b>Error</b>: ".DB::error()."<br><b>Errno.</b>: ".DB::errno());
						} else {
							$msg = '表改名 '.DB::table($newtable).' 完成！';
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

		//升级处理
		if(!empty($updates)) {
			$usql = "ALTER TABLE ".DB::table($newtable)." ".implode(', ', $updates);
			if(!DB::query($usql, 'SILENT')) {
				show_msg('升级表 '.DB::table($newtable).' 出错,请手工执行以下升级语句后,再重新运行本升级程序:<br><br><b>升级SQL语句</b>:<div style=\"position:absolute;font-size:11px;font-family:verdana,arial;background:#EBEBEB;padding:0.5em;\">'.dhtmlspecialchars($usql)."</div><br><b>Error</b>: ".DB::error()."<br><b>Errno.</b>: ".DB::errno());
			} else {
				$msg = '升级表 '.DB::table($newtable).' 完成！';
			}
		} else {
			$msg = '检查表 '.DB::table($newtable).' 完成，不需升级，跳过';
		}
	}

	//处理下一个
	if($specid) {
		$newtable = $spectable;
	}

	if(get_special_table_by_num($newtable, $specid+1)) {
		$next = $theurl . '?step=sql&i='.($_GET['i']).'&specid='.($specid + 1);
	} else {
		$next = $theurl.'?step=sql&i='.($_GET['i']+1);
	}
	show_msg("[ $i / $count_i ] ".$msg, $next);

} elseif ($_GET['step'] == 'data') {// 升级数据


	if(empty($_GET['op']) || $_GET['op'] == 'realname') {// 实名功能

		$nextop = 'setting';// 下一个处理的功能

		// 一次处理 1000 个
		$p = 1000;
		$i = !empty($_GET['i']) ? intval($_GET['i']) : 0;
		$n = 0;
		if($i==0) {
			// profile_setting 表中插入 realname 项
			$value = DB::fetch_first('SELECT * FROM '.DB::table('common_member_profile_setting')." WHERE fieldid = 'realname'");
			if(!empty($value)) {
				show_msg("实名功能升级完毕", "$theurl?step=data&op=$nextop");
			}
			DB::query("INSERT INTO ".DB::table('common_member_profile_setting')." VALUES ('realname', '1', '0', '1', '真实姓名', '', '0', '0', '0', '0', '1', 'text', '0', '', '', '0', '0')");
		}
		$t = DB::result_first('SELECT uid FROM '.DB::table('common_member')." ORDER BY uid DESC LIMIT 1");
		$names = $uids = array();
		$query = DB::query('SELECT * FROM '.DB::table('common_member')." WHERE uid>'$i' AND realname != '' LIMIT $p");
		while($value=DB::fetch($query)) {
			$n = intval($value['uid']);
			// 如果设置了实名，设置到 profile 表
			$value['uid'] = intval($value['uid']);
			$value['realname'] = addslashes($value['realname']);
			DB::update('common_member_profile', array('realname'=>$value['realname']), array('uid'=>$value['uid']));
			DB::update('common_member', array('realname'=>''), array('uid'=>$value['uid']));
			$names[$value['uid']] = $value['realname'];
		}

		// 下一步
		if($n>0) {
			show_msg("实名功能升级中[$n/$t]", "$theurl?step=data&op=realname&i=$n");
		} else {
			show_msg("实名功能升级完毕", "$theurl?step=data&op=$nextop");
		}

	} elseif($_GET['op'] == 'setting') { //论坛配置相关 setting
		$nextop = 'admingroup';// 下一个处理的功能
		$settings = $newsettings = array();
		$query = DB::query('SELECT * FROM '.DB::table('common_setting')." WHERE 1");
		while($value=DB::fetch($query)) {
			$settings[$value[skey]] = $value['svalue'];
		}
		//note 论坛 SEO 设置
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
		//note 水印设置
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
		//群组动态开关
		DB::query("REPLACE INTO ".DB::table('common_setting')." VALUES ('group_allowfeed', '1')");

		//排行榜默认开启
		if(!isset($settings['ranklist'])) {
			DB::query("REPLACE INTO ".DB::table('common_setting')." VALUES ('ranklist', '".'a:11:{s:6:"status";s:1:"1";s:10:"cache_time";s:1:"1";s:12:"index_select";s:8:"thisweek";s:6:"member";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}s:6:"thread";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}s:4:"blog";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}s:4:"poll";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}s:8:"activity";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}s:7:"picture";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}s:5:"forum";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}s:5:"group";a:3:{s:9:"available";s:1:"1";s:10:"cache_time";s:1:"5";s:8:"show_num";s:2:"20";}}'."')");
		}
		//注册地址和名称
		DB::query("REPLACE INTO ".DB::table('common_setting')." VALUES ('regname', 'register')");
		if(empty($settings['reglinkname'])) {
			DB::query("REPLACE INTO ".DB::table('common_setting')." VALUES ('reglinkname', '注册')");
		}

		if(empty($settings['domain'])) {//note 判断第一次升级setting里的domain
			DB::query("REPLACE INTO ".DB::table('common_setting')." VALUES ('domain', '".'a:5:{s:12:"defaultindex";s:9:"forum.php";s:10:"holddomain";s:18:"www|*blog*|*space*";s:4:"list";a:0:{}s:3:"app";a:5:{s:6:"portal";s:0:"";s:5:"forum";s:0:"";s:5:"group";s:0:"";s:4:"home";s:0:"";s:7:"default";s:0:"";}s:4:"root";a:5:{s:4:"home";s:0:"";s:5:"group";s:0:"";s:5:"forum";s:0:"";s:5:"topic";s:0:"";s:7:"channel";s:0:"";}}'."')");
		}
		if(empty($settings['group_recommend'])) {//note 判断是不是第一次升级setting
			//升级新手见习时间由小时改为以分钟为单位
			if($settings['newbiespan'] > 0) {
				$newsettings['newbiespan'] = round($settings['newbiespan'] * 60);
			}
			//清理个人关注的群组分类
			DB::query("UPDATE ".DB::table('common_member_field_forum')." SET attentiongroup=''");

			//初始化群组首页推荐群组
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
		//处理允许查看用户的主题和帖子
		if(!isset($settings['allowviewuserthread'])) {
			//默认开启
			$allowviewuserthread = array('allow'=>'1','fids'=>array());
			$query = DB::query('SELECT ff.fid,ff.viewperm FROM '.DB::table('forum_forum').' f LEFT JOIN '.DB::table('forum_forumfield')." ff ON f.fid = ff.fid WHERE f.status='1' AND f.type IN ('forum','sub')");
			while($value = DB::fetch($query)) {
				$arr = !empty($value['viewperm']) ? explode("\t", $value['viewperm']) : array();
				//没有设置权限或设置为游客或新手可见的版块默认选择
				if(empty($value['viewperm']) || in_array('7', $arr) ||  in_array($settings['newusergroupid'], $arr) ) {
					$allowviewuserthread['fids'][] = $value['fid'];
				}
			}
			DB::query("INSERT INTO ".DB::table('common_setting')." VALUES ('allowviewuserthread', '".addslashes(serialize($allowviewuserthread))."')");
		}
		show_msg("配置项升级完成", "$theurl?step=data&op=$nextop");
	} elseif($_GET['op'] == 'admingroup') { //论坛管理组 admingroup
		$nextop = 'updatecron';// 下一个处理的功能
		DB::query('UPDATE '.DB::table('common_admingroup')." SET allowclearrecycle='1' WHERE admingid='1' OR admingid='2'");
		//增加群组内容管理权限
		if(DB::result_first("SELECT cpgroupid FROM ".DB::table('common_admincp_group')." WHERE cpgroupid='3'")) {
			if(!DB::result_first("SELECT cpgroupid FROM ".DB::table('common_admincp_perm')." WHERE cpgroupid='3' AND perm='threads_group'")) {
				DB::query("INSERT INTO ".DB::table('common_admincp_perm')." VALUES ('3', 'threads_group')");
				DB::query("INSERT INTO ".DB::table('common_admincp_perm')." VALUES ('3', 'prune_group')");
				DB::query("INSERT INTO ".DB::table('common_admincp_perm')." VALUES ('3', 'attach_group')");
				//删除管理权限表中的多余字段
				DB::query("ALTER TABLE ".DB::table('common_admingroup')." DROP `disablepostctrl`");
				//默认所有用户群组发帖不需要审核
				DB::query("UPDATE ".DB::table('common_usergroup_field')." SET allowgroupdirectpost='3'");
				DB::query("UPDATE ".DB::table('common_usergroup_field')." SET allowgroupposturl='3' WHERE groupid='1'");
			}
		}
		show_msg("管理组设置升级完成", "$theurl?step=data&op=$nextop");
	} elseif($_GET['op'] == 'updatecron') { //升级计划任务
		$nextop = 'updatereport';// 下一个处理的功能
		if(!DB::result_first("SELECT filename FROM ".DB::table('common_cron')." WHERE filename='cron_cleanfeed.php'")) {
			DB::query("INSERT INTO ".DB::table('common_cron')." VALUES ('', '1','system','清理过期动态','cron_cleanfeed.php','1269746634','1269792000','-1','-1','0','0')");
		}
		
		//清理没用的计划任务
		if(DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_cron')." WHERE filename IN('cron_birthday_daily.php')")) {
			DB::query("DELETE FROM ".DB::table('common_cron')." WHERE filename IN('cron_birthday_daily.php')");
		}
		
		show_msg("计划任务升级完成", "$theurl?step=data&op=$nextop");
	} elseif($_GET['op'] == 'updatereport') {
		$nextop = 'myappcount';// 下一个处理的功能
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
		show_msg("举报升级完成", "$theurl?step=data&op=$nextop");
	} elseif($_GET['op'] == 'myappcount') {

		$nextop = 'nav';// 下一个处理的功能
		$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_myapp_count')),0);
		if(!$count) {
			DB::query('INSERT INTO '.DB::table('common_myapp_count').' (appid) SELECT appid FROM '.DB::table('common_myapp'));
		}
		show_msg("漫游应用统计升级完成", "$theurl?step=data&op=$nextop");

	} elseif($_GET['op'] == 'nav') {

		$nextop = 'forumstatus';// 下一个处理的功能
		$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_nav')." WHERE navtype='0' AND type='0' AND identifier=''"),0);
		if($count) {
			DB::delete('common_nav', "navtype='0' AND type='0' AND identifier=''");
			$sql = implode('', file(DISCUZ_ROOT.'./install/data/install_data.sql'));
			preg_match("/\[update\_nav\](.+?)\[\/update\_nav\]/is", $sql, $a);
			runquery($a[1]);
		}

		show_msg("导航数据升级完成", "$theurl?step=data&op=$nextop");

	} elseif($_GET['op'] == 'forumstatus') {

		$nextop = 'poststick';// 下一个处理的功能
		$query = DB::query("SELECT fid FROM ".DB::table('forum_forum')." WHERE status='2'");
		if(DB::num_rows($query)) {
			while($row = DB::fetch($query)) {
				$fids[] = $row['fid'];
			}
			DB::update('forum_forumfield', array('hidemenu' => 1), "fid IN (".dimplode($fids).")");
			DB::update('forum_forum', array('status' => 1), "status='2'");
		}

		show_msg("版块状态升级完毕", "$theurl?step=data&op=$nextop");

	} elseif($_GET['op'] == 'poststick') {

		$nextop = 'usergroup_allowvisit';// 全部处理完成
		$query = DB::query("SELECT * FROM ".DB::table('forum_postposition')." WHERE stick='1'", 'SILENT');
		if(DB::num_rows($query)) {
			while($row = DB::fetch($query)) {
				DB::query("REPLACE INTO ".DB::table('forum_poststick')." SET tid='$row[tid]', pid='$row[pid]', position='$row[position]', dateline='$row[dateline]'");
			}
			DB::query("DELETE FROM ".DB::table('forum_postposition')." WHERE stick='1'");
		}

		show_msg("回帖推荐升级完毕", "$theurl?step=data&op=$nextop");

	//将管理员的 allowvisit 提升为 2
	} elseif($_GET['op'] == 'usergroup_allowvisit') {
		$nextop = 'creditrule';
		DB::update('common_usergroup', array('allowvisit' => 2), "groupid='1'");
		show_msg("用户升级完毕", "$theurl?step=data&op=$nextop");
	} elseif($_GET['op'] == 'creditrule') {
		$nextop = 'bbcode';
		$delrule = array('register', 'realname', 'invitefriend', 'report', 'uploadimage', 'editrealname', 'editrealemail', 'delavatar');
		$count = DB::result(DB::query("SELECT COUNT(*) FROM ".DB::table('common_credit_rule')." WHERE action IN(".dimplode($delrule).")"),0);
		if($count) {
			DB::query("DELETE FROM ".DB::table('common_credit_rule')." WHERE action IN(".dimplode($delrule).")");
		}
		show_msg("积分规则升级完毕", "$theurl?step=data&op=$nextop");
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
		show_msg("自定义代码权限升级完毕", "$theurl?step=data&op=$nextop");
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
		show_msg("鉴定图章升级完毕", "$theurl?step=data&op=$nextop");
	} elseif($_GET['op'] == 'block_permission') { //处理模块权限表
		$nextop = 'common_usergroup_field';
		if(!DB::result_first('SELECT skey FROM '.DB::table('common_setting')." WHERE skey='group_recommend' LIMIT 1")) {
			DB::query("UPDATE ".DB::table('common_block_permission')." SET allowmanage=allowsetting,allowrecomment=allowdata");
		}
		show_msg("模块权限升级完毕", "$theurl?step=data&op=$nextop");

	} elseif($_GET['op'] == 'common_usergroup_field') { //用户组权限表
		$nextop = 'group_index';
		if(!DB::result_first('SELECT skey FROM '.DB::table('common_setting')." WHERE skey='group_recommend' LIMIT 1")) {
			DB::query("UPDATE ".DB::table('common_usergroup_field')."
				SET allowcommentarticle=allowcomment,allowblogmod=allowblog,allowdoingmod=allowdoing,allowuploadmod=allowupload,allowsharemod=allowshare,allowdownlocalimg=allowpostarticle");
		}
		show_msg("用户组权限升级完毕", "$theurl?step=data&op=$nextop");

	} elseif($_GET['op'] == 'group_index') { //群组首页
		$nextop = 'domain';
		if(!DB::result_first('SELECT skey FROM '.DB::table('common_setting')." WHERE skey='group_recommend' LIMIT 1")) {
			//导入文件
			$arr = array(
				0 => array('importfile'=>'./data/group_index.xml','primaltplname'=>'group/index', 'targettplname'=>'group/index'), //群组首页DIY模块
			);
			foreach ($arr as $v) {
				import_diy($v['importfile'], $v['primaltplname'], $v['targettplname']);
			}
		}
		show_msg("群组首页升级完毕", "$theurl?step=data&op=$nextop");

	} elseif($_GET['op'] == 'domain') { //群组首页
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
		show_msg("域名设置升级完毕", "$theurl?step=data&op=$nextop");

	} else {//数据升级处理完成

		$deletevar = array('app', 'home');//config中需要删除的项目
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
			show_msg("数据处理完成", "$theurl?step=delete");
		} else {
			show_msg('"config/config_global.php" 文件已更新，由于 "config/" 目录不可写入，我们已将更新的文件保存到 "data/" 目录下，请通过 FTP 软件将其转移到 "config/" 目录下覆盖源文件。<br /><br /><a href="'.$theurl.'?step=delete">当您完成上述操作后点击这里继续</a>');
		}
	}

}elseif ($_GET['step'] == 'delete') {

	if(!$devmode) {
		show_msg("数据删除不处理，进入下一步", "$theurl?step=style");
	}

	//检查需要删除的字段
	//老表集合
	$oldtables = array();
	$query = DB::query("SHOW TABLES LIKE '$config[tablepre]%'");
	while ($value = DB::fetch($query)) {
		$values = array_values($value);
		$oldtables[] = $values[0];//分表、缓存
	}

	//新表集合
	$sql = implode('', file($sqlfile));
	preg_match_all("/CREATE\s+TABLE.+?pre\_(.+?)\s+\((.+?)\)\s*(ENGINE|TYPE)\s*\=/is", $sql, $matches);
	$newtables = empty($matches[1])?array():$matches[1];
	$newsqls = empty($matches[0])?array():$matches[0];

	//需要删除的表
	$deltables = array();
	$delcolumns = array();

	//老的有，新的没有
	foreach ($oldtables as $tname) {
		$tname = substr($tname, strlen($config['tablepre']));
		if(in_array($tname, $newtables)) {
			//比较字段是否多余
			$query = DB::query("SHOW CREATE TABLE ".DB::table($tname));
			$cvalue = DB::fetch($query);
			$oldcolumns = getcolumn($cvalue['Create Table']);

			//新的
			$i = array_search($tname, $newtables);
			$newcolumns = getcolumn($newsqls[$i]);

			//老的有，新的没有的字段
			foreach ($oldcolumns as $colname => $colstruct) {
				if($colname == 'UNIQUE' || $colname == 'KEY') {
					foreach ($colstruct as $key_index => $key_value) {
						if(empty($newcolumns[$colname][$key_index])) {
							$delcolumns[$tname][$colname][$key_index] = $key_value;
						}
					}
				} else {
					//普通字段
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

	//显示
	show_header();
	echo '<form method="post" autocomplete="off" action="'.$theurl.'?step=delete">';

	//删除表
	$deltablehtml = '';
	if($deltables) {
		$deltablehtml .= '<table>';
		foreach ($deltables as $tablename) {
			$deltablehtml .= "<tr><td><input type=\"checkbox\" name=\"deltables[$tablename]\" value=\"1\"></td><td>{$config['tablepre']}$tablename</td></tr>";
		}
		$deltablehtml .= '</table>';
		echo "<p>以下 <strong>数据表</strong> 与标准数据库相比是多余的:<br>您可以根据需要自行决定是否删除</p>$deltablehtml";
	}

	//删除字段
	$delcolumnhtml = '';
	if($delcolumns) {
		$delcolumnhtml .= '<table>';
		foreach ($delcolumns as $tablename => $cols) {
			foreach ($cols as $coltype => $col) {
				if (is_array($col)) {
					foreach ($col as $index => $indexvalue) {
						$delcolumnhtml .= "<tr><td><input type=\"checkbox\" name=\"delcols[$tablename][$coltype][$index]\" value=\"1\"></td><td>{$config['tablepre']}$tablename</td><td>索引($coltype) $index $indexvalue</td></tr>";
					}
				} else {
					$delcolumnhtml .= "<tr><td><input type=\"checkbox\" name=\"delcols[$tablename][$col]\" value=\"1\"></td><td>{$config['tablepre']}$tablename</td><td>字段 $col</td></tr>";
				}
			}
		}
		$delcolumnhtml .= '</table>';

		echo "<p>以下 <strong>字段</strong> 与标准数据库相比是多余的:<br>您可以根据需要自行决定是否删除</p>$delcolumnhtml";
	}

	if(empty($deltables) && empty($delcolumns)) {
		echo "<p>与标准数据库相比，没有需要删除的数据表和字段</p><a href=\"$theurl?step=style\">请点击进入下一步</a></p>";
	} else {
		echo "<p><input type=\"submit\" name=\"delsubmit\" value=\"提交删除\"></p><p>您也可以忽略多余的表和字段<br><a href=\"$theurl?step=style\">直接进入下一步</a></p>";
	}
	echo '</form>';

	show_footer();
	exit();

} elseif ($_GET['step'] == 'style') {
	//恢复风格
	if(empty($_GET['confirm'])) {
		show_msg("请确认是否要恢复默认风格？<br /><br /><a href=\"$theurl?step=style&confirm=yes\">[ 是 ]</a>&nbsp;&nbsp;<a href=\"$theurl?step=cache\">[ 否 ]</a>", '');
	}

	define('IN_ADMINCP', true);
	require_once libfile('function/admincp');
	require_once libfile('function/importdata');
	$dir = DB::result_first("SELECT t.directory FROM ".DB::table('common_style')." s LEFT JOIN ".DB::table('common_template')." t ON t.templateid=s.templateid WHERE s.styleid='1'");
	import_styles(1, $dir, 1, 0);
	DB::update('common_setting', array('svalue' => 1), "skey='styleid'");

	show_msg("默认风格已恢复，进入下一步", "$theurl?step=cache");

} elseif ($_GET['step'] == 'cache') {

	//写log
	if(!$devmode && @$fp = fopen($lockfile, 'w')) {
		fwrite($fp, ' ');
		fclose($fp);
	}

	dir_clear(ROOT_PATH.'./data/template');
	dir_clear(ROOT_PATH.'./data/cache');
	dir_clear(ROOT_PATH.'./data/threadcache');
	dir_clear(ROOT_PATH.'./uc_client/data');
	dir_clear(ROOT_PATH.'./uc_client/data/cache');

	//缓存更新
	show_msg('恭喜，数据库结构升级完成！为了数据安全，请删除本文件。<iframe src="../misc.php?mod=initsys" style="display:none;"></iframe>');

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

//正则匹配,获取字段/索引/关键字信息
function getcolumn($creatsql) {

	$creatsql = preg_replace("/ COMMENT '.*?'/i", '', $creatsql);
	preg_match("/\((.+)\)\s*(ENGINE|TYPE)\s*\=/is", $creatsql, $matchs);

	$cols = explode("\n", $matchs[1]);
	$newcols = array();
	foreach ($cols as $value) {
		$value = trim($value);
		if(empty($value)) continue;
		$value = remakesql($value);//特使字符替换
		if(substr($value, -1) == ',') $value = substr($value, 0, -1);//去掉末尾逗号

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

//整理sql文
function remakesql($value) {
	$value = trim(preg_replace("/\s+/", ' ', $value));//空格标准化
	$value = str_replace(array('`',', ', ' ,', '( ' ,' )'), array('', ',', ',','(',')'), $value);//去掉无用符号
	return $value;
}

//显示
function show_msg($message, $url_forward='') {

	if($url_forward) {
		$message = "<a href=\"$url_forward\">$message (跳转中...)</a><script>setTimeout(\"window.location.href ='$url_forward';\", 1);</script>";
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


//页面头部
function show_header() {
	global $config;

	$nowarr = array($_GET['step'] => ' class="current"');

	print<<<END
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=$config[charset]" />
	<title> 数据库升级程序 </title>
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
	<h1>数据库升级工具</h1>
	<div style="width:90%;margin:0 auto;">
	<table id="menu">
	<tr>
	<td{$nowarr[start]}>升级开始</td>
	<td{$nowarr[sql]}>数据库结构添加与更新</td>
	<td{$nowarr[data]}>数据更新</td>
	<td{$nowarr[delete]}>数据库结构删除</td>
	<td{$nowarr[cache]}>升级完成</td>
	</tr>
	</table>
	<br>
END;
}

//页面顶部
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


//导入DIY文件
function import_diy($importfile, $primaltplname, $targettplname) {
	global $_G;

	$css = $html = '';
	$arr = array();

	$content = file_get_contents(realpath($importfile));
	if (empty($content)) return $arr;
	require_once DISCUZ_ROOT.'./source/class/class_xml.php';
	$diycontent = xml2array($content);

	if ($diycontent) {

		//得到原frameID和新frameID
		foreach ($diycontent['layoutdata'] as $key => $value) {
			if (!empty($value)) getframeblock($value);
		}
		$newframe = array();
		foreach ($_G['curtplframe'] as $value) {
			$newframe[] = $value['type'].random(6);
		}

		//导入block数据
		$mapping = array();
		if (!empty($diycontent['blockdata'])) {
			$mapping = block_import($diycontent['blockdata']);
			unset($diycontent['bockdata']);
		}

		//得到原blockID和新blockID
		$oldbids = $newbids = array();
		if (!empty($mapping)) {
			foreach($mapping as $obid=>$nbid) {
				$oldbids[] = 'portal_block_'.$obid;
				$newbids[] = 'portal_block_'.$nbid;
			}
		}

		//替换框架名和模块样式名
		require_once DISCUZ_ROOT.'./source/class/class_xml.php';
		$xml = array2xml($diycontent['layoutdata'],true);
		$xml = str_replace($oldbids, $newbids, $xml);
		$xml = str_replace((array)array_keys($_G['curtplframe']), $newframe, $xml);
		$diycontent['layoutdata'] = xml2array($xml);

		//替换css样式名
		$css = str_replace($oldbids, $newbids, $diycontent['spacecss']);
		$css = str_replace((array)array_keys($_G['curtplframe']), $newframe, $css);

		//DIY 数据生成文件并入库
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

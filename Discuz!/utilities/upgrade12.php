<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);
@set_magic_quotes_runtime(0);
@set_time_limit(0);

define('DISCUZ_ROOT', getcwd().'/');
define('IN_DISCUZ', TRUE);
$PHP_SELF = htmlspecialchars($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
$newfunction_set = array("<h3>���������񡿣���չ -> ��̳����</h3>
		�˹���������������������̳����ر��Ļ������ܡ�����վ���ڽ��иù��ܵ�����ʱ���濼�����������ͺ;���Ľ�����ֵ��һ��������ͬʱʹ�ö��ֽ�����ʽ(��̳�趨������<a href='./admincp.php?frames=yes&action=magics&operation=config' target='_blank'>����</a>���͡�<a href='./admincp.php?frames=yes&action=medals' target='_blank'>ѫ��</a>������)���ܼ��������ǰ����������������ꡣ�Ի��ֵ�����ҲҪ������Σ���Ҫ��������Ľ�����������ͬ�Ļ�����ֵ��վ��Ҳ�����޸������������ø��Ѻã�������������������������������û�������������Ȥ��

		������һ��ʾ����
		����һ������������д��ѧϰ������ ������10����Ǯ ���������������д�ɡ���ʼ�ҵĵ�һ�Ρ�������һ�ֵ��ߡ� ��������������д�ɡ����ڲ�ͬ��������һöѫ�¡�

		<a href='./admincp.php?frames=yes&action=tasks' target='_blank'><b>���ھͽ����̨����</b></a>",
"<h3>�������Ƽ�������� -> �༭��飩</h3>
		�˹���ͨ���Զ����ֶ���ʽ����̳��������ȡһЩ������Ϊϵͳ�Ƽ������⣬��Щ����һ��Ϊ��̳�����ݾ��ʣ��û�����ȸߵĻ��⡣

		�Ƽ����������Ӧ���ú���̫���������ۻ����ң�̫�������ۡ�

		���ݻ���ʱ��ҲҪ���õõ�����ֵ����̫�������ݳ�ʱ�䲻���£�����������½�������̫СƵ�����»����ֻ����ӷ�����������

		<a href='./admincp.php?frames=yes&action=forums' target='_blank'><b>���ھͽ����̨����</b></a>",
"<h3>�������ȶȡ���ȫ�� -> ��̳���ܣ�</h3>
		�˹��ܻ�Ӱ�������������б���ʾʱ�����ͼ�����ʾ��������ȶȸ��ݻظ���������ֵ�Ȳ�������һ���㷨����õ������ȶ�ֵ�ﵽ�趨����ʾ������50��100��200 ʱ ���������б�������ı�������ʾ��Ӧ�����ͼ�꣬����ʾ����������ų̶ȡ�

		վ��Ӧ�ø���վ�㵱ǰ��Ӫ������趨��Щֵ��һ���Ƽ��ķ����Ǳ�֤�����б��У������������ͨ����ı����� 1:7 ���ҡ�

		<a href='./admincp.php?frames=yes&action=settings&operation=functions' target='_blank'><b>���ھͽ����̨����</b></a>
","<h3>���������ۡ���ȫ�� -> ��̳���ܣ�</h3>
		�˹���ͨ���ռ��û�����������ۣ�����������ͼ�����ʾ���𣬵��ﵽ�趨�ļ�����ֵʱ���������б�����ʾ��������Ķ�Ӧ������Ƽ�ͼ�ꡣ

		<a href='./admincp.php?frames=yes&action=settings&operation=functions' target='_blank'><b>���ھͽ����̨����</b></a>
","<h3>����̳��̬����ȫ�� -> ��̳��̬���ã�</h3>
		�˹���ͨ��ָ������������̳��̬��Ϣ���ٽ���Ա֮�以���Ĳ���������Ŀ��ֵӦ�ø��ݵ�ǰ��̳��Ӫ״����ϸ���ö�����

		���磺��̳�շ�������100���ҵģ����á�����ظ����ﵽһ��ֵ���Ͷ�̬��ʱ������������ ��10, 30, 80�� �� ���������ⱻ�ظ���10�Σ�30�Σ�80�ε�ʱ������̳��̬ҳ����һ����̬��Ϣ���շ�������1000���ҵ���̳���Ϳ������á�30��100��200����

		�ܽ�������̳С����Ծ�û��٣� �շ�����������ôӦ�ý�����Ŀ����ֵ���ͣ���������̳��̬�����ײ������෴����̳�󣬻�Ծ�û��࣬�շ������ܴ���ôӦ�ý�����Ŀ����ֵ���ߣ�������̳��̬���ģ�Ӱ���û����顣

		<a href='./admincp.php?frames=yes&action=settings&operation=dzfeed' target='_blank'><b>���ھͽ����̨����</b></a>");


if(!function_exists('file_put_contents')) {
	define('FILE_APPEND', 'FILE_APPEND');
	if(!defined('LOCK_EX')) {
		define('LOCK_EX', 'LOCK_EX');
	}

	function file_put_contents($file, $data, $flags = '') {
		$contents = (is_array($data)) ? implode('', $data) : $data;

		$mode = ($flags == 'FILE_APPEND') ? 'ab+' : 'wb';

		if(($fp = @fopen($file, $mode)) === false) {
			return false;
		} else {
			$bytes = fwrite($fp, $contents);
			fclose($fp);
			return $bytes;
		}
	}
}

$lang = array(
	'error_message' => '������Ϣ',
	'message_return' => '����',
	'old_step' => '��һ��',
	'new_step' => '��һ��',
	'uc_appname' => '��̳',
	'uc_appreg' => 'ע��',
	'uc_appreg_succeed' => '�� UCenter �ɹ���',
	'uc_continue' => '����������',
	'uc_setup' => '<font color="red">���û�а�װ����������ﰲװ UCenter</font>',
	'uc_title_ucenter' => '����д UCenter �������Ϣ',
	'uc_url' => 'UCenter �� URL',
	'uc_ip' => 'UCenter �� IP',
	'uc_admin' => 'UCenter �Ĺ���Ա�ʺ�',
	'uc_adminpw' => 'UCenter �Ĺ���Ա����',
	'uc_title_app' => '�����Ϣ',
	'uc_app_name' => '������',
	'uc_app_url' => '�� URL',
	'uc_app_ip' => '�� IP',
	'uc_app_ip_comment' => '������ DNS ������ʱ��Ҫ���ã�Ĭ���뱣��Ϊ��',
	'uc_connent_invalid1' => '���ӷ�����',
	'uc_connent_invalid2' => ' ʧ�ܣ��뷵�ؼ�顣',
	'error_message' => '��ʾ��Ϣ',
	'error_return' => '����',

	'tagtemplates_subject' => '����',
	'tagtemplates_uid' => '�û� ID',
	'tagtemplates_username' => '������',
	'tagtemplates_dateline' => '����',
	'tagtemplates_url' => '�����ַ',
);

$msglang = array(
	'redirect_msg' => '��������Զ���תҳ�棬�����˹���Ԥ�����ǵ������������ʱ��û���Զ���תʱ����������',
	'uc_url_empty' => '��û����д UCenter �� URL���뷵����д��',
	'uc_url_invalid' => 'UCenter �� URL ��ʽ���Ϸ��������ĸ�ʽΪ�� http://www.domain.com ���뷵�ؼ�顣',
	'uc_ip_invalid' => '<font color="red">�޷����� UCenter ���ڵ� Web ������������д UCenter ��������IP����� UCenter ����̳��ͬһ̨�����������Գ�����д��127.0.0.1��</font>',
	'uc_admin_invalid' => '<font color="red">��¼ UCenter �Ĺ���Ա�ʺ��������</font>',
	'uc_data_invalid' => 'UCenter ��ȡ����ʧ�ܣ��뷵�ؼ�� UCenter URL������Ա�ʺš����롣 ',
);

require DISCUZ_ROOT.'./include/db_mysql.class.php';
@include DISCUZ_ROOT.'./config.inc.php';

$version['old'] = 'Discuz! 7.0 ��ʽ��';
$version['new'] = 'Discuz! 7.1';
$lock_file = DISCUZ_ROOT.'./forumdata/upgrade12.lock';

if(!$dbhost || !$dbname || !$dbuser) {
	instmsg('��̳���ݿ�����������ݿ������û���Ϊ�ա�');
}

$db = new dbstuff();
$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect, true, $dbcharset);
function get_charset($tablename) {
	global $db;
	$tablestruct = $db->fetch_first("show create table $tablename");
	preg_match("/CHARSET=(\w+)/", $tablestruct['Create Table'], $m);
	return $m[1];
}

if($db->version() > '4.1.0') {
	$tablethreadcharset = get_charset($tablepre.'threads');
	$dbcharset = strtolower($dbcharset);
	$tablethreadcharset = strtolower($tablethreadcharset);
	if($dbcharset && $dbcharset !=  $tablethreadcharset) {
		instmsg("���������ļ� (./config.inc.php) �е��ַ��� ($dbcharset) �����ַ��� ($tablethreadcharset) ��ƥ�䡣");
	}
}

$upgrade1 = <<<EOT

#�����ע;
DROP TABLE IF EXISTS cdb_favoritethreads;
CREATE TABLE cdb_favoritethreads (
	`tid` mediumint(8) NOT NULL DEFAULT '0',
	`uid` mediumint(8) NOT NULL DEFAULT '0',
	`dateline` int(10) NOT NULL DEFAULT '0',
	`newreplies` smallint(6) NOT NULL DEFAULT '0',
	PRIMARY KEY (tid, uid)
) TYPE=MYISAM;

#����ע;
DROP TABLE IF EXISTS cdb_favoriteforums;
CREATE TABLE cdb_favoriteforums (
	`fid` smallint(6) NOT NULL DEFAULT '0',
	`uid` mediumint(8) NOT NULL DEFAULT '0',
	`dateline` int(10) NOT NULL DEFAULT '0',
	`newthreads` mediumint(8) NOT NULL DEFAULT '0',
	PRIMARY KEY (fid, uid)
) TYPE=MYISAM;

#��Ϣ֪ͨ��ʾϵͳ;
DROP TABLE IF EXISTS cdb_prompt;
CREATE TABLE cdb_prompt (
  `uid` MEDIUMINT(8) UNSIGNED NOT NULL,
  `typeid` SMALLINT(6) UNSIGNED NOT NULL,
  `number` SMALLINT(6) UNSIGNED NOT NULL,
  PRIMARY KEY (`uid`, `typeid`)
) TYPE=MyISAM;
DROP TABLE IF EXISTS cdb_prompttype;
CREATE TABLE cdb_prompttype (
  `id` SMALLINT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(255) NOT NULL DEFAULT '',
  `name` VARCHAR(255) NOT NULL DEFAULT '',
  `script` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) TYPE=MyISAM;
DROP TABLE IF EXISTS cdb_promptmsgs;
CREATE TABLE cdb_promptmsgs (
  `id` INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `typeid` SMALLINT(6) UNSIGNED NOT NULL DEFAULT '0',
  `uid` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
  `extraid` INTEGER(10) UNSIGNED NOT NULL DEFAULT '0',
  `new` TINYINT(1) NOT NULL DEFAULT '0',
  `dateline` INTEGER(10) UNSIGNED NOT NULL DEFAULT '0',
  `message` TEXT NOT NULL,
  `actor` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`, `typeid`),
  KEY `new` (`new`),
  KEY `dateline` (`dateline`),
  KEY `extraid` (`extraid`)
) TYPE=MyISAM;

#dz�ڲ�feed;
DROP TABLE IF EXISTS cdb_feeds;
CREATE TABLE cdb_feeds (
  feed_id mediumint(8) unsigned NOT NULL auto_increment,
  type varchar(255) NOT NULL DEFAULT 'default',
  fid smallint(6) unsigned NOT NULL DEFAULT '0',
  typeid smallint(6) unsigned NOT NULL DEFAULT '0',
  sortid smallint(6) unsigned NOT NULL DEFAULT '0',
  appid varchar(30) NOT NULL DEFAULT '',
  uid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(15) NOT NULL DEFAULT '',
  data text NOT NULL DEFAULT '',
  template text NOT NULL DEFAULT '',
  dateline int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (feed_id),
  KEY type(type),
  KEY dateline (dateline),
  KEY uid(uid),
  KEY appid(appid)
) TYPE=MyISAM;

#����;
DROP TABLE IF EXISTS cdb_memberrecommend;
CREATE TABLE cdb_memberrecommend (
  `tid` mediumint(8) unsigned NOT NULL,
  `recommenduid` mediumint(8) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  KEY `tid` (`tid`),
  KEY `uid` (`recommenduid`)
) TYPE=MyISAM;

#��չ����;
DROP TABLE IF EXISTS cdb_addons;
CREATE TABLE cdb_addons (
  `key` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `sitename` varchar(255) NOT NULL DEFAULT '',
  `siteurl` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `contact` varchar(255) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `system` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`key`)
) TYPE=MyISAM;

EOT;

$upgradetable = array(

	#�û����ÿ����󸽼���������
	array('usergroups', 'ADD', 'maxattachnum', "smallint( 6 ) NOT NULL DEFAULT '0'"),

	#���������ǿ
	array('words', 'ADD', 'extra', "varchar(255) NOT NULL DEFAULT ''"),

	#��Ʒ�����û��ֹ���
	array('trades', 'ADD', 'credit', "int(10) unsigned NOT NULL DEFAULT '0'"),
	array('trades', 'ADD', 'costcredit', "int(10) unsigned NOT NULL DEFAULT '0'"),
	array('trades', 'ADD', 'credittradesum', "int(10) unsigned NOT NULL DEFAULT '0'"),
	array('trades', 'INDEX', '', "ADD INDEX(`credittradesum`)"),
	array('tradelog', 'ADD', 'credit', "int(10) unsigned NOT NULL DEFAULT '0'"),
	array('tradelog', 'ADD', 'basecredit', "int(10) unsigned NOT NULL DEFAULT '0'"),

	#����������
	array('forumfields', 'ADD', 'threadplugin', "text NOT NULL"),

	#�Ƿ���������  0 ����   1 �����ע���û�������   2 ��Բ���Ϥ�¹��ܻ�Ա����������
	array('tasks', 'ADD', 'newbietask', "TINYINT(1) NOT NULL DEFAULT '0' AFTER relatedtaskid"),

	#��ǰ���ڽ��е���������
	array('members', 'ADD', 'newbietaskid', "smallint(6) unsigned NOT NULL DEFAULT '0'"),

	#������ʾ˳������Ǹ���
	array('tasks', 'CHANGE', 'displayorder', "`displayorder` SMALLINT(6) NOT NULL DEFAULT '0'"),
	array('taskvars', 'CHANGE', 'sort', "`sort` enum('apply','complete','setting') NOT NULL DEFAULT 'complete'"),

	#isimage�����Ǹ��� ͨ�������ϴ���ͼƬ
	array('attachments', 'CHANGE', 'isimage', "`isimage` TINYINT( 1 ) NOT NULL DEFAULT '0'"),

	#����汾
	array('plugins', 'ADD', 'version', "varchar(20) NOT NULL default ''"),

	#�û�������
	array('members', 'ADD', 'threads', "MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' AFTER `posts`"),

	#�����Ƽ����ݶ���
	array('forumrecommend', 'ADD', 'typeid', "SMALLINT(6) NOT NULL AFTER `tid`"),
	array('forumrecommend', 'ADD', 'position', "tinyint(1) NOT NULL DEFAULT '0'"),
	array('forumrecommend', 'ADD', 'highlight', "tinyint(1) NULL NOT NULL DEFAULT '0'"),
	array('forumrecommend', 'ADD', 'aid', "mediumint(8) unsigned NOT NULL DEFAULT '0'"),
	array('forumrecommend', 'ADD', 'filename', "CHAR(100) NOT NULL DEFAULT ''"),
	array('forumrecommend', 'INDEX', '', "ADD INDEX (`position`)"),
	array('threads', 'ADD', 'recommends', "SMALLINT(6) NOT NULL"),
	array('threads', 'ADD', 'recommend_add', "SMALLINT(6) NOT NULL"),
	array('threads', 'ADD', 'recommend_sub', "SMALLINT(6) NOT NULL"),
	array('threads', 'INDEX', '', "ADD INDEX (`recommends`)"),

	#�û��鷢��URL����
	array('usergroups', 'ADD', 'allowposturl', "TINYINT(1) NOT NULL DEFAULT '3'"),

	#�û���������
	array('usergroups', 'ADD', 'allowrecommend', "TINYINT(1) unsigned NOT NULL DEFAULT '1'"),
	array('usergroups', 'DROP', 'allowavatar', ""),

	#ɾ���������
	array('threads', 'DROP', 'subscribed', ""),

	#ɾ��video��ع���
	array('usergroups', 'DROP', 'allowpostvideo', ""),

	#���߹��ܸĽ�
	array('magics', 'ADD', 'recommend', "TINYINT(1) NOT NULL AFTER `weight`"),

	#ɾ�����õ�bbcodes
	array('bbcodes', 'DROP', 'type', ""),

	#�����ȶ�
	array('threads', 'ADD', 'heats', "INT(10) unsigned NOT NULL DEFAULT '0'"),
	array('threads', 'INDEX', '', "ADD INDEX (`heats`)"),

	#ȥ�� mythreads myposts
	array('threads', 'INDEX', '', "ADD INDEX (`authorid`)"),

	# ����cdb_attachments.description
	array('attachments', 'DROP', 'description', ""),
	array('attachmentfields', 'INDEX', '', "ADD INDEX (`tid`)"),
	array('attachmentfields', 'INDEX', '', "ADD INDEX (`pid`)"),
	array('attachmentfields', 'INDEX', '', "ADD INDEX (`uid`)"),

	#���ݵ�����չ
	array('request', 'ADD', 'system', "tinyint(1) NOT NULL DEFAULT '0'"),
);

$upgrade3 = <<<EOT
#����������;
REPLACE INTO cdb_settings (variable, value) VALUES ('allowthreadplugin', '');

#�༭���Ľ�;
DELETE FROM cdb_bbcodes WHERE `id` = 13 ;
UPDATE cdb_bbcodes SET `tag` = 'p', `icon` = 'cmd_paragraph', `explanation` = '����' WHERE `id` =5 ;

#���������б�;
UPDATE cdb_settings SET variable='newbietasks' WHERE variable='newbietask';

#�����������ʱ��;
REPLACE INTO cdb_settings (variable, value) VALUES ('newbietaskupdate', '');

#��Ϣϵͳ����;
REPLACE INTO cdb_prompttype (`id`, `key`, `name`, `script`) VALUES (1,'pm','˽����Ϣ','pm.php?filter=newpm');
REPLACE INTO cdb_prompttype (`id`, `key`, `name`, `script`) VALUES (2,'announcepm','������Ϣ','pm.php?filter=announcepm');
REPLACE INTO cdb_prompttype (`id`, `key`, `name`, `script`) VALUES (3,'task','��̳����','task.php?item=doing');
REPLACE INTO cdb_prompttype (`id`, `key`, `name`, `script`) VALUES (4,'systempm','ϵͳ��Ϣ','');
REPLACE INTO cdb_prompttype (`id`, `key`, `name`, `script`) VALUES (5,'friend','������Ϣ','');
REPLACE INTO cdb_prompttype (`id`, `key`, `name`, `script`) VALUES (6,'threads','������Ϣ','');

#JS�ļ�����;
REPLACE INTO cdb_settings (variable, value) VALUES ('jspath', 'forumdata/cache/');

#֧����ǩԼ�û�����;
REPLACE INTO cdb_settings (variable, value) VALUES ('ec_contract', '');

#�Ƹ�ͨ;
REPLACE INTO cdb_settings (`variable`, `value`) VALUES ('ec_tenpay_bargainor', '');
REPLACE INTO cdb_settings (`variable`, `value`) VALUES ('ec_tenpay_key', '');

#�û��Ƽ�����;
REPLACE INTO cdb_settings (`variable`, `value`) VALUES ('recommendthread', '');

#ɾ��Insenz��ع���;
DELETE FROM cdb_settings WHERE variable='insenz';
DROP TABLE IF EXISTS cdb_advcaches;
DROP TABLE IF EXISTS cdb_virtualforums;
DROP TABLE IF EXISTS cdb_campaigns;

#ɾ��������ع���
DELETE FROM cdb_settings WHERE variable='maxsubscriptions';
DROP TABLE IF EXISTS cdb_subscriptions;

#ɾ��video��ع���;
DELETE FROM cdb_settings WHERE variable='videoinfo';
DROP TABLE IF EXISTS cdb_videos;
DROP TABLE IF EXISTS cdb_videotags;

#ɾ�����õ�bbcodes;
DELETE FROM cdb_bbcodes WHERE tag='flash';

#�������ڿ��ظĽ�;
REPLACE INTO cdb_settings (variable, value) VALUES ('allowfloatwin', 'a:17:{i:0;s:5:"login";i:1;s:8:"register";i:2;s:6:"sendpm";i:3;s:9:"newthread";i:4;s:5:"reply";i:5;s:9:"attachpay";i:6;s:3:"pay";i:7;s:11:"viewratings";i:8;s:11:"viewwarning";i:9;s:13:"viewthreadmod";i:10;s:8:"viewvote";i:11;s:10:"tradeorder";i:12;s:8:"activity";i:13;s:6:"debate";i:14;s:3:"nav";i:15;s:10:"usergroups";i:16;s:4:"task";}');

#��ҳ��ʾ���;
REPLACE INTO cdb_settings (variable, value) VALUES ('indextype', 'classics');

#�����ȶ�;
REPLACE INTO cdb_settings (variable, value) VALUES ('heatthread', 'a:3:{s:5:"reply";i:5;s:9:"recommend";i:3;s:8:"hottopic";s:10:"50,100,200";}');

#ȥ�� mythreads myposts ������һ���汾ȥ��;
DELETE FROM cdb_settings WHERE variable='myrecorddays';

#���ӷ�����̳�ڲ�feed������;
REPLACE INTO cdb_settings (variable, value) VALUES ('dzfeed_limit', 'a:9:{s:14:"thread_replies";a:4:{i:0;s:3:"100";i:1;s:4:"1000";i:2;s:4:"2000";i:3;s:5:"10000";}s:12:"thread_views";a:3:{i:0;s:3:"500";i:1;s:4:"5000";i:2;s:5:"10000";}s:11:"thread_rate";a:3:{i:0;s:2:"50";i:1;s:3:"200";i:2;s:3:"500";}s:9:"post_rate";a:3:{i:0;s:2:"20";i:1;s:3:"100";i:2;s:3:"300";}s:14:"user_usergroup";a:4:{i:0;s:2:"12";i:1;s:2:"13";i:2;s:2:"14";i:3;s:2:"15";}s:11:"user_credit";a:3:{i:0;s:4:"1000";i:1;s:5:"10000";i:2;s:6:"100000";}s:12:"user_threads";a:5:{i:0;s:3:"100";i:1;s:3:"500";i:2;s:4:"1000";i:3;s:4:"5000";i:4;s:5:"10000";}s:10:"user_posts";a:4:{i:0;s:3:"500";i:1;s:4:"1000";i:2;s:4:"5000";i:3;s:5:"10000";}s:11:"user_digest";a:4:{i:0;s:2:"50";i:1;s:3:"100";i:2;s:3:"500";i:3;s:4:"1000";}}');

#��ҳ�ȵ�;
REPLACE INTO cdb_settings (variable, value) VALUES ('indexhot', '');

#�����Ľ�;
DELETE FROM cdb_settings WHERE variable='allowfloatwin';
REPLACE INTO cdb_settings (variable, value) VALUES ('disallowfloat', '');

#��չ����Ĭ�ϵ���Դ�ṩ��;
TRUNCATE TABLE cdb_addons;
INSERT INTO cdb_addons (`key`, `title`, `sitename`, `siteurl`, `description`, `contact`, `logo`, `system`) VALUES ('25z5wh0o00', 'Comsenz', 'Comsenz�ٷ���վ', 'http://www.comsenz.com', 'Comsenz�ٷ���վ�Ƽ�����̳ģ������', 'ts@comsenz.com', 'http://www.comsenz.com/addon/logo.gif', 1);
INSERT INTO cdb_addons (`key`, `title`, `sitename`, `siteurl`, `description`, `contact`, `logo`, `system`) VALUES ('R051uc9D1i', 'DPS', 'DPS �������', 'http://bbs.7dps.com', '�ṩ Discuz!7.1 �º�(NC)���������һ����װ/����/ж�ش����Ŀ�У����ṩ�������', 'http://bbs.7dps.com/thread-1646-1-1.html', 'http://api.7dps.com/addons/logo.gif', 0);

#ɾ���ظ�֪ͨ�ļƻ�����;
DELETE FROM cdb_crons WHERE filename='notify_daily.inc.php';

#��������������;
REPLACE INTO cdb_settings (variable, value) VALUES ('domainwhitelist', '');

EOT;

$newfunc = getgpc('newfunc');
$newfunc = empty($newfunc) ? 0 : $newfunc;
$step = getgpc('step');
$step = empty($step) ? 1 : $step;
instheader();
if(!isset($cookiepre)) {
	instmsg('config_nonexistence');
} elseif(!ini_get('short_open_tag')) {
	instmsg('short_open_tag_invalid');
}

if(file_exists($lock_file)) {
	instmsg('������������Ӧ�����Ѿ��������ˣ�����Ѿ��ָ��������ֶ�ɾ��<br />'.str_replace(DISCUZ_ROOT, '', $lock_file).'<br />֮������ˢ��ҳ�档');
}

if($step == 1) {

	$msg = '<div class="btnbox marginbot">
			<form method="get">
			<input type="hidden" name="step" value="check" />
				<input type="submit" style="padding: 2px;" value="��ʼ����" name="submit" />
			</form>
		</div>';

echo <<<EOT
		<div class="licenseblock">
		<div class="license">
	<h1>����������ֻ�ܴ� $version[old] ������ $version[new]</h1>
	����֮ǰ<b>��ر������ݿ�����</b>����������ʧ���޷��ָ�<br /><br />
		��ȷ����������Ϊ:
	<ol>
		<li>�ر�ԭ����̳���ϴ� $version[new] ��ȫ���ļ���Ŀ¼����installĿ¼��config.inc.php�ļ��������Ƿ������ϵ� $version[old]
		<li>�ϴ�����������̳Ŀ¼�С�
		<li>���б�����ֱ������������ɵ���ʾ
		<li>�����;ʧ�ܣ���ʹ��Discuz!�����䣨./utilities/tools.php����������ݻָ����߻ָ����ݣ�ȥ��������������б�����
	</ol>
</div></div>
	$msg

EOT;

	instfooter();

} elseif($step == 'check') {

	@touch(DISCUZ_ROOT.'./forumdata/install.lock');
	@unlink(DISCUZ_ROOT.'./install/index.php');

//	echo "<h4>Discuz!����汾���</h4>";

	if(!defined('UC_CONNECT')) {
		instmsg('����config.inc.php�ļ������ǣ���ָ����ݺõ�config.inc.php�ļ���֮���ٳ���������');
	}

	include_once DISCUZ_ROOT.'./discuz_version.php';
	if(!defined('DISCUZ_VERSION') || DISCUZ_VERSION != '7.1') {
		instmsg('����û���ϴ�(�����ϴ�����ȫ)���µ�Discuz!7.1�ĳ����ļ��������ϴ�֮���ٳ���������');
	}

	instmsg("Discuz!����汾���ͨ�����Զ�ִ����һ����", '?step=2');

} elseif($step == 2) {

//	echo "<h4>�������ݱ�</h4>";

	dir_clear('./forumdata/cache');
	dir_clear('./forumdata/templates');

	runquery($upgrade1);

	instmsg("�������ݱ�����ϡ�", '?step=3');
	instfooter();

} elseif($step == 3) {

	//echo "<h4>ת�Ƹ�������</h4>";

	upg_attach_description();

	$recommendthread = array (
		'status' => '1',
		'addtext' => '֧��',
		'subtracttext' => '����',
		'defaultshow' => '0',
		'daycount' => '0',
		'ownthread' => '1',
		'iconlevels' => '10,50,100',
	);
	$db->query("REPLACE INTO {$tablepre}settings (`variable`, `value`) VALUES ('recommendthread', '".addslashes(serialize($recommendthread))."')");

	$db->query("DELETE FROM {$tablepre}bbcodes WHERE type='1'", "SILENT");

	instmsg("ת�Ƹ��������ɹ���", '?step=4');

	instfooter();

} elseif($step == 4) {

	$start = isset($_GET['start']) ? intval($_GET['start']) : 0;

	//echo "<h4></h4>";

	if(isset($upgradetable[$start]) && $upgradetable[$start][0]) {

		//echo "�������ݱ� [ $start ] {$tablepre}{$upgradetable[$start][0]} {$upgradetable[$start][3]}:";
		$successed = upgradetable($upgradetable[$start]);

		if($successed === TRUE) {
			$start ++;
			if(isset($upgradetable[$start]) && $upgradetable[$start][0]) {
				instmsg("�������ݱ� [ $start ] {$tablepre}{$upgradetable[$start][0]} {$upgradetable[$start][3]}:<span class='w'>OK</span>", "?step=4&start=$start");
			}
		} elseif($successed === FALSE) {
			instmsg("�������ݱ�ṹʧ�ܣ�{$tablepre}{$upgradetable[$start][0]} {$upgradetable[$start][3]}");
		} elseif($successed == 'TABLE NOT EXISTS') {
			instmsg("<span class=red>���ݱ�{$tablepre}{$upgradetable[$start][0]}�����ڣ������޷���������ȷ��������̳�汾�Ƿ���ȷ!</span>");
		}
	}

	instmsg("��̳���ݱ�ṹ������ϡ�", "?step=5");
	instfooter();

} elseif($step == 5) {

//	echo "<h4>���²�������</h4>";
	runquery($upgrade3);
	upg_newbietask();

	@include_once DISCUZ_ROOT.'./forumdata/cache/cache_settings.php';
	$timestamp = time();
	$data = array('title' => array(
		'bbname' => $_DCACHE['settings']['bbname'],
		'time' => gmdate($_DCACHE['settings']['dateformat'], $timestamp + $_DCACHE['settings']['timeoffset'] * 3600),
		'version' => $version['new'],
		)
	);
	$template = array('title' => '{bbname} �� {time} ������ {version}');
	$db->query("INSERT INTO {$tablepre}feeds (type, fid, typeid, sortid, appid, uid, username, data, template, dateline)
		VALUES ('feed_announce', '0', '0', '0', '0', '0', '', '".addslashes(serialize($data))."', '".addslashes(serialize($template))."', '$timestamp')");

	instmsg("�������ݸ�����ϡ�", "?step=6");
	instfooter();
} elseif($step == 6) {
	if(getgpc('addfounder_contact','P')) {
		$email = strip_tags(getgpc('email', 'P'));
		$msn = strip_tags(getgpc('msn', 'P'));
		$qq = strip_tags(getgpc('qq', 'P'));
		if(!preg_match("/^[\d]+$/", $qq)) $qq = '';
		if(strlen($email) < 6 || !preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email)) $email = '';
		if(strlen($msn) < 6 || !preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $msn)) $msn = '';

		$contact = serialize(array('qq' => $qq, 'msn' => $msn, 'email' => $email));
		$db->query("REPLACE {$tablepre}settings (variable, value) VALUES ('founder_contact', '$contact')");
		instmsg("�����¹�����ʾ��","?step=7");
	} else {
		$contact = array();
		$contact = unserialize($db->result_first("SELECT value FROM {$tablepre}settings WHERE variable='founder_contact'"));
		$founder_contact = str_replace(array("\n","\t"), array('<br>','&nbsp;&nbsp;&nbsp;&nbsp;'), $founder_contact);
echo <<<EOD
 		<div class="licenseblock">
		<div class="license">
	<h1>���ڡ���ʢ���Ƽƻ�����˵��</h1>
	<ol>
		<li>Ϊ�˲��ϸĽ���Ʒ�����������û����飬Discuz!7.1��������ͳ��ϵͳ��</li>
		<li>��ͳ��ϵͳ���������Ƿ����û�����̳�Ĳ���ϰ�ߣ���������������δ���İ汾�жԲ�Ʒ���иĽ�����Ƴ��������û�������¹��ܡ�</li>
		<li>��ͳ��ϵͳ�����ռ�վ��������Ϣ�����ռ��û����ϣ������ڰ�ȫ���գ����Ҿ���ʵ�ʲ��Բ���Ӱ����̳������Ч�ʡ�</li>
		<li>����װʹ�ñ��汾��ʾ��ͬ����롶��ʢ���Ƽƻ�����Discuz!��Ӫ���Ż�ͨ����վ�����ݵķ���Ϊ���ṩ��Ӫָ�����飬���ǽ���ʾ����θ���վ���������������̳���ܣ���ν��к���Ĺ������ã��Լ��ṩ������һЩ��Ӫ����ȡ�</li>
		<li>Ϊ�˷������Ǻ�����ͨ��Ӫ���ԣ��������³��õ�������ϵ��ʽ��</li>
	</ol>
</div></div>
<div class="desc">
	<h4>��д��ϵ��ʽ</h4>
	<p>��ȷ����ϵ��ʽ���������Ǹ����ṩ���µ���Ϣ�Ͱ�ȫ����ı���</p>
</div>

<form action="$url_forward" method="post" id="postform">
	<table class="tb2">
		<tr>
			<th class="tbopt">QQ��</th>
			<td><input type="text" value="$contact[qq]" name="qq" size="35" class="txt" /></td>
			<td>����ȷ��дQQ����</td>
		</tr>
		<tr>

			<th class="tbopt">MSN��</th>
			<td><input type="text" value="$contact[msn]" name="msn" size="35" class="txt" /></td>
			<td>MSN�˺�</td>
		</tr>
		<tr>
			<th class="tbopt">E-mail��</th>
			<td><input type="text" value="$contact[email]" name="email" size="35" class="txt" /></td>

			<td>�����ַ</td>
		</tr>
		<tr>
			<th class="tbopt"></th>
			<td><input type="submit" class="btn" name="addfounder_contact" value="��һ��" /> &nbsp; &nbsp;<a href='?step=7'>����</a>
			<td></td>
		</tr>
	</table>
</form>

EOD;

	}

} elseif($step == 7) {
	if($newfunction_set[$newfunc]) {
		$newfunction_set[$newfunc] = str_replace(array("\n","\t"), array('<br>','&nbsp;&nbsp;&nbsp;&nbsp;'), $newfunction_set[$newfunc]);
$msg = $newfunction_set[$newfunc];
$nextfunc = $newfunc + 1;
$newfunction_set_count = count($newfunction_set);
echo <<<EOD
	<div class="licenseblock">
		<div class="license">
			<h1>��Ҫ�¹�������</h1>
			$msg
		</div>
	</div>
	<div class="btnbox marginbot">
		<form method="get">
			<input type="hidden" name="step" value="7" />
			<input type="hidden" name="newfunc" value="$nextfunc" />
			����{$newfunction_set_count}������Ҫ�¹��ܣ��ڡ�{$nextfunc}������
			<input type="submit" style="padding: 2px;" value="������һ������" name="submit" />&nbsp;<a href='?step=8'>����</a>
		</form>
	</div>
EOD;
	} else {
		instmsg("�¹��ܲ鿴��ϡ�", "?step=8");
	}

	instfooter();
} else {

	$settings = array();
	$query = $db->query("SELECT value, variable FROM {$tablepre}settings WHERE variable IN('statid', 'statkey', 'bbname')");
	while($row = $db->fetch_array($query)) {
		$settings[$row['variable']] = $row['value'];
	}
	getstatinfo($settings['statid'], $settings['statkey']);

	dir_clear('./forumdata/cache');
	dir_clear('./forumdata/templates');
	dir_clear('./uc_client/data/cache');
	@touch($lock_file);
	if(!@unlink('upgrade12.php')) {
		$msg = '<li><b>��ɾ��������</b></li>';
	} else {
		$msg = '';
	}
echo <<<EOT
		<div class="licenseblock">
		<div class="license">
	<h1>��ϲ����̳���������ɹ�</h1>
	<h3>������������</h3>
	<ol>
		$msg
		<li>ʹ�ù���Ա��ݵ�¼��̳�������̨�����»���</li>
		<li>������̳ע�ᡢ��¼�������ȳ�����ԣ����������Ƿ�����</li>
	</ol>
</div></div>

EOT;
echo '<div class="btnbox marginbot">
			<form method="get" action="index.php">
				<b>��л��ѡ�����ǵĲ�Ʒ��</b><input type="submit" style="padding: 2px;" value="�����ڿ��Է�����̳���鿴�������" name="submit" />
			</form>
		</div><iframe width="0" height="0" src="index.php" style="display:none;"></iframe>';
		
	instfooter();

}

function send_sql_to_uc($sql) {
	$url = UC_API.'/accept_sql.php?appid='.UC_APPID.'&uckey='.UC_KEY.'&sql='.urlencode($sql);
	return file_get_contents($url);
}

function insertconfig($s, $find, $replace) {
	if(preg_match($find, $s)) {
		$s = preg_replace($find, $replace, $s);
	} else {
		// ���뵽���һ��
		$s .= "\r\n".$replace;
	}
	return $s;
}

function instheader() {
	global $charset, $version;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<title>Discuz! ������</title>
<style type="text/css">
/*
(C) 2001-2009 Comsenz Inc.
*/

/* common */
*{ word-wrap:break-word; }
body{ padding:5px 0; background:#FFF; text-align:center; }
body, td, input, textarea, select, button{ color:#666; font:12px Verdana, Tahoma, Arial, sans-serif; }
ul, dl, dd, p, h1, h2, h3, h4, h5, h6, form, fieldset { margin:0; padding:0; }
h1, h2, h3, h4, h5, h6{ font-size:12px; }
a{ color:#2366A8; text-decoration:none; }
	a:hover { text-decoration:underline; }
	a img{ border:none; }
em, cite, strong, th{ font-style:normal; font-weight:normal; }
table{ border-collapse:collapse; }

/* box */
.container{ overflow:hidden; margin:0 auto; width:700px; height:auto !important;text-align:left; border:1px solid #B5CFD9; }
.header{ height:71px; background:url(images/upgrade/bg_repx.gif) repeat-x; }
	.header h1{ text-indent:-9999px; width:270px; height:48px; background:url(images/upgrade/bg_repno.gif) no-repeat 26px 22px; }
	.header span { float: right; padding-right: 10px; }
.main{ padding:20px 20px 0; background:#F7FBFE url(images/upgrade/bg_repx.gif) repeat-x 0 -194px; }
	.main h3{ margin:10px auto; width:75%; color:#6CA1B4; font-weight:700; }
.desc{ margin:0 auto; width:537px; line-height:180%; clear:both; }
	.desc ul{ margin-left:20px; }
.desc1{ margin:10px 0; width:100%; }
	.desc1 ul{ margin-left:25px; }
	.desc1 li{ margin:3px 0; }
.tb2{ margin:15px 0 15px 67px; }
	.tb2 th, .tb2 td{ padding:3px 5px; }
	.tbopt{ width:120px; text-align: left; }
.btnbox{ text-align:center; }
	.btnbox input{ margin:0 2px; }
	.btnbox textarea{ margin-bottom:10px; height:150px; }
.btn{ margin-top:10px; }
.footer{ line-height:40px; text-align:center; background:url(images/upgrade/bg_footer.gif) repeat-x; font-size:11px; }

/* form */
.txt{ width:200px; }

/* file status */
.w{ margin-left: 8px; padding-left: 16px; background:url(images/upgrade/bg_repno.gif) no-repeat 0 -149px;  }
.nw{ margin-left: 8px; padding-left: 16px; background:url(images/upgrade/bg_repno.gif) no-repeat 0 -198px; }

/* space */
.marginbot{ margin-bottom:20px; }
.margintop{ margin-top:20px; }
.red{ color:red; }

.licenseblock{ margin-bottom:15px; padding:8px; border:1px solid #EEE; background:#FFF; overflow:scroll; overflow-x:hidden; }
.license{}
	.license h1{ padding-bottom:10px; font-size:14px; text-align:center; }
	.license h3{ margin:0; color:#666; }
	.license p{ line-height:150%; margin:10px 0; text-indent:25px; }
	.license li{ line-height:150%; margin:5px 0; }
.title{ margin:5px 0 -15px 58px; }
.showmessage { margin: 0 0 20px; line-height: 160%; }
	.showmessage h2 { margin-bottom: 10px;font-size: 14px; }
	.showmessage .btnbox { margin-top: 20px;}
</style>
<meta name="copyright" content="Comsenz Inc." />
<meta http-equiv="x-ua-compatible" content="ie=7" />
<script type="text/javascript">
function redirect(url) {
	window.location=url;
}
function $(id) {
	return document.getElementById(id);
}
</script>
</head>
<div class="container">
	<div class="header">
		<h1>Discuz! ������</h1>
		<span>�� Discuz! 7.0 ������ 7.1</span>
	</div>
	<div class="main">

<?php
}

function instfooter() {
	global $version;
?>
		<div class="footer">&copy;2001 - 2009 <a href="http://www.comsenz.com/">Comsenz</a> Inc.</div>
	</div>
</div>
</body>
</html>
<?php
}

function instmsg($message, $url_forward = '', $postdata = '') {
	global $lang, $msglang;
	$message = $msglang[$message] ? $msglang[$message] : $message;
	if($url_forward) {
		$message .= "<p><a href=\"$url_forward\">$msglang[redirect_msg]</a></p>";
		$message .= "<script>setTimeout(\"redirect('$url_forward');\", 1250);</script>";
	} elseif(strpos($message, $lang['return'])) {
		$message .= "<p><a href=\"javascript:history.go(-1);\" class=\"mediumtxt\">$lang[message_return]</a></p>";
	}

echo <<<EOD
	<div class="showmessage">
	<h2>{$lang[error_message]}</h2>
	<p>$message</p>
	<!--<div class="btnbox"><input type="button" class="btn" value="��ť����" /></div>-->
	</div>
EOD;
		
	instfooter();
	exit;
}

function getgpc($k, $var='G') {
	switch($var) {
		case 'G': $var = &$_GET; break;
		case 'P': $var = &$_POST; break;
		case 'C': $var = &$_COOKIE; break;
		case 'R': $var = &$_REQUEST; break;
	}
	return isset($var[$k]) ? $var[$k] : NULL;
}

function dir_clear($dir) {
	if($directory = dir($dir)) {
		while($entry = $directory->read()) {
			$filename = $dir.'/'.$entry;
			if(is_file($filename)) {
				@unlink($filename);
			}
		}
		@touch($dir.'/index.htm');
		$directory->close();
	}
}

function createtable($sql, $dbcharset) {
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
		(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
}

function runquery($query) {
	global $db, $tablepre, $dbcharset;

	$query = str_replace("\r", "\n", str_replace(' cdb_', ' '.$tablepre, $query));
	$expquery = explode(";\n", $query);
	foreach($expquery as $sql) {
		$sql = trim($sql);
		if($sql == '' || $sql[0] == '#') continue;

		if(strtoupper(substr($sql, 0, 12)) == 'CREATE TABLE') {
			$db->query(createtable($sql, $dbcharset));
		} else {
			$db->query($sql);
		}
	}
}

function upgradetable($updatesql) {
	global $db, $tablepre, $dbcharset;

	$successed = TRUE;

	if(is_array($updatesql) && !empty($updatesql[0])) {

		list($table, $action, $field, $sql) = $updatesql;

		if(empty($field) && !empty($sql)) {

			$query = "ALTER TABLE {$tablepre}{$table} ";
			if($action == 'INDEX') {
				$successed = $db->query("$query $sql", "SILENT");
			} elseif ($action == 'UPDATE') {
				$successed = $db->query("UPDATE {$tablepre}{$table} SET $sql", 'SILENT');
			}

		} elseif($tableinfo = loadtable($table)) {

			$fieldexist = isset($tableinfo[$field]) ? 1 : 0;

			$query = "ALTER TABLE {$tablepre}{$table} ";

			if($action == 'MODIFY') {

				$query .= $fieldexist ? "MODIFY $field $sql" : "ADD $field $sql";
				$successed = $db->query($query, 'SILENT');

			} elseif($action == 'CHANGE') {

				$field2 = trim(substr($sql, 0, strpos($sql, ' ')));
				$field2exist = isset($tableinfo[$field2]);

				if($fieldexist && ($field == $field2 || !$field2exist)) {
					$query .= "CHANGE $field $sql";
				} elseif($fieldexist && $field2exist) {
					$db->query("ALTER TABLE {$tablepre}{$table} DROP $field2", 'SILENT');
					$query .= "CHANGE $field $sql";
				} elseif(!$fieldexist && $fieldexist2) {
					$db->query("ALTER TABLE {$tablepre}{$table} DROP $field2", 'SILENT');
					$query .= "ADD $sql";
				} elseif(!$fieldexist && !$field2exist) {
					$query .= "ADD $sql";
				}
				$successed = $db->query($query);

			} elseif($action == 'ADD') {

				$query .= $fieldexist ? "CHANGE $field $field $sql" :  "ADD $field $sql";
				$successed = $db->query($query);

			} elseif($action == 'DROP') {
				if($fieldexist) {
					$successed = $db->query("$query DROP $field", "SILENT");
				}
				$successed = TRUE;
			}

		} else {

			$successed = 'TABLE NOT EXISTS';

		}
	}
	return $successed;
}

function loadtable($table, $force = 0) {
	global $db, $tablepre, $dbcharset;
	static $tables = array();

	if(!isset($tables[$table]) || $force) {
		if($db->version() > '4.1') {
			$query = $db->query("SHOW FULL COLUMNS FROM {$tablepre}$table", 'SILENT');
		} else {
			$query = $db->query("SHOW COLUMNS FROM {$tablepre}$table", 'SILENT');
		}
		while($field = @$db->fetch_array($query)) {
			$tables[$table][$field['Field']] = $field;
		}
	}
	return $tables[$table];
}

function daddslashes($string, $force = 0) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}

function get_uc_root() {
	$uc_root = '';
	$uc = parse_url(UC_API);
	if($uc['host'] == $_SERVER['HTTP_HOST']) {
		$php_self_len = strlen($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
		$uc_root = substr(__FILE__, 0, -$php_self_len).$uc['path'];
	}
	return $uc_root;
}

function random($length, $numeric = 0) {
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	if($numeric) {
		$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	} else {
		$hash = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++) {
			$hash .= $chars[mt_rand(0, $max)];
		}
	}
	return $hash;
}

function upg_attach_description() {
	global $db, $tablepre;
	if($db->fetch_first("SHOW TABLE STATUS LIKE '{$tablepre}attachmentfields'")) {
		return;
	}
$create_table_sql = "
CREATE TABLE cdb_attachmentfields (
  aid mediumint(8) UNSIGNED NOT NULL ,
  tid mediumint(8) UNSIGNED NOT NULL DEFAULT '0' ,
  pid int(10) UNSIGNED NOT NULL DEFAULT '0' ,
  uid mediumint(8) UNSIGNED NOT NULL DEFAULT '0' ,
  description varchar(255) NOT NULL ,
  PRIMARY KEY (`aid`),
  KEY tid (tid),
  KEY pid (pid,aid),
  KEY uid (uid)
) TYPE=MyISAM;
";
	runquery($create_table_sql);
	$db->query("INSERT INTO {$tablepre}attachmentfields (`aid`, `tid`, `pid`, `uid`, `description`) SELECT `aid`, `tid`, `pid`, `uid`, `description` FROM {$tablepre}attachments WHERE `description`<>''");
}

function getstatinfo($siteid = 0, $key = '') {
	global $db, $tablepre, $dbcharset, $settings;
	if($siteid && $key) {
		return;
	} else {
		$siteid = $key = '';
	}
	$version = '7.1';
	$onlineip = '';
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$onlineip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$onlineip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	$members = $db->result_first("SELECT COUNT(*) FROM {$tablepre}members");
	$funcurl = 'http://stat.discuz.com/stat_ins.php';
	$bbname = $settings['bbname'];
	$PHP_SELF = htmlspecialchars($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
	$url = htmlspecialchars('http://'.$_SERVER['HTTP_HOST'].preg_replace("/\/+(api|archiver|wap)?\/*$/i", '', substr($PHP_SELF, 0, strrpos($PHP_SELF, '/'))));
	$posts = $db->result($db->query("SELECT count(*) FROM {$tablepre}posts"), 0);
	$domain = $_SERVER['HTTP_HOST'];
	$hash = $bbname.$url.$mark.$version.$posts;
	$threads = $db->result($db->query("SELECT count(*) FROM {$tablepre}threads"), 0);
	$hash = md5($hash.$members.$threads.$email.$siteid.md5($key).'install');
	$q = "bbname=$bbname&url=$url&domain=$domain&mark=$mark&version=$version&posts=$posts&members=$members&threads=$threads&email=$email&siteid=$siteid&key=".md5($key)."&ip=$onlineip&time=".time()."&hash=$hash";
	$q=rawurlencode(base64_encode($q));
	$siteinfo = dfopen($funcurl."?action=install&q=$q");
	if(empty($siteinfo)) {
		$siteinfo = dfopen($funcurl."?action=install&q=$q");
	}
	if($siteinfo && preg_match("/^[a-zA-Z0-9_]+,[A-Z]+$/i", $siteinfo)) {
		$siteinfo = explode(',', $siteinfo);
		$db->query("REPLACE INTO {$tablepre}settings (variable, value) VALUES ('statid', '$siteinfo[0]')");
		$db->query("REPLACE INTO {$tablepre}settings (variable, value) VALUES ('statkey', '$siteinfo[1]')");
	}
}

function upg_newbietask() {
	global $db, $tablepre;

	$newbietask = array(
		1 => array(
			'name' => '������һ������',
			'task' => "1, 0, '������һ������', 'ѧϰ����������������һ�����£�BS������������', '', 0, 0, 0, 'all', 'newbie_post_reply', 0, 0, 0, 'credit', '2', 10, -1, ''",
			'vars' => array(
				"'complete', '�ظ�ָ������', '".addslashes('���û�Աֻ�лظ���������������������д����� tid(����һ������ĵ�ַ�� http://localhost/viewthread.php?tid=8 ��ô������� tid ���� 8)������Ϊ������')."', 'threadid', 'text', '0', ''",
				"'setting', '', '', 'entrance', 'text', 'viewthread', ''"
			)
		),
		2 => array(
			'name' => '�ҵĵ�һ��',
			'task' => "1, 0, '�ҵĵ�һ��', 'ѧ�ᷢ����������Ϊ�����Ľ���', '', 0, 0, 0, 'all', 'newbie_post_newthread', 0, 0, 0, 'credit', '2', 10, -1, ''",
			'vars' => array(
				"'complete', '��ָ����鷢��������', '".addslashes('���û�Ա������ĳ����鷢������һƪ����������������')."', 'forumid', 'text', '', ''",
	
				"'setting', '', '', 'entrance', 'text', 'forumdisplay', ''"
			)
		),
		3 => array(
			'name' => '���ڲ�ͬ',
			'task' => "1, 0, '���ڲ�ͬ', '�޸ĸ������ϣ�����ͱ������ڲ�ͬ', '', 0, 0, 0, 'all', 'newbie_modifyprofile', 0, 0, 0, 'credit', '2', 10, -1, ''",
			'vars' => array(
				"'complete', '���Ƹ�������', '".addslashes('���������ֻҪ���Լ��ĸ���������д���������������')."', '', '', '', ''",
				"'setting', '', '', 'entrance', 'text', 'memcp', ''"
			)
		),
		4 => array(
			'name' => '��������',
			'task' => "1, 0, '��������', '�ϴ�ͷ���ô����ʶһ��ȫ�µ���', '', 0, 0, 0, 'all', 'newbie_uploadavatar', 0, 0, 0, 'credit', '2', 10, -1, ''",
			'vars' => array(
				"'complete', '�ϴ�ͷ��', '".addslashes('���������ֻҪ�ɹ��ϴ�ͷ�񼴿��������')."', '', '', '', ''",
				"'setting', '', '', 'entrance', 'text', 'memcp', ''"
			)
		),
		5 => array(
			'name' => '�������',
			'task' => "1, 0, '�������', '�������û�����������Ϣ���������һ�¸���', '', 0, 0, 0, 'all', 'newbie_sendpm', 0, 0, 0, 'credit', '2', 10, -1, ''",
			'vars' => array(
				"'complete', '��ָ����Ա���Ͷ���Ϣ', '".addslashes('ֻ�и��û�Ա�ɹ����Ͷ���Ϣ���������������д�û�Ա���û���')."', 'authorid', 'text', '', ''",
				"'setting', '', '', 'entrance', 'text', 'space', ''"
			)
		),
		6 => array(
			'name' => 'һ���ú�������',
			'task' => "1, 0, 'һ���ú�������', '������ģ�û����������ô�У��Ӹ����Ѱ�', '', 0, 0, 0, 'all', 'newbie_addbuddy', 0, 0, 0, 'credit', '2', 10, -1, ''",
			'vars' => array(
				"'complete', '��ָ����Ա��Ϊ����', '".addslashes('ֻ�н��û�Ա��Ϊ���Ѳ��������������д�û�Ա���û���')."', 'authorid', 'text', '', ''",
				"'setting', '', '', 'entrance', 'text', 'space', ''"
			)
		),
		7 => array(
			'name' => '��Ϣʱ��',
			'task' => "1, 0, '��Ϣʱ��', '��Ϣʱ����ȱ��ʲô������', '', 0, 0, 0, 'all', 'newbie_search', 0, 0, 0, 'credit', '2', 10, -1, ''",
			'vars' => array(
				"'complete', 'ѧ������', '".addslashes('���������ֻҪ�ɹ�ʹ����̳�������ܼ����������')."', '', '', '', ''",
				"'setting', '', '', 'entrance', 'text', 'search', ''"
			)
		)
	);
	if($db->result($db->query("SELECT count(*) FROM `{$tablepre}tasks` WHERE newbietask=1"), 0)) {
		return;
	}
	foreach($newbietask as $k => $sqlarray) {
		$db->query("INSERT INTO `{$tablepre}tasks` (`newbietask`, `available`, `name`, `description`, `icon`, `applicants`, `achievers`, `tasklimits`, `applyperm`, `scriptname`, `starttime`, `endtime`, `period`, `reward`, `prize`, `bonus`, `displayorder`, `version`) VALUES ($sqlarray[task]);");
		$currentid = $db->insert_id();
		foreach($sqlarray['vars'] as $taskvars) {
			$db->query("INSERT INTO `{$tablepre}taskvars` (`taskid`, `sort`, `name`, `description`, `variable`, `type`, `value`, `extra`) VALUES ($currentid, $taskvars);");
		}
	}
}

function dfopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
	$return = '';
	$matches = parse_url($url);
	$host = $matches['host'];
	$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;

	if($post) {
		$out = "POST $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: '.strlen($post)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	} else {
		$out = "GET $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}
	$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
	if(!$fp) {
		return '';
	} else {
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
		if(!$status['timed_out']) {
			while (!feof($fp)) {
				if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
					break;
				}
			}

			$stop = false;
			while(!feof($fp) && !$stop) {
				$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
				$return .= $data;
				if($limit) {
					$limit -= strlen($data);
					$stop = $limit <= 0;
				}
			}
		}
		@fclose($fp);
		return $return;
	}
}
?>
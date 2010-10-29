<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id$
 */

define('UC_VERNAME', '���İ�');
$lang = array(
	'SC_GBK' => '�������İ�',
	'TC_BIG5' => '�������İ�',
	'SC_UTF8' => '�������� UTF8 ��',
	'TC_UTF8' => '�������� UTF8 ��',
	'EN_ISO' => 'ENGLISH ISO8859',
	'EN_UTF8' => 'ENGLIST UTF-8',

	'title_install' => SOFT_NAME.' ��װ��',
	'agreement_yes' => '��ͬ��',
	'agreement_no' => '�Ҳ�ͬ��',
	'notset' => '������',

	'message_title' => '��ʾ��Ϣ',
	'error_message' => '������Ϣ',
	'message_return' => '����',
	'return' => '����',
	'install_wizard' => '��װ��',
	'config_nonexistence' => '�����ļ�������',
	'nodir' => 'Ŀ¼������',
	'short_open_tag_invalid' => '�Բ����뽫 php.ini �е� short_open_tag ����Ϊ On�������޷�������װ��',
	'redirect' => '��������Զ���תҳ�棬�����˹���Ԥ��<br>���ǵ����������û���Զ���תʱ����������',
	'auto_redirect' => '��������Զ���תҳ�棬�����˹���Ԥ',
	'database_errno_2003' => '�޷��������ݿ⣬�������ݿ��Ƿ����������ݿ��������ַ�Ƿ���ȷ',
	'database_errno_1044' => '�޷������µ����ݿ⣬�������ݿ�������д�Ƿ���ȷ',
	'database_errno_1045' => '�޷��������ݿ⣬�������ݿ��û������������Ƿ���ȷ',
	'database_errno_1064' => 'SQL �﷨����',

	'dbpriv_createtable' => 'û��CREATE TABLEȨ�ޣ��޷�������װ',
	'dbpriv_insert' => 'û��INSERTȨ�ޣ��޷�������װ',
	'dbpriv_select' => 'û��SELECTȨ�ޣ��޷�������װ',
	'dbpriv_update' => 'û��UPDATEȨ�ޣ��޷�������װ',
	'dbpriv_delete' => 'û��DELETEȨ�ޣ��޷�������װ',
	'dbpriv_droptable' => 'û��DROP TABLEȨ�ޣ��޷���װ',

	'db_not_null' => '���ݿ����Ѿ���װ�� UCenter, ������װ�����ԭ�����ݡ�',
	'db_drop_table_confirm' => '������װ�����ȫ��ԭ�����ݣ���ȷ��Ҫ������?',

	'writeable' => '��д',
	'unwriteable' => '����д',
	'old_step' => '��һ��',
	'new_step' => '��һ��',

	'database_errno_2003' => '�޷��������ݿ⣬�������ݿ��Ƿ����������ݿ��������ַ�Ƿ���ȷ',
	'database_errno_1044' => '�޷������µ����ݿ⣬�������ݿ�������д�Ƿ���ȷ',
	'database_errno_1045' => '�޷��������ݿ⣬�������ݿ��û������������Ƿ���ȷ',
	'database_connect_error' => '���ݿ����Ӵ���',

	'step_env_check_title' => '��ʼ��װ',
	'step_env_check_desc' => '�����Լ��ļ�Ŀ¼Ȩ�޼��',
	'step_db_init_title' => '��װ���ݿ�',
	'step_db_init_desc' => '����ִ�����ݿⰲװ',

	'step1_file' => 'Ŀ¼�ļ�',
	'step1_need_status' => '����״̬',
	'step1_status' => '��ǰ״̬',
	'not_continue' => '�뽫���Ϻ�沿����������',

	'tips_dbinfo' => '��д���ݿ���Ϣ',
	'tips_dbinfo_comment' => '',
	'tips_admininfo' => '��д����Ա��Ϣ',
	'step_ext_info_title' => '��װ�ɹ���',
	'step_ext_info_comment' => '��������½',

	'ext_info_succ' => '��װ�ɹ���',
	'install_submit' => '�ύ',
	'install_locked' => '��װ�������Ѿ���װ���ˣ������ȷ��Ҫ���°�װ���뵽��������ɾ��<br /> '.str_replace(ROOT_PATH, '', $lockfile),
	'error_quit_msg' => '���������������⣬��װ�ſ��Լ���',

	'step_app_reg_title' => '�������л���',
	'step_app_reg_desc' => '�������������Լ����� UCenter',
	'tips_ucenter' => '����д UCenter �����Ϣ',
	'tips_ucenter_comment' => 'UCenter �� Comsenz ��˾��Ʒ�ĺ��ķ������Discuz! Board �İ�װ�����������˳���������Ѿ���װ�� UCenter������д������Ϣ�������뵽 <a href="http://www.discuz.com/" target="blank">Comsenz ��Ʒ����</a> ���ز��Ұ�װ��Ȼ���ټ�����',

	'advice_mysql_connect' => '���� mysql ģ���Ƿ���ȷ����',
	'advice_fsockopen' => '�ú�����Ҫ php.ini �� allow_url_fopen ѡ���������ϵ�ռ��̣�ȷ�������˴����',
	'advice_gethostbyname' => '�Ƿ�php�����н�ֹ��gethostbyname����������ϵ�ռ��̣�ȷ�������˴����',
	'advice_file_get_contents' => '�ú�����Ҫ php.ini �� allow_url_fopen ѡ���������ϵ�ռ��̣�ȷ�������˴����',
	'advice_xml_parser_create' => '�ú�����Ҫ PHP ֧�� XML������ϵ�ռ��̣�ȷ�������˴����',

	'ucurl' => 'UCenter �� URL',
	'ucpw' => 'UCenter ��ʼ������',
	'ucip' => 'UCenter ��IP��ַ',
	'ucenter_ucip_invalid' => '��ʽ��������д��ȷ�� IP ��ַ',
	'ucip_comment' => '�����������������Բ���',

	'tips_siteinfo' => '����дվ����Ϣ',
	'sitename' => 'վ������',
	'siteurl' => 'վ�� URL',

	'forceinstall' => 'ǿ�ư�װ',
	'dbinfo_forceinstall_invalid' => '��ǰ���ݿ⵱���Ѿ�����ͬ����ǰ׺�����ݱ��������޸ġ�����ǰ׺��������ɾ���ɵ����ݣ�����ѡ��ǿ�ư�װ��ǿ�ư�װ��ɾ�������ݣ����޷��ָ�',

	'click_to_back' => '���������һ��',
	'adminemail' => 'ϵͳ���� Email',
	'adminemail_comment' => '���ڷ��ͳ�����󱨸�',
	'dbhost_comment' => '���ݿ��������ַ, һ��Ϊ localhost',
	'tablepre_comment' => 'ͬһ���ݿ����ж����̳ʱ�����޸�ǰ׺',
	'forceinstall_check_label' => '��Ҫɾ�����ݣ�ǿ�ư�װ !!!',

	'uc_url_empty' => '��û����д UCenter �� URL���뷵����д',
	'uc_url_invalid' => 'URL ��ʽ����',
	'uc_url_unreachable' => 'UCenter �� URL ��ַ������д��������',
	'uc_ip_invalid' => '�޷�����������������дվ��� IP',
	'uc_admin_invalid' => 'UCenter ��ʼ�����������������д',
	'uc_data_invalid' => 'ͨ��ʧ�ܣ����� UCenter ��URL ��ַ�Ƿ���ȷ ',
	'uc_dbcharset_incorrect' => 'UCenter ���ݿ��ַ����뵱ǰӦ���ַ�����һ��',
	'uc_api_add_app_error' => '�� UCenter ���Ӧ�ô���',
	'uc_dns_error' => 'UCenter DNS���������뷵����дһ�� UCenter �� IP��ַ',

	'ucenter_ucurl_invalid' => 'UCenter ��URLΪ�գ����߸�ʽ��������',
	'ucenter_ucpw_invalid' => 'UCenter �Ĵ�ʼ������Ϊ�գ����߸�ʽ��������',
	'siteinfo_siteurl_invalid' => 'վ��URLΪ�գ����߸�ʽ��������',
	'siteinfo_sitename_invalid' => 'վ������Ϊ�գ����߸�ʽ��������',
	'dbinfo_dbhost_invalid' => '���ݿ������Ϊ�գ����߸�ʽ��������',
	'dbinfo_dbname_invalid' => '���ݿ���Ϊ�գ����߸�ʽ��������',
	'dbinfo_dbuser_invalid' => '���ݿ��û���Ϊ�գ����߸�ʽ��������',
	'dbinfo_dbpw_invalid' => '���ݿ�����Ϊ�գ����߸�ʽ��������',
	'dbinfo_adminemail_invalid' => 'ϵͳ����Ϊ�գ����߸�ʽ��������',
	'dbinfo_tablepre_invalid' => '���ݱ�ǰ׺Ϊ�գ����߸�ʽ��������',
	'admininfo_username_invalid' => '����Ա�û���Ϊ�գ����߸�ʽ��������',
	'admininfo_email_invalid' => '����ԱEmailΪ�գ����߸�ʽ��������',
	'admininfo_password_invalid' => '����Ա����Ϊ�գ�����д',
	'admininfo_password2_invalid' => '�������벻һ�£�����',

	'install_dzfull' => '<br><label><input type="radio"'.(getgpc('install_ucenter') != 'no' ? ' checked="checked"' : '').' name="install_ucenter" value="yes" onclick="if(this.checked)$(\'form_items_2\').style.display=\'none\';" /> ȫ�°�װ Discuz! X (�� UCenter Server)</label>',
	'install_dzonly' => '<br><label><input type="radio"'.(getgpc('install_ucenter') == 'no' ? ' checked="checked"' : '').' name="install_ucenter" value="no" onclick="if(this.checked)$(\'form_items_2\').style.display=\'\';" /> ����װ Discuz! X (�ֹ�ָ���Ѿ���װ�� UCenter Server)</label>',

	'username' => '����Ա�˺�',
	'email' => '����Ա Email',
	'password' => '����Ա����',
	'password_comment' => '����Ա���벻��Ϊ��',
	'password2' => '�ظ�����',

	'admininfo_invalid' => '����Ա��Ϣ���������������Ա�˺ţ����룬����',
	'dbname_invalid' => '���ݿ���Ϊ�գ�����д���ݿ�����',
	'tablepre_invalid' => '���ݱ�ǰ׺Ϊ�գ����߸�ʽ��������',
	'admin_username_invalid' => '�Ƿ��û������û������Ȳ�Ӧ������ 15 ��Ӣ���ַ����Ҳ��ܰ��������ַ���һ�������ģ���ĸ��������',
	'admin_password_invalid' => '��������治һ�£�����������',
	'admin_email_invalid' => 'Email ��ַ���󣬴��ʼ���ַ�Ѿ���ʹ�û��߸�ʽ��Ч�������Ϊ������ַ',
	'admin_invalid' => '������Ϣ����Ա��Ϣû����д����������ϸ��дÿ����Ŀ',
	'admin_exist_password_error' => '���û��Ѿ����ڣ������Ҫ���ô��û�Ϊ��̳�Ĺ���Ա������ȷ������û������룬�����������̳����Ա������',

	'tagtemplates_subject' => '����',
	'tagtemplates_uid' => '�û� ID',
	'tagtemplates_username' => '������',
	'tagtemplates_dateline' => '����',
	'tagtemplates_url' => '�����ַ',

	'uc_version_incorrect' => '���� UCenter ����˰汾���ͣ������� UCenter ����˵����°汾���������������ص�ַ��http://www.comsenz.com/ ��',
	'config_unwriteable' => '��װ���޷�д�������ļ�, ������ config.inc.php ��������Ϊ��д״̬(777)',

	'install_in_processed' => '���ڰ�װ...',
	'install_succeed' => '��װ�ɹ����������',
	'install_founder_contact' => '������һ����д��ϵ��ʽ',

	'init_credits_karma' => '����',
	'init_credits_money' => '��Ǯ',

	'init_postno0' => '¥��',
	'init_postno1' => 'ɳ��',
	'init_postno2' => '���',
	'init_postno3' => '�ذ�',

	'init_support' => '֧��',
	'init_opposition' => '����',

	'init_group_0' => '��Ա',
	'init_group_1' => '����Ա',
	'init_group_2' => '��������',
	'init_group_3' => '����',
	'init_group_4' => '��ֹ����',
	'init_group_5' => '��ֹ����',
	'init_group_6' => '��ֹ IP',
	'init_group_7' => '�ο�',
	'init_group_8' => '�ȴ���֤��Ա',
	'init_group_9' => '��ؤ',
	'init_group_10' => '������·',
	'init_group_11' => 'ע���Ա',
	'init_group_12' => '�м���Ա',
	'init_group_13' => '�߼���Ա',
	'init_group_14' => '���ƻ�Ա',
	'init_group_15' => '��̳Ԫ��',

	'init_rank_1' => '������ѧ',
	'init_rank_2' => 'С��ţ��',
	'init_rank_3' => 'ʵϰ����',
	'init_rank_4' => '����׫����',
	'init_rank_5' => '��Ƹ����',

	'init_cron_1' => '��ս��շ�����',
	'init_cron_2' => '��ձ�������ʱ��',
	'init_cron_3' => 'ÿ����������',
	'init_cron_4' => '����ͳ�����ʼ�ף��',
	'init_cron_5' => '����ظ�֪ͨ',
	'init_cron_6' => 'ÿ�չ�������',
	'init_cron_7' => '��ʱ��������',
	'init_cron_8' => '��̳�ƹ�����',
	'init_cron_9' => 'ÿ����������',
	'init_cron_10' => 'ÿ�� X-Space�����û�',
	'init_cron_11' => 'ÿ���������',

	'init_bbcode_1' => 'ʹ���ݺ�����������Ч������ HTML �� marquee ��ǩ��ע�⣺���Ч��ֻ�� Internet Explorer ���������Ч��',
	'init_bbcode_2' => 'Ƕ�� Flash ����',
	'init_bbcode_3' => '��ʾ QQ ����״̬�������ͼ����Ժ�������������',
	'init_bbcode_4' => '�ϱ�',
	'init_bbcode_5' => '�±�',
	'init_bbcode_6' => 'Ƕ�� Windows media ��Ƶ',
	'init_bbcode_7' => 'Ƕ�� Windows media ��Ƶ����Ƶ',

	'init_qihoo_searchboxtxt' =>'����ؼ���,������������̳',
	'init_threadsticky' =>'ȫ���ö�,�����ö�,�����ö�',

	'init_default_style' => 'Ĭ�Ϸ��',
	'init_default_forum' => 'Ĭ�ϰ��',
	'init_default_template' => 'Ĭ��ģ����ϵ',
	'init_default_template_copyright' => '��ʢ���루�������Ƽ����޹�˾',

	'init_dataformat' => 'Y-n-j',
	'init_modreasons' => '���/SPAM\r\n�����ˮ\r\nΥ������\r\n�Ĳ�����\r\n�ظ�����\r\n\r\n�Һ���ͬ\r\n��Ʒ����\r\nԭ������',
	'init_link' => 'Discuz! �ٷ���̳',
	'init_link_note' => '�ṩ���� Discuz! ��Ʒ���š���������뼼������',

	'init_promotion_task' => '��վ�ƹ�����',
	'init_gift_task' => '���������',
	'init_avatar_task' => 'ͷ��������',

	'license' => '<div class="license"><h1>���İ���ȨЭ�� �����������û�</h1>

<p>��Ȩ���� (c) 2001-2010����ʢ���루�������Ƽ����޹�˾��������Ȩ����</p>

<p>��л��ѡ�� Discuz! ��̳��Ʒ��ϣ�����ǵ�Ŭ����Ϊ���ṩһ����Ч���ٺ�ǿ���������̳���������</p>

<p>Discuz! Ӣ��ȫ��Ϊ Crossday Discuz! Board������ȫ��Ϊ Discuz! ��̳�����¼�� Discuz!��</p>

<p>��ʢ���루�������Ƽ����޹�˾Ϊ Discuz! ��Ʒ�Ŀ����̣���������ӵ�� Discuz! ��Ʒ����Ȩ���й����Ұ�Ȩ������Ȩ�ǼǺ� 2006SR11895������ʢ���루�������Ƽ����޹�˾��ַΪ http://www.comsenz.com��Discuz! �ٷ���վ��ַΪ http://www.discuz.com��Discuz! �ٷ���������ַΪ http://www.discuz.net��</p>

<p>Discuz! ����Ȩ�����л����񹲺͹����Ұ�Ȩ��ע�ᣬ����Ȩ�ܵ����ɺ͹��ʹ�Լ������ʹ���ߣ����۸��˻���֯��ӯ�������;��Σ�������ѧϰ���о�ΪĿ�ģ���������ϸ�Ķ���Э�飬����⡢ͬ�⡢�����ر�Э���ȫ������󣬷��ɿ�ʼʹ�� Discuz! �����</p>

<p>����ȨЭ�������ҽ������� Discuz! X �汾����ʢ���루�������Ƽ����޹�˾ӵ�жԱ���ȨЭ������ս���Ȩ��</p>

<h3>I. Э����ɵ�Ȩ��</h3>
<ol>
<li>����������ȫ���ر������û���ȨЭ��Ļ����ϣ��������Ӧ���ڷ���ҵ��;��������֧�������Ȩ��Ȩ���á�</li>
<li>��������Э��涨��Լ�������Ʒ�Χ���޸� Discuz! Դ����(������ṩ�Ļ�)�����������Ӧ������վҪ��</li>
<li>��ӵ��ʹ�ñ������������̳��ȫ����Ա���ϡ����¼������Ϣ������Ȩ���������е����������ݵ���ط�������</li>
<li>�����ҵ��Ȩ֮�������Խ������Ӧ������ҵ��;��ͬʱ�������������Ȩ������ȷ���ļ���֧�����ޡ�����֧�ַ�ʽ�ͼ���֧�����ݣ��Թ���ʱ�����ڼ���֧��������ӵ��ͨ��ָ���ķ�ʽ���ָ����Χ�ڵļ���֧�ַ�����ҵ��Ȩ�û����з�ӳ����������Ȩ����������������Ϊ��Ҫ���ǣ���û��һ�������ɵĳ�ŵ��֤��</li>
</ol>

<h3>II. Э��涨��Լ��������</h3>
<ol>
<li>δ����ҵ��Ȩ֮ǰ�����ý������������ҵ��;����������������ҵ��վ����Ӫ����վ����Ӫ��ΪĿ��ʵ��ӯ������վ����������ҵ��Ȩ���½http://www.discuz.com�ο����˵����Ҳ�����µ�8610-51657885�˽����顣</li>
<li>���öԱ��������֮��������ҵ��Ȩ���г��⡢���ۡ���Ѻ�򷢷������֤��</li>
<li>������Σ���������;��Ρ��Ƿ񾭹��޸Ļ��������޸ĳ̶���Σ�ֻҪʹ�� Discuz! ��������κβ��֣�δ��������ɣ���̳ҳ��ҳ�Ŵ��� Discuz! ���ƺͿ�ʢ���루�������Ƽ����޹�˾������վ��http://www.comsenz.com��http://www.discuz.com �� http://www.discuz.net�� �����Ӷ����뱣����������������޸ġ�</li>
<li>��ֹ�� Discuz! ��������κβ��ֻ������Է�չ�κ������汾���޸İ汾��������汾�������·ַ���</li>
<li>�����δ�����ر�Э������������Ȩ������ֹ��������ɵ�Ȩ�������ջأ����е���Ӧ�������Ρ�</li>
</ol>

<h3>III. ���޵�������������</h3>
<ol>
<li>����������������ļ�����Ϊ���ṩ�κ���ȷ�Ļ��������⳥�򵣱�����ʽ�ṩ�ġ�</li>
<li>�û�������Ը��ʹ�ñ�������������˽�ʹ�ñ�����ķ��գ�����δ�����Ʒ��������֮ǰ�����ǲ���ŵ�ṩ�κ���ʽ�ļ���֧�֡�ʹ�õ�����Ҳ���е��κ���ʹ�ñ���������������������Ρ�</li>
<li>��ʢ���루�������Ƽ����޹�˾����ʹ�ñ������������̳�е����»���Ϣ�е����Ρ�</li>
</ol>

<p>�й� Discuz! �����û���ȨЭ�顢��ҵ��Ȩ�뼼���������ϸ���ݣ����� Discuz! �ٷ���վ�����ṩ����ʢ���루�������Ƽ����޹�˾ӵ���ڲ�����֪ͨ������£��޸���ȨЭ��ͷ����Ŀ���Ȩ�����޸ĺ��Э����Ŀ����Ըı�֮���������Ȩ�û���Ч��</p>

<p>�����ı���ʽ����ȨЭ����ͬ˫������ǩ���Э��һ����������ȫ�ĺ͵�ͬ�ķ���Ч������һ����ʼ��װ Discuz!��������Ϊ��ȫ��Ⲣ���ܱ�Э��ĸ�������������������������Ȩ����ͬʱ���ܵ���ص�Լ�������ơ�Э����ɷ�Χ�������Ϊ����ֱ��Υ������ȨЭ�鲢������Ȩ��������Ȩ��ʱ��ֹ��Ȩ������ֹͣ�𺦣�������׷��������ε�Ȩ����</p></div>',

	'uc_installed' => '���Ѿ���װ�� UCenter�������Ҫ���°�װ����ɾ�� data/install.lock �ļ�',
	'i_agree' => '������ϸ�Ķ�����ͬ�����������е���������',
	'supportted' => '֧��',
	'unsupportted' => '��֧��',
	'max_size' => '֧��/���ߴ�',
	'project' => '��Ŀ',
	'ucenter_required' => 'Discuz! ��������',
	'ucenter_best' => 'Discuz! ���',
	'curr_server' => '��ǰ������',
	'env_check' => '�������',
	'os' => '����ϵͳ',
	'php' => 'PHP �汾',
	'attachmentupload' => '�����ϴ�',
	'unlimit' => '������',
	'version' => '�汾',
	'gdversion' => 'GD ��',
	'allow' => '����',
	'unix' => '��Unix',
	'diskspace' => '���̿ռ�',
	'priv_check' => 'Ŀ¼���ļ�Ȩ�޼��',
	'func_depend' => '���������Լ��',
	'func_name' => '��������',
	'check_result' => '�����',
	'suggestion' => '����',
	'advice_mysql' => '���� mysql ģ���Ƿ���ȷ����',
	'advice_fopen' => '�ú�����Ҫ php.ini �� allow_url_fopen ѡ���������ϵ�ռ��̣�ȷ�������˴����',
	'advice_file_get_contents' => '�ú�����Ҫ php.ini �� allow_url_fopen ѡ���������ϵ�ռ��̣�ȷ�������˴����',
	'advice_xml' => '�ú�����Ҫ PHP ֧�� XML������ϵ�ռ��̣�ȷ�������˴����',
	'none' => '��',

	'dbhost' => '���ݿ������',
	'dbuser' => '���ݿ��û���',
	'dbpw' => '���ݿ�����',
	'dbname' => '���ݿ���',
	'tablepre' => '���ݱ�ǰ׺',

	'ucfounderpw' => '��ʼ������',
	'ucfounderpw2' => '�ظ���ʼ������',

	'init_log' => '��ʼ����¼',
	'clear_dir' => '���Ŀ¼',
	'select_db' => 'ѡ�����ݿ�',
	'create_table' => '�������ݱ�',
	'succeed' => '�ɹ� ',

	'testdata' => '��������',
	'testdata_check_label' => '��װ��ҳģ��������������ݣ��ļ���',
	'install_data' => '���ڰ�װ����',
	'install_test_data' => '���ڰ�װ��������',

	'method_undefined' => 'δ���巽��',
	'database_nonexistence' => '���ݿ�������󲻴���',
	'founder_contact' => '<h4>���ڡ���ʢ���Ƽƻ�����˵��</h4>

	Ϊ�˲��ϸĽ���Ʒ�����������û����飬Discuz! X����ʢ���Ƽƻ�������ϵͳ���������Ƿ����û�����̳�Ĳ���ϰ�ߣ���������������δ���İ汾�жԲ�Ʒ���иĽ�����Ƴ��������û�������¹��ܡ�

	��ϵͳ�����ռ�վ��������Ϣ�����ռ��û����ϣ������ڰ�ȫ���գ����Ҿ���ʵ�ʲ��Բ���Ӱ����̳������Ч�ʡ�

	����װʹ�ñ��汾��ʾ��ͬ����롶��ʢ���Ƽƻ�����Discuz!��Ӫ���Ż�ͨ����վ��ķ���Ϊ���ṩ��Ӫָ�����飬���ǽ���ʾ����θ���վ���������������̳���ܣ���ν��к���Ĺ������ã��Լ��ṩ������һЩ��Ӫ����ȡ�

	Ϊ�˷������Ǻ�����ͨ��Ӫ���ԣ��������³��õ�������ϵ��ʽ',
	'skip_current' => '��������',
	'topic' => 'ר��',

);

$msglang = array(
	'config_nonexistence' => '���� config.inc.php ������, �޷�������װ, ���� FTP �����ļ��ϴ������ԡ�',
);



?>
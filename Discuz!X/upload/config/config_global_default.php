<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id$
 */

$_config = array();

// ���ݿ����������
$_config['db']['map'] = array();
$_config['db'][1]['dbhost']  		= 'localhost';		// ��������ַ
$_config['db'][1]['dbuser']  		= 'root';		// �û�
$_config['db'][1]['dbpw'] 	 	= 'root';		// ����
$_config['db'][1]['dbcharset'] 		= 'gbk';		// �ַ���
$_config['db'][1]['pconnect'] 		= 0;			// �Ƿ��������
$_config['db'][1]['dbname']  		= 'ultrax';		// ���ݿ�
$_config['db'][1]['tablepre'] 		= 'pre_';		// ����ǰ׺

// �ڴ�������Ż����ã�����������ҪPHP��չ���֧�֣����� memcache �������������ã��� memcache �޷�����ʱ�����Զ���������������Ż�ģʽ��
$_config['memory']['prefix'] = 'discuz_';
$_config['memory']['eaccelerator'] = 1;				// ������ eaccelerator ��֧��
$_config['memory']['xcache'] = 1;				// ������ xcache ��֧��
$_config['memory']['memcache']['server'] = '';			// memcache ��������ַ
$_config['memory']['memcache']['port'] = 11211;			// memcache �������˿�
$_config['memory']['memcache']['pconnect'] = 1;			// memcache �Ƿ񳤾�����
$_config['memory']['memcache']['timeout'] = 1;			// memcache ���������ӳ�ʱ

// �������������
$_config['server']['id']		= 1;			// ��������ţ���webserver��ʱ�����ڱ�ʶ��ǰ��������ID

// �����������
$_config['download']['readmod'] = 2;				// �����ļ���ȡģʽ; ģʽ2Ϊ���ʡ�ڴ淽ʽ������֧�ֶ��߳�����
								// 1=fread 2=readfile 3=fpassthru 4=fpassthru+multiple
$_config['download']['xsendfile']['type'] = 0;			// �Ƿ����� X-Sendfile ���ܣ���Ҫ������֧�֣�0=close 1=nginx 2=lighttpd 3=apache
$_config['download']['xsendfile']['dir'] = '/down/';		// ���� nginx X-sendfile ʱ����̳����Ŀ¼������ӳ��·������ʹ�� / ��β

//  CONFIG CACHE
$_config['cache']['type'] 			= 'sql';	// �������� file=�ļ�����, sql=���ݿ⻺��

// ҳ���������
$_config['output']['charset'] 			= 'gbk';	// ҳ���ַ���
$_config['output']['forceheader']		= 1;		// ǿ�����ҳ���ַ��������ڱ���ĳЩ��������
$_config['output']['gzip'] 			= 0;		// �Ƿ���� Gzip ѹ�����
$_config['output']['tplrefresh'] 		= 1;		// ģ���Զ�ˢ�¿��� 0=�ر�, 1=��
$_config['output']['language'] 			= 'zh_cn';	// ҳ������ zh_cn/zh_tw
$_config['output']['staticurl'] 		= 'static/';	// վ�㾲̬�ļ�·������/����β
$_config['output']['ajaxvalidate']		= 0;		// �Ƿ��ϸ���֤ Ajax ҳ�����ʵ�� 0=�رգ�1=��

// COOKIE ����
$_config['cookie']['cookiepre'] 		= 'uchome_'; 	// COOKIEǰ׺
$_config['cookie']['cookiedomain'] 		= ''; 		// COOKIE������
$_config['cookie']['cookiepath'] 		= '/'; 		// COOKIE����·��

// վ�㰲ȫ����
$_config['security']['authkey']			= 'asdfasfas';	// վ�������Կ
$_config['security']['urlxssdefend']		= true;		// ���� URL XSS ����
$_config['security']['attackevasive']		= 0;		// CC �������� 1|2|4

$_config['security']['querysafe']['status']	= 1;		// �Ƿ���SQL��ȫ��⣬���Զ�Ԥ��SQLע�빥��
$_config['security']['querysafe']['dfunction']	= array('load_file','hex','substring','if','ord','char');
$_config['security']['querysafe']['daction']	= array('intooutfile','intodumpfile','unionselect','(select');
$_config['security']['querysafe']['dnote']	= array('/*','*/','#','--','"');
$_config['security']['querysafe']['dlikehex']	= 1;
$_config['security']['querysafe']['afullnote']	= 1;

$_config['admincp']['founder']			= '1';		// վ�㴴ʼ�ˣ�ӵ��վ������̨�����Ȩ�ޣ�ÿ��վ��������� 1���������ʼ��
								// ����ʹ��uid��Ҳ����ʹ���û����������ʼ��֮����ʹ�ö���",���ֿ�;
$_config['admincp']['forcesecques']		= 0;		// ������Ա�������ð�ȫ���ʲ��ܽ���ϵͳ���� 0=��, 1=��[��ȫ]
$_config['admincp']['checkip']			= 1;		// ��̨��������Ƿ���֤����Ա�� IP, 1=��[��ȫ], 0=�񡣽��ڹ���Ա�޷���½��̨ʱ���� 0��
$_config['admincp']['runquery']			= 1;		// �Ƿ������̨���� SQL ��� 1=�� 0=��[��ȫ]
$_config['admincp']['dbimport']			= 1;		// �Ƿ������̨�ָ���̳����  1=�� 0=��[��ȫ]

?>
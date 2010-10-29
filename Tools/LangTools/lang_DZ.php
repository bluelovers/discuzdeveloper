<?php

/*
	(C)2007-2009 http://www.monkeye.cn
	����ɽ�ֲ���������Ʒ
	DisAD ���԰��Զ���ȡ����
	�������޸�·��
*/

chdir('../../');

$path = './disad/template/';
$pathpub = './disad/template_pub/';
$d = dir($path);
$langs = array('discuz_lang' => 'disad');

while(false !== ($entry = $d->read())) {
	$file = pathinfo($entry);
	if($file['extension'] == 'htm') {
		$c = file_get_contents($path.$entry);
		$c = preg_replace('/[\xA0-\xFF]+/e', 'returnstr(\0, "$file[basename]")', $c);
		file_put_contents($pathpub.$entry, $c);
	}
}

$v = var_export($langs, 1);

$s = <<<EOF
<?php

/*
	(C)2007-2009 http://www.monkeye.cn
	����ɽ�ֲ���������Ʒ
*/

\$language = $v;

?>
EOF;

file_put_contents($pathpub.'templates.lang.php', $s);

$c = file_get_contents($path.'message.lang.php');
file_put_contents($pathpub.'message.lang.php', $c);

function returnstr($s, $basename) {
	global $langs;
	$c = count($langs);
	$key = str_replace('.htm', '', $basename).'_'.$c;
	$langs[$key] = $s;
	return '{lang '.$key.'}';
}

?>
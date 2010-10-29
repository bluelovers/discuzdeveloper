<?php
/**
 * Name: X1语言包提取工具
 * Author: Monkey && Kookxiang
 * Date: 2009/5/22 17:10:03
 * 修改后的模版放置到 template_pub 语言包，语言包放置到 data/plugindata/$plugin.lang.php 里
 * 代码中的语言包放置到 script_pub，请手动修改提取结果。
 */
@$plugin = $_POST['plugin'] ? $_POST['plugin'] : $_GET['plugin'];
echo <<<EOF
<form method="post" action="">
Plugin folder: <input type="text" name="plugin" /><input type="submit" />
</form>
EOF;
if(!$plugin) exit();

$path = $plugin.'/template/';
$pathpub = $plugin.'/template_pub/';
$phppub = $plugin.'/script_pub/';
@mkdir($phppub, 777);

$langs = array();
$d = dir($plugin);

while(false !== ($entry = $d->read())) {
	$file = pathinfo($entry);
	$extend = explode("." , $entry);
	$va = count($extend)-1;
	if($extend[$va] == 'php') {
		$c = file_get_contents($plugin.'/'.$entry);
		$c = preg_replace('/(showmessage|cpmsg)\("[\xA0-\xFF]+[^"]+[\xA0-\xFF<>\.]+"/e', 'returnstr_php(\'\0\', "$file[basename]","\1")', $c);
		$c = preg_replace("/(showmessage|cpmsg)\('[\xA0-\xFF]+[^']*[\xA0-\xFF<>\.]+'/e", 'returnstr_php(\'\0\', "$file[basename]","\1")', $c);
		$c = preg_replace('/"[\xA0-\xFF]+[^"]+[\xA0-\xFF<>\.]+"/e', 'returnstr_php(\'\0\', "$file[basename]")', $c);
		$c = preg_replace("/'[\xA0-\xFF]+[^']+[\xA0-\xFF<>\.>]+'/e", 'returnstr_php(\'\0\', "$file[basename]")', $c);
		$c = preg_replace('/[\xA0-\xFF]+[\xA0-\xFFa-zA-Z0-9,<>\.\s]*[\xA0-\xFF]+/e', 'returnstr_php(\'\0\', "$file[basename]")', $c);
		file_put_contents($phppub.$entry, $c);
	}
}

$vs = var_export($langs, 1);

$langs = array();
if (file_exists($path)){
	@mkdir($pathpub, 777);
	$d = dir($path);
	while(false !== ($entry = $d->read())) {
		$file = pathinfo($entry);
		if($file['extension'] == 'htm') {
			$c = remark(file_get_contents($path.$entry));
			$c = preg_replace('/[\xA0-\xFF]+[\xA0-\xFFa-zA-Z0-9,\.\s]*[\xA0-\xFF]+/e', 'returnstr(\'\0\', "$file[basename]")', $c);
			file_put_contents($pathpub.$entry, $c);
		}
	}
}
$vt = var_export($langs, 1);

$s = <<<EOF
<?php

\$scriptlang['$plugin'] = $vs;

\$templatelang['$plugin'] = $vt;

?>
EOF;

file_put_contents('../../data/plugindata/'.$plugin.'.lang.php', $s);
echo 'Plugin "'.$plugin.'": Done!';
function returnstr($s, $basename) {
	global $langs, $plugin, $filec;
	@$c = ++$filec[$basename];
	$key = str_replace('.', '_', $basename).'_'.$c;
	if(in_array($s, $langs)) {
		$lang_flip = array_flip($langs);
		$key = $lang_flip[$s];
	} else {
		$langs[$key] = $s;
	}
	return '{lang '.$plugin.':'.$key.'}';
}
function returnstr_php($s, $basename, $function="") {
	global $langs, $plugin, $filec;
	@$c = ++$filec[$basename];
	$key = str_replace('.', '_', $basename).'_'.$c;
	if(in_array($s, $langs)) {
		$lang_flip = array_flip($langs);
		$key = $lang_flip[$s];
	} else {
		$langs[$key] = $s;
	}
	if ($function){
		return $function.'("'.$plugin.':'.$key.'"';
	}
	return 'lang("plugin/'.$plugin.'","'.$key.'")';
}

function remark($buf) {
	$remark = array(
		array('//note', "\r\n"),
		array('/*', '*/'),
	);
	foreach($remark as $v) {			
		$data = '';
		while(count($strcut = explode($v[0], $buf, 2)) > 1) {
			$data .= $strcut[0];
			if(($strcut = explode($v[1], $strcut[1], 2)) > 1) {
				$data = preg_replace('/[\t ]+$/', '', $data);
				$data = preg_replace('/\r\n$/', '', $data);
				if($v[1] == "\r\n") {
					$buf = "\r\n";	
				} else {
					$buf = '';
				}
				$buf .= $strcut[1];
			} else {
				$buf = $strcut[0];
				break;
			}
		}
		$data .= $strcut[0];
		$buf = $data;
	}
	return $buf;
}

?>
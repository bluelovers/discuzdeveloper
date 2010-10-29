<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id$
*/

function sqldebug($sql='') {
	if(empty($sql)) {
		return;
	}
	global $sqldebug;
	static $sqlstarttime = 0, $process = 0, $start = 0, $sqli = 1;

	$mtime = explode(' ', microtime());
	if($sqlstarttime) {
		$start = number_format($sqlstarttime - $GLOBALS['discuz_starttime'], 6);
		$process = number_format($mtime[1] + $mtime[0] - $sqlstarttime, 6);
	}
	$lines = debug_backtrace();
	$sqlstarttime = $mtime[1] + $mtime[0];
	$sqldebug .= '<table width=100% border=1 style="table-layout:fixed"><tr><td colspan=9 style="background: #B0B0B0;color: #fff">'.
		'<div class="right" style="font-size:9px"><b>SQL Start: </b>'.$start.'<b> Processed: </b>'.$process.'</div><b>['.$sqli.']</b></td></tr><tr><td colspan=9 style="background: #E4E4E4">'.sqlshowformat($sql).'</td></tr>'.
		'<tr><td colspan=9>BACKTRACE<br>';
	foreach($lines as $row) {
		$sqldebug .= $row['file'].':'.$row['line'].'<br>';
		if($row['function'] == 'query') {
			break;
		}
	}
	$sqldebug .= '</td></tr>';
	if(preg_match("/^select /i", $sql)) {
		$sqldebug .= "<tr><td>id</td><td>table</td><td>type</td><td>possible_keys</td><td>key</td><td>key_len</td><td>ref</td><td>rows</td><td>Extra</td></tr>";
		$explainquery = mysql_query("explain $sql");
		if($explainquery) {
			while( $explain = mysql_fetch_assoc($explainquery) ) {
				$sqldebug .= "<tr><td>{$explain['id']}</td><td>{$explain['table']}</td><td>{$explain['type']}</td><td>{$explain['possible_keys']}</td><td>{$explain['key']}</td><td>{$explain['key_len']}</td><td>{$explain['ref']}</td><td>{$explain['rows']}</td><td>{$explain['Extra']}</td></tr>";
			}
		}
	}
	$sqldebug .="</table>";
	$sqli++;
}

function debugtools() {
	global $_COOKIE, $_SESSION, $_DCOOKIE, $_DCACHE, $_DSESSION, $_DCACHE, $_DPLUGIN, $hookscript, $sqldebug, $debuginfo, $sqlspenttimes;
	$table1 = "<table cellspacing=0 cellpadding=0 width=100% align=center><tr><td class=altbg2 style=\"font-size:12px;\">";
	$table2 = "</td></tr></table>";
	$sqldebuglist = $table1.$sqldebug.$table2;
	$dcookielist = $table1."<div style=\"border:1px soild black;\">&#36;_DCOOKIE</div><br>".preg_replace(array('/\n/', '/\s/'), array('<br>', '&nbsp;'), daddslashes(print_r($_DCOOKIE, true), '"'));
	$dcookielist .= "<div style=\"border:1px soild black;\">&#36;_COOKIE</div><br>".preg_replace(array('/\n/', '/\s/'), array('<br>', '&nbsp;'), daddslashes(print_r($_COOKIE, true), '"')).$table2;
	$includelist = $table1.preg_replace(array('/\n/', '/\s/'), array('<br>', '&nbsp;'), daddslashes(print_r(get_included_files(), true), '"')).$table2;
	$dsessionlist = $table1."<div style=\"border:1px soild black;\">&#36;_DSESSION</div><br>".preg_replace(array('/\n/', '/\s/'), array('<br>', '&nbsp;'), daddslashes(print_r($_DSESSION, true), '"')).$table2;
	$dsessionlist .= $table1."<div style=\"border:1px soild black;\">&#36;_SESSION</div><br>".preg_replace(array('/\n/', '/\s/'), array('<br>', '&nbsp;'), daddslashes(print_r($_SESSION, true), '"')).$table2;
	$dcachelist = $table1."<div style=\"border:1px soild black;\">&#36;_DCACHE</div><br>".preg_replace(array('/\n/', '/\s/'), array('<br>', '&nbsp;'), daddslashes(print_r($_DCACHE, true), '"')).$table2;
	$dpluginlist = $table1."<div style=\"border:1px soild black;\">&#36;hookscript</div><br>".preg_replace(array('/\n/', '/\s/'), array('<br>', '&nbsp;'), daddslashes(print_r($hookscript[CURSCRIPT], true), '"')).$table2;
	$debugwinenv = $table1."<ul><li>PHP版本: ".PHP_VERSION."</li><li>MYSQL版本: ".mysql_get_server_info()."</li></ul>".$table2;

	$out  = "<div id=debug_bar style=\"background:#EFEFEF;border-left:#CCC 5px solid; text-align:left;padding: 0 10px\">";
	$out .= "<table cellspacing=0 cellpadding=0 width=100% align=\"center\">";
	$out .= "<tr><td colspan=\"3\">";
	$out .= "<span style=\"cursor:pointer;\" onclick=\"$('debug_bar').style.display = 'none'\">[关闭]</span>";
	$out .= "<span style=\"cursor:pointer;\" onclick=\"debugdisplay('debugwinquery')\"> | [查询 ".$debuginfo['queries']." 次]</span>";
	$out .= "<span style=\"cursor:pointer;\" onclick=\"debugdisplay('debugwininclude')\"> | [引用".count(get_included_files())." 个]</span>";
	$out .= "<span style=\"cursor:pointer;\" onclick=\"debugdisplay('debugwindcookie')\"> | [DCOOKIE]</span>";
	$out .= "<span style=\"cursor:pointer;\" onclick=\"debugdisplay('debugwindsession')\"> | [DSESSION]</span>";
	$out .= "<span style=\"cursor:pointer;\" onclick=\"debugdisplay('debugwinplugin')\"> | [嵌入点]</span>";
	$out .= "<span style=\"cursor:pointer;\" onclick=\"debugdisplay('debugwinenv')\"> | [环境]</span>";
	$out .= "<span style=\"cursor:pointer;\" onclick=\"debugdisplay('updatecache');ajaxget('include/debug.func.php?action=updatecache', 'updatecache');$('updatecache').style.display=''\"> | [更新缓存]</span>";
	$out .= "</td></tr></table>";

	$out .= "<div id=\"debugwinquery\" style=\"display:none;\">".$sqldebuglist."</div>";
	$out .= "<div id=\"debugwindcookie\" style=\"display:none;\">".$dcookielist."</div>";
	$out .= "<div id=\"debugwindsession\" style=\"display:none;\">".$dsessionlist."</div>";
	$out .= "<div id=\"debugwinplugin\" style=\"display:none;\">".$dpluginlist."</div>";
	$out .= "<div id=\"debugwininclude\" style=\"display:none;\">".$includelist."</div>";
	$out .= "<div id=\"debugwinenv\" style=\"display:none;\">".$debugwinenv."</div>";
	$out .= "<div id=\"updatecache\" style=\"display:none;\"></div>";

	$out .= <<<EOF
<script>
var debugwinarray = new Array(
'debugwinquery', 'debugwininclude', 'debugwindcookie'
, 'debugwindsession', 'debugwincache', 'debugwinplugin'
, 'debugwinuser', 'debugwinpower', 'debugwingroup'
, 'debugwinsettings', 'debugwinenv', 'updatecache'
)
function debugdisplay(debugwinid) {
	for(i = 0; i < debugwinarray.length; i++) {
		if(did = document.getElementById(debugwinarray[i])) {
			if(debugwinid == debugwinarray[i]) {
				did.style.display = did.style.display == '' ? 'none' : '';
			}else{
				did.style.display = 'none';
			}
		}
	}
}
</script>
EOF;

	echo "\n\n\n\n\n\n\n\n\n".$out;
}



function sqlshowformat($sql) {
	return preg_replace('/(SELECT|FROM|WHERE|DELETE|UPDATE|SET|INSERT INTO|REPLACE INTO|ORDER BY|GROUP BY|VALUES) /i', '<br /><b>\\1 </b>', htmlspecialchars($sql)).'<br /><br />';
}

if($_GET['action'] == 'updatecache') {

	define('IN_DISCUZ', TRUE);
	define('DISCUZ_ROOT', substr(dirname(__FILE__), 0, -7));
	$timestamp = time();

	@set_time_limit(1000);
	@ignore_user_abort(TRUE);

	require_once DISCUZ_ROOT.'./config.inc.php';
	require_once DISCUZ_ROOT.'./include/db_'.$database.'.class.php';

	$db = new dbstuff;
	$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect, true, $dbcharset);
	unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);

	require_once DISCUZ_ROOT.'./include/global.func.php';
	require_once DISCUZ_ROOT.'./include/cache.func.php';
	updatecache();

	$tpl = dir(DISCUZ_ROOT.'./forumdata/templates');
	while($entry = $tpl->read()) {
		if(preg_match("/\.tpl\.php$/", $entry)) {
			@unlink(DISCUZ_ROOT.'./forumdata/templates/'.$entry);
		}
	}
	$tpl->close();

	include template('header_ajax');
	echo '全部缓存更新完毕。';
	include template('footer_ajax');
}

?>
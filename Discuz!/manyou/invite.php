<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id$
*/

$s = '';
foreach($_GET as $k => $v) {
	$s .= '&'.$k.'='.rawurlencode($v);
}

header('location: ../userapp.php?script=invite'.$s);

?>
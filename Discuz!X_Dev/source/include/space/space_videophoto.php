<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id$
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(empty($_G['setting']['videophoto'])) {
	showmessage('no_open_videophoto');
}

require_once libfile('function/spacecp');
ckvideophoto($space);

$videophoto = getvideophoto($space['videophoto']);

include_once template("home/space_videophoto");

?>
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

if($space['videophotostatus']) {
	space_merge($space, 'field_home');
	$videophoto = getvideophoto($space['videophoto']);
} else {
	$videophoto = '';
}
$actives = array('verify' =>' class="a"');
$opactives = array('videophoto' =>' class="a"');

$operation = 'verify';
$opactives = array('videophoto' =>' class="a"');
include template("home/spacecp_videophoto");
?>
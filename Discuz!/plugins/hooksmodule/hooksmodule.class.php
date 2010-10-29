<?php

/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	嵌入点模型 2009/9/21 16:43:36

	本模型供开发者演示并熟悉所有嵌入点的位置
*/

class plugin_hooksmodule {
	var $vars = array();
	function plugin_hooksmodule() {
		include_once DISCUZ_ROOT . './forumdata/cache/plugin_hooksmodule.php';
		$data = explode("\n", $_DPLUGIN['hooksmodule']['vars']['data']);
		foreach($data as $row) {
			list($k, $v) = explode(':', $row);
			$this->vars[$k] = $v;
		}
	}

	function index_header() {return $this->vars['index_header'];}
	function index_hot() {return $this->vars['index_hot'];}
	function index_navbar() {return $this->vars['index_navbar'];}
	function index_top() {return $this->vars['index_top'];}
	function index_middle() {return $this->vars['index_middle'];}
	function index_bottom() {return $this->vars['index_bottom'];}

	function forumdisplay_header() {return $this->vars['forumdisplay_header'];}
	function forumdisplay_forumaction() {return $this->vars['forumdisplay_forumaction'];}
	function forumdisplay_modlink() {return $this->vars['forumdisplay_modlink'];}
	function forumdisplay_top() {return $this->vars['forumdisplay_top'];}
	function forumdisplay_middle() {return $this->vars['forumdisplay_middle'];}
	function forumdisplay_thread() {return array($this->vars['forumdisplay_thread']);}
	function forumdisplay_bottom() {return $this->vars['forumdisplay_bottom'];}

	function memcp_side() {return $this->vars['memcp_side'];}

	function profile_baseinfo_top() {return $this->vars['profile_baseinfo_top'];}
	function profile_baseinfo_bottom() {return $this->vars['profile_baseinfo_bottom'];}
	function profile_extrainfo() {return $this->vars['profile_extrainfo'];}
	function profile_side_top() {return $this->vars['profile_side_top'];}
	function profile_side_bottom() {return $this->vars['profile_side_bottom'];}

	function viewthread_top() {return $this->vars['viewthread_top'];}
	function viewthread_fastpost_side() {return $this->vars['viewthread_fastpost_side'];}
	function viewthread_fastpost_content() {return $this->vars['viewthread_fastpost_content'];}
	function viewthread_profileside() {return array($this->vars['viewthread_profileside']);}
	function viewthread_imicons() {return array($this->vars['viewthread_imicons']);}
	function viewthread_sidetop() {return array($this->vars['viewthread_sidetop']);}
	function viewthread_sidebottom() {return array($this->vars['viewthread_sidebottom']);}
	function viewthread_postheader() {return array($this->vars['viewthread_postheader']);}
	function viewthread_posttop() {return array($this->vars['viewthread_posttop']);}
	function viewthread_postbottom() {return array($this->vars['viewthread_postbottom']);}
	function viewthread_useraction() {return $this->vars['viewthread_useraction'];}
	function viewthread_postfooter() {return array($this->vars['viewthread_postfooter']);}
	function viewthread_endline() {return array($this->vars['viewthread_endline']);}
	function viewthread_middle() {return $this->vars['viewthread_middle'];}
	function viewthread_bottom() {return $this->vars['viewthread_bottom'];}
	
	function post_top() {return $this->vars['post_top'];}
	function post_middle() {return $this->vars['post_middle'];}
	function post_bottom() {return $this->vars['post_bottom'];}
	
	function my_navextra() {return $this->vars['my_navextra'];}
	
	function memcp_profilesettings() {return $this->vars['memcp_profilesettings'];}	

	function global_header() {return $this->vars['global_header'];}
	function global_footer() {return $this->vars['global_footer'];}
	function global_footerlink() {return $this->vars['global_footerlink'];}

}

?>
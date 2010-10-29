<?php

class plugin_fastreplylogin {
	
	function viewthread_fastpost_side() {		
		if(!$GLOBALS['discuz_uid']) {
			$return = '<dl id="fastreplylogin" class="profile">用户名<br><input name="username" size="15"><br>密码<br><input name="password" size="15"></dl>';
		}
		return $return;
	}
	
	function post_fastreplylogin() {
		if($_GET['action'] == 'reply' && $_POST['username']) {
			require_once DISCUZ_ROOT.'./include/login.func.php';
			$result = userlogin();
			if($result <= 0) {
				showmessage('登录失败，请重试。');
			}
			$GLOBALS['formhash'] = formhash();
		}
	}

}

?>
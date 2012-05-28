<?php

	
	/**
	 * Update array from AJAX call in todolist.php
	 *
	 * LICENSE: TheAlliance.be 2012 (c)
	 *
	 * @author     Bart Stassen <bart@thealliance.be>
	 * @copyright  2012 The Alliance
	 * @project    zCode
	 */
	 
	 
	$updDate = $_POST['date'];
	$updTask = $_POST['task'];
	$updKey  = $_POST['key'];
	$action  = $_POST['action'];

	if (isset($updKey)) {
				$taskArr = getCookieArr($_COOKIE['ztasks']);
			
				switch($action) {
					case "update":
						$taskArr[$updKey]['date'] = $updDate;
						$taskArr[$updKey]['task'] = ucfirst(stripslashes($updTask)); // clean up and capitalize new title
						break;
						
					case "delete":
						unset($taskArr[$updKey]);
						break;
					
				}
						
				// store new array to cookie
				$expire = 60 * 60 * 24 * 60 + time(); 
				setcookie('ztasks', serialize($taskArr), $expire); 
				$taskArr = getCookieArr($_COOKIE['ztasks']);
				
	}
	
	function getCookieArr($cookie) {
		return get_magic_quotes_gpc() ? unserialize(stripslashes($cookie)) : unserialize($cookie);
	}
	?>
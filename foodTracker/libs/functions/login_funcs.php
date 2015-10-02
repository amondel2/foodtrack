<?php
function requries_login() {
	if(!is_loged_in())
	{
		header('Location: ' . WEB_BASE_COMMON . 'index.php');
		die();
	}	
}
function is_loged_in() {
	return $_SESSION &&	$_SESSION['login_id'] && strlen($_SESSION['login_id']) > 0;
}
?>
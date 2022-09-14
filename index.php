<?php
	session_start();
	if(!isset($_SESSION['lang']))
	{
		$_SESSION['lang'] = 'fr';
	}
	$main_path = dirname(__FILE__) . '/web/index.php';
	require_once $main_path;
?>

<?php 
	date_default_timezone_set('Asia/Kolkata');
	header('Content-Type: application/json');
	if(session_status() == PHP_SESSION_NONE)
		session_start();
	
?>
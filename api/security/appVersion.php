<?php
	$responseMap = array();
	$responseMap['status'] = 'success';
	$responseMap['statusCode'] = 200;
	$message = array();
	$message['version'] = 1;
	$responseMap['response'] = $message;
	return json_encode($responseMap);
?>
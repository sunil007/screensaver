<?php
	$responseMap = array();
	$responseMap['status'] = 'success';
	$responseMap['statusCode'] = 200;
	$message = array();
	$message['version'] = 1;
	$message['url'] = "https://play.google.com/store/apps/details?id=in.matrixedu.testseries";
	$responseMap['response'] = $message;
	echo json_encode($responseMap);
?>
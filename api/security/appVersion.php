<?php
	$responseMap = array();
	$responseMap['status'] = 'success';
	$responseMap['statusCode'] = 200;
	$message = array();
	$message['version'] = 8;
	$message['minVersion'] = 8;
	$message['url'] = "https://play.google.com/store/apps/details?id=com.onesecurepvt.onesecureapp";
	$responseMap['response'] = $message;
	echo json_encode($responseMap);
?>

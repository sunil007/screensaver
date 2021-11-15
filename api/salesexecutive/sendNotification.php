<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/salesexecutive_security.php";
	include_once __DIR__."/../../model/entity.php";
	include_once __DIR__."/../../model/login.php";
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];

	$userId = Token::getTokenUserId($token, $mobile);
	$salesExecutive = SalesExecutive::getSalesExecutiveById($userId);
	if(!SalesExecutive::isSalesExecutiveValid($salesExecutive)){
		echo Response::getFailureResponse(null, 422);exit(0);
	}
	if(isset($_POST['title']) && isset($_POST['body']) && isset($_POST['type'])){
		$title = $_POST['title'];
		$body = $_POST['body'];
		$type = $_POST['type'];

$response  = FireBaseNotification::SendNotificationToUser(null, $type, $title,$body);
$arr = json_decode($response, true);

//echo $response;

$responseNum = 200;
if($arr["success"] == 0)
 $responseNum = 430;
else
 $responseNum = 200;

		if($responseNum == 200)
			echo Response::getSuccessResponse(null, 200);
		else
			echo Response::getFailureResponse(null, $responseNum);
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>

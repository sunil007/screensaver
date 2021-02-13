<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/salesman_security.php";
	include_once __DIR__."/../../model/entity.php";
		
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$salesMan = SalesMan::getSalesManById($userId);
	if(!SalesMan::isSalesManValid($salesMan)){
		echo Response::getFailureResponse(null, 421);exit(0);
	}
	
	if($salesMan){
		if($salesMan->status == 1){
			echo Response::getSuccessResponse($salesMan->toMapObject(), 200);
		}else if($salesMan->status == -1){
			echo Response::getFailureResponse(null, 408);
		}else{
			echo Response::getFailureResponse(null, 410);
		}
	}else{
		echo Response::getFailureResponse(null, 407);
	}
?>
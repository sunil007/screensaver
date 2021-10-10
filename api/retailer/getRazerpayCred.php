<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/retailer_security.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/Retailer.php";
	include_once __DIR__."/../../model/entity.php";
	
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);

	$user = Retailer::getRetailerById($userId);
	if($user){
		if($user->status == 1){
			$credMap = Securevault::getByNames(Securevault::$RAZER_PAY_ID.','.Securevault::$RAZER_PAY_SECRET);
			$map = array();
			$map['id'] = $credMap[Securevault::$RAZER_PAY_ID]->value;
			$map['secret'] = $credMap[Securevault::$RAZER_PAY_SECRET]->value;
			echo Response::getSuccessResponse($map, 200);
		}else if($user->status == -1){
			echo Response::getFailureResponse(null, 408);
		}else{
			echo Response::getFailureResponse(null, 410);
		}
	}else{
		echo Response::getFailureResponse(null, 407);
	}
?>

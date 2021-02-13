<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/token_security.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
	include_once __DIR__."/../../model/Policy.php";
	
	if(!isset($_POST['policyId'])){
		echo Response::getFailureResponse(null, 409);exit(0);
	}
	
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$user = User::getUserById($userId);
	if($user){
		if($user->status == 1){
			$userPolicy = Policy::getPolicyByIdAndUserId($_POST['policyId'], $user->id);
			if($userPolicy)
				echo Response::getSuccessResponse($userPolicy->toMapObject(), 200);
			else
				echo Response::getFailureResponse(null, 415);
		}else{
			echo Response::getFailureResponse(null, 408);
		}
	}else{
		echo Response::getFailureResponse(null, 407);
	}	
?>
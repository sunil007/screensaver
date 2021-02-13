<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/retailer_security.php";
	include_once __DIR__."/../../model/entity.php";
		
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$retailer = Retailer::getRetailerById($userId);	
	if(!Retailer::isRetailerValid($retailer)){
		echo Response::getFailureResponse(null, 420);exit(0);
	}
		
	if(isset($_POST['userid']) && is_numeric($_POST['userid']) &&  isset($_POST['policyId'])){
		
		//$salesPerson = User::getUserByMobileNumber($mobile);
		
		$policyUser =  User::getUserById($_POST['userid']);
		if(!$policyUser || $policyUser->status != 1){
			echo Response::getFailureResponse(null, 419);
		    exit(0);
		}
		$policy = Policy::getPolicyByIdAndUserId($_POST['policyId'], $policyUser->id);
		
		if($policy->retailerId != $retailer->id){
			echo Response::getFailureResponse(null, 416);
		    exit(0);
		}
		
		
		if($policy){
			echo Response::getSuccessResponse($policy->toMapObject(), 200);
		}else{
			echo Response::getFailureResponse(null, 415);
		}
		
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
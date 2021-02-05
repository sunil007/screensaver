<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/salesp_security.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
	include_once __DIR__."/../../model/Policy.php";
		
	if(
		isset($_POST['userMobile']) && is_numeric($_POST['userMobile']) &&  isset($_POST['policyId']) 
	){
		
		//$salesPerson = User::getUserByMobileNumber($mobile);
		$policyUser = User::getUserByMobileNumber($_POST['userMobile']);
		if(!$policyUser && $policyUser->status == 1){
			echo Response::getFailureResponse($policy->toMapObject(), 407);
		    exit(0);
		}
		$policy = Policy::getPolicyByIdAndUserId($_POST['policyId'], $policyUser->id);
		if($policy){
			echo Response::getSuccessResponse($policy->toMapObject(), 200);
		}else{
			echo Response::getFailureResponse(null, 415);
		}
		
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
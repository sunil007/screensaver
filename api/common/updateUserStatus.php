<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/sales_security.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
		
	if(
		isset($_POST['userMobile']) && is_numeric($_POST['userMobile']) &&
		isset($_POST['status']) && is_numeric($_POST['status']) && ($_POST['status'] == 0 || $_POST['status'] == 1 || $_POST['status'] == -1)
	){
		$mobile = $_POST['mobile'];	
		$userMobileNumber = $_POST['userMobile'];
		$status = $_POST['status'];
		
		
		$currentUser = User::getUserByMobileNumber($mobile);
		$userObject = User::getUserByMobileNumber($userMobileNumber);
			
		/*Validating Current User*/
		if(!$currentUser){
			echo Response::getFailureResponse(null, 409);
			exit(0);
		}
		if(!$userObject || $userObject->managerId != $currentUser->id){
			echo Response::getFailureResponse(null, 416);
			exit(0);
		}
		User::updateUserStatus($userObject->id, $status);
		echo Response::getSuccessResponse(null, 200);
		
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
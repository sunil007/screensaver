<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/sales_security.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
	include_once __DIR__."/../../model/Utility.php";
		
	if(
		isset($_POST['userMobile']) && is_numeric($_POST['userMobile'])
	){
		$mobile = $_POST['mobile'];	
		$userMobileNumber = $_POST['userMobile'];
		
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
		
		$profileImagePath = Utility::uploadFile($_FILE);
		if($profileImagePath){
			User::updateUserProfileImage($userObject->id, $profileImagePath);
			echo Response::getSuccessResponse(null, 200);
		}else{
			echo Response::getFailureResponse(null, 414);
		}
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
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
	
	if(
		isset($_POST['userMobile']) && is_numeric($_POST['userMobile']) &&
		isset($_POST['userId']) && is_numeric($_POST['userId']) && 
		isset($_POST['userName']) && 
		isset($_POST['userAddressLine1']) && 
		isset($_POST['userAddressLine2']) && 
		isset($_POST['userCity']) && 
		isset($_POST['userState']) && 
		isset($_POST['userPincode']) && 
		isset($_POST['userAadhar'])
	){
		
		$userId = $_POST['userId'];
		$userName = $_POST['userName'];
		$userAddressLine1 = $_POST['userAddressLine1'];
		$userAddressLine2 = $_POST['userAddressLine2'];
		$userCity = $_POST['userCity'];
		$userState = $_POST['userState'];
		$userPincode = $_POST['userPincode'];
		$userAadhar = $_POST['userAadhar'];
		$mobile = $_POST['mobile'];	
		$userMobileNumber = $_POST['userMobile'];
		$email = isset($_POST['email'])?$_POST['email']:"";
		
		$userObject = User::getUserById($userId);
		if(!$userObject || $userObject->status != 0){
			echo Response::getFailureResponse(null, 416);
			exit(0);
		}
		
		User::updateUserDetails($userObject->id, $userName, $email, $userAddressLine1, $userAddressLine2, $userCity, $userState, $userPincode, $userAadhar, 0);
		echo Response::getSuccessResponse(null, 200);
		
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
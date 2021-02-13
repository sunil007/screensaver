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
	
	if(isset($_POST['userid']) && is_numeric($_POST['userid'])){
		
		$userObject = User::getUserById($_POST['userid']);
		if(!$userObject || $userObject->status != 0){
			echo Response::getFailureResponse(null, 416);
			exit(0);
		}
		
		$profileImagePath = Utility::uploadFile($_FILES, "user/aadhar/".$userObject->id."/");
		if($profileImagePath){
			User::updateUserAadharImage($userObject->id, $profileImagePath);
			echo Response::getSuccessResponse(null, 200);
		}else{
			echo Response::getFailureResponse(null, 414);
		}
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
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
	if(isset($_POST['userMobile']) && is_numeric($_POST['userMobile'])){
		$userMobileNumber = $_POST['userMobile'];
		$userObject = User::getUserByMobileNumber($userMobileNumber);
		if($userObject){
			if($userObject->status == 1){
				$map = array();
				$map['userId'] = $userObject->id;
				$map['mobile'] = $userObject->mobile;
				$map['status'] = $userObject->status;
				$map['type'] = $userObject->type;
				echo Response::getSuccessResponse($map, 200);
			}else if($userObject->status == 1){
				echo Response::getFailureResponse(null, 408);
			}else{
				echo Response::getSuccessResponse(null, 201);
			}
		}else{
			echo Response::getSuccessResponse(null, 201);
		}
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
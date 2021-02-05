<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/salesp_security.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
		
	if(isset($_POST['userMobile']) && is_numeric($_POST['userMobile'])){
		
		$mobile = $_POST['mobile'];
		$userMobileNumber = $_POST['userMobile'];
		$userObject = User::getUserByMobileNumber($userMobileNumber);
		if($userObject){
			if($userObject->status == 1){
				$map = array();
				$map['userId'] = $userObject->id;
				$map['mobile'] = $userObject->mobile;
				$map['status'] = $userObject->status;
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
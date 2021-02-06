<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/sales_security.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
		
	if(isset($_POST['userMobile']) && is_numeric($_POST['userMobile']) && isset($_POST['userotp']) && is_numeric($_POST['userotp']) && isset($_POST['userTimestamp'])){
		
		$mobile = $_POST['mobile'];	
		$userMobileNumber = $_POST['userMobile'];
		$userOTP = $_POST['userotp'];
		$userTimestamp = $_POST['userTimestamp'];
		$isValidOtp = OTP::validateOTP($userMobileNumber, $userTimestamp, $userOTP);
		
		if($isValidOtp){
			$currentUser = User::getUserByMobileNumber($mobile);
			$userObject = User::getUserByMobileNumber($userMobileNumber);
			
			/*Validating Current User*/
			if(!$currentUser){
				echo Response::getFailureResponse(null, 409);
				exit(0);
			}
			
			if($userObject){
				if($userObject->status == 0){
					$map = array();
					$map['userId'] = $userObject->id;
					$map['userType'] = $userObject->type;
					echo Response::getSuccessResponse($map, 200);
				}else{
					echo Response::getFailureResponse(null, 411);
				}
			}else{
				$currentUseType = $currentUser->type;
				$newUserType = "UNKNOWN";
				$userid = -1;
				
				if($currentUseType == 'STATE_HEAD'){
					$userid = User::createNewDistictHead($userMobileNumber, $currentUser->id);
					$newUserType = "DISTRICT_HEAD";
				}
				else if($currentUseType == 'DISTRICT_HEAD'){
					$userid = User::createNewDistributer($userMobileNumber, $currentUser->id);
					$newUserType = "DISTRIBUTER";
				}
				else if($currentUseType == 'DISTRIBUTER'){
					$userid = User::createNewSalesExecutive($userMobileNumber, $currentUser->id);
					$newUserType = "SALESE";
				}
				else if($currentUseType == 'SALESE'){
					$userid = User::createNewSalesPerson($userMobileNumber, $currentUser->id);
					$newUserType = "SALESP";
				}
				else if($currentUseType == 'SALESP'){
					$userid = User::createNewUserEntry($userMobileNumber, $currentUser->id);
					$newUserType = "USER";
				}
				
				$map = array();
				$map['userId'] = $userid;
				$map['userType'] = $newUserType;
				echo Response::getSuccessResponse($map, 200);
			}
		}else{
			echo Response::getFailureResponse(null, 402);
		}
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/salesexecutive_security.php";
	include_once __DIR__."/../../model/entity.php";
		
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$salesExecutive = SalesExecutive::getSalesExecutiveById($userId);
	if(!SalesExecutive::isSalesExecutiveValid($salesExecutive)){
		echo Response::getFailureResponse(null, 422);exit(0);
	}
	
	if(isset($_POST['userMobile']) && is_numeric($_POST['userMobile']) && isset($_POST['userotp']) && is_numeric($_POST['userotp']) && isset($_POST['userTimestamp'])){
		
		$mobile = $_POST['mobile'];	
		$userMobileNumber = $_POST['userMobile'];
		$userOTP = $_POST['userotp'];
		$userTimestamp = $_POST['userTimestamp'];
		$isValidOtp = OTP::validateOTP($userMobileNumber, $userTimestamp, $userOTP);
		
		if($isValidOtp){
			
			$userObject = SalesMan::getSalesManByMobileNumber($userMobileNumber);

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

				$userid = SalesMan::createNewSalesManEntry($userMobileNumber, $salesExecutive->id);
				$newUserType = "SALESMAN";
				
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
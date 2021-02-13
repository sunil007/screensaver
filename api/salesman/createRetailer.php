<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/salesman_security.php";
	include_once __DIR__."/../../model/entity.php";
		
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$salesMan = SalesMan::getSalesManById($userId);
	if(!SalesMan::isSalesManValid($salesMan)){
		echo Response::getFailureResponse(null, 421);exit(0);
	}
	
	if(isset($_POST['userMobile']) && is_numeric($_POST['userMobile']) && isset($_POST['userotp']) && is_numeric($_POST['userotp']) && isset($_POST['userTimestamp'])){
		
		$mobile = $_POST['mobile'];	
		$userMobileNumber = $_POST['userMobile'];
		$userOTP = $_POST['userotp'];
		$userTimestamp = $_POST['userTimestamp'];
		$isValidOtp = OTP::validateOTP($userMobileNumber, $userTimestamp, $userOTP);
		
		if($isValidOtp){
			
			$userObject = Retailer::getRetailerByMobileNumber($userMobileNumber);

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

				$userid = Retailer::createNewRetailerEntry($userMobileNumber, $salesMan->id);
				$newUserType = "RETAILER";
				
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
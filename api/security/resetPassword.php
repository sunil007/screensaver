<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
	include_once __DIR__."/../../model/Login.php";
		
	if(isset($_POST['mobile']) && isset($_POST['timeStamp']) && isset($_POST['otp']) && isset($_POST['newPassword'])){
		
		$isValidOtp = OTP::validateOTP($_POST['mobile'], $_POST['timeStamp'], $_POST['otp']);
		if($isValidOtp){
			$user = Login::isUserIdExist($_POST['mobile']);
			if($user){
				Login::updatePassword($_POST['mobile'], $_POST['newPassword']);
				echo Response::getSuccessResponse(null, 200);
			}else{
				echo Response::getFailureResponse(null, 407);
			}
		}else{
			echo Response::getFailureResponse(null, 402);
		}		
	}else{
		echo Response::getFailureResponse(null, 409);
	}
	
	
?>
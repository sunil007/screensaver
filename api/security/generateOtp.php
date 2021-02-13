<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
		
	if(isset($_POST['mobile']) && isset($_POST['timeStamp']) && isset($_POST['code']) && $_POST['code'] == 'IAMAJOKER'){
		$otp = OTP::generateOTP($_POST['mobile'], $_POST['timeStamp']);
		if($otp){
			echo Response::getSuccessResponse(null, 200);
		}else{
			echo Response::getFailureResponse(null, 401);
		}		
	}else{
		echo Response::getFailureResponse(null, 409);
	}
	
	
?>
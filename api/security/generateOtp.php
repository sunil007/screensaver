<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../../model/entity.php";
		
	if(isset($_POST['mobile']) && isset($_POST['timeStamp']) && isset($_POST['code']) && $_POST['code'] == 'IAMAJOKER'){
		$otp = OTP::generateOTP($_POST['mobile'], $_POST['timeStamp']);
		SMSSender::sendOTP($_POST['mobile'], $otp);
		if($otp){
			echo Response::getSuccessResponse(null, 200);
		}else{
			echo Response::getFailureResponse(null, 401);
		}		
	}else{
		echo Response::getFailureResponse(null, 409);
	}
	
	
?>
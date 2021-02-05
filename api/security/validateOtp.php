<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
		
	if(isset($_POST['mobile']) && isset($_POST['timeStamp']) && isset($_POST['otp'])){
		
		$isValidOtp = OTP::validateOTP($_POST['mobile'], $_POST['timeStamp'], $_POST['otp']);
		if($isValidOtp){
			$user = User::getUserByMobileNumber($_POST['mobile']);
			if($user){
				if($user->status == 1){
					$tokenMap = Token::generateToken($user->mobile, $user->id, $user->type);
					$message = array();
					$message['token'] = $tokenMap['TOKEN'];
					$message['refreshToken'] = $tokenMap['REFRESH'];
					$message['userId'] = $user->id;
					$message['type'] = $user->type;
					echo Response::getSuccessResponse($message, 200);
				}else{
					echo Response::getFailureResponse(null, 408);
				}
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
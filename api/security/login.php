<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
		
	if(isset($_POST['mobile']) && isset($_POST['token'])){
		
		$isValidToken = Token::validateToken($_POST['token'], $_POST['mobile']);
		if($isValidToken){
			$user = User::getUserByMobileNumber($_POST['mobile']);
			if($user){
				if($user->status == 1){
					$message = array();
					$message['userId'] = $user->id;
					$message['type'] = $user->type;
					echo Response::getSuccessResponse($message, 200);
				}else if($user->status == -1){
					echo Response::getFailureResponse(null, 408);
				}else{
					echo Response::getFailureResponse(null, 410);
				}
			}else{
				echo Response::getFailureResponse(null, 407);
			}
		}else{
			echo Response::getFailureResponse(null, 405);
		}		
	}else{
		echo Response::getFailureResponse(null, 409);
	}
?>
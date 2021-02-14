<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../../model/entity.php";
		
	if(isset($_POST['mobile']) && isset($_POST['token'])){
		
		$tokenUserType = Token::getTokenUserType($_POST['token'], $_POST['mobile']);
		$tokenUserId = Token::getTokenUserId($_POST['token'], $_POST['mobile']);
		if($tokenUserType == 'USER')
			$user = User::getUserById($tokenUserId);
		else if($tokenUserType == 'RETAILER')
			$user = Retailer::getRetailerById($tokenUserId);
		else if($tokenUserType == 'SALESMAN')
			$user = SalesMan::getSalesManById($tokenUserId);
		else if($tokenUserType == 'SALESEXECUTIVE')
			$user = SalesExecutive::getSalesExecutiveById($tokenUserId);
		else
			$user = false;
			
		if($user){
			if($user->status == 1){
				$token = Token::validateToken($_POST['token'], $user->mobile);
				if($token){
					$tokenMap = Token::generateToken($user->mobile, $user->id, $user->type);
					$message = array();
					$message['userId'] = $user->id;
					$message['type'] = $user->type;
					$message['mobile'] = $user->mobile;
					$message['token'] = $tokenMap['TOKEN'];
					$message['refreshToken'] = $tokenMap['REFRESH'];
					echo Response::getSuccessResponse($message, 200);
				}else{
					echo Response::getFailureResponse(null, 405);
				}
			}else if($user->status == -1){
				echo Response::getFailureResponse(null, 408);
			}else{
				echo Response::getFailureResponse(null, 410);
			}
		}else{
			echo Response::getFailureResponse(null, 407);
		}
			
	}else{
		echo Response::getFailureResponse(null, 409);
	}
?>
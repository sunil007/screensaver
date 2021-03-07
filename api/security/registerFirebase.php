<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../../model/entity.php";
		
	if(isset($_POST['mobile']) && isset($_POST['token']) && isset($_POST['firebaseId'])){
		
		$firebaseId = $_POST['firebaseId'];
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
		else if($tokenUserType == 'REVIEWER')
			$user = Reviewer::getReviewerById($tokenUserId);
		else
			$user = false;
			
		if($user){
			if($user->status == 1){
				$token = Token::validateToken($_POST['token'], $user->mobile);
				if($token){
					Login::updateFirebaseForMobileAndRefId($user->mobile, $user->id, $tokenUserType, $firebaseId);
					echo Response::getSuccessResponse(null, 200);
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
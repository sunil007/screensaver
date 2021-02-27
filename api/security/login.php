<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../../model/entity.php";
		
	if(isset($_POST['userid']) && isset($_POST['password'])){
		
		$loginUsers = Login::getUserByUserIdAndPassword($_POST['userid'], $_POST['password']);
		if($loginUsers && sizeof($loginUsers) > 0){
			
			$userArray = array();
			foreach($loginUsers as $loginUser){
				if($loginUser->type == 'USER')
					$user = User::getUserById($loginUser->ref_id);
				else if($loginUser->type == 'RETAILER')
					$user = Retailer::getRetailerById($loginUser->ref_id);
				else if($loginUser->type == 'SALESMAN')
					$user = SalesMan::getSalesManById($loginUser->ref_id);
				else if($loginUser->type == 'SALESEXECUTIVE')
					$user = SalesExecutive::getSalesExecutiveById($loginUser->ref_id);
				else if($loginUser->type == 'REVIEWER')
					$user = Reviewer::getReviewerById($loginUser->ref_id);
				else
					$user = false;
				if($user){
					$tokenMap = Token::generateToken($user->mobile, $user->id, $user->type);
					$message = array();
					$message['userId'] = $user->id;
					$message['mobile'] = $user->mobile;
					$message['type'] = $user->type;
					$message['status'] = $user->status;
					$message['token'] = $tokenMap['TOKEN'];
					$message['refreshToken'] = $tokenMap['REFRESH'];
					array_push($userArray, $message);
				}
			}
			if($userArray && sizeof($userArray) > 0){
				$details = array();
				$details['users'] = $userArray;
				echo Response::getSuccessResponse($details, 200);
			}else{
				echo Response::getFailureResponse(null, 407);
			}
		}else{
			echo Response::getFailureResponse(null, 403);
		}		
	}else{
		echo Response::getFailureResponse(null, 409);
	}
?>
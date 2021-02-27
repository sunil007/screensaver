<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/reviewer_security.php";
	include_once __DIR__."/../../model/entity.php";
	
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$reviewer = Reviewer::getReviewerById($userId);	
	if(!Reviewer::isReviewerValid($reviewer)){
		echo Response::getFailureResponse(null, 426);exit(0);
	}	
	
	if($reviewer){
		if($reviewer->status == 1){
			$userPolicyList = Policy::getAllPolicyForValidation($reviewer->id);
				
			$responseMap = array();
			$responseMap['policies'] = array();
			foreach($userPolicyList as $userPolicy){
				array_push($responseMap['policies'], $userPolicy->toMapObject());
			}
			echo Response::getSuccessResponse($responseMap, 200);
		}else{
			echo Response::getFailureResponse(null, 408);
		}
	}else{
		echo Response::getFailureResponse(null, 407);
	}
	
?>
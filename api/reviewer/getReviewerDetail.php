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
			echo Response::getSuccessResponse($reviewer->toMapObject(), 200);
		}else if($reviewer->status == -1){
			echo Response::getFailureResponse(null, 408);
		}else{
			echo Response::getFailureResponse(null, 410);
		}
	}else{
		echo Response::getFailureResponse(null, 407);
	}
?>
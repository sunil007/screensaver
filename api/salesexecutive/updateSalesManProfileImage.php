<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/salesexecutive_security.php";
	include_once __DIR__."/../../model/entity.php";
		
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$salesExecutive = SalesExecutive::getSalesExecutiveById($userId);
	if(!SalesExecutive::isSalesExecutiveValid($salesExecutive)){
		echo Response::getFailureResponse(null, 421);exit(0);
	}	
	
	if(isset($_POST['userid']) && is_numeric($_POST['userid'])){
		
		$userObject = SalesMan::getSalesManById($_POST['userid']);
		if(!$userObject || $userObject->status != 0){
			echo Response::getFailureResponse(null, 416);
			exit(0);
		}
		
		$profileImagePath = Utility::uploadFile($_FILES, "salesman/profile/".$userObject->id."/");
		if($profileImagePath){
			SalesMan::updateSalesManProfileImage($userObject->id, $profileImagePath);
			echo Response::getSuccessResponse(null, 200);
		}else{
			echo Response::getFailureResponse(null, 414);
		}
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
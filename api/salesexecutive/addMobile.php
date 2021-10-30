<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/salesexecutive_security.php";
	include_once __DIR__."/../../model/entity.php";
		
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$salesExecutive = SalesExecutive::getSalesExecutiveById($userId);
	if(!SalesExecutive::isSalesExecutiveValid($salesExecutive)){
		echo Response::getFailureResponse(null, 422);exit(0);
	}
	
	if(isset($_POST['company']) && is_numeric($_POST['model']) && isset($_POST['price'])){
		
		$company = $_POST['company'];	
		$model = $_POST['model'];
		$price = $_POST['price'];
		$mobile = new Mobile();
		$responseNum = $mobile->addMobile($company, $model, $price);
		if($responseNum == 200)
			echo Response::getSuccessResponse(null, 200);
		else
			echo Response::getFailureResponse(null, $responseNum);
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
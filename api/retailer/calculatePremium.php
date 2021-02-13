<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/retailer_security.php";
	include_once __DIR__."/../../model/entity.php";
		
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	//$retailer = Retailer::getRetailerById($userId);	
		
	if(isset($_POST['mobileCurrentPrice']) && is_numeric($_POST['mobileCurrentPrice']) && isset($_POST['mobileCompany']) && isset($_POST['mobileModel'])){
		
		$mobileCurrentPrice = $_POST['mobileCurrentPrice'];
		$mobileCompany = $_POST['mobileCompany'];
		$mobileModel = $_POST['mobileModel'];
		$policyPrice = Policy::calculatePrice($mobileCurrentPrice, $mobileCompany, $mobileModel);
		
		$map = array();
		$map['premium'] = $policyPrice;
		echo Response::getSuccessResponse($map, 200);
		
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/retailer_security.php";
	include_once __DIR__."/../../model/entity.php";
		
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$retailer = Retailer::getRetailerById($userId);	
	if(!Retailer::isRetailerValid($retailer)){
		echo Response::getFailureResponse(null, 420);exit(0);
	}	
	
	$startDate = null;
	$endDate = null;
	if(isset($_POST['startDate']))
		$startDate = new DateTime($_POST['startDate']);
	if(isset($_POST['endDate']))
		$endDate = new DateTime($_POST['endDate']);
	
	$salesStatus = $retailer->getSalesStatus($startDate, $endDate);
	
	$map = array();
	$map['status'] = $salesStatus;
	echo Response::getSuccessResponse($map, 200);
	
?>
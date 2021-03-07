<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/salesman_security.php";
	include_once __DIR__."/../../model/entity.php";
		
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$salesMan = SalesMan::getSalesManById($userId);
	if(!SalesMan::isSalesManValid($salesMan)){
		echo Response::getFailureResponse(null, 421);exit(0);
	}
	
	$startDate = null;
	$endDate = null;
	if(isset($_POST['startDate']))
		$startDate = new DateTime($_POST['startDate']." 00:00:00");
	if(isset($_POST['endDate']))
		$endDate = new DateTime($_POST['endDate']." 23:59:59");
	
	$map = SalesMan::getSalesStatus($startDate, $endDate, $salesMan->id);
	echo Response::getSuccessResponse($map, 200);
	

?>
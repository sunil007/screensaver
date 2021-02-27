<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/common_security.php";
	include_once __DIR__."/../../model/entity.php";
		
	if(!isset($_POST['company'])){
		echo Response::getFailureResponse(null, 409);exit(0);
	}
	
	$companyMap = Mobile::getMobileModel($_POST['company']);
	echo Response::getSuccessResponse($companyMap, 200);
?>
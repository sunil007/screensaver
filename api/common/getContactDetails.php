<?php
	include_once __DIR__."/../config/timezone.php";
	//include_once __DIR__."/../config/common_security.php";
	include_once __DIR__."/../../model/entity.php";
	
	$contactMap = array("phone"=>CompanyDetails::$contactPhone,"email"=>CompanyDetails::$contactEmail);
	echo Response::getSuccessResponse($contactMap, 200);
?>

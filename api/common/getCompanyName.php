<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/common_security.php";
	include_once __DIR__."/../../model/entity.php";
		
	$companyMap = Mobile::getCompanyList();
	echo Response::getSuccessResponse($companyMap, 200);
?>
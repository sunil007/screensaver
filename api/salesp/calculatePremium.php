<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/salesp_security.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
	include_once __DIR__."/../../model/Policy.php";
		
	if(isset($_POST['mobileCurrentPrice']) && is_numeric($_POST['mobileCurrentPrice']) && isset($_POST['mobileCompany']) && isset($_POST['mobileModel'])){
		
		$mobileCurrentPrice = $_POST['mobileCurrentPrice'];
		$mobileCompany = $_POST['mobileCompany'];
		$mobileModel = $_POST['mobileModel'];
		$policyPrice = Policy::calculatePrice($mobileCurrentPrice, $mobileCompany, $mobileModel);
		
		$map = array();
		$map['premium'] =$policyPrice;
		echo Response::getSuccessResponse($map, 200);
		
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
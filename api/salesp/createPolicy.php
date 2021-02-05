<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/salesp_security.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
	include_once __DIR__."/../../model/Policy.php";
		
	if(isset(
		$_POST['userMobile']) && is_numeric($_POST['userMobile']) && 
		isset($_POST['mobileIMEI']) && isset($_POST['mobileModel']) &&
		isset($_POST['mobileCompany']) && isset($_POST['mobileCurrentPrice']) && is_numeric($_POST['mobileCurrentPrice'])
	){
		
		$mobile = $_POST['mobile'];
		$userMobile = $_POST['userMobile'];
		$mobileIMEI = $_POST['mobileIMEI'];
		$mobileModel = $_POST['mobileModel'];
		$mobileCompany = $_POST['mobileCompany'];
		$mobileCurrentPrice = $_POST['mobileCurrentPrice'];
		
		$salesPerson = User::getUserByMobileNumber($mobile);
		$policyUser = User::getUserByMobileNumber($userMobile);
		if(!$salesPerson || $salesPerson->status != 1){
			echo Response::getFailureResponse(null, 412);
			exit(0);
		}
		if(!$policyUser || $policyUser->status != 1){
			echo Response::getFailureResponse(null, 407);
			exit(0);
		}
		
		$policyId = Policy::createNewPolicyEntry($policyUser->mobile, $policyUser->id, $mobileIMEI, $mobileModel, $mobileCompany, $mobileCurrentPrice, $salesPerson->id);
		if($policyId != false && $policyId > 0){
			$map = array();
			$map['policyId'] = $policyId;
			echo Response::getSuccessResponse($map, 200);
		}else{
			echo Response::getFailureResponse(null, 413);
		}
		
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
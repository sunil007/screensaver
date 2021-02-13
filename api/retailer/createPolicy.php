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
		
	if(isset(
		$_POST['userid']) && is_numeric($_POST['userid']) && 
		isset($_POST['mobileIMEI']) && isset($_POST['mobileModel']) &&
		isset($_POST['mobileCompany']) && isset($_POST['mobileCurrentPrice']) && is_numeric($_POST['mobileCurrentPrice'])
	){
		
		$userid = $_POST['userid'];
		$mobileIMEI = $_POST['mobileIMEI'];
		$mobileModel = $_POST['mobileModel'];
		$mobileCompany = $_POST['mobileCompany'];
		$mobileCurrentPrice = $_POST['mobileCurrentPrice'];
		
		$policyUser = User::getUserById($userid);
		if(!User::isUserValid($policyUser)){
			echo Response::getFailureResponse(null, 407);
			exit(0);
		}
		
		$policyId = Policy::createNewPolicyEntry($policyUser->mobile, $policyUser->id, $mobileIMEI, $mobileModel, $mobileCompany, $mobileCurrentPrice, $retailer->id);
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
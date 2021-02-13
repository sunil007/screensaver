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
	
	if(isset($_POST['userId']) && isset($_POST['policyId']) && is_numeric($_POST['policyId'])){
		
		$policy = Policy::getPolicyById($_POST['policyId']);
		if($policy->userId != $_POST['userId']){
			echo Response::getFailureResponse(null, 416);
			exit(0);
		}
		if($policy->retailerId != $retailer->id){
			echo Response::getFailureResponse(null, 416);
			exit(0);
		}
		if($policy->status != "InActive"){
			echo Response::getFailureResponse(null, 416);
			exit(0);
		}
		
		$mobileImagePath = Utility::uploadFile($_FILES, "policy/images/".$policy->id."/");
		if($mobileImagePath){
			Policy::updatePolicyMobileImage2($policy->id, $mobileImagePath);
			echo Response::getSuccessResponse(null, 200);
		}else{
			echo Response::getFailureResponse(null, 414);
		}
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
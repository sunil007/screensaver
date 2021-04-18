<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/retailer_security.php";
	include_once __DIR__."/../../model/entity.php";
		
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$retailer = Retailer::getRetailerById($userId);
	if(!$retailer){
		echo Response::getFailureResponse(null, 419);exit(0);
	}
	
	if($retailer){
		if(isset($_POST['ifsc']) && isset($_POST['acno'])){
			if($retailer->status == 1){
				Retailer::updateRetailerBankDetails($retailer->id, $_POST['acno'], $_POST['ifsc']);
				echo Response::getSuccessResponse(null, 200);
			}else if($retailer->status == -1){
				echo Response::getFailureResponse(null, 408);
			}else{
				echo Response::getFailureResponse(null, 410);
			}
		}else{
			echo Response::getFailureResponse(null, 409);
		}
	}else{
		echo Response::getFailureResponse(null, 407);
	}
?>
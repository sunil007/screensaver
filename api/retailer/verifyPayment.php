<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/retailer_security.php";
	include_once __DIR__."/../../model/entity.php";
	include_once __DIR__."/../../model/PolicyOrder.php";
	
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$retailer = Retailer::getRetailerById($userId);	
	if(!Retailer::isRetailerValid($retailer)){
		echo Response::getFailureResponse(null, 420);exit(0);
	}
	
	if(!isset($_POST['policyId']) || !isset($_POST['orderId'])){
		echo Response::getFailureResponse(null, 409);exit(0);
	}
	
	if($retailer){
		if($retailer->status == 1){
			
			$policy = Policy::getPolicyById($_POST['policyId']);
			if($policy){
				if($policy->status == 'InActive'){
					$isPaymentDone = PolicyOrder::getPaymentStatusByOrderId($policy->id, $_POST['orderId']);
					if($isPaymentDone){
						/*Notification To Reviewer*/
						FireBaseNotification::SendNotificationToUser(null, "REVIEWER", "New AMC Available to Review","New AMC Available to Review.");
						echo Response::getSuccessResponse(null, 427);
					}else{
						echo Response::getFailureResponse(null, 428);
					}
				}else if($policy->status == 'Under-Review'){
					echo Response::getSuccessResponse(null, 427);
				}else{
					echo Response::getFailureResponse(null, 423);
				}
			}else{
				echo Response::getFailureResponse(null, 415);
			}			
		}else{
			echo Response::getFailureResponse(null, 408);
		}
	}else{
		echo Response::getFailureResponse(null, 420);
	}
	
?>
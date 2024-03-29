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
	
	if(!isset($_POST['policyId'])){
		echo Response::getFailureResponse(null, 409);exit(0);
	}
	
	if($retailer){
		if($retailer->status == 1){
			
			$policy = Policy::getPolicyById($_POST['policyId']);
			if($policy){
				if($policy->dateOfRegistration != null && $policy->status == 'InActive'){
					$currentTimeStamp = new DateTime();
					$paymentValidTill = $policy->dateOfRegistration;
					$paymentValidTill->add(new DateInterval(Policy::$paymentPeriodWIndow));
					//echo $paymentValidTill;
					if($currentTimeStamp > $paymentValidTill){
						//Policy is Laps
						Policy::lapsPolicy($policy->id, -1);
						echo Response::getFailureResponse(null, 423);
					}else{
						/*API GATWAY*/
						$gatewayOrderid = PolicyOrder::createNewPolicyOrderEntry($policy->id, $policy->policyTotalAmount, $retailer->type, $retailer->id);
						$retObj = array();
						$retObj['orderId'] = $gatewayOrderid;
						echo Response::getSuccessResponse($retObj, 200);
					}
				}else if($policy->status == 'Under-Review'){
					echo Response::getSuccessResponse(null, 425);
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
<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/reviewer_security.php";
	include_once __DIR__."/../../model/entity.php";
	include_once __DIR__."/../../model/PolicyOrder.php";
	
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$reviewer = Reviewer::getReviewerById($userId);	
	if(!Reviewer::isReviewerValid($reviewer)){
		echo Response::getFailureResponse(null, 426);exit(0);
	}
	
	if(isset($_POST['policyId']) && is_numeric($_POST['policyId']) && isset($_POST['action']) && ($_POST['action'] == 'ACTIVATE' || $_POST['action'] == 'REJECT')){
		if($reviewer){
			if($reviewer->status == 1){
				$userPolicy = Policy::getPolicyById($_POST['policyId']);
				if($userPolicy && $userPolicy->status == 'Under-Review' && $userPolicy->dateOfRegistration != null && $userPolicy->dateOfRegistration != ""){
					$policyRegistration = $userPolicy->dateOfRegistration;
					$policyRegistration->add(new DateInterval(Policy::$validationPeriodWindow));
					$currentTime = new DateTime();
					if($policyRegistration > $currentTime){
						if($_POST['action'] == 'ACTIVATE'){
							Policy::activatePolicy($_POST['policyId'], $reviewer->id);
							
							/*Adding Commision to Retailer*/
							$retailerComission = Policy::calculateRetailerCommission($userPolicy->policyPrice);
							Wallet::updateBalance($userPolicy->retailerId, 'RETAILER', $retailerComission, "Policy ".$userPolicy->id." Commission");
							FireBaseNotification::SendNotificationToUser($userPolicy->userId, "USER", "AMC Status - Activation","Your AMC number ".$userPolicy->id." is activated.");
						}
						else if($_POST['action'] == 'REJECT'){
							Policy::rejectPolicy($_POST['policyId'], $reviewer->id);
							FireBaseNotification::SendNotificationToUser($userPolicy->userId, "USER", "AMC Status - Rejection","Your AMC number ".$userPolicy->id." is rejected and you amount refund is initiated.");
							PolicyOrder::initiateRefund($_POST['policyId'], Policy::$REJECT_REFUND_PERCENT);
							/*TODO : Refund Initiation*/
						}
						echo Response::getSuccessResponse(null, 200);
					}else{
						Policy::refundNotInitiatedPolicy($_POST['policyId']);
						PolicyOrder::initiateRefund($_POST['policyId'], Policy::$LAPS_REFUND_PERCENT);
						Policy::lapsPolicy($_POST['policyId']);
						echo Response::getFailureResponse(null, 417);
					}
				}else{
					echo Response::getFailureResponse(null, 415);
				}
			}else{
				echo Response::getFailureResponse(null, 408);
			}
		}else{
			echo Response::getFailureResponse(null, 407);
		}
	}else{
		echo Response::getFailureResponse(null, 409);
	}
?>
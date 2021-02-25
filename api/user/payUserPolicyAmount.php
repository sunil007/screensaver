<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/token_security.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
	include_once __DIR__."/../../model/Policy.php";
	
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$user = User::getUserById($userId);
	
	if(!isset($_POST['policyId'])){
		echo Response::getFailureResponse(null, 409);exit(0);
	}
	
	if($user){
		if($user->status == 1){
			
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
						Policy::underReviewPolicy($policy->id, -1);
						echo Response::getSuccessResponse(null, 200);
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
		echo Response::getFailureResponse(null, 407);
	}
	
?>
<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/reviewer_security.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
	include_once __DIR__."/../../model/Policy.php";
	
	$mobile = $_POST['mobile'];
	
	if(isset($_POST['policyId']) && is_numeric($_POST['policyId'])){
		$user = User::getUserByMobileNumber($mobile);
		if($user){
			if($user->status == 1){
				$userPolicy = Policy::getPolicyById($_POST['policyId']);
				if($userPolicy && $userPolicy->status == 'InActive' && $polict->dateOfRegistration != null && $polict->dateOfRegistration != ""){
					$policyRegistration = $policy->dateOfRegistration;
					$policyRegistration->add(new DateInterval(Policy::$validationPeriodWindow));
					$currentTime = new DateTime();
					if($policyRegistration > $currentTime){
						Policy::validatePolicy($_POST['policyId'], $user->id);
						echo Response::getSuccessResponse(null, 200);
					}else{
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
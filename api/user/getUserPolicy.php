<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/token_security.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
	include_once __DIR__."/../../model/Policy.php";
	
	$mobile = $_POST['mobile'];
	
	if(isset($_POST['policyId'])){
		$user = User::getUserByMobileNumber($mobile);
		if($user){
			if($user->status == 1){
				$userPolicy = Policy::getPolicyByIdAndUserId($_POST['policyId'], $user->id);
				echo Response::getSuccessResponse($userPolicy->toMapObject(), 200);
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
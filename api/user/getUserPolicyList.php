<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/token_security.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
	include_once __DIR__."/../../model/Policy.php";
	
	$mobile = $_POST['mobile'];
	
	$user = User::getUserByMobileNumber($mobile);
	if($user){
		if($user->status == 1){
			$showAll = (isset($_POST['showAll']) && $_POST['showAll'] == 'Y')?true:false;
			
			if($showAll)
				$userPolicyList = Policy::getPolicyByUserid($user->id);
			else
				$userPolicyList = Policy::getPolicyByUseridExcludeExpired($user->id);
				
			$responseMap = array();
			$responseMap['policies'] = array();
			foreach($userPolicyList as $userPolicy){
				array_push($responseMap['policies'], $userPolicy->toMapObject());
			}
			echo Response::getSuccessResponse($responseMap, 200);
		}else{
			echo Response::getFailureResponse(null, 408);
		}
	}else{
		echo Response::getFailureResponse(null, 407);
	}
	
?>
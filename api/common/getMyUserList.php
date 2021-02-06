<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/token_security.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
		
	$mobile = $_POST['mobile'];
	$user = User::getUserByMobileNumber($mobile);
	if($user){
		if($user->status == 1){
			$users = User::getUsersByManagerId($user->id);
			$map = array();
			$map['users'] = array();
			foreach($users as $user){
				$mapElement = array();
				$mapElement['id'] = $user->id;
				$mapElement['name'] = $user->name;
				$mapElement['phone'] = 'XXXX-XXX-'.substr($user->mobile, -3);
				$mapElement['status'] = $user->status;
				$mapElement['statusName'] = $user->statusName;
				array_push($map['users'], $mapElement);
			}
			echo Response::getSuccessResponse($map, 200);
		}else if($user->status == -1){
			echo Response::getFailureResponse(null, 408);
		}else{
			echo Response::getFailureResponse(null, 410);
		}
	}else{
		echo Response::getFailureResponse(null, 407);
	}

?>
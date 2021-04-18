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
	
	if(isset($_POST['userid']) && is_numeric($_POST['userid'])){
		
		$userObject = User::getUserById($_POST['userid']);
		if(!$userObject || $userObject->status != 0){
			echo Response::getFailureResponse(null, 416);
			exit(0);
		}
		User::updateUserStatus($userObject->id, '1');
		$loginDetail = Login::getLoginByRefIdAndType($userObject->id, $userObject->type);
		$responsemap = array();
		if($loginDetail && sizeof($loginDetail) > 0){
			$responsemap['loginid'] = $loginDetail[0]->userid;
			$responsemap['password'] = $loginDetail[0]->password;
		}
		
		echo Response::getSuccessResponse($responsemap, 200);
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
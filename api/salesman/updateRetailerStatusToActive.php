<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/salesman_security.php";
	include_once __DIR__."/../../model/entity.php";
		
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$salesMan = SalesMan::getSalesManById($userId);
	if(!SalesMan::isSalesManValid($salesMan)){
		echo Response::getFailureResponse(null, 421);exit(0);
	}
	
	if(isset($_POST['userid']) && is_numeric($_POST['userid'])){
		
		$userObject = Retailer::getRetailerById($_POST['userid']);
		if(!$userObject || $userObject->status != 0){
			echo Response::getFailureResponse(null, 416);
			exit(0);
		}
		Retailer::updateRetailerStatus($userObject->id, '1');
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
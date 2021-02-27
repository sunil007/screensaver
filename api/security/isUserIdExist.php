<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../../model/entity.php";
		
	if(isset($_POST['userid'])){
		
		$loginUsersExist = Login::isUserIdExist($_POST['userid']);
		if($loginUsersExist){
			echo Response::getFailureResponse(null, 200);
		}else{
			echo Response::getFailureResponse(null, 407);
		}		
	}else{
		echo Response::getFailureResponse(null, 409);
	}
?>
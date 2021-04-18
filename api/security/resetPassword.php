<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
	include_once __DIR__."/../../model/Login.php";
		
	if(isset($_POST['mobile']) && isset($_POST['userid']) && isset($_POST['type']) && isset($_POST['currentPassword']) && isset($_POST['newPassword'])){
		$logins = Login::getLoginByRefIdAndType($_POST['userid'], $_POST['type']);
		$isDone = false;
		foreach($logins as $login){
			if($login->userid == $_POST['mobile'] && $login->password == $_POST['currentPassword']){
				Login::updatePasswordById($login->id, $_POST['newPassword']);
				$isDone = true;
			}
		}
		if($isDone)
			echo Response::getSuccessResponse(null, 200);		
		else
			echo Response::getFailureResponse(null, 429);
	}else{
		echo Response::getFailureResponse(null, 409);
	}
	
	
?>
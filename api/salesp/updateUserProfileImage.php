<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/salesp_security.php";
	include_once __DIR__."/../../model/OTP.php";
	include_once __DIR__."/../../model/Token.php";
	include_once __DIR__."/../../model/Response.php";
	include_once __DIR__."/../../model/User.php";
	include_once __DIR__."/../../model/Utility.php";
		
	if(
		isset($_POST['userMobile']) && is_numeric($_POST['userMobile'])
	){
		$user = User::getUserByMobileNumber($_POST['userMobile']);
		if(!$user || $user->status != 1){
			echo Response::getFailureResponse(null, 407);
		}
		$currentTime = new DateTime();
		$target_dir = __DIR__."/../../profile/".$currentTime->format("YmdHis");
		$webtarget_dir = "profile/".$currentTime->format("YmdHis");
		$target_file = $target_dir . basename($_FILES["profileImage"]["name"]);
		$webtarget_file = $webtarget_dir . basename($_FILES["profileImage"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		$check = getimagesize($_FILES["profileImage"]["tmp_name"]);
		if($check !== false) {
			if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file)) {
				$uploadOk = 1;
			}
		} else {
			$uploadOk = 0;
		}
		
		if($uploadOk){
			$profileImagePath = Utility::$webAssetPrefex.$webtarget_file;
			User::updateUserProfileImage($user->id, $profileImagePath);
			echo Response::getSuccessResponse(null, 200);
		}else{
			echo Response::getFailureResponse(null, 414);
		}
	}else{
		echo Response::getFailureResponse(null, 409);
	}

?>
<?php
	class Utility{
		
		public static $webAssetPrefex = "http://test.classmatrix.in/screen-saver/";
		public static function clean($string){
			$string = str_replace("'","",$string);
			$string = str_replace("=","",$string);
			return $string;
		}
		
		public static function uploadFile($fileObject){
			
			$currentTime = new DateTime();
			$target_dir = __DIR__."/../../profile/".$currentTime->format("YmdHis");
			$webtarget_dir = "profile/".$currentTime->format("YmdHis");
			$target_file = $target_dir . basename($fileObject["profileImage"]["name"]);
			$webtarget_file = $webtarget_dir . basename($fileObject["profileImage"]["name"]);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

			$check = getimagesize($fileObject["profileImage"]["tmp_name"]);
			if($check !== false){
				if (move_uploaded_file($fileObject["profileImage"]["tmp_name"], $target_file)) {
					$uploadOk = 1;
				}
			}else{
				$uploadOk = 0;
			}
			
			if($uploadOk){
				$profileImagePath = Utility::$webAssetPrefex.$webtarget_file;
				return $profileImagePath;
			}else{
				return false;
			}
		}
		
	}


?>
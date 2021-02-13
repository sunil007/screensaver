<?php
	class Utility{
		
		public static $webAssetPrefex = "http://onesecure.in/";
		public static function clean($string){
			$string = str_replace("'","",$string);
			$string = str_replace("=","",$string);
			return $string;
		}
		
		public static function uploadFile($fileObject, $subFolder){
			
			$currentTime = new DateTime();
			$target_dir = __DIR__."/../assets/".$subFolder;
			$webtarget_dir = "assets/".$subFolder;
			
			if (!file_exists($target_dir)) {
				mkdir($target_dir, 0755, true);
			}
			
			$target_file = $target_dir .$currentTime->format("YmdHis"). basename($fileObject["uploadFile"]["name"]);
			$webtarget_file = $webtarget_dir .$currentTime->format("YmdHis"). basename($fileObject["uploadFile"]["name"]);
			$uploadOk = 1;
			//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			
			$fileSize = $_FILES['uploadFile']['size'];
			if($fileSize < 104857600){
				if (move_uploaded_file($fileObject["uploadFile"]["tmp_name"], $target_file)) {
					$uploadOk = 1;
				}
			}else{
				$uploadOk = 0;
			}
			
			if($uploadOk){
				$profileImagePath = $webtarget_file;
				return $profileImagePath;
			}else{
				return false;
			}
		}
		
	}


?>
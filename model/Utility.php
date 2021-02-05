<?php
	class Utility{
		
		public static $webAssetPrefex = "http://test.classmatrix.in/screen-saver/";
		public static function clean($string){
			$string = str_replace("'","",$string);
			$string = str_replace("=","",$string);
			return $string;
		}
		
	}


?>
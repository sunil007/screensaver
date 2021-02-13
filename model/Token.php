<?php
	class Token{
		
		public static $tokenSalt = "THIS IS A RANDOM SALT STIRNG FOR TOKEN";
		public static $refreshTokenSalt = "THIS IS A RANDOM REFRESH TOKEN";
		//public static $tokenValidity = 'PT15M';TODO
		public static $tokenValidity = 'PT1500000000M';
		public static $tokenRefreshValidity = 'PT10080M';
		
		public static function generateToken($mobile, $userId, $userType){
			$tokenMap = array();
			$timeStamp = (new DateTime())->format("Y-m-d H:i:s");
			
			$tokenString = hash("SHA256", $mobile.$userId.$userType.$timeStamp.Token::$tokenSalt).".".$mobile.".".$userId.".".$userType.".".$timeStamp;
			$refreshTokenString = hash("SHA256", $tokenString.$timeStamp.Token::$refreshTokenSalt).".".$mobile.".".$userId.".".$userType.".".$timeStamp;
			$tokenMap['TOKEN'] = $tokenString;
			$tokenMap['REFRESH'] = $refreshTokenString;
			return $tokenMap;
		}
		
		public static function validateToken($tokenString, $mobileObtained){
			$tokenArray = explode(".",$tokenString);
			if(sizeof($tokenArray) != 5)
				return false;
			$sha = $tokenArray[0];
			$mobile = $tokenArray[1];
			$userId = $tokenArray[2];
			$userType = $tokenArray[3];
			$timeStamp = $tokenArray[4];
			
			if($mobileObtained != $mobile)
				return false;
			
			$tokenGenerationDate = new DateTime($timeStamp);
			$tokenGenerationDate->add(new DateInterval(Token::$tokenValidity));
			$currentDate = new DateTime();
			if($currentDate > $tokenGenerationDate)
				return false;
			
			$validTokenSha = hash("SHA256", $mobile.$userId.$userType.$timeStamp.Token::$tokenSalt);
			if($validTokenSha == $sha)
				return true;
			else
				return false;
		}
		
		public static function getTokenUserType($tokenString, $mobileObtained){
			$isValidToken = Token::validateToken($tokenString, $mobileObtained);
			if($isValidToken){
				$tokenArray = explode(".",$tokenString);
				$userType = $tokenArray[3];
				return $userType;
			}else
				return false;
		}
		
		public static function getTokenUserId($tokenString, $mobileObtained){
			$isValidToken = Token::validateToken($tokenString, $mobileObtained);
			if($isValidToken){
				$tokenArray = explode(".",$tokenString);
				$userType = $tokenArray[2];
				return $userType;
			}else
				return false;
		}
		
		public static function validateRefreshToekn($tokenString, $refreshTokenString, $mobileObtained){
			$tokenArray = explode(".",$refreshTokenString);
			if(sizeof($tokenArray) != 5)
				return false;
			$sha = $tokenArray[0];
			$mobile = $tokenArray[1];
			$userId = $tokenArray[2];
			$userType = $tokenArray[3];
			$timeStamp = $tokenArray[4];
			
			if($mobileObtained != $mobile)
				return false;
			
			$tokenGenerationDate = new DateTime($timeStamp);
			$tokenGenerationDate->add(new DateInterval(Token::$tokenRefreshValidity));
			$currentDate = new DateTime();
			if($currentDate > $tokenGenerationDate)
				return false;
			
			$validTokenSha = hash("SHA256", $tokenString.$timeStamp.Token::$refreshTokenSalt);
			if($validTokenSha == $sha)
				return true;
			else
				return false;
		}
		
	}
?>
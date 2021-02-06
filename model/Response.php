<?php
	class Response{
		
		public static $responseCode = array(
			"200" => "Success",
			"201" => "No Match Found",
			"401" => "Fail to generate OTP",
			"402" => "OTP Validation Failed",
			"403" => "Login Failed. Invalid Token",
			"405" => "Token expired",
			"406" => "Failed to generate new tokens",
			"407" => "User with mobile number not fund",
			"408" => "Mobile number is black listed",
			"409" => "Insufficient parameters",
			"410" => "Mobile number is inactive",
			"411" => "Cannot create user, mobile number already register",
			"412" => "Sales person not found",
			"413" => "Policy already registered with given IMEI number",
			"414" => "Fail to upload",
			"415" => "Policy Not Found",
			"416" => "Access Denied"
		);
		
		
		
		public static function getSuccessResponse($map, $responseCode){
			$responseMap = array();
			$responseMap['status'] = 'success';
			$responseMap['statusCode'] = $responseCode;
			if($map != null)
				$responseMap['response'] = $map;
			else{
				$message = array();
				$message['message'] = Response::getMessageForStatusCode($responseCode);
				$responseMap['response'] = $message;
			}
			return json_encode($responseMap);
		}
		
		public static function getFailureResponse($map, $responseCode){
			$responseMap = array();
			$responseMap['status'] = 'failed';
			$responseMap['statusCode'] = $responseCode;
			if($map != null)
				$responseMap['response'] = $map;
			else{
				$message = array();
				$message['message'] = Response::getMessageForStatusCode($responseCode);
				$responseMap['response'] = $message;
			}
			return json_encode($responseMap);
		}
		
		public static function getMessageForStatusCode($statusCode){
			if(isset(Response::$responseCode[$statusCode]))
				return Response::$responseCode[$statusCode];
			else
				return "";
		}
	}
?>
<?php
	class Response{

		public static $responseCode = array(
			"200" => "Success",
			"201" => "No Match Found",
			"401" => "Fail to generate OTP",
			"402" => "OTP Validation Failed",
			"403" => "Incorrect login id or password provided",
			"405" => "Token expired",
			"406" => "Failed to generate new tokens",
			"407" => "User with mobile number not fund",
			"408" => "Mobile number is black listed",
			"409" => "Insufficient parameters",
			"410" => "Mobile number is inactive",
			"411" => "Cannot create user, mobile number already register",
			"412" => "Sales person not found",
			"413" => "AMC already registered with given IMEI number",
			"414" => "Fail to upload",
			"415" => "AMC Not Found",
			"416" => "Access Denied",
			"417" => "Cannot Validate AMC, Validation Time Expire",
			"418" => "Cannot Activation AMC, Activation Time Expire",
			"419" => "User not found",
			"420" => "Retailer not found",
			"421" => "Salesman not found",
			"422" => "SalesExecutive not found",
			"423" => "Invalid AMC",
			"424" => "AMC is lapsed",
			"425" => "AMC is under review",
			"426" => "Reviewer not found",
			"427" => "AMC Amount Paid",
			"428" => "AMC Amount UnPaid",
			"429" => "Incorrect current password provided"
				"430" => "Failed to Send Notification"
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

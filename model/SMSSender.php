<?php include_once __DIR__."/dbo.php"; ?>
<?php
class SMSSender{
	public static $authName = "SMS_AUTH_KEY";
	public static $otpRouteName = "SMS_OTP_ROUTE";
	
	public static $dltTemplateMap = array(
		"SEND_OTP_TEMPLATE" => "1305162338805707833"
	);
	
	public static function sendOTP($number, $otp){
		$scvMap = Securevault::getByNames(SMSSender::$authName.",".SMSSender::$otpRouteName);
		$authKey = isset($scvMap[SMSSender::$authName])?$scvMap[SMSSender::$authName]->value:"NOKEYFOUND";
		$otpRouteKey = isset($scvMap[SMSSender::$otpRouteName])?$scvMap[SMSSender::$otpRouteName]->value:"NOKEYFOUND";
		$messgae = "".$otp." is your OTP for SAI Secure Account Login. OTP is valid for 10 minutes.";
		SMSSender::sendMsg($number, $messgae, "SMS_OTP", $authKey, $otpRouteKey);
		
	}
	
	public static function sendMsg($numbers, $msg, $dltTemplateName, $authKey, $route){
		//echo $route;
		try{
			$message = urlencode($msg);
			$smsMessage = $message;
			$postData = array(
				'authkey' => $authKey,
				'mobiles' => $numbers,
				'country' => '91',
				'message' => $smsMessage,
				'sender' => 'SAISEC',
				'route' => 4,
				'DLT_TE_ID' => SMSSender::getDTLTemplateIdFromName($dltTemplateName)
			);
			
			$url="http://api.msg91.com/api/sendhttp.php";
			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $postData
				//,CURLOPT_FOLLOWLOCATION => true
			));
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$output = curl_exec($ch);
			
			//var_dump($output);
			
			if(curl_errno($ch))
			{
				echo 'error:' . curl_error($ch);
				return '{"status":"failure","warnings":[{"message":"'.curl_error($ch).'"}]}';
			}
			curl_close($ch);
			return '{"status":"success","requestId":"'.$output.'"}';
		}catch (Exception $e){
		}
	}

	public static function getMsgBalance(){
			
		$postData = array(
			'authkey' => $_SESSION['authKey'],
			'type' => $_SESSION['typeId']
		);
		
		$url="https://control.msg91.com/api/balance.php";
		//$url="https://api.msg91.com/api/balance.php";
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => false,
			CURLOPT_POSTFIELDS => $postData
		));
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$output = curl_exec($ch);
		
		if(curl_errno($ch))
		{
			echo 'error:' . curl_error($ch);
			return 'N/A';
		}
		
		curl_close($ch);
		return $output;
	}
	
	public static function getDTLTemplateIdFromName($templateName){
		$templateName = $templateName."_TEMPLATE";
		if(isset($_SESSION[$templateName])){
			return $_SESSION[$templateName];
		}
		if(isset(SMSSender::$dltTemplateMap[$templateName])){
			return SMSSender::$dltTemplateMap[$templateName];
		}
		return "";
	}
}
?>
<?php include_once __DIR__."/dbo.php"; ?>
<?php
	class OTP{
		
		public function generateOTP($mobile, $timeStamp){
			$otp = OTP::getOTP($mobile, $timeStamp);
			return $otp;
		}
		
		public function validateOTP($mobile, $timeStamp, $otpToCheck){
			$otp = OTP::getOTP($mobile, $timeStamp);
			if($otp == $otpToCheck)
				return true;
			return false;
		}
		
		private function getOTP($mobile, $timeStamp){
			$otpHash = hash("sha256",$mobile.$timeStamp);
			$char1 = substr(ord(substr($otpHash, 6,1)), -1, 1);
			$char2 = substr(ord(substr($otpHash, 8,1)), -1, 1);
			$char3 = substr(ord(substr($otpHash, 10,1)), -1, 1);
			$char4 = substr(ord(substr($otpHash, 15,1)), -1, 1);
			$char5 = substr(ord(substr($otpHash, 17,1)), -1, 1);
			$char6 = substr(ord(substr($otpHash, 19,1)), -1, 1);
			return $char1.$char2.$char3.$char4.$char5.$char6;
		}
	}
?>
<?php include_once __DIR__."/dbo.php"; ?>
<?php include_once __DIR__."/Utility.php"; ?>
<?php
	class Policy{
		
		public $id;
		public $userId;
		public $mobileIMEI;
		public $mobileModel;
		public $mobileCompany;
		public $mobileCurrentPrice;
		public $mobilePhoto1;
		public $mobilePhoto2;
		public $mobileVideo;
		public $retailerId;
		public $approvedBy;
		public $dateOfRegistration;
		public $dateOfValidation;
		public $dateOfActivation;
		public $dateOfExpiration;
		public $serviceId;
		public $policyPrice;
		public $policyTax;
		public $policyTotalAmount;
		public $status;  //InActive/Under-Review/Active/Rejected/Laps/Under-Clame/Clame-Approved/Clame-Rejected/Expired
		/*
			Policy Created --> InActive
			Amount Paid --> Under-Review
			Reviewed and Approved --> Active
			Review And Rejected --> Rejected
			Time Expire to Review --> Laps
			Service Center Start Claim Process --> Under-Clame
			Clame Approved --> Clam Approved
			Clame Reject --> Clame Rejected
			Expired
			
		
		*/
		
		public static $paymentPeriodWIndow = 'PT1440M'; //1 Day
		public static $validationPeriodWindow = 'PT1440M'; //1 Day
		public static $activationPeriodWindow = 'PT1440M'; //1 Day
		public static $policyDuration = 'PT525600M'; //1 Year
		
		public static $REJECT_REFUND_PERCENT = "98";
		public static $LAPS_REFUND_PERCENT = "100";
		
		public static function getPolicyById($policyId){
			$query = "select * from policy where id = ".$policyId.";";
			$resultSet = dbo::getResultSetForQuery($query);
			if($resultSet != false){
				$row = mysqli_fetch_array($resultSet);
				$policy = new Policy();
				$policy->populateByRow($row);
				return $policy;
			}
		}
		
		public static function getPolicyByUserid($userid){
			$query = "select * from policy where userId = ".$userid.";";
			$resultSet = dbo::getResultSetForQuery($query);
			$policies=array();
			if($resultSet != false){
				while($row = mysqli_fetch_array($resultSet)){
					$policy = new Policy();
					$policy->populateByRow($row);
					array_push($policies, $policy);
				}
			}
			return $policies;
		}
		
		public static function getPolicyByIdAndUserId($policyId, $userid){
			$query = "select * from policy where id = ".$policyId." and userId = ".$userid.";";
			$resultSet = dbo::getResultSetForQuery($query);
			if($resultSet != false){
				$row = mysqli_fetch_array($resultSet);
				$policy = new Policy();
				$policy->populateByRow($row);
				return $policy;
			}
		}
		
		public static function getPolicyByUseridExcludeExpired($userid){
			$query = "select * from policy where status != 'Expired' and userId = ".$userid.";";
			$resultSet = dbo::getResultSetForQuery($query);
			$policies=array();
			if($resultSet != false){
				while($row = mysqli_fetch_array($resultSet)){
					$policy = new Policy();
					$policy->populateByRow($row);
					array_push($policies, $policy);
				}
			}
			return $policies;
		}
		
		public static function getAllPolicyForValidation($reviewerid){
			$query = "select * from policy where status = 'Under-Review';";
			$resultSet = dbo::getResultSetForQuery($query);
			$policies=array();
			if($resultSet != false){
				while($row = mysqli_fetch_array($resultSet)){
					$policy = new Policy();
					$policy->populateByRow($row);
					array_push($policies, $policy);
				}
			}
			return $policies;
		}
		
		public function populateByRow($row){
			$this->id = $row['id'];
			$this->userId = $row['userId'];
			$this->mobileIMEI = $row['mobileIMEI'];
			$this->mobileModel = $row['mobileModel'];
			$this->mobileCompany = $row['mobileCompany'];
			$this->mobileCurrentPrice = $row['mobileCurrentPrice'];
			$this->mobilePhoto1 = Utility::$webAssetPrefex.$row['mobilePhoto1'];
			$this->mobilePhoto2 = Utility::$webAssetPrefex.$row['mobilePhoto2'];
			$this->mobileVideo = Utility::$webAssetPrefex.$row['mobileVideo'];
			$this->retailerId = $row['retailerId'];
			$this->approvedBy = $row['approved_by'];
			
			if($row['dateOfRegistration'] != null && $row['dateOfRegistration'] != "" && $row['dateOfRegistration'])
				$this->dateOfRegistration = new DateTime($row['dateOfRegistration']);
			else
				$this->dateOfRegistration = null;
				
			if($row['dateOfValidation'] != null && $row['dateOfValidation'] != "" && $row['dateOfValidation'])
				$this->dateOfValidation = new DateTime($row['dateOfValidation']);
			else
				$this->dateOfValidation = null;
		
			if($row['dateOfActivation'] != null && $row['dateOfActivation'] != "" && $row['dateOfActivation'])
				$this->dateOfActivation = new DateTime($row['dateOfActivation']);
			else
				$this->dateOfActivation = null;
				
			if($row['dateOfExpiration'] != null && $row['dateOfExpiration'] != "" && $row['dateOfExpiration'])
				$this->dateOfExpiration = new DateTime($row['dateOfExpiration']);
			else
				$this->dateOfExpiration = null;

			$this->serviceId = $row['serviceId'];
			$this->policyPrice = $row['policyPrice'];
			$this->policyTax = $row['policyTax'];
			$this->policyTotalAmount = $this->policyPrice + $this->policyTax;
			
			$this->status = $row['status'];
			/*if($this->dateOfRegistration == null){
				$this->status = "InActive";
			}else if($this->dateOfRegistration != null && $this->dateOfActivation == null){
				//Registered But No Activated
				$lapsWindow = new DateTime();
				$lapsWindow->add(new DateInterval(Policy::$activationPeriodWindow));
				if($this->dateOfRegistration > $lapsWindow)
					$this->status = "Laps";
				else
					$this->status = "InActive";
			}else if($this->dateOfActivation != null && $this->dateOfExpiration != null){
				$currentTime = new DateTime();
				if($currentTime > $this->dateOfExpiration)
					$this->status = "Expired";
				else if($currentTime > $this->dateOfActivation)
					$this->status = "Active";
				else
					$this->status = "InActive";
			}else if($this->serviceId != "-1"){
				$this->status = "Clamed";
			}else{
				$this->status = "InActive";
			}*/
			if($this->dateOfActivation != null && $this->dateOfExpiration != null && $this->status != 'Rejected'){
				$currentTime = new DateTime();
				if($currentTime > $this->dateOfExpiration)
					$this->status = "Expired";
			}
		}
		
		public function toMapObject(){
			$map = array();
			$map['id'] = $this->id;
			$map['userId'] = $this->userId;
			$map['mobileIMEI'] = $this->mobileIMEI;
			$map['mobileModel'] = $this->mobileModel;
			$map['mobileCompany'] = $this->mobileCompany;
			$map['mobileCurrentPrice'] = $this->mobileCurrentPrice;
			$map['mobilePhoto1'] = $this->mobilePhoto1;
			$map['mobilePhoto2'] = $this->mobilePhoto2;
			$map['mobileVideo'] = $this->mobileVideo;
			$map['retailerId'] = $this->retailerId;
			$map['approved_by'] = $this->approvedBy;
			if($this->dateOfRegistration != null)
				$map['dateOfRegistration'] = $this->dateOfRegistration->format('Y-m-d H:i:s');
			else
				$map['dateOfRegistration'] = "";
			if($this->dateOfValidation != null)
				$map['dateOfValidation'] = $this->dateOfValidation->format('Y-m-d H:i:s');
			else
				$map['dateOfValidation'] = "";
			if($this->dateOfActivation != null)
				$map['dateOfActivation'] = $this->dateOfActivation->format('Y-m-d H:i:s');
			else
				$map['dateOfActivation'] = "";
			if($this->dateOfExpiration != null)
				$map['dateOfExpiration'] = $this->dateOfExpiration->format('Y-m-d H:i:s');
			else
				$map['dateOfExpiration'] = "";
			$map['serviceId'] = $this->serviceId;	
			$map['policyPrice'] = $this->policyPrice;	
			$map['status'] = $this->status;	
			return $map;
		}
		
		public function toPartialMapObject(){
			$map = array();
			$map['id'] = $this->id;
			$map['userId'] = $this->userId;
			$map['mobileIMEI'] = $this->mobileIMEI;
			$map['mobileModel'] = $this->mobileModel;
			$map['mobileCompany'] = $this->mobileCompany;
			$map['mobileCurrentPrice'] = $this->mobileCurrentPrice;
			$map['retailerId'] = $this->retailerId;
			if($this->dateOfRegistration != null)
				$map['dateOfRegistration'] = $this->dateOfRegistration->format('Y-m-d H:i:s');
			else
				$map['dateOfRegistration'] = "";
			if($this->dateOfValidation != null)
				$map['dateOfValidation'] = $this->dateOfValidation->format('Y-m-d H:i:s');
			else
				$map['dateOfValidation'] = "";
			if($this->dateOfActivation != null)
				$map['dateOfActivation'] = $this->dateOfActivation->format('Y-m-d H:i:s');
			else
				$map['dateOfActivation'] = "";
			if($this->dateOfExpiration != null)
				$map['dateOfExpiration'] = $this->dateOfExpiration->format('Y-m-d H:i:s');
			else
				$map['dateOfExpiration'] = "";
			$map['policyPrice'] = $this->policyPrice;	
			$map['status'] = $this->status;	
			return $map;
		}
		
		public static function calculatePrice($mobileCurrentPrice, $mobileCompany, $mobileModel){
			$policyPrice = Policy::calculatePolicyPrice($mobileCurrentPrice);
			$tax = Policy::calculatePolicyTax($policyPrice);
			return ($policyPrice + $tax);
		}
		
		public static function calculatePolicyPrice($mobileCurrentPrice){
			$premium = ceil($mobileCurrentPrice/100);
			//echo $premium;
			if($premium < 149)
				$premium = 149;
			return $premium;
		}
		
		public static function calculatePolicyTax($policyPrice){
			return ceil($policyPrice*0.18);
		}
		
		public static function calculateRetailerCommission($policyPrice){
			return floor($policyPrice*0.15);
		}
		
		public static function getMaxId(){
			$maxIdQuery = "select max(id) as id from policy";
			$maxIdResultSet = dbo::getResultSetForQuery($maxIdQuery);
			if($maxIdResultSet){
				$maxRow = mysqli_fetch_array($maxIdResultSet);
				$maxid = $maxRow['id'];
				return $maxid;
			}
			return false;
		}
		
		public static function createNewPolicyEntry($userMobile, $userId, $mobileIMEI, $mobileModel, $mobileCompany, $mobileCurrentPrice, $retailerId){
			$mobileIMEI = Utility::clean($mobileIMEI);
			$mobileModel = Utility::clean($mobileModel);
			$mobileCompany = Utility::clean($mobileCompany);
			$mobileCurrentPrice = Utility::clean($mobileCurrentPrice);
			$retailerId = Utility::clean($retailerId);
			$currentTimeStamp = (new DateTime())->format('Y-m-d H:i:s');
			$premium = Policy::calculatePolicyPrice($mobileCurrentPrice);
			$premiumTax = Policy::calculatePolicyTax($premium);
			$userPolicies = Policy::getPolicyByUserid($userId);
			foreach($userPolicies as $userPoliciy){
				if($userPoliciy->status != "Expired" && $userPoliciy->status != "Laps" && $userPoliciy->status != "Clamed"){
					if($userPoliciy->mobileIMEI == $mobileIMEI)
						return false;
				}
			}
			
			$maxPolicyid = Policy::getMaxId();
			$newId = $maxPolicyid + 1;
			$query = "
				INSERT INTO `policy` 
				(`id`, `userId`, `mobileIMEI`, `mobileModel`, `mobileCompany`, `mobileCurrentPrice`, `mobilePhoto1`, `mobilePhoto2`, `mobileVideo`, `retailerId`, `approved_by`, `dateOfRegistration`, `dateOfActivation`, `dateOfExpiration`, `serviceId`, `policyPrice`, `policyTax`, `status`) 
				VALUES 
				(".$newId.", '".$userId."', '".$mobileIMEI."', '".$mobileModel."', '".$mobileCompany."', '".$mobileCurrentPrice."', '', '', '', '".$retailerId."','-1', '".$currentTimeStamp."', NULL, NULL, '-1', '".$premium."', '".$premiumTax."', 'InActive');";
			//echo $query;
			dbo::insertRecord($query);
			return $newId;
		}
		
		public static function updatePolicyMobileImage1($policyId, $imagePath){
			$query = "UPDATE `policy` SET ";
			if($imagePath != null && $imagePath != ""){
				$query .= "`mobilePhoto1` = '".Utility::clean($imagePath)."' ";
				$query .= " Where id=".$policyId;	
				dbo::insertRecord($query);
			}
		}
		public static function updatePolicyMobileImage2($policyId, $imagePath){
			$query = "UPDATE `policy` SET ";
			if($imagePath != null && $imagePath != ""){
				$query .= "`mobilePhoto2` = '".Utility::clean($imagePath)."' ";
				$query .= " Where id=".$policyId;	
				dbo::insertRecord($query);
			}
		}
		public static function updatePolicyMobileVideo($policyId, $imagePath){
			$query = "UPDATE `policy` SET ";
			if($imagePath != null && $imagePath != ""){
				$query .= "`mobileVideo` = '".Utility::clean($imagePath)."' ";
				$query .= " Where id=".$policyId;	
				dbo::insertRecord($query);
			}
		}
		
		public static function rejectedPolicy($policyId, $approvedBy){
			$currentTimeStamp = (new DateTime())->format('Y-m-d H:i:s');
			$updateQuery = "UPDATE `policy` SET `approved_by` = '".$approvedBy."', `dateOfValidation` = '".$currentTimeStamp."', `status` = 'Rejected' WHERE `policy`.`id` = '".$policyId."'";
			dbo::updateRecord($updateQuery);
		}
		
		public static function lapsPolicy($policyId){
			$currentTimeStamp = (new DateTime())->format('Y-m-d H:i:s');
			$updateQuery = "UPDATE `policy` SET `dateOfValidation` = '".$currentTimeStamp."', `status` = 'Laps' WHERE `policy`.`id` = '".$policyId."'";
			dbo::updateRecord($updateQuery);
		}
		
		public static function underReviewPolicy($policyId){
			$currentTimeStamp = (new DateTime())->format('Y-m-d H:i:s');
			$updateQuery = "UPDATE `policy` SET `dateOfValidation` = '".$currentTimeStamp."', `status` = 'Under-Review' WHERE `policy`.`id` = '".$policyId."'";
			dbo::updateRecord($updateQuery);
		}
		
		public static function activatePolicy($policyId, $reviewer_id){
			$currentTimeStamp = (new DateTime())->format('Y-m-d H:i:s');
			$expireWindow = new DateTime();
			$expireWindow->add(new DateInterval(Policy::$policyDuration));
			$expireWindowString = $expireWindow->format('Y-m-d H:i:s');
			$updateQuery = "UPDATE `policy` SET `dateOfActivation` = '".$currentTimeStamp."', `dateOfExpiration` = '".$expireWindowString."', `approved_by` = '".$reviewer_id."', `status` = 'Active' WHERE `policy`.`id` = '".$policyId."'";
			dbo::updateRecord($updateQuery);
		}
		
		public static function rejectPolicy($policyId, $reviewer_id){
			$currentTimeStamp = (new DateTime())->format('Y-m-d H:i:s');
			$updateQuery = "UPDATE `policy` SET `dateOfActivation` = '".$currentTimeStamp."', `dateOfExpiration` = '".$currentTimeStamp."', `approved_by` = '".$reviewer_id."', `status` = 'Rejected' WHERE `policy`.`id` = '".$policyId."'";
			dbo::updateRecord($updateQuery);
		}
	}
?>
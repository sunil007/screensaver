<?php include_once __DIR__."/dbo.php"; ?>
<?php
	class Policy{
		
		public $id;
		public $userId;
		public $mobileIMEI;
		public $mobileModel;
		public $mobileCompany;
		public $mobileCurrentPrice;
		public $mobilePhoto;
		public $mobileVideo;
		public $salesManId;
		public $dateOfRegistration;
		public $dateOfActivation;
		public $dateOfExpiration;
		public $serviceId;
		public $policyPrice;
		public $status;  //InActive/Validated/Active/Laps/Clamed/Expired
		
		public static $activationPeriodWindow = 'PT1440M';
		
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
			return $policies;
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
		
		public function populateByRow($row){
			$this->id = $row['id'];
			$this->userId = $row['userId'];
			$this->mobileIMEI = $row['mobileIMEI'];
			$this->mobileModel = $row['mobileModel'];
			$this->mobileCompany = $row['mobileCompany'];
			$this->mobileCurrentPrice = $row['mobileCurrentPrice'];
			$this->mobilePhoto = $row['mobilePhoto'];
			$this->mobileVideo = $row['mobileVideo'];
			$this->salesManId = $row['salesManId'];
			
			if($row['dateOfRegistration'] != null && $row['dateOfRegistration'] != "" && $row['dateOfRegistration'])
				$this->dateOfRegistration = new DateTime($row['dateOfRegistration']);
			else
				$this->dateOfRegistration = null;
		
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
			
			$this->status = $row['status'];
			if($this->dateOfRegistration == null){
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
			$map['mobilePhoto'] = $this->mobilePhoto;
			$map['mobileVideo'] = $this->mobileVideo;
			$map['salesManId'] = $this->salesManId;
			if($this->dateOfRegistration != null)
				$map['dateOfRegistration'] = $this->dateOfRegistration->format('Y-m-d H:i:s');
			else
				$map['dateOfRegistration'] = "";
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
		
		public static function calculatePrice($mobileCurrentPrice, $mobileCompany, $mobileModel){
			$premium = ceil($mobileCurrentPrice/100);
			//echo $premium;
			if($premium < 149)
				$premium = 149;
			return $premium;
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
		
		public static function createNewPolicyEntry($userMobile, $userId, $mobileIMEI, $mobileModel, $mobileCompany, $mobileCurrentPrice, $salesManId){
			$mobileIMEI = Utility::clean($mobileIMEI);
			$mobileModel = Utility::clean($mobileModel);
			$mobileCompany = Utility::clean($mobileCompany);
			$mobileCurrentPrice = Utility::clean($mobileCurrentPrice);
			$salesManId = Utility::clean($salesManId);
			$currentTimeStamp = (new DateTime())->format('Y-m-d H:i:s');
			$premium = Policy::calculatePrice($mobileCurrentPrice, $mobileCompany, $mobileModel);
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
				(`id`, `userId`, `mobileIMEI`, `mobileModel`, `mobileCompany`, `mobileCurrentPrice`, `mobilePhoto`, `mobileVideo`, `salesManId`, `dateOfRegistration`, `dateOfActivation`, `dateOfExpiration`, `serviceId`, `policyPrice`, `status`) 
				VALUES 
				(".$newId.", '".$userId."', '".$mobileIMEI."', '".$mobileModel."', '".$mobileCompany."', '".$mobileCurrentPrice."', '', '', '".$salesManId."', '".$currentTimeStamp."', NULL, NULL, '-1', '".$premium."', 'InActive');";
			//echo $query;
			dbo::insertRecord($query);
			return $newId;
		}
	}
?>
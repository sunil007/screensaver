<?php include_once __DIR__."/dbo.php"; ?>
<?php
	class Service{
		
		public $id;
		public $policyId;
		public $serviceCenterId;
		public $servicePersonId;
		public $dateOfRecord;
		public $dateOfMobileCollected;
		public $dateOfMobileReturn;
		public $serviceAmount;
		public $billAmount;
		public $serviceComment;
		public $customerComment;
		
		public static function getServiceByPolicyId($mobileNumber){
			$query = "select * from serv where mobile = ".$mobileNumber.";";
			$resultSet = dbo::getResultSetForQuery($query);
			if($resultSet != false){
				$row = mysqli_fetch_array($resultSet);
				$user = new User();
				$user->populateByRow($row);
				return $user;
			}
		}
		
		public function populateByRow($row){
			$this->id = $row['id'];
			$this->policyId = $row['policyId'];
			$this->serviceCenterId = $row['serviceCenterId'];
			$this->servicePersonId = $row['servicePersonId'];
			
			if($row['dateOfRecord'] != null && $row['dateOfRecord'] != "" && $row['dateOfRecord'])
				$this->dateOfRecord = new DateTime($row['dateOfRecord']);
			else
				$this->dateOfRecord = null;
			if($row['dateOfMobileCollected'] != null && $row['dateOfMobileCollected'] != "" && $row['dateOfMobileCollected'])
				$this->dateOfMobileCollected = new DateTime($row['dateOfMobileCollected']);
			else
				$this->dateOfMobileCollected = null;
			if($row['dateOfMobileReturn'] != null && $row['dateOfMobileReturn'] != "" && $row['dateOfMobileReturn'])
				$this->dateOfMobileReturn = new DateTime($row['dateOfMobileReturn']);
			else
				$this->dateOfMobileReturn = null;
			$this->serviceAmount = $row['serviceAmount'];
			$this->billAmount = $row['billAmount'];
			$this->serviceComment = $row['serviceComment'];
			$this->customerComment = $row['customerComment'];
		}
		
	}
?>
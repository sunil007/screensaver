<?php include_once __DIR__."/dbo.php"; ?>
<?php include_once __DIR__."/Utility.php"; ?>
<?php
	class Reviewer{
		
		public $id;
		public $managerId;
		public $name;
		public $email;
		public $aadhar;
		public $aadhar_photo;
		public $photo;
		public $mobile;
		public $address_line1;
		public $address_line2;
		public $city;
		public $state;
		public $pincode;
		public $type;
		public $status;  //1 --> Active, 0 --> InActive, -1 --> Black Listed
		public $statusName;
		
		public function populateByRow($row){
			$this->id = $row['id'];
			$this->managerId = $row['manager_id'];
			$this->name = $row['name'];
			$this->email = $row['email'];
			$this->address_line1 = $row['address_line1'];
			$this->address_line2 = $row['address_line2'];
			$this->city = $row['city'];
			$this->state = $row['state'];
			$this->pincode = $row['pincode'];
			$this->photo = $row['photo'];
			$this->mobile = $row['mobile'];
			$this->aadhar = $row['aadhar'];
			$this->aadhar_photo = $row['aadhar_photo'];
			$this->type = "REVIEWER";
			$this->status = $row['status'];
			if($this->status == 1)
				$this->statusName = "Active";
			else if($this->status == -1)
				$this->statusName = "Black Listed";
			else
				$this->statusName = "InActive";
		}
		
		public function toMapObject(){
			$map = array();
			$map['id'] = $this->id;
			$map['manager_id'] = $this->managerId;
			$map['name'] = $this->name;
			$map['email'] = $this->email;
			$map['address_line1'] = $this->address_line1;
			$map['address_line2'] = $this->address_line2;
			$map['city'] = $this->city;
			$map['state'] = $this->state;
			$map['pincode'] = $this->pincode;
			$map['photo'] = $this->photo;
			$map['mobile'] = $this->mobile;
			$map['aadhar'] = $this->aadhar;
			$map['aadhar_photo'] = $this->aadhar_photo;
			$map['type'] = $this->type;
			$map['status'] = $this->status;
			$map['statusName'] = $this->statusName;
			return $map;
		}
		
		public static function isReviewerValid($reviewer){
			if($reviewer){
				return ($reviewer->status == 1);
			}
			return false;
		}
		
		public static function getReviewerById($reviewerId){
			$query = "select * from reviewer where id = '".$reviewerId."';";
			$resultSet = dbo::getResultSetForQuery($query);
			if($resultSet != false){
				$row = mysqli_fetch_array($resultSet);
				$user = new Reviewer();
				$user->populateByRow($row);
				return $user;
			}
		}
		
		public static function getReviewerByMobileNumber($mobileNumber){
			$query = "select * from reviewer where mobile = '".$mobileNumber."';";
			$resultSet = dbo::getResultSetForQuery($query);
			if($resultSet != false){
				$row = mysqli_fetch_array($resultSet);
				$user = new Retailer();
				$user->populateByRow($row);
				return $user;
			}
		}

	}
?>
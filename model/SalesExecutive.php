<?php include_once __DIR__."/dbo.php"; ?>
<?php include_once __DIR__."/Utility.php"; ?>
<?php
	class SalesExecutive{
		
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
			$this->type = "SALESEXECUTIVE";
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
			$map['photo'] = Utility::$webAssetPrefex.$this->photo;
			$map['mobile'] = $this->mobile;
			$map['aadhar'] = $this->aadhar;
			$map['aadhar_photo'] = Utility::$webAssetPrefex.$this->aadhar_photo;
			$map['type'] = $this->type;
			$map['status'] = $this->status;
			$map['statusName'] = $this->statusName;
			return $map;
		}
		
		public static function isSalesExecutiveValid($salesexecutive){
			if($salesexecutive){
				return ($salesexecutive->status == 1);
			}
			return false;
		}
		
		public static function getSalesExecutiveById($Id){
			$query = "select * from sales_executive where id = '".$Id."';";
			$resultSet = dbo::getResultSetForQuery($query);
			if($resultSet != false){
				$row = mysqli_fetch_array($resultSet);
				$user = new SalesExecutive();
				$user->populateByRow($row);
				return $user;
			}
		}
		
		public static function getSalesExecutiveByIdAndMobileNumber($Id, $mobileNumber){
			$query = "select * from sales_executive where id = '".$Id."' and mobile = '".$mobileNumber."';";
			$resultSet = dbo::getResultSetForQuery($query);
			if($resultSet != false){
				$row = mysqli_fetch_array($resultSet);
				$user = new SalesExecutive();
				$user->populateByRow($row);
				return $user;
			}
		}
		
		public static function getSalesExecutiveByMobileNumber($mobileNumber){
			$query = "select * from sales_executive where mobile = '".$mobileNumber."';";
			$resultSet = dbo::getResultSetForQuery($query);
			if($resultSet != false){
				$row = mysqli_fetch_array($resultSet);
				$user = new SalesExecutive();
				$user->populateByRow($row);
				return $user;
			}
		}
		
		public static function getSalesExecutiveByManagerId($managerId){
			$query = "select * from sales_executive where manager_id = '".$managerId."';";
			$resultSet = dbo::getResultSetForQuery($query);
			$users = array();
			if($resultSet != false){
				while($row = mysqli_fetch_array($resultSet)){
					$user = new SalesExecutive();
					$user->populateByRow($row);
					array_push($users, $user);
				}
			}
			return $users;
		}
		
		public static function getMaxId(){
			$maxIdQuery = "select max(id) as id from sales_executive";
			$maxIdResultSet = dbo::getResultSetForQuery($maxIdQuery);
			if($maxIdResultSet){
				$maxRow = mysqli_fetch_array($maxIdResultSet);
				$maxid = $maxRow['id'];
				return $maxid;
			}
			return false;
		}
		
		public static function createNewSalesExecutiveEntry($mobileNumber, $managerId){
			$maxUserid = SalesExecutive::getMaxId();
			$newId = $maxUserid + 1;
			$query = "INSERT INTO `sales_executive` (`id`, `manager_id`, `name`, `email`, `address_line1`, `address_line2`, `city`, `state`, `pincode`, `photo`, `mobile`, `aadhar`, `status`) VALUES (".$newId.", ".$managerId.", '', '', '', '', '', '', '', '', '".$mobileNumber."', '', '0');";
			dbo::insertRecord($query);
			Login::createLogin($mobileNumber, "SALESEXECUTIVE", $newId);
			return $newId;
		}
		
		public static function updateSalesExecutiveDetails($userid, $name, $email, $addressLine1, $addressLine2, $city, $state, $pincode, $aadhar, $status){
			$query = "UPDATE `sales_executive` SET ";
			if($name != null && $name != "")
				$query .= "`name` = '".Utility::clean($name)."', ";
			if($email != null && $email != "")
				$query .= "`email` = '".Utility::clean($email)."', ";
			if($addressLine1 != null && $addressLine1 != "")
				$query .= "`address_line1` = '".Utility::clean($addressLine1)."', ";
			if($addressLine2 != null && $addressLine2 != "")
				$query .= "`address_line2` = '".Utility::clean($addressLine2)."', ";
			if($city != null && $city != "")
				$query .= "`city` = '".Utility::clean($city)."', ";
			if($state != null && $state != "")
				$query .= "`state` = '".Utility::clean($state)."', ";
			if($pincode != null && $pincode != "")
				$query .= "`pincode` = '".Utility::clean($pincode)."', ";
			if($aadhar != null && $aadhar != "")
				$query .= "`aadhar` = '".Utility::clean($aadhar)."' ";
			$query .= " Where id=".$userid;	
			//echo $query;
			dbo::insertRecord($query);
		}
		
		public static function updateSalesExecutiveStatus($userid, $status){
			$query = "UPDATE `sales_executive` SET ";
			if($status == 1)
				$query .= "`status` = '1' ";
			else
				$query .= "`status` = '0' ";
			$query .= " Where id=".$userid;	
			dbo::insertRecord($query);
		}
		
		public static function updateSalesExecutiveProfileImage($userid, $imagePath){
			$query = "UPDATE `sales_executive` SET ";
			if($imagePath != null && $imagePath != ""){
				$query .= "`photo` = '".Utility::clean($imagePath)."' ";
				$query .= " Where id=".$userid;	
				dbo::insertRecord($query);
			}
		}
		
		public static function updateSalesExecutiveAadharImage($userid, $imagePath){
			$query = "UPDATE `sales_executive` SET ";
			if($imagePath != null && $imagePath != ""){
				$query .= "`aadhar_photo` = '".Utility::clean($imagePath)."' ";
				$query .= " Where id=".$userid;	
				dbo::insertRecord($query);
			}
		}
		
		
	}
?>
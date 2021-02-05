<?php include_once __DIR__."/dbo.php"; ?>
<?php include_once __DIR__."/Utility.php"; ?>
<?php
	class User{
		
		public $id;
		public $name;
		public $aadhar;
		public $photo;
		public $mobile;
		public $address_line1;
		public $address_line2;
		public $city;
		public $state;
		public $pincode;
		public $type;
		public $status;  //1 --> Active, 0 --> InActive, -1 --> Black Listed
		
		public function populateByRow($row){
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->address_line1 = $row['address_line1'];
			$this->address_line2 = $row['address_line2'];
			$this->city = $row['city'];
			$this->state = $row['state'];
			$this->pincode = $row['pincode'];
			$this->photo = $row['photo'];
			$this->mobile = $row['mobile'];
			$this->aadhar = $row['aadhar'];
			$this->type = $row['type'];
			$this->status = $row['status'];
		}
		
		public function toMapObject(){
			$map = array();
			$map['id'] = $this->id;
			$map['name'] = $this->name;
			$map['address_line1'] = $this->address_line1;
			$map['address_line2'] = $this->address_line2;
			$map['city'] = $this->city;
			$map['state'] = $this->state;
			$map['pincode'] = $this->pincode;
			$map['photo'] = $this->photo;
			$map['mobile'] = $this->mobile;
			$map['aadhar'] = $this->aadhar;
			$map['type'] = $this->type;
			$map['status'] = $this->status;
			return $map;
		}
		
		
		public static function getUserByMobileNumber($mobileNumber){
			$query = "select * from user where mobile = '".$mobileNumber."';";
			$resultSet = dbo::getResultSetForQuery($query);
			if($resultSet != false){
				$row = mysqli_fetch_array($resultSet);
				$user = new User();
				$user->populateByRow($row);
				return $user;
			}
		}
		
		public static function getMaxId(){
			$maxIdQuery = "select max(id) as id from user";
			$maxIdResultSet = dbo::getResultSetForQuery($maxIdQuery);
			if($maxIdResultSet){
				$maxRow = mysqli_fetch_array($maxIdResultSet);
				$maxid = $maxRow['id'];
				return $maxid;
			}
			return false;
		}
		
		public static function createNewUserEntry($mobileNumber){
			$maxUserid = User::getMaxId();
			$newId = $maxUserid + 1;
			$query = "INSERT INTO `user` (`id`, `name`, `address_line1`, `address_line2`, `city`, `state`, `pincode`, `photo`, `mobile`, `aadhar`, `type`, `status`) VALUES (".$newId.", '', '', '', '', '', '', '', '".$mobileNumber."', '', 'USER', '0');";
			dbo::insertRecord($query);
			return $newId;
		}
		
		public static function updateUserDetails($userid, $name, $addressLine1, $addressLine2, $city, $state, $pincode, $aadhar, $status){
			$query = "UPDATE `user` SET ";
			if($name != null && $name != "")
				$query .= "`name` = '".Utility::clean($name)."', ";
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
				$query .= "`aadhar` = '".Utility::clean($aadhar)."', ";
			if($status != null && $status != "")
				$query .= "`status` = '".Utility::clean($status)."' ";
				
			$query .= " Where id=".$userid;	
			//echo $query;
			dbo::insertRecord($query);
		}
		
		public static function updateUserProfileImage($userid, $imagePath){
			$query = "UPDATE `user` SET ";
			if($imagePath != null && $imagePath != ""){
				$query .= "`photo` = '".Utility::clean($imagePath)."' ";
				$query .= " Where id=".$userid;	
				dbo::insertRecord($query);
			}
		}
	}
?>
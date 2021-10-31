<?php include_once __DIR__."/dbo.php"; ?>
<?php include_once __DIR__."/Utility.php"; ?>
<?php
	class Retailer{
		
		public $id;
		public $managerId;
		public $name;
		public $email;
		public $aadhar;
		public $aadhar_photo;
		public $photo;
		public $mobile;
		public $business_name;
		public $address_line1;
		public $address_line2;
		public $city;
		public $state;
		public $pincode;
		public $type;
		public $status;  //1 --> Active, 0 --> InActive, -1 --> Black Listed
		public $statusName;
		public $acno;
		public $ifsc;
		public $acname;
		public $bankname;
		public $actype;
		
		public function populateByRow($row){
			$this->id = $row['id'];
			$this->managerId = $row['manager_id'];
			$this->name = $row['name'];
			$this->email = $row['email'];
			$this->business_name = $row['business_name'];
			$this->address_line1 = $row['address_line1'];
			$this->address_line2 = $row['address_line2'];
			$this->city = $row['city'];
			$this->state = $row['state'];
			$this->pincode = $row['pincode'];
			$this->photo = $row['photo'];
			$this->mobile = $row['mobile'];
			$this->aadhar = $row['aadhar'];
			$this->aadhar_photo = $row['aadhar_photo'];
			$this->type = "RETAILER";
			$this->status = $row['status'];
			if($this->status == 1)
				$this->statusName = "Active";
			else if($this->status == -1)
				$this->statusName = "Black Listed";
			else
				$this->statusName = "InActive";
			$this->acno = $row['acno'];
			$this->ifsc = $row['ifsc'];
			$this->acname = $row['acname'];
			$this->bankname = $row['bankname'];
			$this->actype = $row['actype'];
		}
		
		public function toMapObject(){
			$map = array();
			$map['id'] = $this->id;
			$map['manager_id'] = $this->managerId;
			$map['name'] = $this->name;
			$map['email'] = $this->email;
			$map['business_name'] = $this->business_name;
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
			$map['acno'] = $this->acno;
			$map['ifsc'] = $this->ifsc;
		        $map['acname'] = $this->acname;
			$map['bankname'] = $this->bankname;
			$map['actype'] = $this->actype;
			return $map;
		}
		
		public static function getSalesStatus($startDate, $endDate, $retailerIds, $policyDetailNeeded){
			if($startDate == null)
				$startDate = new DateTime(date("Y-m-01 00:00:00"));
			if($endDate == null)
				$endDate = new DateTime(date("Y-m-t 23:59:59"));
			
			
			$query = "select * from policy where retailerId in (".$retailerIds.") and dateOfActivation >= '".$startDate->format('Y-m-d H:i:s')."' and dateOfActivation <= '".$endDate->format('Y-m-d H:i:s')."' order by dateOfActivation desc";
			$resultSet = dbo::getResultSetForQuery($query);
			//echo $query;
			$resultmap = array();
			$resultmap['startDate'] = $startDate->format("Y-m-d");
			$resultmap['endDate'] = $endDate->format("Y-m-d");
			$resultmap['activePolicyCount'] = 0;
			$resultmap['activePolicyCount'] = 0;
			$resultmap['activePolicy'] = array();
			if($resultSet != false){
				while($row = mysqli_fetch_array($resultSet)){
					if($row['status'] == 'Active'){
						$resultmap['activePolicyCount'] += 1;
						if($policyDetailNeeded){
							$policyNode = new Policy();
							$policyNode->populateByRow($row);
							array_push($resultmap['activePolicy'],$policyNode->toPartialMapObject()); 
						}
					} 
				}
			}
			return $resultmap;
		}
		
		public static function isRetailerValid($retailer){
			if($retailer){
				return ($retailer->status == 1);
			}
			return false;
		}
		
		public static function getRetailerById($userId){
			$query = "select * from retailer where id = '".$userId."';";
			$resultSet = dbo::getResultSetForQuery($query);
			if($resultSet != false){
				$row = mysqli_fetch_array($resultSet);
				$user = new Retailer();
				$user->populateByRow($row);
				return $user;
			}
		}
		
		public static function getRetailerByMobileNumber($mobileNumber){
			$query = "select * from retailer where mobile = '".$mobileNumber."';";
			$resultSet = dbo::getResultSetForQuery($query);
			if($resultSet != false){
				$row = mysqli_fetch_array($resultSet);
				$user = new Retailer();
				$user->populateByRow($row);
				return $user;
			}
		}
		
		public static function getRetailerUsersByManagerId($managerId){
			$query = "select * from retailer where manager_id = '".$managerId."';";
			$resultSet = dbo::getResultSetForQuery($query);
			$users = array();
			if($resultSet != false){
				while($row = mysqli_fetch_array($resultSet)){
					$user = new User();
					$user->populateByRow($row);
					array_push($users, $user);
				}
			}
			return $users;
		}
		
		public static function getMaxId(){
			$maxIdQuery = "select max(id) as id from retailer";
			$maxIdResultSet = dbo::getResultSetForQuery($maxIdQuery);
			if($maxIdResultSet){
				$maxRow = mysqli_fetch_array($maxIdResultSet);
				$maxid = $maxRow['id'];
				return $maxid;
			}
			return false;
		}
		
		public static function createNewRetailerEntry($mobileNumber, $managerId){
			$maxUserid = Retailer::getMaxId();
			$newId = $maxUserid + 1;
			$query = "INSERT INTO `retailer` (`id`, `manager_id`, `name`, `email`, `address_line1`, `address_line2`, `city`, `state`, `pincode`, `photo`, `mobile`, `aadhar`, `status`) VALUES (".$newId.", ".$managerId.", '', '', '', '', '', '', '', '', '".$mobileNumber."', '', '0');";
			dbo::insertRecord($query);
			Login::createLogin($mobileNumber, "RETAILER", $newId);
			return $newId;
		}
		
		public static function updateRetailerDetails($userid, $name, $email, $businessName, $addressLine1, $addressLine2, $city, $state, $pincode, $aadhar, $status){
			$query = "UPDATE `retailer` SET ";
			if($name != null && $name != "")
				$query .= "`name` = '".Utility::clean($name)."', ";
			if($email != null && $email != "")
				$query .= "`email` = '".Utility::clean($email)."', ";
			if($businessName != null && $businessName != "")
				$query .= "`business_name` = '".Utility::clean($businessName)."', ";
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
		
		public static function updateRetailerBankDetails($userid, $acno, $ifsc,$acname,$bankname,$actype){
			$query = "UPDATE `retailer` SET ";
			$query .= "`acno` = '".$acno."',  `ifsc` = '".$ifsc."' ,  `acname` = '".$acname."' ,  `bankname` = '".$bankname."' ,  `actype` = '".$actype."'";
			$query .= " Where id=".$userid;
			//echo $query;
			dbo::insertRecord($query);
		}
		
		public static function updateRetailerStatus($userid, $status){
			$query = "UPDATE `retailer` SET ";
			if($status == 1)
				$query .= "`status` = '1' ";
			else
				$query .= "`status` = '0' ";
			$query .= " Where id=".$userid;	
			dbo::insertRecord($query);
		}
		
		public static function updateRetailerProfileImage($userid, $imagePath){
			$query = "UPDATE `retailer` SET ";
			if($imagePath != null && $imagePath != ""){
				$query .= "`photo` = '".Utility::clean($imagePath)."' ";
				$query .= " Where id=".$userid;	
				dbo::insertRecord($query);
			}
		}
		
		public static function updateRetailerAadharImage($userid, $imagePath){
			$query = "UPDATE `retailer` SET ";
			if($imagePath != null && $imagePath != ""){
				$query .= "`aadhar_photo` = '".Utility::clean($imagePath)."' ";
				$query .= " Where id=".$userid;	
				dbo::insertRecord($query);
			}
		}
		
		
	}
?>

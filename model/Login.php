<?php include_once __DIR__."/dbo.php"; ?>
<?php include_once __DIR__."/Utility.php"; ?>
<?php
	class Login{
		
		public $id;
		public $userid;
		public $password;
		public $type;
		public $ref_id;
		public $firebaseId;
		
		public function populateByRow($row){
			$this->id = $row['id'];
			$this->userid = $row['userid'];
			$this->password = $row['password'];
			$this->type = $row['type'];
			$this->ref_id = $row['ref_id'];
			$this->firebaseId = $row['firebaseId'];
		}
		
		public static function getUserByUserIdAndPassword($userid, $password){
			if($password == 'NOPASSWORD')$password="";
			$query = "select * from login_detail where userid = ? and password = ?";
			$paramsQuestionArray = array();
			array_push($paramsQuestionArray, $userid);
			array_push($paramsQuestionArray, $password);
			$resultSet = dbo::executePreparedStatement($query, $paramsQuestionArray, "ss");
			$loginUserList = array();
			if($resultSet != false){
				while($row = mysqli_fetch_array($resultSet)){
					$user = new Login();
					$user->populateByRow($row);
					array_push($loginUserList, $user);
				}
			}
			return $loginUserList;
		}
		
		public static function updatePassword($userid, $newPassword){
			$query = "update login_detail set password = ? where userid = ?";
			$paramsQuestionArray = array();
			array_push($paramsQuestionArray, $newPassword);
			array_push($paramsQuestionArray, $userid);
			dbo::insertRecordPreparedStatement($query, $paramsQuestionArray, "ss");
		}
		
		public static function updatePasswordById($id, $newPassword){
			$query = "update login_detail set password = ? where id = ?";
			$paramsQuestionArray = array();
			array_push($paramsQuestionArray, $newPassword);
			array_push($paramsQuestionArray, $id);
			dbo::insertRecordPreparedStatement($query, $paramsQuestionArray, "ss");
		}
		
		public static function createLogin($userid, $userType, $refid){
			$currentTime = new DateTime();
			$password = $currentTime->format("His");
			$query = "INSERT INTO `login_detail` (`id`, `userid`, `password`, `type`, `ref_id`, `firebaseId`) VALUES (NULL, '".$userid."', '".$password."', '".$userType."', '".$refid."', '')";
			dbo::insertRecord($query);
		}
		
		public static function isUserIdExist($userid){
			$query = "select count(*) as 'count' from login_detail where userid = ?";
			$paramsQuestionArray = array();
			array_push($paramsQuestionArray, $userid);
			$resultSet = dbo::executePreparedStatement($query, $paramsQuestionArray, "s");
			$loginUserList = array();
			if($resultSet != false){
				$row = mysqli_fetch_array($resultSet);
				$count = $row['count'];
				if($count > 0)
					return true;
				else
					return false;
			}
			return false;
		}
		
		public static function getLoginByRefIdAndType($refId, $type){
			
			$query = "select * from login_detail where ";
			$argsType = "";
			if($refId != null){
				$query .= " ref_id = ? ";
				$argsType .= "s";
			}
			$query .= " and type = ?";
			$argsType .= "s";
			
			$paramsQuestionArray = array();
			if($refId != null)
				array_push($paramsQuestionArray, $refId);
			array_push($paramsQuestionArray, $type);
			
			
			$resultSet = dbo::executePreparedStatement($query, $paramsQuestionArray, $argsType);
			$loginUserList = array();
			if($resultSet != false){
				while($row = mysqli_fetch_array($resultSet)){
					$user = new Login();
					$user->populateByRow($row);
					array_push($loginUserList, $user);
				}
			}
			return $loginUserList;
		}
		
		
		public static function updateFirebaseForMobileAndRefId($mobileNumber, $refId, $type, $firebaseId){
			$query = "UPDATE `login_detail` SET `firebaseId` = '".$firebaseId."' WHERE `userid` = '".$mobileNumber."' and `type` = '".$type."' and `ref_id` = '".$refId."';";
			//echo $query;
			dbo::updateRecord($query);
		}
	}
?>
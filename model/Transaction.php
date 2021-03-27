<?php include_once __DIR__."/dbo.php"; ?>
<?php include_once __DIR__."/Utility.php"; ?>
<?php
	class Transaction{
		
		public $id;
		public $entityId;
		public $entityType;
		public $amount;
		public $comment;
		public $dateTime;
		
		public static function getTransactionsByEntity($entityId, $entityType, $limit){
			$query = "select * from transaction where entity_id = ".$entityId." and entity_type = '".$entityType."' order by datetime desc ";
			if(is_numeric($limit))
				$query .= " limit ".$limit;
			$resultSet = dbo::getResultSetForQuery($query);
			$trans=array();
			if($resultSet != false){
				while($row = mysqli_fetch_array($resultSet)){
					$tran = new Transaction();
					$tran->populateByRow($row);
					array_push($trans, $tran);
				}
			}
			return $trans;
		}
		
		public function populateByRow($row){
			$this->id = $row['id'];
			$this->entityId = $row['entity_id'];
			$this->entityType = $row['entity_type'];
			$this->amount = $row['amount'];
			$this->comment = $row['comment'];
			$this->dateTime = $row['datetime'];
		}
		
		public function toMapObject(){
			$map = array();
			$map['id'] = $this->id;
			$map['entity_id'] = $this->entityId;
			$map['entity_type'] = $this->entityType;
			$map['amount'] = $this->amount;
			$map['comment'] = $this->comment;
			$map['datetime'] = $this->dateTime;
			$map['datetimeString'] = (new DateTime($this->dateTime))->format('d-M-Y h:i A');
			return $map;
		}
		
		public static function addTranslaction($entityId, $entityType, $amount, $comment, $dateTime){
			
			$insertQuery = "INSERT INTO `transaction` (`id`, `entity_id`, `entity_type`, `amount`, `comment`, `datetime`) VALUES (NULL, '".$entityId."', '".$entityType."', '".$amount."', '".$comment."', '".$dateTime."');";
			dbo::insertRecord($insertQuery);
		}
	}
?>
<?php include_once __DIR__."/dbo.php"; ?>
<?php include_once __DIR__."/Utility.php"; ?>
<?php include_once __DIR__."/Transaction.php"; ?>
<?php
	class Wallet{
		
		public $id;
		public $entityId;
		public $entityType;
		public $balance;
		
		public static function getWalletByEntity($entityId, $entityType){
			$query = "select * from wallet where entity_id = ".$entityId." and entity_type = '".$entityType."'";
			$resultSet = dbo::getResultSetForQuery($query);
			if($resultSet != false){
				$row = mysqli_fetch_array($resultSet);
				$wal = new Wallet();
				$wal->populateByRow($row);
				return $wal;
			}
		}
		
		public function populateByRow($row){
			$this->id = $row['id'];
			$this->entityId = $row['entity_id'];
			$this->entityType = $row['entity_type'];
			$this->balance = $row['balance'];
		}
		
		public function toMapObject(){
			$map = array();
			$map['id'] = $this->id;
			$map['entity_id'] = $this->entityId;
			$map['entity_type'] = $this->entityType;
			$map['balance'] = $this->balance;
			return $map;
		}
		
		public static function updateBalance($entityId, $entityType, $amount, $comment){
			$dateTimeObj = new DateTime();
			$dateTime = $dateTimeObj->format('Y-m-d H:i:s');
			$walletRow = Wallet::getWalletByEntity($entityId, $entityType);
			if(!$walletRow){
				$insertQuery = "INSERT INTO `wallet` (`id`, `entity_id`, `entity_type`, `balance`) VALUES (NULL, '".$entityId."', '".$entityType."', '".$amount."');";
				dbo::insertRecord($insertQuery);
				Transaction::addTranslaction($entityId, $entityType, $amount, $comment, $dateTime);
			}else{
				$balance = $walletRow->balance + $amount;
				$updateQuery = "UPDATE `wallet` SET `balance` = '".$balance."' WHERE `wallet`.`id` = ".$walletRow->id.";";
				dbo::insertRecord($updateQuery);
				Transaction::addTranslaction($entityId, $entityType, $amount, $comment, $dateTime);
			}
		}
	}
?>
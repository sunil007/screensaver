<?php include_once __DIR__."/dbo.php"; ?>
<?php include_once __DIR__."/Utility.php"; ?>
<?php
	class PolicyOrder{
		
		public $id;
		public $policyId;
		public $orderCreated;
		public $gatewayOrderId;
		public $orderStatus;

		
		public static function getPolicyOrderByGatewayOrderId($orderId){
			$query = "select * from policy_order where gateway_order_id = ".$orderId.";";
			$resultSet = dbo::getResultSetForQuery($query);
			if($resultSet != false){
				$row = mysqli_fetch_array($resultSet);
				$policy = new PolicyOrder();
				$policy->populateByRow($row);
				return $policy;
			}
		}
		
		public function populateByRow($row){
			$this->id = $row['id'];
			$this->policyId = $row['policy_id'];
			$this->gatewayOrderId = $row['gateway_order_id'];
			$this->orderStatus = $row['order_status'];
			if($row['order_created'] != null && $row['order_created'] != "")
				$this->orderCreated = new DateTime($row['order_created']);
			else
				$this->orderCreated = null;
				
		}
		
		public function toMapObject(){
			$map = array();
			$map['id'] = $this->id;
			$map['policy_id'] = $this->policyId;
			$map['gateway_order_id'] = $this->gatewayOrderId;
			$map['order_status'] = $this->orderStatus;
			if($this->orderCreated != null)
				$map['order_created'] = $this->orderCreated->format('Y-m-d H:i:s');
			else
				$map['order_created'] = "";
		}

		public static function getMaxId(){
			$maxIdQuery = "select max(id) as id from policy_order";
			$maxIdResultSet = dbo::getResultSetForQuery($maxIdQuery);
			if($maxIdResultSet){
				$maxRow = mysqli_fetch_array($maxIdResultSet);
				$maxid = $maxRow['id'];
				return $maxid;
			}
			return false;
		}
		
		public static function createNewPolicyOrderEntry($policy_id){
			$maxPolicyOrderid = PolicyOrder::getMaxId();
			$newId = $maxPolicyOrderid + 1;
			$currentTimeString = (new DateTime())->format('Y-m-d H:i:s');
			$query = "
				INSERT INTO `policy_order` (`id`, `policy_id`, `order_created`, `gateway_order_id`, `order_status`) VALUES (NULL, '".$policy_id."', '".$currentTimeString."', '', 'UnPaid');";
			dbo::insertRecord($query);
			return $newId;
		}
		
		public static function updatePolicyGatewayOrderId($id, $gateway_order_id){
			$query = "UPDATE `policy_order` SET `gateway_order_id` = '".$gateway_order_id."' WHERE `id` = ".$id.";";
			dbo::insertRecord($query);
			return $newId;
		}
		
		public static function updatePolicyOrderStatusPaid($id, $status){
			$query = "UPDATE `policy_order` SET `order_status` = '".$status."' WHERE `id` = ".$id.";";
			dbo::insertRecord($query);
			return $newId;
		}
	}
?>
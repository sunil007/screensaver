<?php
	class Mobile{
		
		public function getCompanyList(){
			$query = "select distinct company from mobile where status = 1";
			$resultSet = dbo::getResultSetForQuery($query);
			$company=array();
			if($resultSet != false){
				while($row = mysqli_fetch_array($resultSet)){
					array_push($company, $row['company']);
				}
			}
			return $company;
		}
		
		public function getMobileModel($companyName, $model = null){
			$query = "select * from mobile where company = '".$companyName."' and status = 1 ";
			if($model != null){
				$query .= " and model = '".$model."' ";
			}
			$resultSet = dbo::getResultSetForQuery($query);
			$company=array();
			if($resultSet != false){
				while($row = mysqli_fetch_array($resultSet)){
					$dataObj = array();
					$dataObj['id'] = $row['id'];
					$dataObj['model'] = $row['model'];
					$dataObj['price'] = $row['price'];
					$dataObj['policyAmount'] = Policy::calculatePolicyPrice($row['price']);
					$dataObj['tax'] = Policy::calculatePolicyTax($dataObj['policyAmount']);
					$dataObj['totalPrice'] = $dataObj['policyAmount']+$dataObj['tax'];
					array_push($company, $dataObj);
				}
			}
			return $company;
		}
		
		public function addMobile($com, $mod, $price){
			$checkModel = $this->getMobileModel($com, $mod);
			if(sizeof($checkModel) > 0){
				/*Update Price*/
				foreach($checkModel as $model){
					$query = "UPDATE `mobile` SET `price` = '".$price."' WHERE `id` = ".$model['id'].";";
					dbo::updateRecord($query);
				}
			}else{
				/*Insert Model*/
				$query = "INSERT INTO `mobile` (`id`, `company`, `model`, `price`, `status`) VALUES (NULL, '".$com."', '".$mod."', '".$price."', '1');";
				dbo::insertRecord($query);
			}
			return 200;
		}
	}
?>
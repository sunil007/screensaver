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
		
		public function getMobileModel($companyName){
			$query = "select * from mobile where company = '".$companyName."' and status = 1";
			$resultSet = dbo::getResultSetForQuery($query);
			$company=array();
			if($resultSet != false){
				while($row = mysqli_fetch_array($resultSet)){
					$dataObj = array();
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
	}
?>
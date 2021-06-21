<?php include_once __DIR__."/dbo.php"; ?>
<?php
	class Securevault{
		
		public $id;
		public $name;
		public $value;
		
		public function populateByRow($row){
			$this->id = $row['id'];
			$this->name = $row['name'];
			$this->value = $row['value'];
		}
		
		public function toMapObject(){
			$map = array();
			$map['id'] = $this->id;
			$map['name'] = $this->name;
			$map['value'] = $this->value;
			return $map;
		}
		
		public static function getByNames($names){
			$namesVal = "";
			foreach(explode(",",$names) as $name){
				$namesVal .= "'".$name."',";
			}
			if(substr($namesVal, -1) == ",") $namesVal = substr_replace($namesVal ,"",-1);
			$query = "select * from securevault where name in (".$namesVal.");";
			//echo $query;
			$resultSet = dbo::getResultSetForQuery($query);
			$scvMap = array();
			if($resultSet != false){
				while($row = mysqli_fetch_array($resultSet)){
					$scv = new Securevault();
					$scv->populateByRow($row);
					$scvMap[$scv->name]= $scv;
				}
			}
			return $scvMap;
		}
	}
?>
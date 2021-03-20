<? include_once __DIR__."/Login.php"; ?>
<?php
	class FireBaseNotification{
		private static $key = 'AAAAQ3OxKi0:APA91bF0dUCFsTkCsmMRYNTSoiIomyYIM1bKlVLb202uI4fo2D2ic29grnbSSNE55jEDght_Wh3MrNlkYyjBJOg301mu3pCabutw5uKRBNNeSQICfAoUqkwevuJA_uVDMzX5WU54J-7F';
		public static function SendNotificationToUser($refid, $userType, $title, $text){
			
			$logins = Login::getLoginByRefIdAndType($refid, $userType);
			$usersKeyArray = array();
			foreach($logins as $login){
				array_push($usersKeyArray, $login->firebaseId);
			}
			
			define( 'API_ACCESS_KEY', FireBaseNotification::$key );
			{
				//$msg = array('body' 	=> $text,'title'	=> $title, 'click_action' => 'classmatrix.scheduledetail.activity');
				$msg = array('body' 	=> $text,'title'	=> $title);
				
				if(!isset($data)){
					$data = array();
				}
				//array_merge($data,$msg);
				$data['TITLE'] = $title;
				$data['BODY'] = $text;
				$fields = array('registration_ids'=> $usersKeyArray, 'notification'	=> $msg);
				$headers = array('Authorization: key=' . API_ACCESS_KEY,'Content-Type: application/json');
			
				#Send Reponse To FireBase Server	
				$ch = curl_init();
				curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
				curl_setopt( $ch,CURLOPT_POST, true );
				curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
				curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, true );
				curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
				/*AS OF NOW STOPING FIRE BASE NOTIFICATION*/
				$result = curl_exec($ch );
				curl_close( $ch );
				
				#Echo Result Of FireBase Server
				//echo $result;
			}
			
		}
	}

?>
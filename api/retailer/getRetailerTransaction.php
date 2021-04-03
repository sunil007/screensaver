<?php
	include_once __DIR__."/../config/timezone.php";
	include_once __DIR__."/../config/retailer_security.php";
	include_once __DIR__."/../../model/entity.php";
		
	$mobile = $_POST['mobile'];
	$token = $_POST['token'];
	$userId = Token::getTokenUserId($token, $mobile);
	$retailer = Retailer::getRetailerById($userId);
	if(!$retailer){
		echo Response::getFailureResponse(null, 419);exit(0);
	}
	
	$transactionCount = (isset($_POST['count']) && is_numeric($_POST['count']))?$_POST['count']:20;
	
	if($retailer){
		if($retailer->status == 1){
			$retailerTransaction = Transaction::getTransactionsByEntity($retailer->id, $retailer->type, $transactionCount);
			$retailerWallet = Wallet::getWalletByEntity($retailer->id, $retailer->type);
			$responseMap = array();
			$responseMap['balance'] = 0;
			$responseMap['transaction'] = array();
			if($retailerWallet)
				$responseMap['balance'] = $retailerWallet->balance;
			if($retailerTransaction){
				foreach($retailerTransaction as $tran){
					array_push($responseMap['transaction'], $tran->toMapObject());
				}
			}
			echo Response::getSuccessResponse($responseMap, 200);
			
			
			
		}else if($retailer->status == -1){
			echo Response::getFailureResponse(null, 408);
		}else{
			echo Response::getFailureResponse(null, 410);
		}
	}else{
		echo Response::getFailureResponse(null, 407);
	}
?>
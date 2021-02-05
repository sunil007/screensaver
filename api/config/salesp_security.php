<?php include_once __DIR__."/../../model/Token.php"; ?>
<?php include_once __DIR__."/../../model/Response.php"; ?>
<?php 
	if(isset($_POST['mobile']) && isset($_POST['token'])){
		$tokenUserType = Token::getTokenUserType($_POST['token'], $_POST['mobile']);
		if($tokenUserType && ($tokenUserType == 'SALESP')){
			//Valid SalesP Call
		}else{
			echo Response::getFailureResponse(null, 405);
			exit(0);
		}
	}else{
		echo Response::getFailureResponse(null, 409);
		exit(0);
	}
?>
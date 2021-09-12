<?php include_once '../../model/entity.php';?>
<?php include_once '../../model/PolicyOrder.php';?>
<?php 
	if(!isset($_GET['policyId']) || !isset($_GET['userId']))
		exit(0);
?>
<?php
	include __DIR__."/../../model/libs/pdf/PDF.php";
	$user = User::getUserById($_GET['userId']);
	$policy = Policy::getPolicyById($_GET['policyId']);
	$policyOrder = PolicyOrder::getPolicyOrderForPoilicy("'Paid','Refunded'",$_GET['policyId']);
	
	if(!$user || !$policy || $user->status != 1 || !$policyOrder || $policy->userId != $user->id)
		exit(0);
	
	
	$pdf = new PDF();
	$pdf->setDefaults("Invoice #".$policyOrder->id);
	/*output the result*/

	/*set font to arial, bold, 14pt*/
	$pdf->SetFont('Arial','B',20);

	/*Cell(width , height , text , border , end line , [align] )*/

	$pdf->Cell(71 ,10,'',0,0);
	$pdf->Cell(59 ,5,'Invoice #'.$policyOrder->id,0,0);
	$pdf->Cell(59 ,10,'',0,1);

	$pdf->SetFont('Arial','B',15);
	$pdf->Cell(71 ,5,CompanyDetails::$name,0,0);
	$pdf->Cell(59 ,5,'',0,0);
	$pdf->Cell(59 ,5,'Details',0,1);

	$pdf->SetFont('Arial','',10);

	$pdf->Cell(130 ,5,CompanyDetails::$addressLine1,0,0);
	$pdf->Cell(25 ,5,'Name:',0,0);
	$pdf->Cell(34 ,5,$user->name,0,1);

	$pdf->Cell(130 ,5,CompanyDetails::$addressLine2,0,0);
	$pdf->Cell(25 ,5,'Invoice Date:',0,0);
	$pdf->Cell(34 ,5,$policyOrder->orderCreated->format('d M, Y'),0,1);
	 
	$pdf->Cell(130 ,5,'',0,0);
	$pdf->Cell(25 ,5,'',0,0);
	$pdf->Cell(34 ,5,'',0,1);


	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(130 ,5,"GST : ".CompanyDetails::$gst,0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(59 ,5,'Mob: '.$user->mobile,0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(189 ,10,'',0,1);

	$pdf->Cell(50 ,10,'',0,1);

	$pdf->SetFont('Arial','',10);
	

	$headerArray = array("Sr","Description","AMC No.","Price (Rs.)","CGST (9%)","SGST (9%)","Total (Rs.)");
	$widthArray = array(10,90,20,25,25,25,30);
	$alighArray = array("C","L","R","C","C","C");
	$dataArray = array();
	$description = "AMC ".$policy->mobileCompany." ".$policy->mobileModel." ".$policy->mobileIMEI ;//."\n"."Status : ".$policy->status;
	if($policy->status == 'Active')
		$description .= "\nExpires On : ". $policy->dateOfExpiration->format('d-M-Y');
	$dataRow = array(
		"1",
		$description, 
		$policy->id,
		$policy->policyPrice,
		number_format($policy->policyTax/2,1),
		number_format($policy->policyTax/2,1),
		$policy->policyTotalAmount
	);
	array_push($dataArray,$dataRow);
	$pdf->drawEasyTable("",$headerArray, $dataArray, $widthArray, $alighArray);

	foreach(CompanyDetails::$invoiceTerms as $text){
		$pdf->drawTextLine($text, false);
	}

	$pdf->Output('I','Invoice '.$policyOrder->id.'.pdf');
		
?>
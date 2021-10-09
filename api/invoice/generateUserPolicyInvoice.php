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
	$pdf->setDefaults("Invoice #".$policyOrder->no);
	/*output the result*/

	/*set font to arial, bold, 14pt*/
	$pdf->SetFont('Arial','B',12);

	/*Cell(width , height , text , border , end line , [align] )*/

	$pdf->Cell(71 ,10,'',0,0);
	$pdf->Cell(49 ,10,'',0,0);
	$pdf->Cell(59 ,10,'',0,1);

	$pdf->Cell(71 ,5,'',0,0);
	$pdf->Cell(40 ,5,'',0,0);
	$pdf->Cell(35 ,5,'Invoice Number ',0,0);
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(34 ,5,': '.$policyOrder->no,0,1);

	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(71 ,5,'',0,0);
	$pdf->Cell(40 ,5,'',0,0);
	$pdf->Cell(35 ,5,'Invoice Date ',0,0);
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(34 ,5,': '.$policyOrder->orderCreated->format('d/m/Y'),0,1);

	$pdf->Cell(71 ,10,'',0,0);
	$pdf->Cell(40 ,10,'',0,0);
	$pdf->Cell(59 ,10,'',0,1);


  $pdf->Image('../../assets/images/logo.png',25,10,30,32);
	$pdf->Cell(71 ,10,'',0,0);
	$pdf->Cell(40 ,5,'',0,0);
	$pdf->Cell(59 ,10,'',0,1);

	$pdf->SetFont('Arial','B',13);
	$pdf->Cell(71 ,5,CompanyDetails::$name,0,0);
	$pdf->Cell(40 ,5,'',0,0);
	$pdf->Cell(59 ,5,'Customer',0,1);

	$pdf->SetFont('Arial','',10);


	$pdf->Cell(71 ,5,CompanyDetails::$addressLine1,0,0);
	$pdf->Cell(40 ,5,'',0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(13 ,5,'Name :',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(56 ,5,$user->name,0,1);


	$pdf->Cell(71 ,5,CompanyDetails::$addressLine2,0,0);
	$pdf->Cell(40 ,5,'',0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(17 ,5,'Address :',0,0);
 	$pdf->SetFont('Arial','',10);
	$pdf->Cell(52 ,5,$user->address_line1,0,1);


		$pdf->Cell(71 ,5,'',0,0);
		$pdf->Cell(40 ,5,'',0,0);
		$pdf->Cell(17 ,5,'',0,0);
    	$pdf->SetFont('Arial','',10);
		$pdf->Cell(52 ,5,$user->address_line2,0,1);


	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(71 ,5,"GST : ".CompanyDetails::$gst,0,0);

	$pdf->Cell(40 ,5,'',0,0);
	$pdf->Cell(16 ,5,'Mobile :',0,0);
  	$pdf->SetFont('Arial','',10);
	$pdf->Cell(53 ,5,$user->mobile,0,1);

	$pdf->Cell(50 ,10,'',0,1);

	$pdf->SetFont('Arial','',10);


	$headerArray = array("Sr","Description","AMC No.","Price (Rs.)","CGST (9%)","SGST (9%)","Total (Rs.)");
	$widthArray = array(10,60,50,25,25,25,30);
	$alighArray = array("C","L","R","C","C","C");
	$dataArray = array();
	$description = "AMC ID - ".$policy->id."\n".$policy->mobileCompany." ".$policy->mobileModel." ".$policy->mobileIMEI ;//."\n"."Status : ".$policy->status;
	if($policy->status == 'Active')
		$description .= "\nExpiry Date : ". $policy->dateOfExpiration->format('d-M-Y');
	// $dataRow = array(
	// 	"1",
	// 	$description,
	// 	$policy->id,
	// 	$policy->policyPrice,
	// 	number_format($policy->policyTax/2,1),
	// 	number_format($policy->policyTax/2,1),
	// 	$policy->policyTotalAmount
	// );
	// array_push($dataArray,$dataRow);
	// // $dataRow = array("","","","","","");
	// // array_push($dataArray,$dataRow);
	// $pdf->drawEasyTable("",$headerArray, $dataArray, $widthArray, $alighArray);
	//Header
	$pdf->SetFillColor(204 , 204, 204);
  	$pdf->Cell(8 ,5,'Sr',1,0,'',true);
		$pdf->Cell(85 ,5,'Description',1,0,'',true);
		$pdf->Cell(20 ,5,'HSN',1,0,'',true);
		$pdf->Cell(17 ,5,'Price(Rs)',1,0,'',true);
		$pdf->Cell(17 ,5,'CGST(9%)',1,0,'',true);
		$pdf->Cell(17 ,5,'SGST(9%)',1,0,'',true);
		$pdf->Cell(27 ,5,'Total(Rs)',1,1,'',true);


		$pdf->Cell(8 ,15,'1',1,0,'');
		$current_y = $pdf->GetY();
		$current_x = $pdf->GetX();
		$pdf->MultiCell(85 ,5,$description,1,'');

		$pdf->SetXY($current_x + 85, $current_y);
    $current_x = $pdf->GetX();
			$pdf->Cell(20 ,15,CompanyDetails::$hsn,1,0,'');
		$pdf->Cell(17 ,15,$policy->policyPrice,1,0,'');
		$pdf->Cell(17 ,15,	number_format($policy->policyTax/2,1),1,0,'');
		$pdf->Cell(17 ,15,	number_format($policy->policyTax/2,1),1,0,'');
		$pdf->Cell(27 ,15,$policy->policyTotalAmount,1,1,'');

    	$pdf->SetFont('Arial','B',10);
		$pdf->Cell(130 ,6,'Total : ',1,0,'');
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(17 ,6,	number_format($policy->policyTax/2,1),1,0,'',true);
		$pdf->Cell(17 ,6,	number_format($policy->policyTax/2,1),1,0,'',true);
		$pdf->Cell(27 ,6,$policy->policyTotalAmount,1,1,'',true);
		//$pdf->MultiCell(71 ,10,'afffffffffffffffffffffffffff ffffffffffffffffffffffffffffff asfdddddddddd',1);
	//	$pdf->Cell(71 ,10,'',1,1);
	$pdf->ln(10);
  	$pdf->SetFont('Arial','B',12);
	 	$pdf->Cell(71 ,10,'Terms and Conditions',0,1);
			$pdf->SetFont('Arial','',10);
	foreach(CompanyDetails::$compantTerms as $text){
		$pdf->drawEasyTextLine($text, false);
	}

	$pdf->Output('I','Invoice '.$policyOrder->no.'.pdf');

?>

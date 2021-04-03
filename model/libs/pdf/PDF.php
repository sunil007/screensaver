 <?php
	require(__DIR__.'/fpdf/fpdf.php');
	include_once __DIR__."/easyTable.php";
	include_once __DIR__."/exfpdf.php";
	class PDF extends exFPDF
	{
		
		public static $marginX = 10;
		public static $marginY = 10;
		
		public static $pageMaxY = 265;
		public static $pageMaxX = 190;
		public static $tableHeaderRowHeight = 7;
		public static $tableDataRowHeight = 6;
		
		function setDefaults($title){
			$this->SetFont('Arial','',10);
			$this->SetTitle($title, array(true));
			$this->AddPage();
		}
		
		function drawPageImageHeader($source, $logoHeight, $logoWidth, $headerText, $otherText){

			$x = PDF::$marginX;
			$y = PDF::$marginY;
			$height = $logoHeight;
			$width = $logoWidth;
			 
			
			if(strstr($source, ".jpg") || strstr($source, ".JPG")) 
				$this->Image($source,$x,$y,$width,$height,'JPG');
			else if(strstr($source, ".png") || strstr($source, ".PNG")) 
				$this->Image($source,$x,$y,$width,$height,'PNG');
			else
				$this->Image($source,$x,$y,$width,$height,'JPG');
			
			$this->SetX($width+$x+5);
			$this->SetFont('','B');
			$this->MultiCell(0, 8, $headerText, 0, 'L', false);
			
			if(isset($otherText) && $otherText != null){
				$this->SetX($width+$x+5);
				$this->SetFont('','');
				$this->MultiCell(0, 8, $otherText, 0, 'L', false);
			}
			
			$this->SetY($y+$height+5, true);
			$this->blanRow();
			$this->SetY($y+$height+10, true);
		}
		
		function drawImage($source, $x, $y, $height){
			if(strpos($source, "jpg") != -1 || strpos($source, "JPG") != -1) 
				$this->Image($source,$x,$y,$height,0,'JPG');
			else if(strpos($source, "PNG") != -1 || strpos($source, "PNG") != -1) 
				$this->Image($source,$x,$y,$height,0,'PNG');
			else
				$this->Image($source,$x,$y,$height,0,'JPG');
			
			$this->SetY($y+$height+5, true);
		}
		
		function drawEasyTable($tableTitle, $header, $data, $cellWidths, $cellAlignment){
			
			if($tableTitle != Null && $tableTitle != "")
				$this->drawEasyTextLine($tableTitle, true);
			
			$percentString = sizeof($header);
			
			if(sizeof($cellWidths) == sizeof($header)){
				$totalWidth = 0;
				foreach($cellWidths as $cellWidth){
					$totalWidth += $cellWidth;
				}
				$totalAssign = 0;
				$percentString = '%{';
				$count = 0;
				
				foreach($cellWidths as $cellWidth){
					$cellPercentWidth = number_format(($cellWidth/$totalWidth)*100, 0);
					$count = $count + 1;
					if($count == sizeof($cellWidths)){
						$remainingCellWidth = 100 - $totalAssign;
						$percentString .= $remainingCellWidth;
						$totalAssign += $remainingCellWidth;
					}else{
						$percentString = $percentString.$cellPercentWidth.',';
						$totalAssign += $cellPercentWidth;
					}
				}
				$percentString .= "}";
			}
			
			$table=new easyTable($this, $percentString, 'border:1;border-color:#ccc;');
			foreach($header as $headerText){
				 $table->easyCell($headerText, 'bgcolor:#cccccc;valign:M');
			}
			$table->printRow(true);
			$rcounter = 0;
			foreach($data as $row){
				foreach($row as $rowText){
					if($rcounter%2 == 0)
						$table->easyCell($rowText,'valign:M;');
					else
						$table->easyCell($rowText, 'bgcolor:#ececec;valign:M;');
				}
				$rcounter++;
				$table->printRow();
			}
			$table->endTable(4);
		}
		
		
		function drawTable($tableTitle, $header, $data, $cellWidths, $cellAlignment){
			if($tableTitle != Null && $tableTitle != "")
				$this->drawTextLine($tableTitle, true);
			$this->drawTableHeader($header, $cellWidths, $cellAlignment);
			
			$fill = false;
			foreach($data as $row)
			{
				$getY = $this->GetY();
				if($getY > PDF::$pageMaxY){
					$this->blanRow();
					$this->AddPage();
					$this->drawTableHeader($header, $cellWidths, $cellAlignment);
				}
				$this->drawTableData($row, $cellWidths, $fill, $cellAlignment);
				$fill = !$fill;
			}
			$this->blanRow();
			$this->Ln();
			//$this->Cell(array_sum($w),0,'','T');
		}
		
		function drawBlankLine(){
			$this->SetY($this->GetY()+5);
		}
		
		function drawTextLine($text, $bold){
			if($bold)
				$this->SetFont('','B');
			else
				$this->SetFont('','');
			$this->Cell(PDF::$pageMaxX,PDF::$tableHeaderRowHeight,$text,0,0,'L',false);
			$this->SetFont('','');
			$this->Ln();
		}
		
		function drawEasyTextLine($text, $bold){
			$table=new easyTable($this, 1, 'border:0;');
			$this->SetFont('','');
			if($bold)
				$table->easyCell("<b>".$text."</b>");
			else
				$table->easyCell($text);
			$table->printRow();
			$table->endTable(4);
		}
		
		
		function drawTableHeader($header, $cellWidths, $cellAlignment){
			$this->SetFillColor(251,251,251);
			$this->SetTextColor(51,51,51);
			$w = $cellWidths;
			for($i=0;$i<count($header);$i++)
				$this->Cell($w[$i],PDF::$tableHeaderRowHeight,$header[$i],1,0,$cellAlignment[$i],true);
			$this->Ln();
		}
		
		function drawTableData($row, $cellWidths, $fill, $cellAlignment){
			$w = $cellWidths;
			$this->SetFillColor(237,237,237);
			$this->SetTextColor(51,51,51);
			$this->SetFont('');

			for($i=0;$i<count($row);$i++){
				$this->Cell($w[$i],PDF::$tableDataRowHeight,$row[$i],'LR',0,$cellAlignment[$i],$fill);
			}
			$this->Ln();
		}
		
		function blanRow(){
			$this->Cell(PDF::$pageMaxX,0,"",1,0,'C',true);
		}
	}
?>
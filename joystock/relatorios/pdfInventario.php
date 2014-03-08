<?php
	
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	include_once(PATH_FUNCAO."/fpdf.php");
	include_once(PATH_FUNCAO."/CTRLCodeSession.class.php");
	
	/* Iniciando cripto para session */
	$CodeSession = new CodeSession();
	
	$pdf = new FPDF();
	
	$BDFB = new FB();
	$BDFB->Conecta();
	
	//print_r($_POST);
	
	$query = "SELECT T1.COD_PRD, T1.NME_PRD, T1.QTD_PRD FROM ".
			 "upcsprd_tb T1 ".
			 "WHERE T1.EXC_UPCSPRD IS NULL AND T1.TPO_PRD = '1' ORDER BY T1.COD_PRD DESC";
	$dados = $BDFB->FBSelect($query);
	
    //$TamPag = array('297','212');
    $pdf->FPDF('P','mm',"A4"); 
    $zoom='default';
    $pdf->SetDisplayMode($zoom);
    $pdf->SetAutoPageBreak(TRUE,15); //TIRA A QUEBRA DE PAGINA AUTOMATICA
    $pdf->AliasNbPages();
    $pdf->SetMargins(10,10);
    $pdf->AddPage();
    
    $pdf->SetFont("arial", 'B', 15);
	$pdf->SetFillColor(102);
	$pdf->SetDrawColor(50);
	$pdf->SetTextColor(50);
    
	  	
    $pdf->Cell(190,10,utf8_decode('Relatório do Estoque'),0,1,'C',0,'');
    
    $pdf->Ln(5);
    
    $pdf->SetTextColor(255);
    $pdf->Cell(45,5,utf8_decode('CÓDIGO'),1,0,'C',1,'');
    $pdf->Cell(100,5,utf8_decode('PRODUTO'),1,0,'C',1,'');
    $pdf->Cell(45,5,utf8_decode('QUANTIDADE'),1,1,'C',1,'');
    
    	
	$pdf->SetTextColor(25);	
	$pdf->SetFont("arial", '', 8);
	while ($row = @mysql_fetch_array($dados)){
		$pdf->Cell(45,5,str_pad($row['COD_PRD'],5,0,STR_PAD_LEFT),1,0,'C',0,'');
		$pdf->Cell(100,5,$row['NME_PRD'],1,0,'L',0,'');
    	$pdf->Cell(45,5,utf8_decode(abs($row['QTD_PRD'])),1,1,'C',0,'');
    	$total += $row['VLR']; 
	}
	
	
	$pdf->Ln(10);
	$cordY = $pdf->GetY();
    $pdf->SetDrawColor(200,200,200);
    //$pdf->Line(10,$cordY,200,$cordY);
    
    
    $pdf->Output('Inventario.pdf',I);
?>
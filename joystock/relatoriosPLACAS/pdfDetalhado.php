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
	
	$where .= !empty($_GET['COD_CLN'])?" AND T3.COD_CLN = {$_GET['COD_CLN']} ":"";
	$where .= !empty($_GET['COD_GCL'])?" AND COD_GCL = {$_GET['COD_GCL']} ":"";
	$where .= !empty($_GET['DTA_VN1'])?" AND DTA_MVM >= '".VerifData($_GET['DTA_VN1'])."' ":"";
	$where .= !empty($_GET['DTA_VN2'])?" AND DTA_MVM <= '".VerifData($_GET['DTA_VN2'])."' ":"";
	
	
	$query = "SELECT T1.COD_PRD, T1.COD_MVM, T1.HST_PRD, T3.DTA_MVM, T2.NME_PRD, NME_CLN, T1.QTD_PRD AS QTD, T1.VLR_TPR AS VLR FROM ".
			 "upcsmpr_tb T1 ".
			 "JOIN upcsprd_tb T2 on T1.COD_PRD = T2.COD_PRD ".
			 "JOIN upcsmvm_tb T3 on T1.COD_MVM = T3.COD_MVM ".
			 "JOIN upcscln_tb T4 on T3.COD_CLN = T4.COD_CLN ".
			 "WHERE T2.EXC_UPCSPRD IS NULL AND T1.TPO_MVM = '2' $where ORDER BY T1.COD_PRD DESC";
	$dados = $BDFB->FBSelect($query);
	
    $TamPag = array('297','212');
    $pdf->FPDF('P','mm',$TamPag); 
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
    
	  	
    $pdf->Cell(277,10,utf8_decode('Relatório Detalhado de Vendas'),0,1,'C',0,'');
    
    $pdf->SetFont("arial", '', 12);
    $pdf->Cell(277,5,utf8_decode("Período: {$_GET['DTA_VN1']} à {$_GET['DTA_VN2']}"),0,1,'C',0,'');
    if (!empty($_GET['NME_CLN'])){
    	$pdf->Cell(277,5,utf8_decode("Cliente: {$_GET['NME_CLN']}"),0,1,'C',0,'');
    }	
    
    if (!empty($_GET['COD_GCL'])){
    	$query = "SELECT DSC_GCL FROM upcsgcl_tb WHERE COD_GCL = {$_GET['COD_GCL']}";
    	$dados2 = $BDFB->FBSelect($query);
    	$row = @mysql_fetch_array($dados2);
    	$pdf->Cell(277,5,utf8_decode("Grupo de Clientes: {$row['DSC_GCL']}"),0,1,'C',0,'');
    }
    
    $pdf->Ln(5);
    
    $pdf->SetTextColor(255);
    $pdf->Cell(20,5,utf8_decode('ORDEM'),1,0,'C',1,'');
    $pdf->Cell(78,5,utf8_decode('PRODUTO'),1,0,'C',1,'');
    $pdf->Cell(20,5,utf8_decode('PLACA'),1,0,'C',1,'');
    $pdf->Cell(20,5,utf8_decode('DATA'),1,0,'C',1,'');
	$pdf->Cell(79,5,utf8_decode('CLIENTE'),1,0,'C',1,'');    
    $pdf->Cell(30,5,utf8_decode('QUANTIDADE'),1,0,'C',1,'');
    $pdf->Cell(30,5,utf8_decode('VALOR'),1,1,'C',1,'');
    	
	$pdf->SetTextColor(25);	
	$pdf->SetFont("arial", '', 8);
	while ($row = @mysql_fetch_array($dados)){
		
		$atual = $row['COD_PRD'];
		
		if ($msm == $atual || abs($msm) == 0){
    		$parcial += $row['VLR'];
    		$QTDparcial += $row['QTD'];
    	}else {
    		$pdf->SetTextColor(255);
   			$pdf->Cell(10,5,'',0,0,'L',0,'');
   			$pdf->Cell(207,5,'TOTAL',1,0,'L',1,'');
   			$pdf->Cell(30,5,$QTDparcial*1,1,0,'C',1,'');
   			$pdf->Cell(30,5,number_format($parcial, 2, ",", "."),1,1,'C',1,'');
   			$parcial = $row['VLR'];
   			$QTDparcial = $row['QTD'];
    	}
		
    	$pdf->SetTextColor(25);
    	$pdf->Cell(20,5,str_pad($row['COD_MVM'],5,0,STR_PAD_LEFT),1,0,'C',0,'');
    	$pdf->Cell(78,5,utf8_decode($row['NME_PRD']),1,0,'L',0,'');
    	$pdf->Cell(20,5,utf8_decode($row['HST_PRD']),1,0,'C',0,'');
		$pdf->Cell(20,5,$BDFB->MostrarCampo("date",$row['DTA_MVM']),1,0,'C',0,'');
		$pdf->Cell(79,5,$row['NME_CLN'],1,0,'L',0,'');
    	$pdf->Cell(30,5,utf8_decode(abs($row['QTD'])),1,0,'C',0,'');
    	$pdf->Cell(30,5,number_format($row['VLR'], 2, ",", "."),1,1,'C',0,'');
    	
    	$msm = $atual;
    	
    	$total += $row['VLR']; 
    	$qtdTotal += $row['QTD']; 
	}
	 $pdf->SetTextColor(255);
	$pdf->Cell(10,5,'',0,0,'L',0,'');
	$pdf->Cell(207,5,'TOTAL',1,0,'L',1,'');
   	$pdf->Cell(30,5,$QTDparcial*1,1,0,'C',1,'');
   	$pdf->Cell(30,5,number_format($parcial, 2, ",", "."),1,1,'C',1,'');
	
   	$pdf->Ln(2);
   	
	$pdf->SetFont("arial", '', 10);
	$pdf->Cell(217,5,'TOTAL GERAL',1,0,'L',1,'');
	$pdf->Cell(30,5,$qtdTotal,1,0,'C',1,'');
	$pdf->Cell(30,5,number_format($total, 2, ",", "."),1,1,'C',1,'');
	
	$pdf->Ln(10);
	$cordY = $pdf->GetY();
    $pdf->SetDrawColor(200,200,200);
    //$pdf->Line(10,$cordY,200,$cordY);
    
    
    $pdf->Output('Vendas.pdf',I);
?>
<?php
	session_start();
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
	
	
	
   

    //$TamPag = array('210','150');
    $pdf->FPDF('P','mm','A4'); 
    $zoom='default';
    $pdf->SetDisplayMode($zoom);
    $pdf->SetAutoPageBreak(TRUE,10); //TIRA A QUEBRA DE PAGINA AUTOMATICA
    $pdf->AliasNbPages();
    $pdf->SetMargins(10,5,10,5);
    $pdf->AddPage();
    
    
    if (!empty($_GET['COD'])) {
	  	$query = "SELECT T2.VLR_UNT, T2.HST_PRD, T2.QTD_PRD, T2.VLR_DPR, T2.VLR_TPR, T3.NME_PRD, T4.DSC_RSM, T1.VLR_DSC, T1.VLR_TTL FROM upcsmvm_tb T1 ".
				 "JOIN upcsmpr_tb T2 ON T1.COD_MVM = T2.COD_MVM ".
				 "JOIN upcsprd_tb T3 ON T2.COD_PRD = T3.COD_PRD ".
				 "JOIN upcsumd_tb T4 ON T3.COD_UMD = T4.COD_UMD ".
				 "WHERE T1.COD_MVM = {$_GET['COD']}";	
		$hoje = date("d/m/Y");
    	
		$dados = $BDFB->FBSelect($query);
		
		$query = "SELECT * FROM upcsmvm_tb T1 ".
				 "JOIN upcscln_tb T2 ON T1.COD_CLN = T2.COD_CLN ".
				 "WHERE T1.COD_MVM = {$_GET['COD']}";
		
		
		$dados_cln = $BDFB->FBSelect($query);
		$row_cln = @mysql_fetch_array($dados_cln);
		
    	
		
		
		$pdf->SetFont("arial", 'B', 15);
		$pdf->Ln(3);
		$pdf->Cell(190,7,utf8_decode("Plenitude Produtos Naturais"),1,1,'C',0,'');
		$pdf->Ln(1);
		$pdf->SetFont("arial", '', 8);
		$pdf->Cell(90,3,utf8_decode("Rua Rio de Janeiro, 1.991 - L.P. Pereira"),0,1,'C',0,'');
		$pdf->Cell(90,3,utf8_decode("Tel.: (37) 3214-3018"),0,0,'C',0,'');
		$pdf->SetFont("arial", 'B', 15);
		$pdf->SetTextColor(254, 24, 0);
		$pdf->Cell(50,3,utf8_decode("N°: ").str_pad($row_cln['COD_MVM'],5,0,STR_PAD_LEFT),0,0,'C',0,'');
		$pdf->SetTextColor(30, 30, 30);
		$pdf->SetFont("arial", 'B', 10);
		$pdf->Cell(50,3,"Data: ".$BDFB->MostrarCampo('date',$row_cln['DTA_EMS']),0,1,'C',0,'');
		$pdf->SetFont("arial", '', 8);
		$pdf->Cell(90,3,utf8_decode("CEP: 35502-024 - DIVINÓPOLIS - MINAS GERAIS"),0,1,'C',0,'');
		
		
		
		$pdf->Rect(10,15,190,10);
		
		$pdf->Line(100,15,100,25);
		$pdf->Line(150,15,150,25);
		
		$pdf->Rect(10,25,190,8);
		$pdf->Rect(10,33,190,10);
		
		$pdf->Ln(2);
		
		
		$pdf->SetTextColor(0);
	    $pdf->SetFont("arial", 'B', 10);
	    $pdf->Cell(190,5,utf8_decode('RECIBO DE VENDA'),0,0,'C',0,'');
	    $pdf->Ln(7);
	    
	    $pdf->SetFont("arial", '', 9);
	    $pdf->Cell(150,5,"CLIENTE: ".$row_cln['NME_CLN'],0,0,'L',0,'');
	    
	    /*if ($row_cln['TPO_PSS'] == 1){
	    	$pdf->Cell(40,5,"CNPJ: ".$row_cln['CNJ_CLN'],0,1,'R',0,'');
	    }else {
	    	$pdf->Cell(40,5,"CPF/CNPJ: ".$row_cln['CNJ_CLN'],0,1,'R',0,'');
	    }
	    
	    $pdf->Cell(190,5,utf8_decode("ENDEREÇO: ").$row_cln['END_CLN'].", ".$row_cln['NMR_END']." - ".$row_cln['BRR_CLN'],0,1,'L',0,'');
	    $pdf->Cell(160,5,utf8_decode("CIDADE: ").$row_cln['CDD_CLN'],0,0,'L',0,'');*/
	    $pdf->Cell(30,5,utf8_decode("TEL.: ").$row_cln['TEL_001'],0,0,'R',0,'');
	    
	    
	    $pdf->Ln(5);
	    $pdf->SetFillColor(102);
		$pdf->SetDrawColor(50);
		$pdf->SetTextColor(255);
	    
	    
		/*$pdf->Cell(20,5,'U. Medida',1,0,'C',1,'');*/
		$pdf->Cell(130,5,'Produto',1,0,'C',1,0);
		$pdf->Cell(20,5,utf8_decode('Qtd'),1,0,'C',1,'');
		$pdf->Cell(20,5,'Valor Unt.',1,0,'C',1,0);
		$pdf->Cell(20,5,'Valor',1,1,'C',1,0);
		
		
		$pdf->SetFont("arial", '', 7);
		$pdf->SetTextColor(10);
		
		while ($row = @mysql_fetch_array($dados)){
			
			$valorUnitario = number_format($row['VLR_UNT'],2,",",".");
			
			if ($row['VLR_DSC'] > 0){
				$desconto = number_format($row['VLR_DSC'],2,",",".");
			}
			
			$total_geral = number_format($row['VLR_TTL'],2,",",".");
			
			$geral += $row['VLR_TPR'];
			$total = number_format($row['VLR_TPR'],2,",",".");
			
			//print_r($row);
			
			/*$pdf->Cell(20,5,utf8_decode($row['DSC_RSM']),1,0,'C',0,'');*/
			$pdf->Cell(120,5,$row['NME_PRD'],1,0,'L',0,0);
			$pdf->Cell(20,5,utf8_decode($row['QTD_PRD']),1,0,'C',0,'');
			$pdf->Cell(20,5,utf8_decode($valorUnitario),1,0,'C',0,0);
			$pdf->Cell(20,5,utf8_decode($total),1,1,'C',0,0);
			
			
		}	
		
		$pdf->SetTextColor(255);
		$geral2 = number_format($geral,2,",",".");
		$pdf->Cell(190,5,utf8_decode("TOTAL:  ".$geral2),1,1,'R',1,0);
		
		$pdf->SetTextColor(50);
		if ($desconto > 0){
			$pdf->Cell(190,5,utf8_decode("DESCONTO:  -".$desconto),1,1,'R',0,0);
			$pdf->SetTextColor(255);
			$pdf->SetFont("arial", 'B', 10);
			$pdf->Cell(190,5,utf8_decode("TOTAL:  ".$total_geral),1,1,'R',1,0);
		}
		
	/*$pdf->SetFont("arial", 'B', 10);
	$pdf->Ln(15);
	$cordY = $pdf->GetY();
    $pdf->SetDrawColor(0,0,0);
    $pdf->Line(70,$cordY,140,$cordY);
    $pdf->Ln(0);
    $pdf->Cell(70,5,'',0,0,'R',0,0);
    $pdf->Cell(50,5,"Assinatura",0,1,'C',0,0);*/
    
	    
	   	
		
    }
    
    
    $pdf->Output('Recibo.pdf',I);
?>
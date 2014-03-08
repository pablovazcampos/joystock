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
	
	
    //$TamPag = array('297','212');
    $pdf->FPDF('P','mm',"A4"); 
    $zoom='default';
    $pdf->SetDisplayMode($zoom);
    $pdf->SetAutoPageBreak(TRUE,15); //TIRA A QUEBRA DE PAGINA AUTOMATICA
    $pdf->AliasNbPages();
    $pdf->SetMargins(10,10);
    $pdf->AddPage();
    
    session_start();
    if (!empty($_SESSION['query_pdf'])) {
	  	
    	//RAIZ
    	if ($_SESSION['tipo'] == "treeview.root"){
			$query = $_SESSION['query_pdf'];
			
			$hoje = date("d/m/Y");
	    	
			$dados = $BDFB->FBSelect($query);
			
			
			
		    $pdf->Ln(9);
		    $pdf->SetTextColor(0);
		    $pdf->SetFont("arial", 'B', 10);
		    $pdf->Cell(190,5,utf8_decode('FLUXO DE CAIXA')." - ".$_GET['INI'].utf8_decode(" à ").$_GET['FIM'],0,0,'C',0,'');
		    
		    $pdf->Ln(9);
		    $pdf->SetFillColor(102);
			$pdf->SetDrawColor(50);
			$pdf->SetTextColor(255);
		    
		    $pdf->Cell(100,5,utf8_decode('Conta'),1,0,'L',1,'');
			$pdf->Cell(30,5,'Data',1,0,'C',1,'');
			$pdf->Cell(30,5,utf8_decode('Crédito'),1,0,'C',1,0);
			$pdf->Cell(30,5,utf8_decode('Débito'),1,1,'C',1,0);
			
			
			
			$pdf->SetFont("arial", '', 9);
			$pdf->SetTextColor(10);
			while ($row = @mysql_fetch_array($dados)){
				
				if ($row['TPO_MVM'] == 1){
					$total += $row['VLR_MVM'];
					$totalCredito += $row['VLR_MVM'];
					$credito = number_format($row['VLR_MVM'],2,",",".");
					$debito = "";
				}elseif ($row['TPO_MVM'] == 2){
					$total -= $row['VLR_MVM'];
					$totalDebito += $row['VLR_MVM'];
					$debito = number_format($row['VLR_MVM'],2,",",".");
					$credito = "";
				}
				
				$pdf->Cell(100,5,$row['DSC_CDC'],1,0,'L',0,'');
				$pdf->Cell(30,5,$BDFB->MostrarCampo("date",$row['DTA_MVM']),1,0,'C',0,'');
				$pdf->Cell(30,5,$credito,1,0,'C',0,0);
				$pdf->Cell(30,5,$debito,1,1,'C',0,0);			
				
			}
			
			
			$pdf->SetFont("arial", 'B', 12);
			$pdf->SetFillColor(102);
			$pdf->SetDrawColor(50);
			$pdf->SetTextColor(255);
			
			$pdf->Cell(130,8,"",1,0,'L',1,'');
			$pdf->Cell(30,8,number_format($totalCredito,2,",","."),1,0,'C',1,'');
			$pdf->Cell(30,8,number_format($totalDebito,2,",","."),1,1,'C',1,0);
			
			$pdf->SetFont("arial", 'B', 15);
			$pdf->Cell(190,12,"SALDO:   ".number_format($total,2,",","."),1,1,'R',1,0);
		//CATEGORIAS E SUBRAIZ	
    	}elseif ($_SESSION['tipo'] == 3 || $_SESSION['tipo'] == 4){
    		
    		$query = $_SESSION['query_pdf'];
			
			$hoje = date("d/m/Y");
	    	
			$dados = $BDFB->FBSelect($query);
			
			
			
		    $pdf->Ln(9);
		    $pdf->SetTextColor(0);
		    $pdf->SetFont("arial", 'B', 10);
		    if ($_SESSION['tipo'] == 3){
		    	$pdf->Cell(190,5,utf8_decode('SERVIÇOS')." - ".$_GET['INI'].utf8_decode(" à ").$_GET['FIM'],0,0,'C',0,'');
		    }else {
		    	$pdf->Cell(190,5,utf8_decode('TAXAS')." - ".$_GET['INI'].utf8_decode(" à ").$_GET['FIM'],0,0,'C',0,'');
		    }
		    
		    $pdf->Ln(9);
		    $pdf->SetFillColor(102);
			$pdf->SetDrawColor(50);
			$pdf->SetTextColor(255);
		    
		    $pdf->Cell(15,5,utf8_decode('Ordem'),1,0,'C',1,'');
			$pdf->Cell(73,5,'Cliente',1,0,'C',1,'');
			$pdf->Cell(60,5,utf8_decode('Veículo'),1,0,'C',1,0);
			$pdf->Cell(22,5,'Data',1,0,'C',1,0);
			$pdf->Cell(20,5,'Valor',1,1,'C',1,0);
			
			
			
			$pdf->SetFont("arial", '', 7);
			$pdf->SetTextColor(10);
			while ($row = @mysql_fetch_array($dados)){
				//print_r($row);
				$total += $row['VLR_MVM'];
				$pdf->Cell(15,5,str_pad(utf8_decode($row['COD_OSR']),5,0,STR_PAD_LEFT),1,0,'C',0,'');
				$pdf->Cell(73,5,utf8_decode($row['NME_CLN']),1,0,'L',0,'');
				$pdf->Cell(60,5,utf8_decode($row['DSC_VCL']),1,0,'L',0,0);
				$pdf->Cell(22,5,$BDFB->MostrarCampo("date", $row['DTA_MVM']),1,0,'C',0,0);
				$pdf->Cell(20,5,number_format($row['VLR_MVM'],2,',','.'),1,1,'C',0,0);
				
				
				
			}
			
			
			$pdf->SetFillColor(102);
			$pdf->SetDrawColor(50);
			$pdf->SetTextColor(255);
			
			
			
			$pdf->SetFont("arial", 'B', 15);
			$pdf->Cell(190,12,"TOTAL:   ".number_format($total,2,",","."),1,1,'R',1,0);
    	//NODOS	
    	}else {
    		
    		$query = $_SESSION['query_pdf'];
			
			
		    $pdf->Ln(9);
		    $pdf->SetTextColor(0);
		    $pdf->SetFont("arial", 'B', 10);
		    
		    $pdf->Cell(190,5,$_GET['INI'].utf8_decode(" à ").$_GET['FIM'],0,0,'C',0,'');
		    
		    $pdf->Ln(9);
		    $pdf->SetFillColor(102);
			$pdf->SetDrawColor(50);
			$pdf->SetTextColor(255);
		    
		    $pdf->Cell(100,5,utf8_decode('Conta'),1,0,'C',1,'');
			$pdf->Cell(45,5,'Data',1,0,'C',1,0);
			$pdf->Cell(45,5,'Valor',1,1,'C',1,0);
			
			
			
			$pdf->SetFont("arial", '', 7);
			$pdf->SetTextColor(10);
			foreach ($query AS $sub){
				//print_r($row);
				$total += $sub[1];
				$pdf->Cell(100,5,$sub[2],1,0,'L',0,'');
				$pdf->Cell(45,5,$BDFB->MostrarCampo("date", $sub[0]),1,0,'C',0,0);
				$pdf->Cell(45,5,number_format($sub[1],2,',','.'),1,1,'C',0,0);
				
				
				
			}
			
			
			$pdf->SetFillColor(102);
			$pdf->SetDrawColor(50);
			$pdf->SetTextColor(255);
			
			
			
			$pdf->SetFont("arial", 'B', 15);
			$pdf->Cell(190,12,"TOTAL:   ".number_format($total,2,",","."),1,1,'R',1,0);
    		
    	}
		
			
		
	}
	
	$pdf->Ln(10);;
	$cordY = $pdf->GetY();
    $pdf->SetDrawColor(200,200,200);
    //$pdf->Line(10,$cordY,200,$cordY);
    
    
    $pdf->Output('Ordens.pdf',I);
?>
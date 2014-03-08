<?
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	include_once(PATH_FUNCAO."/CTRLCodeSession.class.php");
	
	$BDFB = new FB();
	$BDFB->Conecta();
	$CodeSession = new CodeSession();
	//$CodeSession->ValidaPagina();
	
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	
	
	if (empty($_GET['COD'])){
		$_GET['COD'] = $_POST['COD'];
		$_GET['INI'] = $_POST['INI'];
		$_GET['FIM'] = $_POST['FIM'];
	}
	
	
	//treeview.root
	
	if(!empty($_GET['COD'])){
		
		//print $_GET['INI']."wqwqw";
		$INI = $BDFB->TratarCampo("date",$_GET['INI']);
		$FIM = $BDFB->TratarCampo("date",$_GET['FIM']);
		
		$query = "SELECT COD_CTT, IMG_CDC, TPO_CDC FROM upcscdc_tb WHERE COD_CDC = {$_GET['COD']}";
		$dados = $BDFB->FBSelect($query);
		$row_CTT = @mysql_fetch_array($dados);
		
		if (!empty($_POST['COD'])){
			$hoje = "'".date("Y-m-d")."'";
			$valor = str_replace(".","",$_POST['VLR_MVM']);
			$valor = str_replace(",",".",$valor);
			$data = $BDFB->TratarCampo("date", $_POST['DTA_MVM']);
			if ($row_CTT['TPO_CDC'] == 2){
				$tipo = 1;
			}else {
				$tipo = 2;
			}
			
			$query = "INSERT INTO upcsmcc_tb VALUES(0,'{$tipo}', {$_GET['COD']}, $valor, $data, null )";
			if (!$BDFB->EXECQuery($query)){
				$msg = "Falha ao salvar lançamento!";
			}
		}
		
		
		
		$_SESSION['tipo'] = $_GET['COD'];
		
		if ($_GET['COD'] != "treeview.root"){
			if ($row_CTT['IMG_CDC'] == "tri.png"){
				$ctt = "COD_CTT";
			}else {
				$ctt = "COD_CDC";
			}
			
			$cod = $_GET['COD'];
			$res = array();
			$_SESSION['query_pdf'] = $res = listaContas($cod, $res, $ctt, $INI, $FIM);
			//print_r($res);
			
		}else{

			$_SESSION['query_pdf'] = $query = "SELECT T2.DSC_CDC, T1.TPO_MVM, T1.VLR_MVM, T1.DTA_CDS  FROM upcsmcc_tb T1 ".
											  "JOIN upcscdc_tb T2 ON T1.COD_CDC = T2.COD_CDC ".
											  "LEFT JOIN upcsmpr_tb T3 ON T1.COD_MCC = T3.COD_MCC ".
											  "LEFT JOIN upcsmvm_tb T4 ON T3.COD_MVM = T4.COD_MVM ".
											  "WHERE T1.DTA_CDS >= $INI AND T1.DTA_CDS <= $FIM ORDER BY T1.DTA_CDS DESC ";
			$dadosTotal = $BDFB->FBSelect($query);
			
		}
		
		/*else {	
			
			$cod = $_GET['COD'];
			$res = array();
			$_SESSION['query_pdf'] = $res = listaContas($cod, $res, "COD_CDC", $INI, $FIM);
			//print_r($res);
			//print count($res);
			
			
		}*/
			
	}
	
	
	function listaContas($codigo, $res, $aux, $ini, $fim){
		
		$BDFB = new FB();
		$BDFB->Conecta();
		
		$query = "SELECT * FROM upcscdc_tb WHERE $aux = $codigo ORDER BY COD_CDC ASC";
		$dados1 = $BDFB->FBSelect($query);
		while ($row1 = @mysql_fetch_array($dados1)){
			if ($row1['IMG_CDC'] == "tri.png"){
				$subRes = listaContas($row1['COD_CDC'], array(), "COD_CTT", $ini, $fim);
				foreach ($subRes as $sub){
					array_push($res, $sub);
				}
			}else {
				$query = "SELECT T4.DTA_MVM, VLR_MVM, DSC_CDC, NME_CLN FROM upcsmcc_tb T1 ".
						 "JOIN upcscdc_tb T2 ON T1.COD_CDC = T2.COD_CDC ".
						 "LEFT JOIN upcsmpr_tb T3 ON T1.COD_MCC = T3.COD_MCC ".
						 "LEFT JOIN upcsmvm_tb T4 ON T3.COD_MVM = T4.COD_MVM ".
						 "JOIN upcscln_tb T5 ON T4.COD_CLN = T5.COD_CLN ".
						 "WHERE T1.COD_CDC = {$row1['COD_CDC']} AND T4.DTA_MVM >= $ini AND T4.DTA_MVM <= $fim ";
				$dados2 = $BDFB->FBSelect($query);
				while ($row2 = @mysql_fetch_array($dados2)){
					array_push($res, array($row2['DTA_MVM'], $row2['VLR_MVM'], $row2['DSC_CDC'], $row2['NME_CLN']));
				}
				
				if (count($res) == 0){
					$query = "SELECT T1.DTA_CDS, VLR_MVM, DSC_CDC FROM upcsmcc_tb T1 ".
							 "JOIN upcscdc_tb T2 ON T1.COD_CDC = T2.COD_CDC ".
							 "WHERE T1.COD_CDC = {$row1['COD_CDC']} AND T1.DTA_CDS >= $ini AND T1.DTA_CDS <= $fim ";
					$dados2 = $BDFB->FBSelect($query);
					while ($row2 = @mysql_fetch_array($dados2)){
						array_push($res, array($row2['DTA_CDS'], $row2['VLR_MVM'], $row2['DSC_CDC']));
					}	
				}
				
			}
		}
		
		return $res;
	}
	
	

?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<script type="text/javascript" src="/joystock/pcte-common/CTRLGeral.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/jquery.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.maskedinput.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.maskMoney.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.validation.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/br.gov.dataprev.jquery.maskedinput.patterns.js"></script>
		
		<script type="text/javascript" src="/joystock/pcte-common/_js/corner.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/rc_autocompleter.js"></script>
	    <link rel="stylesheet" href="/joystock/pcte-common/estilos.css" type="text/css" media="all" />
	    <link rel="stylesheet" href="/joystock/pcte-common/e1024.css" type="text/css" media="all" />
	</head>
	
	<body  style="overflow:auto">
		<form id="form1" method="POST" action="lancamentos.php?width=<?=$_GET['width']?>&height=<?=$_GET['height']?>">
			<input type="hidden" name="COD" value="<?=$_GET['COD']?>">
			<input type="hidden" name="INI" value="<?=$_GET['INI']?>">
			<input type="hidden" name="FIM" value="<?=$_GET['FIM']?>">
			<center>
			<?
				if ($row_CTT['IMG_CDC'] == "square_greenS.gif"){
					$tam = 300;		
				}else{
					$tam = 270;
				}
			?>
			<div style="  height:-webkit-calc(100% - <?=$tam?>px); ">
				<?if ($_GET['COD'] != "treeview.root"):?>
				
					<?if ($row_CTT['IMG_CDC'] == "square_greenS.gif"): ?>
						<div id="LBL" style="float:left; padding-left:10px; position:relative; text-align:left; width:470px; margin-left:5px; ">	
							Data: <input class="dataFormat" style="width:100px; text-align:center" type="text" id="formulario" name="DTA_MVM" value="<?=date("d/m/Y")?>" onclick="this.select()">
							&nbsp;&nbsp;
							Valor: <input class="moedaFormat" style="width:100px; text-align:center" type="text" id="formulario" name="VLR_MVM" value="0,00" onclick="this.select()">
							<input style="position:absolute; right:15px; width:70px; text-align:center; height:30px; " type="submit" id="box1" name="salvar" value="Salvar">
						</div>
					
					<?endif;?>
					
					<div style="float:left; text-align:left; width:99%;  ">	
						<div style="float:left; padding:2px 0px; width:35%; border-right:solid 1px #FFF" id="legend2">Cliente/Fornecedor</div>
						<div style="float:left; padding:2px 0px; width:35%; border-right:solid 1px #FFF" id="legend2">Conta</div>
						<div style="float:left; padding:2px 0px; width:15%; border-right:solid 1px #FFF" id="legend2">Data</div>
						<div style="float:left; padding:2px 0px; width:-webkit-calc(15% - 3px)" id="legend2">Valor</div>
					</div>
					
					
					<div style="float:left; overflow-y:scroll; padding-top:2px; width:99%;  height:94%">	
						<?$x=1?>
						<?if (count($res) > 0)foreach ($res AS $sub):?>
							<?$cor = ($x%2)==0?"#EEE":"#DDD"?>
							<?$total += $row['VLR_MVM'];?>
							<div style="float:left; height:12px; font-size:10px; text-align:left; background-color:<?=$cor?>; padding:2px 0px; width:-webkit-calc((100% + 4px) - 65%); border-right:solid 1px #FFF; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" ><?=$sub[3]?></div>
							<div style="float:left; height:12px; font-size:10px; text-align:left; background-color:<?=$cor?>; padding:2px 0px; width:-webkit-calc((100% + 4px) - 65%); border-right:solid 1px #FFF; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" ><?=$sub[2]?></div>
							<div style="float:left; height:12px; font-size:10px; background-color:<?=$cor?>; padding:2px 0px; width:-webkit-calc((100% + 1px) - 85%); border-right:solid 1px #FFF" ><?=$BDFB->MostrarCampo("date",$sub[0])?></div>
							<div style="float:left; height:12px; font-size:10px; background-color:<?=$cor?>; padding:2px 0px; width:-webkit-calc(15% - 12px);"><?=number_format($sub[1],2,",",".")?></div>
							<?$x++?>
						<?endforeach;?>	
					</div>
					
					<div style="float:left; text-align:left; width:99%; margin-left:5px; ">	
						<table  width="100%"  >
							<tr valign="top">
								<td id="legend2" style="text-align:right" align="right" width="100%">TOTAL:&nbsp;&nbsp;&nbsp;<?=number_format($total,2,",",".")?></td>
							</tr>
						</table>
					</div>
					
				<?else:?>
					<div style="float:left; text-align:left; width:99%;  ">	
						<div style="float:left; padding:2px 0px; width:49%; border-right:solid 1px #FFF" id="legend2">Conta</div>
						<div style="float:left; padding:2px 0px; width:17%; border-right:solid 1px #FFF" id="legend2">Data</div>
						<div style="float:left; padding:2px 0px; width:17%; border-right:solid 1px #FFF" id="legend2">Crédito</div>
						<div style="float:left; padding:2px 0px; width:-webkit-calc(17% - 3px)" id="legend2">Débito</div>
					</div>
					
					
					<div style="float:left; overflow-y:scroll; width:99%; padding-top:2px; height:91% ">	
							<?$x=1?>
							<?while ($row = @mysql_fetch_array($dadosTotal)):?>
								<?$cor = ($x%2)==0?"#EEE":"#DDD"?>
								<?
									if ($row['TPO_MVM'] == 2){
										$total += $row['VLR_MVM'];
										$totalCredito += $row['VLR_MVM'];
										$credito = number_format($row['VLR_MVM'],2,",",".");
										$debito = "";
									}elseif ($row['TPO_MVM'] == 1){
										$total -= $row['VLR_MVM'];
										$totalDebito += $row['VLR_MVM'];
										$debito = number_format($row['VLR_MVM'],2,",",".");
										$credito = "";
									}
										
								?>
								<div style="float:left; font-size:10px; text-align:left; background-color:<?=$cor?>; height:12px; padding:2px 0px; width:-webkit-calc((100% + 6px) - 51%); border-right:solid 1px #FFF; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" ><?=$row['DSC_CDC']?></div>
								<div style="float:left; font-size:10px; background-color:<?=$cor?>; height:12px; padding:2px 0px; width:-webkit-calc((100% + 2px) - 83%); border-right:solid 1px #FFF; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" ><?=$BDFB->MostrarCampo("date",$row['DTA_CDS'])?></div>
								<div style="float:left; font-size:10px; background-color:<?=$cor?>; height:12px; padding:2px 0px; width:-webkit-calc((100% + 2px) - 83%); border-right:solid 1px #FFF" ><?=$credito?></div>
								<div style="float:left; font-size:10px; background-color:<?=$cor?>; height:12px; padding:2px 0px; width:-webkit-calc(17% - 13px);"><?=$debito?></div>
								<?$x++?>
							<?endwhile;?>	
					</div>
					
					<div style="float:left; text-align:left; width:99%;">	
						<div style="float:left; padding:2px 0px; width:66%;  height:15px; border-right:solid 1px #FFF" id="legend2"></div>
						<div style="float:left; padding:2px 0px; width:17%;  height:15px; border-right:solid 1px #FFF" id="legend2"><?=number_format($totalCredito,2,",",".")?></div>
						<div style="float:left; padding:2px 0px; width:-webkit-calc(17% - 2px);  height:15px; " id="legend2"><?=number_format($totalDebito,2,",",".")?></div>
					</div>
					
					<div style="float:left; text-align:left; width:99%; ">	
						<div style="float:left; text-align:right; padding:2px 0px; width:100%;  " id="legend2">
							TOTAL:&nbsp;&nbsp;&nbsp;<label style="color:<?=($total >= 0)?"#83b2ff":"#ff8383"?>"><?=number_format($total,2,",",".")?></label>
						</div>
					</div>
				<?endif;?>
				
				
				
			</div>
				
			</center>
		</form>
	</body>	
</html>
<?php
	session_start();
	
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	include_once(PATH_FUNCAO."/CTRLBDados.php");
	include_once(PATH_FUNCAO."/breg.php");
	include_once(PATH_FUNCAO."/XAJAX/xajax_core/xajax.inc.php");
	include_once(PATH_FUNCAO."/functionAjax.php");
	include_once(PATH_FUNCAO."/CTRLCodeSession.class.php");
	
	
	$ajax = new xajax();
	$ajax->configure('javascript URI', '../pcte-common/XAJAX/');
	
	$ajax->registerFunction('AutoCompleterCliente');  
	$ajax->registerFunction('AutoCompleterProduto');
	$ajax->registerFunction('incluiProduto');
	$ajax->registerFunction('carregaMovimento');
	
		
    $ajax->processRequest();
	
	$BDFB = new FB();
	$BDFB->Conecta();
	$CodeSession = new CodeSession();
	
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	
	if($_POST["breg"] == "pesquisar"){
		header("Location: pesquisaMovimento.php");
		$_POST["breg"] = "";
		exit();		
	}
	
	
	if (!empty($_GET['COD'])){
		$query = "SELECT TPO_MVM FROM upcsmvm_tb WHERE COD_MVM = {$_GET['COD']}";
		$dados = $BDFB->FBSelect($query);
		$row = @mysql_fetch_array($dados);
		$_GET['new'] = 1;
		$_GET['TIPO'] = $row['TPO_MVM'];
	}
	
	
	
	if ($_GET['new'] == 1){
		$_SESSION['COD'] = "";
		$_SESSION['msg'] = "";
		$_SESSION['STS_BREG'] = 0;
		$_SESSION['TIPO'] = $_GET['TIPO']; 
		$_SESSION['status_sistema'] = "";
	}
	
	if($_POST["breg"] == "imprimir"){
		$_SESSION['close'] = "/joystock/movimentacao/venda.php";
		$_SESSION['FrmImprimir'] = "/joystock/impresso/recibo.php?COD={$_SESSION['COD']}";
		header("Location: ../pcte-common/print_index.php");
		$_POST["breg"] = "";
		exit();		
	}
	
	
	
	$corMsg = "#".COR;
	
	$sql = "SHOW TABLE STATUS FROM `".BANCO_DADOS."` LIKE 'upcsmvm_tb'";
	$dados = $BDFB->FBSelect($sql);
	$rss = mysql_fetch_array($dados);
	$codigo = $rss['Auto_increment'];
	
	$_POST['DTA_EMS'] = date("d/m/Y");
	$_POST['HRA_EMS'] = date("H:i:s");
	$_POST['TPO_MVM'] = $_SESSION['TIPO'];
	$_POST['COD_USR'] = $CodeSession->decode('COD_USR');
	
	
	
    
    
	
	$frmMaster			 = 'venda.php';							
	$NME_FRM 			 = 'venda';
	$DSC_FRM             = 'Venda';
	$ACTION 			 = 'venda.php';
	$_SESSION['NME_RTN'] = "venda.php";
	$TABELA              = "upcsmvm_tb"  ;
	
	
	
	
	
	require_once(PATH_FUNCAO."/CTRLReg.php");
	
	
	
	
	$capa = "inline";
	if ($_SESSION['status_sistema'] == 'novo' || $_SESSION['status_sistema'] == 'editar'){
		$capa = "none";
	}
	
	
	if ($_SESSION['status_sistema'] == 'salvar' && $_POST['breg'] == 'salvar'){
		
		//DELETA ESTOQUE DOS AGRUPADOS
		$query = "SELECT T3.COD_PRD, T3.QTD_PRD, T1.QTD_PRD AS MOV FROM upcsmpr_tb T1 ".
				 "JOIN upcsprd_tb T2 ON T1.COD_PRD = T2.COD_PRD ".
				 "JOIN upcskpr_tb T3 ON T2.COD_PRD = T3.COD_KIT ".
				 "WHERE COD_MVM = ".abs($CPOValue['COD_MVM'])." AND TPO_PRD = '2' ";
		$dados = $BDFB->FBSelect($query);
		while ($row = @mysql_fetch_array($dados)){
			if ($CPOValue['TPO_MVM'] == '1'){
				
				$tot = $row['QTD_PRD'] * $row['MOV'];
				$query = "UPDATE upcsprd_tb SET QTD_PRD = (QTD_PRD - {$tot}) WHERE COD_PRD = {$row['COD_PRD']}";
				$BDFB->EXECQuery($query);
				
			}elseif ($CPOValue['TPO_MVM'] == '2'){
				
				$tot = $row['QTD_PRD'] * $row['MOV'];
				$query = "UPDATE upcsprd_tb SET QTD_PRD = (QTD_PRD + {$tot}) WHERE COD_PRD = {$row['COD_PRD']}";
				$BDFB->EXECQuery($query);
				
			}
		}
		
		
		
		
		$query = "DELETE FROM upcsmpr_tb WHERE COD_MVM = ".abs($CPOValue['COD_MVM']);
		$BDFB->EXECQuery($query);
		
		for ($x=1;$_POST['CDG'.$x];$x++){
			$qtd = str_replace(".","",$_POST['QTD'.$x]);
			$qtd = str_replace(",",".",$qtd);
			$dsn = str_replace(".","",$_POST['DSN'.$x]);
			$dsn = str_replace(",",".",$dsn);
			$ttl = str_replace(".","",$_POST['TTL'.$x]);
			$ttl = str_replace(",",".",$ttl);
			$unt = str_replace(".","",$_POST['UNT'.$x]);
			$unt = str_replace(",",".",$unt);
			$query = "INSERT INTO upcsmpr_tb VALUES(".abs($CPOValue['COD_MVM']).", ".$_POST['CDG'.$x].", {$qtd}, ".
					 "{$dsn}, {$ttl}, {$_SESSION['TIPO']}, {$unt}, null, '{$_POST['HST'.$x]}' )";
			$BDFB->EXECQuery($query);
		}	
		
		//INCLUI ESTOQUE DOS AGRUPADOS
		$query = "SELECT T3.COD_PRD, T3.QTD_PRD, T1.QTD_PRD AS MOV FROM upcsmpr_tb T1 ".
				 "JOIN upcsprd_tb T2 ON T1.COD_PRD = T2.COD_PRD ".
				 "JOIN upcskpr_tb T3 ON T2.COD_PRD = T3.COD_KIT ".
				 "WHERE COD_MVM = ".abs($CPOValue['COD_MVM'])." AND TPO_PRD = '2' ";
		$dados = $BDFB->FBSelect($query);
		while ($row = @mysql_fetch_array($dados)){
			if ($CPOValue['TPO_MVM'] == '1'){
				
				$tot = $row['QTD_PRD'] * $row['MOV'];
				$query = "UPDATE upcsprd_tb SET QTD_PRD = (QTD_PRD + {$tot}) WHERE COD_PRD = {$row['COD_PRD']}";
				$BDFB->EXECQuery($query);
				
			}elseif ($CPOValue['TPO_MVM'] == '2'){

				$tot = $row['QTD_PRD'] * $row['MOV'];
				$query = "UPDATE upcsprd_tb SET QTD_PRD = (QTD_PRD - {$tot}) WHERE COD_PRD = {$row['COD_PRD']}";
				$BDFB->EXECQuery($query);
							
			}
		}
		
		
	}
	
	
	if (!empty($CPOValue['COD_MVM'])){
		$num = abs($CPOValue['COD_MVM']);
		$onload = "onload='xajax_carregaMovimento($num,{$_SESSION['TIPO']}); form=\"frmbreg\"'";
		
		$query = "SELECT NME_CLN FROM upcscln_tb WHERE COD_CLN = {$CPOValue['COD_CLN']}";
		$dados = $BDFB->FBSelect($query);
		$row = @mysql_fetch_array($dados);
		$nome = $row['NME_CLN'];
	}else {
		$onload = "onload='form=\"frmbreg\"'";
	}
	
	$tam = "10px";
	$color = "green";
	
	//BUSCA O USUÁRIO
	if (!empty($CPOValue['COD_USR'])){
		$query = "SELECT NME_USR FROM upcsusr_tb WHERE COD_USR = {$CPOValue['COD_USR']}";
		$dados = $BDFB->FBSelect($query);
		$rowuser = @mysql_fetch_array($dados);
	}
	
	
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		
		
		<script src="/joystock/pcte-common/_js/jquery.min.js"></script>
		<script>
	    	var dindin = {};
			dindin.Real = jQuery.noConflict(true);
	    </script>
		
		<script type="text/javascript" src="/joystock/pcte-common/CTRLGeral.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/mascaras.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/jquery.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.maskedinput.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.maskMoney.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.validation.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/br.gov.dataprev.jquery.maskedinput.patterns.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/rc_autocompleter.js"></script>
	    <link rel="stylesheet" href="/joystock/pcte-common/e1024.css" type="text/css" media="all" />
	    
	   
	    
	    
	    <link rel="stylesheet" href="/joystock/pcte-common/messi.css" type="text/css" media="all" />
		<script type="text/javascript" src="/joystock/pcte-common/_js/messi.js"></script>
		<?php $ajax->printJavascript();?>
</head>
<body  style="overflow-y:auto"   <?=$onload?>>
	<form id="frmbreg" name="frmbreg" target="" action="venda.php" method="POST" >
		<center>	
		<div  align="center" style="position:relative; margin-top:30px; width:950px; height:510px; z-index:99">
			<div id="letraAzul" style="font-size:18px; color:#e0d6c7; padding:10px" align="left" >
				<?if ($_SESSION['TIPO'] == 1):?>
					Compra
				<?else :?>	
					Venda
				<?endif;?>	
			</div>
			<div id="mLogin" style="position:relative; height:510px;  overflow-y:hidden; overflow-x:hidden">
				<div style="position:absolute;  right:10px; width:580px;">
					<?
						//$_SESSION['ADMIN']?"editar":""
						$BREG = BarraRegistro();
						$breg = new Breg($BREG[$estado],$ACTION);
						$breg->bregImprimir("","","","","","imprimir","","");
						$breg->defineBreg("novo","salvar","editar","cancelar","pesquisar","","",false);	
					?>
				</div>
				<?if (!empty($rowuser)):?>
					<div id="boxNot" style="position:absolute; font-size:12px; top:20px; left:10px; width:180px;">
						Usuário: <?=$rowuser['NME_USR']?>
					</div>
				<?endif;?>	
			
				<table   style="margin-top:60px" id="LBL" width='100%' cellpadding="2" cellspacing="5">
					<tr>
				   		<td colspan="2" id="msg"  bgcolor="<?=$corMsg?>"  height="10px" style="COLOR:#FFFFFF; font-size:11px; font-weight:bold; " colspan="6" align="center">
			   				<div id="valueMsg"><?=$_SESSION['msg']?></div>
				   		</td>
				   </tr>
				   <tr id="LBL">
				   		<td id="MRG" width="85%"  style='position:relative; border:solid 1px #<?=COR?>; color:#FFF; border-radius:3px;  padding:0px 0px 5px 0px;'>
				   			<div style="position:absolute; right:0px; top:0px; width:-webkit-calc(100% - 88px); height:100%; background-color:#333; opacity:0.8; display:<?=$capa?>"></div>
				   			<div style='float:left; width:80px;  padding:5px 5px 0px 5px;'>
					   			Código:
					   			<input onkeydown="return handleEnter(this, event);" id='numeracao'  disabled name='COD_MVM' type='text' value='<?=(empty($CPOValue['COD_MVM']))?str_pad($codigo,5,0,STR_PAD_LEFT):str_pad($CPOValue['COD_MVM'],5,0,STR_PAD_LEFT)?>'>
				   			</div>
				   			<div style='float:left; width:80px;  padding:5px 5px 0px 0px;'>
					   			Data:
					   			<input <?=empty($CPOValue['DTA_MVM'])?"value='".date('d/m/Y')."'":$CPO['DTA_MVM']?> onblur='if(this.value=="__/__/____"){this.value="<?=date('d/m/Y')?>"}' onkeydown="return handleEnter(this, event);" id="formulario" class="dataFormat"   type="text" name="DTA_MVM" style="width:100%; color:#666">
				   			</div>
				   			<div style='float:left; width:80px;  padding:5px 5px 0px 0px;'>
				   			Forma Pgto:
				   			<select <?=$readonly?> name="FRM_PGT" id="formulario" style="width:100%">
				   				<option <?=$CPOValue['FRM_PGT']==1?"selected":""?> value="1">Dinheiro</option>
				   				<option <?=$CPOValue['FRM_PGT']==2?"selected":""?> value="2">Cheque</option>
				   				<option <?=$CPOValue['FRM_PGT']==3?"selected":""?> value="3">Cartão</option>
				   			</select>
				   			</div>
				   			<div id="cliente" style='float:left; -webkit-transition:1s; width:-webkit-calc(100% - 560px);  padding:5px 5px 0px 0px;'>
					   			<?if ($_SESSION['TIPO'] == 1):?>Fornecedor<?else:?>Cliente<?endif;?>	
					   			<input <?=$readonly?> onkeydown=" return handleEnter(this, event);" value="<?=empty($_POST['NME_CLN'])?$nome:$_POST['NME_CLN']?>"  class="formularioAuto" autocomplete="off" id="edtAutocompleterCLN" name="NME_CLN" style="width:100%;  color:#666" onkeyup="rc_autocompleter(event, 'edtAutocompleterCLN', 'preview', 'AutoCompleterCliente', 'COD_CLN')" onblur="window.setTimeout('rc_invisible(\'preview\')',200); document.getElementById('cliente').style.width = '-webkit-calc(100% - 560px)'; document.getElementById('produto').style.width = '-webkit-calc(100% - 560px)'" onfocus=" document.getElementById('cliente').style.width = '-webkit-calc(100% - 460px)'; document.getElementById('produto').style.width = '-webkit-calc(100% - 660px)'" >
					   			<input id="COD_CLN" name="COD_CLN"  type="hidden" value="<?=empty($_POST['COD_CLN'])?$CPOValue['COD_CLN']:$_POST['COD_CLN']?>">
					   		</div>	
					   			
				   			<!--<div style='float:left; width:80px;  padding:5px 5px 0px 0px;'>
				   			Tipo:
				   			<select <?//$readonly?> name="TPO_VND" id="formulario" style="width:80px">
				   				<option <?//$CPOValue['TPO_VND']!=2?"selected":""?> value="1">Varejo</option>
				   				<option <?//$CPOValue['TPO_VND']==2?"selected":""?> value="2">Atacado</option>
				   			</select>
				   			</div>-->	
				   			
				   			<div id="produto" style='float:left; -webkit-transition:1s; width:-webkit-calc(100% - 560px);  padding:5px 0px 0px 0px;'>
					   			<div style="float:left;  width:35%">Produto:</div>
						  		<input autocomplete='off' style="width:100%;  font-size:<?=$tam?>; color:<?=$color?> "   type="text"  id="edtAutocompleter" class="formularioAuto" name="NME_PRD"  onkeyup="rc_autocompleter(event, 'edtAutocompleter', 'preview', 'AutoCompleterProduto', 'COD_PRD')" value="Pesquise aqui por código do produto, código de barras ou Descrição..." onclick="this.select()" onkeydown="bloquearCtrlJ(); return clicando(this, event);" onfocus=" document.getElementById('cliente').style.width = '-webkit-calc(100% - 660px)'; document.getElementById('produto').style.width = '-webkit-calc(100% - 460px)'" onblur="window.setTimeout('rc_invisible(\'preview\')',200); document.getElementById('cliente').style.width = '-webkit-calc(100% - 560px)'; document.getElementById('produto').style.width = '-webkit-calc(100% - 560px)'" >
								<input id="COD_PRD" name="COD_PRD"  type="hidden" >
						  	</div>		
						  	<div style='float:left; width:50px;  padding:19px 0px 0px 0px;'>
					   			<input name="BTN_PRD"  type="button" value="Incluir" id="box1" style="cursor:pointer; height:30px"  onclick="xajax_incluiProduto(this.form.COD_PRD.value, this.form.NME_PRD.value, this.form.COD_CLN.value, <?=$_SESSION['TIPO']?>); this.form.NME_PRD.focus()" >
				   			</div>	
				   		</td>
				   		<td id="MRG" width="15%" style='position:relative; border:solid 1px #<?=COR?>; color:#FFF; border-radius:3px; padding:0px 0px 5px 0px;'>
				   			<!--<div style="float:left; width:45%; padding:10px 0px 5px 5px">Desconto:</div>
				   			<div style="float:left;width:45%; padding:5px 5px 5px 0px">
				   				<input onkeyup="totalGeral(); "  onkeydown="return handleEnter(this, event);" style="width:100%; text-align:center" type="text"  name="VLR_DSC" value="<?//number_format($CPOValue['VLR_DSC'],2,",",'.')?>" class="moedaFormat">
				   			</div>-->
				   			<input onkeyup="totalGeral(); "  onkeydown="return handleEnter(this, event);" style="width:100%; text-align:center" type="hidden"  name="VLR_DSC" value="<?=number_format($CPOValue['VLR_DSC'],2,",",'.')?>" class="moedaFormat">
				   			<!--<div style="float:left; width:35%; padding:10px 0px 5px 5px">Total:</div>-->
				   			<div style="float:center;width:93%; padding:5px">
				   				Total:
				   				<input onfocus="this.form.DSC_PDT.focus();" onkeydown="return handleEnter(this, event);" id="numeracao" style="width:100%; text-align:center; " type="text" name="VLR_TTL" value="<?=number_format($CPOValue['VLR_TTL'],2,",",'.')?>" class="moedaFormat">
				   			</div>	
				   		</td>
				   		
				   </tr>	
				   	
				 
				  
				  
				  
				  	
				</table>
			
				<div style=" margin-top:0px; overflow-y:auto; height:340px">
					<table id="table1" style="text-align: left; width: 100%;  border="0" cellpadding="1" cellspacing="2">
							<tr style="background-color: #41967A;">
								<td id="legend2" style="width:3%; color:#FFFFFF; text-align: center;">
									
								</td>
								<td id="legend2" style="width:3%;color:#FFFFFF; text-align: center; ">
									Ítem
								</td>
								<td id="legend2" style="width:34%;color:#FFFFFF; text-align: center; ">
									Descrição
								</td>
								<td id="legend2" style="width:10%;color:#FFFFFF; text-align: center; ">
									Histórico
								</td>
								<td id="legend2" style="width:10%;color:#FFFFFF; text-align: center;">
									Quantidade
								</td>
								<td id="legend2" style="width:10%;color:#FFFFFF; text-align: center;">
									Unitário
								</td>
								<td id="legend2" style="width:10%;color:#FFFFFF; text-align: center;">
									Valor
								</td>
								<td id="legend2" style="width:10%;color:#FFFFFF; text-align: center;">
									Desconto(%)
								</td>
								<td id="legend2" style="width:10%;color:#FFFFFF; text-align: center;">
									Total
								</td>
							</tr>
					</table>
					<div id="vendas" ></div>	
				</div>
			</div>
		</div>
	</center>
	<input id="delete" type="hidden" name="delete" value="0">
	<div  id="preview" onmouseover="style.visibility='visible';"  style="visibility:visible; height:300px; z-index:99999" class="rc_autocompleter"></div>
	<script>
	   dindin.Real.noConflict ();
	   	(function($) {
			$(document).ready(function() {
	      		$("#delete").on('click', function() {
	          		new Messi('Deseja realmente excluir este ítem?', {title: 'Excluir Ítem', buttons: [{id: 0, label: 'SIM', val: 'Y', class: 'btn2-success'}, {id: 1, label: 'NÃO', val: 'N', class: 'btn2-danger'}], callback: function(val) { deleteRow(document.getElementById('delete').value,2) }});
	        	});
		    });
	    })(dindin.Real);
  
	</script>
</form>	
</body>
</html>

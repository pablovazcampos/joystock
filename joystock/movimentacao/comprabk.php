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
	
	$BDFB = new FB();
	$BDFB->Conecta();
	$CodeSession = new CodeSession();
	
	
	if($_POST["breg"] == "pesquisar"){
		header("Location: pesquisaCompra.php");
		$_POST["breg"] = "";
		exit();		
	}
	
	if ($_GET['new'] == 1){
		$_SESSION['COD'] = "";
		$_SESSION['msg'] = "";
		$_SESSION['STS_BREG'] = 0;
		
	}
	
	
	$corMsg = "#".COR;
	
	$sql = "SHOW TABLE STATUS FROM `".BANCO_DADOS."` LIKE 'upcsmvm_tb'";
	$dados = $BDFB->FBSelect($sql);
	$rss = mysql_fetch_array($dados);
	$codigo = $rss['Auto_increment'];
	
	$_POST['DTA_EMS'] = date("d/m/Y");
	$_POST['HRA_EMS'] = date("H:i:s");
	$_POST['TPO_MVM'] = 2;
	$_POST['COD_CLN'] = 1; //FORNECEDOR
	$_POST['COD_USR'] = $CodeSession->decode('COD_USR');
	
	
	$ajax = new xajax();
	$ajax->configure('javascript URI', '../pcte-common/XAJAX/');
	
	$ajax->registerFunction('AutoCompleterProduto');
	$ajax->registerFunction('incluiProduto');
	$ajax->registerFunction('carregaMovimento');
	
    $ajax->processRequest();
	
	$frmMaster			 = 'compra.php';							
	$NME_FRM 			 = 'compra';
	$DSC_FRM             = 'Compra';
	$ACTION 			 = 'compra.php';
	$_SESSION['NME_RTN'] = "compra.php";
	$TABELA              = "upcsmvm_tb"  ;
	
	
	require_once(PATH_FUNCAO."/CTRLReg.php");
	
	$disabled = "disabled";
	$capa = "inline";
	if ($_SESSION['status_sistema'] == 'novo' || $_SESSION['status_sistema'] == 'editar'){
		$disabled = "";
		$capa = "none";
	}
	
	
	if ($_SESSION['status_sistema'] == 'salvar' && $_POST['breg'] == 'salvar'){
		
		$query = "DELETE FROM upcsmpr_tb WHERE COD_MVM = ".abs($CPOValue['COD_MVM']);
		$BDFB->EXECQuery($query);
		
		for ($x=1;$_POST['CDG'.$x];$x++){
			$qtd = str_replace(".","",$_POST['QTD'.$x]);
			$qtd = str_replace(",",".",$qtd);
			$query = "INSERT INTO upcsmpr_tb VALUES(".abs($CPOValue['COD_MVM']).", ".$_POST['CDG'.$x].", {$qtd}, ".
					 "0, 0, 2 )";
			$BDFB->EXECQuery($query);
		}	
	}
	
	
	if (!empty($CPOValue['COD_MVM'])){
		$num = abs($CPOValue['COD_MVM']);
		$onload = "onload='xajax_carregaMovimento($num,2); form=\"frmbreg\"'";
	}else {
		$onload = "onload='form=\"frmbreg\"'";
	}
	
	$tam = "10px";
	$color = "green";
	
	
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script type="text/javascript" src="/joystock/pcte-common/CTRLGeral.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/jquery.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.maskedinput.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.maskMoney.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.validation.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/br.gov.dataprev.jquery.maskedinput.patterns.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/rc_autocompleter.js"></script>
	    <link rel="stylesheet" href="/joystock/pcte-common/e1024.css" type="text/css" media="all" />
	    <link rel="stylesheet" href="/joystock/pcte-common/messi.css" type="text/css" media="all" />
	    <script src="/joystock/pcte-common/_js/jquery.min.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/messi.js"></script>
		<?php $ajax->printJavascript();?>
</head>
<body  style="overflow-y:hidden"   <?=$onload?>>
	<form id="frmbreg" name="frmbreg" target="" action="compra.php" method="POST" >
		<center>	
		<div  align="center" style="position:relative; margin-top:30px; width:650px; height:500px; z-index:99">
			<div id="letraAzul" style="font-size:18px; color:#e0d6c7; padding:10px" align="left" >
				Compra
			</div>
			<div id="mLogin" style="position:relative; height:500px;  overflow-y:hidden; overflow-x:hidden">
				<div style="position:absolute;  right:0px; width:580px;">
					<?
						$BREG = BarraRegistro();
						$breg = new Breg($BREG[$estado],$ACTION);
						$breg->defineBreg("novo","salvar","editar","cancelar","pesquisar","","fim",false);	
					?>
				</div>
			
				<table   style="margin-top:60px" id="LBL" width='100%' cellpadding="2" cellspacing="5">
					<tr>
				   		<td colspan="2" id="msg"  bgcolor="<?=$corMsg?>"  height="10px" style="COLOR:#FFFFFF; font-size:11px; font-weight:bold; " colspan="6" align="center">
			   				<div id="valueMsg"><?=$_SESSION['msg']?></div>
				   		</td>
				   </tr>
				   <tr id="LBL">
				   		<td id="MRG" width="100%"  style='position:relative; border:solid 1px #<?=COR?>; color:#FFF; border-radius:3px; width:100%; padding:0px 0px 5px 0px;'>
				   			<div style="position:absolute; right:0px; width:-webkit-calc(100% - 98px); height:100%; background-color:#333; opacity:0.8; display:<?=$capa?>"></div>
				   			<div style='float:left; width:80px;  padding:5px 5px 0px 15px;'>
					   			Código:
					   			<input onkeydown="return handleEnter(this, event);" id='numeracao'  disabled name='COD_MVM' type='text' value='<?=(empty($CPOValue['COD_MVM']))?str_pad($codigo,5,0,STR_PAD_LEFT):str_pad($CPOValue['COD_MVM'],5,0,STR_PAD_LEFT)?>'>
				   			</div>
				   			<div style='float:left; width:80px;  padding:5px 5px 0px 0px;'>
					   			Data:
					   			<input <?=$disabled?> <?=empty($CPOValue['DTA_MVM'])?"value='".date('d/m/Y')."'":$CPO['DTA_MVM']?> onblur='if(this.value=="__/__/____"){this.value="<?=date('d/m/Y')?>"}' onkeydown="return handleEnter(this, event);" id="formulario" class="dataFormat"   type="text" name="DTA_MVM" style="width:100%; color:#666">
				   			</div>	
				   			<div style='float:left;  width:-webkit-calc(100% - 265px);  padding:5px 15px 0px 0px;'>
					   			Produto:
						  		<input <?=$disabled?> autocomplete='off' style="width:100%; font-size:<?=$tam?>; color:<?=$color?> "   type="text"  id="edtAutocompleter" class="formularioAuto" name="NME_PRD"  onkeyup="rc_autocompleter(event, 'edtAutocompleter', 'preview', 'AutoCompleterProduto', 'COD_PRD')" value="Pesquise aqui por código do produto, código de barras ou Descrição..." onclick="this.select()" onkeydown="return clicando(this, event);" onblur="window.setTimeout('rc_invisible(\'preview\')',200);" >
								<input id="COD_PRD" name="COD_PRD"  type="hidden" >
						  	</div>		
						  	<div style='float:left; width:50px;  padding:18px 0px 0px 0px;'>
					   			<input name="BTN_PRD"  type="button" value="Incluir" id="box1" style="cursor:pointer; height:30px"  onclick="xajax_incluiProduto(this.form.COD_PRD.value, this.form.NME_PRD.value, 0,2); this.form.NME_PRD.focus()" >
				   			</div>
						  	
				   		</td>
				   </tr>	
				</table>
				
				<div style=" margin-top:0px;   ">
					<table id="table1" style="text-align: left; width: 100%;  border="0" cellpadding="1" cellspacing="2">
							<tr style="background-color: #41967A;">
								<td id="legend2" style="width:3%; color:#FFFFFF; text-align: center;">
									
								</td>
								<td id="legend2" style="width:3%;color:#FFFFFF; text-align: center; ">
									Ítem
								</td>
								<td id="legend2" style="width:44%;color:#FFFFFF; text-align: center; ">
									Descrição
								</td>
								<td id="legend2" style="width:10%;color:#FFFFFF; text-align: center;">
									Quantidade
								</td>
								
							</tr>
					</table>
					<div id="vendas"></div>	
				</div>
			</div>
		</div>
	</center>
	<input id="delete" type="hidden" name="delete" value="0">
	<div  id="preview" onmouseover="style.visibility='visible';"  style="visibility:hidden; height:200px; z-index:99999" class="rc_autocompleter"></div>
	<script>
	    jQuery.noConflict ();
	    (function($) {
	      $(document).ready(function() {
	        
	      	$("#delete").on('click', function() {
	          new Messi('Deseja realmente excluir este ítem da compra?', {title: 'Excluir Ítem', buttons: [{id: 0, label: 'SIM', val: 'Y', class: 'btn2-success'}, {id: 1, label: 'NÃO', val: 'N', class: 'btn2-danger'}], callback: function(val) { deleteRow(document.getElementById('delete').value,2) }});
	        });
	      	
	      });
	    })(jQuery);
  
	</script>
</form>	
</body>
</html>
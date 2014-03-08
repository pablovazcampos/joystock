<?php
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	include_once(PATH_FUNCAO."/CTRLBDados.php");
	include_once(PATH_FUNCAO."/breg.php");
	include_once(PATH_FUNCAO."/CTRLCodeSession.class.php");
	include_once(PATH_FUNCAO."/XAJAX/xajax_core/xajax.inc.php");
	include_once(PATH_FUNCAO."/functionAjax.php");
	
	$ajax = new xajax();
	$ajax->configure('javascript URI', '../pcte-common/XAJAX/');
	
	$ajax->registerFunction('AutoCompleterProduto');
	
		
    $ajax->processRequest();
	
	$CodeSession = new CodeSession();
	$BDFB = new FB();
	$BDFB->Conecta();

	
	
	if($_POST["breg"] == "pesquisar"){
		header("Location: pesquisaMovimentacaoManual.php");
		$_POST["breg"] = "";
		exit();		
	}
	
	
	$sql = "SHOW TABLE STATUS FROM `".BANCO_DADOS."` LIKE 'upcsmmn_tb'";
	$dados = $BDFB->FBSelect($sql);
	$rss = mysql_fetch_array($dados);
	$codigo = $rss['Auto_increment'];
	
	
	$_POST['DTA_CDS'] = date("d/m/Y");
	$_POST['COD_USR'] = $CodeSession->decode('COD_USR');

	
	//$_GET['COD'] = 1;
	
	//LIMPA O CÓDIGO SE VIER DE FORA
	if ($_GET['new'] == 1){
		$_SESSION['COD'] = "";
		$_SESSION['msg'] = "";
		
		
	}
	
	$corMsg = "#".COR;
	
	$estados = SiglaEstado();
	
	$frmMaster			 = 'movimentacaoManual.php';							
	$NME_FRM 			 = 'index';
	$DSC_FRM             = 'Movimentação Manual';
	$ACTION 			 = 'movimentacaoManual.php';
	$_SESSION['NME_RTN'] = "/movimentacao/movimentacaoManual.php";
	$TABELA              = 'upcsmmn_tb';
	$WHERE               = "";
	
	
	if (empty($_GET['COD'])){
		if (!empty($_SESSION['COD'])){
			$_POST['COD_MMN'] = $_SESSION['COD'];
		}
	}else {
		$_POST['COD_MMN'] = $_GET['COD'];
	}
	
	require_once(PATH_FUNCAO."/CTRLReg.php");
	
	
	$tam = "10px";
	$color = "green";
	
	
	if (!empty($CPOValue['COD_MMN'])){
		$num = abs($CPOValue['COD_MMN']);
		
		$query = "SELECT NME_PRD FROM upcsprd_tb WHERE COD_PRD = {$CPOValue['COD_PRD']}";
		$dados = $BDFB->FBSelect($query);
		$row = @mysql_fetch_array($dados);
		$nome = $row['NME_PRD'];
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
		<script type="text/javascript" src="/joystock/pcte-common/_js/rc_autocompleter.js"></script>
	    <link rel="stylesheet" href="/joystock/pcte-common/e1024.css" type="text/css" media="all" />
	    <?php $ajax->printJavascript();?>
	</head>
	<body onload='form="frmbreg"'>
		<center>
			<div style="margin-top:50px">
			<fieldset  style=" background-color:#FFFFFF;  width:600px; height:350px;  border-style: solid;border-width:2px; border-color:#<?=COR?>;  z-index: 11;">
				<legend  id="legend"  align="left"><?=$DSC_FRM?></legend>
				<div id="main" style="position:relative;  width:580px; margin-top:5px; height:350px">	
					<div style="position:absolute; top:-15px;  right:0px; width:550px;">
						<?
							$BREG = BarraRegistro();
							$breg = new Breg($BREG[$estado],$ACTION);
							$breg->defineBreg("novo","salvar","editar","cancelar","pesquisar","");	
						?>
					</div>
					
					
					
					<div style=" padding-top:50px;">
						<table   cellpadding="0px" width='100%' height="100%"  >
							<tr >
						   		<td id="msg"  bgcolor="<?=$corMsg?>" height="5px" style="font-size:13px; font-weight:bold; " align="center">
					   				<?=$_SESSION['msg']?>
						   		</td>
						  	</tr>
							<tr  style='font-size:75%;'>
								<td   valign="top" style='font-weight:bold; padding-top:10px'  align="center" id='LBL'>
									<div style='float:center; text-align:left; width:570px;  padding:0 0px 0px 0px;'>
										<div style='float:left; width:80px;  padding:0 5px 0px 0px;'>
											Código:<input id="numeracao" onkeydown='return handleEnter(this, event)'  readonly name='COD_MMN' type='text' value='<?=(empty($CPOValue['COD_MMN']))?str_pad($codigo,6,0,STR_PAD_LEFT):str_pad($CPOValue['COD_MMN'],6,0,STR_PAD_LEFT)?>'>
										</div>
										<div style='float:left; width:80px;  padding:0px 5px 0px 0px;'>
								   			Data:
								   			<input <?=empty($CPOValue['DTA_MMN'])?"value='".date('d/m/Y')."'":$CPO['DTA_MMN']?> onblur='if(this.value=="__/__/____"){this.value="<?=date('d/m/Y')?>"}' onkeydown="return handleEnter(this, event);" id="formulario" class="dataFormat"   type="text" name="DTA_MMN" style="width:100%; color:#666">
							   			</div>
							   			<div style='float:left; width:315px; padding:0px 5px 0px 0px;'>
								   			Produto:
									  		<input autocomplete='off' style="width:100%; font-size:<?=$tam?>; color:<?=$color?>" value="<?=empty($_POST['DSC_PRD'])?$nome:$_POST['DSC_PRD']?>"  type="text"  id="edtAutocompleter" class="formularioAuto" name="NME_PRD"  onkeyup="rc_autocompleter(event, 'edtAutocompleter', 'preview', 'AutoCompleterProduto', 'COD_PRD')" value="Pesquise aqui por código do produto, código de barras ou Descrição..." onclick="this.select()" onblur="window.setTimeout('rc_invisible(\'preview\')',200); " onkeydown='return handleEnter(this, event)'>
											<input id="COD_PRD" <?=$CPO['COD_PRD']?> name="COD_PRD"  type="hidden" >
									  	</div>
									  	<div style='float:left; width:80px;  padding:0px 0px 0px 0px;'>
								   			Quantidade:
								   			<?$qtd = abs($CPOValue['QTD_PRD'])?>
								   			<input onkeydown="return handleEnter(this, event);" id="formulario" type="text" name="QTD_PRD" style="width:100%; color:#666; text-align:center" value="<?=empty($qtd)?"":$qtd?>">
							   			</div>
							   			<div style='float:left; width:160px;  padding:5px 5px 0px 0px;'>
								   			Tipo:
								   			<select style="width:100%" id="formulario" name="TPO_MMN" >
								   				<option></option>
								   				<option <?=$CPOValue['TPO_MMN']==1?"selected":""?> value="1">Entrada</option>
								   				<option <?=$CPOValue['TPO_MMN']==2?"selected":""?> value="2">Saída</option>
								   			</select>
							   			</div>
							   			<div  style='float:left; width:100%; padding:5px 0px 0px 0px;'>
											Motivo: 
							  				<font size="1" face="arial, helvetica, sans-serif"> ( Limite de 350 caracteres. Faltam&nbsp;<input onkeydown="return handleenter(this, event);" disabled type=text style="height:11px; border:none; text-align:center; font-size:9px" name=remLen size=2 maxlength=3 value="350"> )</font><br>
									  		<textarea onKeyDown="textCounter(this.form.OBS_MMN,this.form.remLen,350);" onKeyUp="textCounter(this.form.OBS_MMN,this.form.remLen,350);" style="width:100%; border-radius:5px; color:#666; height:74px" name='OBS_MMN'><?=$CPOValue["OBS_MMN"]?></textarea>
										</div>
										
									
								</td>
							</tr>
						</table>
					</div>
			</fieldset>
			</div>
			</center>
			<div  id="preview" onmouseover="style.visibility='visible';"  style="visibility:visible; height:300px; z-index:99999" class="rc_autocompleter"></div>
		</form>	
	</body>
</html>
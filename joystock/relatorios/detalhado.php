<?	
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	include_once(PATH_FUNCAO."/CTRLCodeSession.class.php");
	include_once(PATH_FUNCAO."/XAJAX/xajax_core/xajax.inc.php");
	include_once(PATH_FUNCAO."/functionAjax.php");
	
	
	$ajax = new xajax();
	$ajax->configure('javascript URI', '../pcte-common/XAJAX/');
	
	$ajax->registerFunction('AutoCompleterCliente');  
	$ajax->registerFunction('AutoCompleterProduto'); 
	
		
    $ajax->processRequest();
    
    
	
	$BDFB = new FB();
	$BDFB->Conecta();
	
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	
	
	$CodeSession = new CodeSession();
	$form = "detalhado.php?TIPO=".$_GET['TIPO'];
	
	$tp = array(1=>"Compras", 2=>"Vendas");
	
	$_POST['DTA_VN1'] = empty($_POST['DTA_VN1'])?date("01/m/Y"):$_POST['DTA_VN1'];
	$_POST['DTA_VN2'] = empty($_POST['DTA_VN2'])?date("d/m/Y"):$_POST['DTA_VN2'];
	
	
	
?>	

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" href="/joystock/pcte-common/e1024.css" type="text/css" media="all" />
		<link rel="stylesheet" href="/joystock/pcte-common/messi.css" type="text/css" media="all" />
		<script type="text/javascript" src="/joystock/pcte-common/CTRLGeral.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/jquery.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.maskedinput.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.maskMoney.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.validation.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/br.gov.dataprev.jquery.maskedinput.patterns.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/rc_autocompleter.js"></script>
		<title>JOYSTOCK</title>
		<?php $ajax->printJavascript();?>
	</head>

	<body style="overflow-y:hidden" onload="form='frmbreg'; destino='vendas'" >
		<form name="frmbreg" method="POST" action="<?=$form?>">
			<center>	
				<div style="height:-webkit-calc(100% - 95px); width:100%; overflow-y:auto; overflow-x:hidden">
					<table width="100%" height="100%" cellpadding="0" cellspacing="0">
						<tr  style="position:relative;  height:45px " width="100%" >
							<td valign="top" id="letra" style="border-bottom:solid 2px #333; font-size:30px; padding-top:5px; " colspan="12" align="center">Relatório Detalhado de <?=$tp[$_GET['TIPO']]?></td>
							<div style="position:absolute;right:5px; top:5px; padding:2px ">
								<img id="box1" title="Atualizar"    onclick="location.href='/joystock/relatorios/detalhado.php?TIPO=<?=$_GET['TIPO']?>'" style="width:28px; cursor:pointer; height:28px;" src="/joystock/pcte-common/images/actualiser.png">
								<!--<img id="box1" title="Voltar"    onclick="location.href='/joystock/cadastros/produtos.php?new=1'" style="width:28px; cursor:pointer; height:28px;" src="/joystock/pcte-common/images/back.png">-->
							</div>
						</tr>
						<tr style="height:30px">
							<td align="center" valign="top" id="letra" style="font-size:15px;  " colspan="12" align="left">
								<div style="float:left; text-align:left; width:10%;   padding:5px 0px 0px 5px" >
									Filtros:
								</div>
								
								<div style="float:left; font-size:12px; text-align:left; width:20%; padding:0px 5px 0px 10px" >
									Produto:
									<input <?=$readonly?> autocomplete="off" onkeydown="return handleEnter(this, event);" value="<?=empty($_POST['DSC_PRD'])?$produto:$_POST['DSC_PRD']?>"  class="formularioAuto" autocomplete="off" id="edtAutocompleter" name="DSC_PRD" style="width:100%;  color:#666" onkeyup="rc_autocompleter(event, 'edtAutocompleter', 'preview', 'AutoCompleterProduto', 'COD_PRD')" onblur="window.setTimeout('rc_invisible(\'preview\')',200); " >
					   				<input id="COD_PRD" name="COD_PRD"  type="hidden" value="<?=empty($_POST['COD_PRD'])?$CPOValue['COD_PRD']:$_POST['COD_PRD']?>">
								</div>
								
								<div style="float:left; font-size:12px; text-align:left; width:20%; padding:0px 5px 0px 10px" >
									Cliente:
									<input <?=$readonly?> autocomplete="off" onkeydown="return handleEnter(this, event);" value="<?=empty($_POST['NME_CLN'])?$nome:$_POST['NME_CLN']?>"  class="formularioAuto" autocomplete="off" id="edtAutocompleterCLN" name="NME_CLN" style="width:100%;  color:#666" onkeyup="rc_autocompleter(event, 'edtAutocompleterCLN', 'preview', 'AutoCompleterCliente', 'COD_CLN')" onblur="window.setTimeout('rc_invisible(\'preview\')',200); " >
					   				<input id="COD_CLN" name="COD_CLN"  type="hidden" value="<?=empty($_POST['COD_CLN'])?$CPOValue['COD_CLN']:$_POST['COD_CLN']?>">
								</div>
								<div style='float:left; text-align:left; font-size:12px; width:20%; padding:0px 5px 0 0px;'>
									Grupo de Clientes:
									<select id="formulario" onchange="submit();" style='color:#666666; width:100%'  name='COD_GCL'>
										<option></option>
										<?=$BDFB->FBObjBDSelect($_POST['COD_GCL'],"","upcsgcl_tb","COD_GCL","DSC_GCL","","","");?>
									</select>
								</div>
								<div style="float:left; padding:0px 0px 0px 0px; text-align:left; font-size:12px; width:90px">
									Período:
									<input autocomplete="off" onblur="if(this.value.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/) == null){alert('Data Inválida')}" onkeyup=" if(this.value.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/) != null){submit()};" id="formulario" type="text" name="DTA_VN1" value="<?=$_POST['DTA_VN1']?>" class="dataFormat" onclick="this.select()" style="text-align:center; width:100%"> 
								</div>
								
								<div style="float:left; padding:22px 4px 0px 2px; font-size:12px; width:10px">
									à
								</div>
								
								<div style="float:left; padding:0px 0px 0px 0px; font-size:12px; width:100px">
									<br>
									<input autocomplete="off" onblur="if(this.value.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/) == null){alert('Data Inválida')}" onkeyup=" if(this.value.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/) != null){submit()};" id="formulario" type="text" name="DTA_VN2" value="<?=$_POST['DTA_VN2']?>" class="dataFormat" onclick="this.select()" style="text-align:center; width:100%"> 
								</div>
							</td>
						</tr>	
						<tr style="height:100%">
							<td align="center">
								<iframe scrolling="No" src="pdfDetalhado.php?COD_PRD=<?=$_POST['COD_PRD']?>&COD_CLN=<?=$_POST['COD_CLN']?>&NME_CLN=<?=$_POST['NME_CLN']?>&COD_GCL=<?=$_POST['COD_GCL']?>&DTA_VN1=<?=$_POST['DTA_VN1']?>&DTA_VN2=<?=$_POST['DTA_VN2']?>&TIPO=<?=$_GET['TIPO']?>" name="FrmPrincipal" frameborder="0" border="0" height="100%" width="100%" ></iframe>
							</td>
						</tr>
						<tr >
							<td align="center">
								.
							</td>
						</tr>
					</table>	
				</div>
			</center>	
			<div  id="preview" onmouseover="style.visibility='visible';"  style="visibility:visible; height:300px; z-index:99999" class="rc_autocompleter"></div>	
		</form>			
	</body>				
</html>
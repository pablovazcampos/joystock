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
	
	$_POST['TPO_PRD'] = '2';
	
	$ajax->registerFunction('upload');
	$ajax->registerFunction('AutoCompleterCliente');
	$ajax->registerFunction('AutoCompleterProduto');
	$ajax->registerFunction('AutoCompleterGrupoClientes');
	$ajax->registerFunction('incluiFornecedor');
	$ajax->registerFunction('incluiGrupoClientes');
	$ajax->registerFunction('incluiKits');
	$ajax->registerFunction('carregaMovimentoFornecedor');
	$ajax->registerFunction('carregaMovimentoKits');
	$ajax->registerFunction('carregaMovimentoGrupoClientes');
	
	
	$ajax->processRequest();
	
	
	$CodeSession = new CodeSession();
	
	$BDFB = new FB();
	$BDFB->Conecta();
	
	if($_POST["breg"] == "pesquisar"){
		header("Location: pesquisaKits.php");
		$_POST["breg"] = "";
		exit();		
	}
	
	
	
	$sql = "SHOW TABLE STATUS FROM `".BANCO_DADOS."` LIKE 'upcsprd_tb'";
	$dados = $BDFB->FBSelect($sql);
	$rss = mysql_fetch_array($dados);
	$codigo = $rss['Auto_increment'];
	
	//LIMPA O CÓDIGO SE VIER DE FORA
	if ($_GET['new'] == 1){
		$_SESSION['COD'] = "";
		$_SESSION['msg'] = "";
		$_SESSION['TIPO'] = 1;
		$_SESSION['STS_BREG'] = 0;
	}
	
	$corMsg = "#".COR;
	
	
	$frmMaster			 = 'produtos.php';							
	$NME_FRM 			 = 'index';
	$DSC_FRM             = 'Cadastro de Produtos Agrupados';
	$ACTION 			 = 'kits.php';
	$_SESSION['NME_RTN'] = "/joystock/cadastros/kits.php";
	$TABELA              = "upcsprd_tb"  ;
	
	
	
	require_once(PATH_FUNCAO."/CTRLReg.php");
	
	
	
	if ($_SESSION['status_sistema'] == 'salvar' && $_POST['breg'] == 'salvar'){
		
		$query = "DELETE FROM upcskpr_tb WHERE COD_KIT = ".abs($CPOValue['COD_PRD']);
		$BDFB->EXECQuery($query);
		
		for ($x=1;$_POST['CDG'.$x];$x++){
			$prc = str_replace(".","",$_POST['PRC'.$x]);
			$prc = str_replace(",",".",$prc);
			$query = "INSERT INTO upcskpr_tb VALUES(".abs($CPOValue['COD_PRD']).", ".$_POST['CDG'.$x].", {$prc})";
			$BDFB->EXECQuery($query);
		}
		
		
		
		$query = "DELETE FROM upcspvn_tb WHERE COD_PRD = ".abs($CPOValue['COD_PRD']);
		$BDFB->EXECQuery($query);
		
		for ($x=1;$_POST['CDGV'.$x];$x++){
			$prc = str_replace(".","",$_POST['PRCV'.$x]);
			$prc = str_replace(",",".",$prc);
			$query = "INSERT INTO upcspvn_tb VALUES(".abs($CPOValue['COD_PRD']).", ".$_POST['CDGV'.$x].", {$prc})";
			$BDFB->EXECQuery($query);
		}	
		
		
		
	}
	
	
	
	$codKit = abs($CPOValue['COD_KIT']);
	$qtd = $BDFB->TratarCampo("decimal", $_POST['QTD_PRD']);
	
	if (!empty($_POST['BTNIncuir']) && !empty($_POST['COD_PRD']) && !empty($_POST['QTD_PRD'])){
		$query = "INSERT INTO upcskpr_tb VALUES(0,{$codKit},{$_POST['COD_PRD']},$qtd)";
		$BDFB->EXECQuery($query);
	}
	
	$query = "SELECT T1.QTD_PRD, T2.NME_PRD, T1.COD_KPR FROM upcskpr_tb T1 ".
			 "JOIN upcsprd_tb T2 ON T1.COD_PRD = T2.COD_PRD ".
			 "WHERE T1.COD_KIT = $codKit";
	$dados = $BDFB->FBSelect($query);
	
	
	
	if (!empty($CPOValue['COD_PRD'])){
		$num = abs($CPOValue['COD_PRD']);
		$onload = "onload='xajax_carregaMovimentoKits($num); xajax_carregaMovimentoGrupoClientes($num); form=\"frmbreg\"'";
	}else {
		$onload = "onload='form=\"frmbreg\"'";
	}
	
	
	
	
	
	if (empty($_SESSION['msg'])){
		$_SESSION['msg'] = "*Foto do Produto: Tamanho máximo: 5MB / Arquivos suportados: JPG, PNG e GIF.";
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
	
	<body <?=$onload?>>
		<form id="frmbreg" name="frmbreg" target="" action="kits.php" method="POST" >
		<center>
			<div style="margin-top:20px">
			<fieldset  style=" background-color:#FFFFFF;  width:750px; height:530px;  border-style: solid;border-width:2px; border-color:#<?=COR?>;  z-index: 11;">
				<legend  id="legend"  align="left"><?=$DSC_FRM?></legend>
				<div id="main" style="position:relative;  width:730px; margin-top:5px; height:350px">	
					<div style="position:absolute; top:-15px;  right:0px; width:550px;">
					<?
						$BREG = BarraRegistro();
						$breg = new Breg($BREG[$estado],$ACTION);
						$breg->defineBreg("novo","salvar","editar","cancelar","pesquisar","",false);	
						
					?>
					</div>
			<div style="padding-top:25px;">
			<table  style="margin-top:25px" id="LBL" width='100%'>
			   <tr>
			   		<td colspan="2" id="msg"  bgcolor="<?=$corMsg?>"  height="13px" style=" font-size:13px; font-weight:bold" colspan="6" align="center">
		   				<?=$_SESSION['msg']?>
			   		</td>
			  </tr>	
			  
			  <tr>	
		  		<td colspan="2" width="100%" valign="top" style='font-weight:bold; padding-top:15px'  id='LBL'>
					<div style='float:left; width:132px; height:174px;  padding:0 5px 0px 0px;'>
						<iframe src="photo.php?COD=<?=(empty($CPOValue['COD_PRD']))?$codigo:abs($CPOValue['COD_PRD'])?>" width="100%" height="100%" scrolling="No" style="border:none"></iframe>
					</div>
					<div style='float:left; width:80px;  padding:0 5px 0px 0px;'>
						Código:<input id='numeracao'  readonly name='COD_PRD' type='text' value='<?=(empty($CPOValue['COD_PRD']))?str_pad($codigo,6,0,STR_PAD_LEFT):str_pad($CPOValue['COD_PRD'],6,0,STR_PAD_LEFT)?>'>
					</div>
					<div style='float:left; width:150px; padding:0 5px 0px 0px;'>
						Código de Barras:
						<input onkeydown="bloquearCtrlJ(); return clicando2(this, event);" style="width:100%;" class="numeroFormat" id='formulario' name='COD_BRR' type='text' <?=$CPO['COD_BRR']?>>
					</div>
					<div style='float:left; width:-webkit-calc(100% - 378px); padding:0 0px 0px 0px;'>
						Nome:<input style="width:100%;" id='formulario' name='NME_PRD' type='text' <?=$CPO['NME_PRD']?>>
					</div>
					<div  style='float:left; width:-webkit-calc(100% - 138px); padding:5px 0px 0px 0px;'>
						Descrição do Produto: 
		  				<font size="1" face="arial, helvetica, sans-serif"> ( Limite de 350 caracteres. Faltam&nbsp;<input onkeydown="return handleenter(this, event);" disabled type=text style="height:11px; border:none; text-align:center; font-size:9px" name=remLen size=2 maxlength=3 value="350"> )</font><br>
				  		<textarea onKeyDown="textCounter(this.form.DSC_PRD,this.form.remLen,350);" onKeyUp="textCounter(this.form.DSC_PRD,this.form.remLen,350);" style="width:100%; border-radius:5px; color:#666; height:74px" name='DSC_PRD'><?=$CPOValue["DSC_PRD"]?></textarea>
					</div>
					
					<div style='float:left; width:200px; padding:5px 5px 0 0px;'>
						Categoria:
						<select id="formulario" style='color:#666666; width:100%'  name='COD_GPP'>
						   	<option></option>
							<?=$BDFB->FBObjBDSelect($CPOValue['COD_GPP'],"","upcsgpp_tb","COD_GPP","DSC_GPP","","","");?>
						</select>
					</div>
					<div style='float:left; width:200px; padding:5px 5px 0 0px;'>
						Unidade de Medida:
						<select id="formulario" style='color:#666666; width:100%'  name='COD_UMD'>
						   	<option></option>
							<?=$BDFB->FBObjBDSelect($CPOValue['COD_UMD'],"","upcsumd_tb","COD_UMD","DSC_UMD","","","");?>
						</select>
					</div>
					<div style='float:left; width:-webkit-calc(100% - 548px); padding:5px 0px 5px 0px;'>
						Status:
						<select id="formulario" style='color:#666666; width:100%'  name='STS_PRD'>
						   	<option></option>
						   	<option <?=$CPOValue['STS_PRD']!=2?"selected":""?> value="1">Habilitado</option>
						   	<option <?=$CPOValue['STS_PRD']==2?"selected":""?> value="2">Desabilitado</option>
						</select>
					</div>
					
					
					<div style="float:left;  width:-webkit-calc(50% - 5px);">
						<div id="legend2" style="float:left; width:100%;" >
							Produtos Simples
						</div>
						
						<div style="-webkit-transition:1s;   float:left; width:100%">
							<div id="fornecedor" style='float:left; width:65%;  padding:2px 0px 5px 0px;'>
					   			Produto Simples: 
					   			<input <?=$readonly?> autocomplete='off' style="width:100%; border-radius:5px 0px 0px 5px; color:#666 " onkeydown="return handleEnter(this, event)" type="text"  id="edtAutocompleter" class="formularioAuto" name="DSC_KIT"  onkeyup="rc_autocompleter(event, 'edtAutocompleter', 'preview', 'AutoCompleterProduto', 'COD_KIT')"  onclick="this.select()" >
								<input id="COD_KIT" name="COD_KIT"  type="hidden" >
					   		</div>
					   		<div id="fornecedor" style='float:left; width:20%;  padding:2px 0px 5px 0px;'>
					   			Qtd.: 
					   			<input <?=$readonly?> class="decimal4Format" id="formulario" onkeydown="return handleEnter(this, event);" type="text" autocomplete="off" name="QTD_KIT" style="width:100%; text-align:center; border-radius:0px 0px 0px 0px; color:#666" onclick="this.select()" >
					   		</div>
					   		<div style='float:left; width:15%;  padding:16px 0px 0px 0px;'>
					   			<input style="cursor:pointer; width:100%; border-radius:0px 5px 5px 0px; height:30px" type="button" name="BTNIncuir" value="Incluir" id="box1" onclick="xajax_incluiKits(this.form.COD_KIT.value, this.form.DSC_KIT.value, this.form.QTD_KIT.value ); this.form.DSC_KIT.focus()">
				   			</div>	
				   		</div>	
				   		
						<div style=" margin-top:0px;   ">
							<table id="table1" style="text-align: left; width: 100%;  border="0" cellpadding="1" cellspacing="2">
									<tr style="background-color: #41967A;">
										<td id="legend2" style="width:3%; color:#FFFFFF; text-align: center;">
											
										</td>
										
										<td id="legend2" style="width:44%;color:#FFFFFF; text-align: center; ">
											Produtos Simples
										</td>
										<td id="legend2" style="width:10%;color:#FFFFFF; text-align: center;">
											Quantidade
										</td>
										
									</tr>
							</table>
							<div id="vendas"></div>	
						</div>
					</div>	
					
					<div style="float:left;  width:-webkit-calc(50% - 5px); padding-left:6px">
						<div id="legend2" style="float:left; width:100%;" >
							Preço de Venda
						</div>
						
						<div style="-webkit-transition:1s;   float:left; width:100%">
							<div id="fornecedor" style='float:left; width:65%;  padding:2px 0px 5px 0px;'>
					   			Grupo de Clientes: 
					   			<input <?=$readonly?> onkeydown="return handleEnter(this, event);" class="formularioAuto" autocomplete="off" id="edtAutocompleterGCL" name="DSC_GCL" style="width:100%; border-radius:5px 0px 0px 5px; color:#666" onkeyup="rc_autocompleter(event, 'edtAutocompleterGCL', 'preview', 'AutoCompleterGrupoClientes', 'COD_GCL')" >
					   			<input id="COD_GCL" name="COD_GCL"  type="hidden" >
					   		</div>
					   		<div id="fornecedor" style='float:left; width:20%;  padding:2px 0px 5px 0px;'>
					   			Preço: 
					   			<input <?=$readonly?> onkeydown="return handleEnter(this, event);" id="formulario" autocomplete="off" name="PRC_VEN" style="width:100%; text-align:center; border-radius:0px 0px 0px 0px; color:#666" class="moedaFormat" >
					   		</div>
					   		<div style='float:left; width:15%;  padding:16px 0px 0px 0px;'>
					   			<input name="BTN_PRV"  type="button" value="Incluir" id="box1" style="cursor:pointer; width:100%; border-radius:0px 5px 5px 0px; height:30px"  onclick="xajax_incluiGrupoClientes(this.form.COD_GCL.value, this.form.DSC_GCL.value, this.form.PRC_VEN.value ); this.form.DSC_GCL.focus()" >
				   			</div>	
				   		</div>	
				   		
						<div style=" margin-top:0px;   ">
							<table id="table2" style="text-align: left; width: 100%;  border="0" cellpadding="1" cellspacing="2">
									<tr style="background-color: #41967A;">
										<td id="legend2" style="width:3%; color:#FFFFFF; text-align: center;">
											
										</td>
										
										<td id="legend2" style="width:44%;color:#FFFFFF; text-align: center; ">
											Grupo de Clientes
										</td>
										<td id="legend2" style="width:10%;color:#FFFFFF; text-align: center;">
											Preço
										</td>
									</tr>
							</table>
						</div>
					</div>	
					
					
					
			  	</td>
			  </tr>
			  <tr>	
			  	
			  </tr>
			  
			  
			  	
			</table>
			</div>
			
			</div>
			</fieldset>
			</div>
			<input id="delete" type="hidden" name="delete" value="0">
			<input id="delete2" type="hidden" name="delete2" value="0">
			<div  id="preview" onmouseover="style.visibility='visible';"  style="visibility:visible; height:150px; z-index:99999" class="rc_autocompleter"></div>
			<script>
			    dindin.Real.noConflict ();
			    (function($) {
			      $(document).ready(function() {
			        
			      	$("#delete").on('click', function() {
			          new Messi('Deseja realmente excluir este produto simples?', {title: 'Excluir Preço', buttons: [{id: 0, label: 'SIM', val: 'Y', class: 'btn2-success'}, {id: 1, label: 'NÃO', val: 'N', class: 'btn2-danger'}], callback: function(val) { if(val == 'Y'){deleteRowFornecedor(document.getElementById('delete').value)} }});
			        });
			        
			        $("#delete2").on('click', function() {
			          new Messi('Deseja realmente excluir o preço deste Grupo de Clientes?', {title: 'Excluir Preço', buttons: [{id: 0, label: 'SIM', val: 'Y', class: 'btn2-success'}, {id: 1, label: 'NÃO', val: 'N', class: 'btn2-danger'}], callback: function(val) { if(val == 'Y'){ deleteRowGrupoCliente(document.getElementById('delete2').value)} }});
			        });
			      	
			      });
			    })(dindin.Real);
		  
			</script>
			</center>
		</form>	
	</body>
</html>
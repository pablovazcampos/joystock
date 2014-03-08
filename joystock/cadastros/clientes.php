<?php
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	include_once(PATH_FUNCAO."/CTRLBDados.php");
	include_once(PATH_FUNCAO."/breg.php");
	include_once(PATH_FUNCAO."/CTRLCodeSession.class.php");
	
	
	$_POST['ISS_CLN'] = '1';
	
	$CodeSession = new CodeSession();
	$BDFB = new FB();
	$BDFB->Conecta();

	if ($_POST['TPO_PSS'] == 2){
		$_POST['CNJ_CLN'] = $_POST['CNJ_CLN02'];
	}
	
	$_POST['CNJ_CLN'] = strtoupper(strtr($_POST['CNJ_CLN'] ,"-./","|||"));
	$_POST['CNJ_CLN'] = str_replace("|","",$_POST['CNJ_CLN']);
	
	if($_POST["breg"] == "pesquisar"){
		header("Location: pesquisaClientes.php");
		$_POST["breg"] = "";
		exit();		
	}
	
	
	$sql = "SHOW TABLE STATUS FROM `".BANCO_DADOS."` LIKE 'upcscln_tb'";
	$dados = $BDFB->FBSelect($sql);
	$rss = mysql_fetch_array($dados);
	$codigo = $rss['Auto_increment'];
	
	
	$_POST['DTA_CDS'] = date("d/m/Y");
	$_POST['COD_USR'] = $CodeSession->decode('COD_USR');
	$_POST['ATV_CLN'] = empty($_POST['ATV_CLN'])?null:$_POST['ATV_CLN'];
	
	
	
	//$_GET['COD'] = 1;
	
	//LIMPA O CÓDIGO SE VIER DE FORA
	if ($_GET['new'] == 1){
		$_SESSION['COD'] = "";
		$_SESSION['msg'] = "";
		
		
	}
	
	$corMsg = "#".COR;
	
	
	if ($_POST['breg'] == "salvar" && $_SESSION['status_sistema'] == 'novo'){
		$query = "SELECT COD_CLN FROM upcscln_tb WHERE CNJ_CLN  = ".trim($_POST['CNJ_CLN']);
		$dados = $BDFB->FBSelect($query);
		$row = @mysql_fetch_array($dados);
		if (!empty($row['COD_CLN'])){
			$erro = 1;
			$_POST['breg'] = "cancelar";
			if ($_POST['TPO_PSS'] == 1){
			$erro = "CNPJ JÁ CADASTRADO";	
			}else {
				$erro = "CPF JÁ CADASTRADO";
			}
			$corMsg = "red";
		}
	}
	
	$estados = SiglaEstado();
	
	$frmMaster			 = 'clientes.php';							
	$NME_FRM 			 = 'index';
	$DSC_FRM             = 'Cadastro de Clientes';
	$ACTION 			 = 'clientes.php';
	$_SESSION['NME_RTN'] = "/cadastro/clientes.php";
	$TABELA              = 'upcscln_tb';
	$WHERE               = " AND ISS_CLN = '1' ";
	
	
	if (empty($_GET['COD'])){
		if (!empty($_SESSION['COD'])){
			$_POST['COD_CLN'] = $_SESSION['COD'];
		}
	}else {
		$_POST['COD_CLN'] = $_GET['COD'];
	}
	
	require_once(PATH_FUNCAO."/CTRLReg.php");
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
	    
	</head>
	<body>
		<center>
			<div style="margin-top:50px">
			<fieldset  style=" background-color:#FFFFFF;  width:600px; height:450px;  border-style: solid;border-width:2px; border-color:#<?=COR?>;  z-index: 11;">
				<legend  id="legend"  align="left"><?=$DSC_FRM?></legend>
				<div id="main" style="position:relative;  width:580px; margin-top:5px; height:450px">	
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
											Código:<input id="numeracao" onkeydown='return handleEnter(this, event)'  readonly name='COD_CLN' type='text' value='<?=(empty($CPOValue['COD_CLN']))?str_pad($codigo,6,0,STR_PAD_LEFT):str_pad($CPOValue['COD_CLN'],6,0,STR_PAD_LEFT)?>'>
										</div>
										<div style='float:left; width:325px; padding:0 5px 0px 0px;'>
											Nome:<input  onkeydown='return handleEnter(this, event)'   style="width:100%; <?=$CPOStyle['NME_CLN']?>" id='formulario' name='NME_CLN' type='text' <?=$CPO['NME_CLN']?> >
										</div>
										
										<div style='float:left; width:150px; padding:0 0px 0px 0px;'>
											Grupo:
											<select id="formulario" style='color:#666666; width:100%'  name='COD_GCL'>
											   	<option></option>
												<?=$BDFB->FBObjBDSelect($CPOValue['COD_GCL'],"","upcsgcl_tb","COD_GCL","DSC_GCL","","","");?>
											</select>
										</div>
										
										<div style='float:left; width:105%; padding:0px 5px 0 0px;'>
										</div>
										
										<div style='float:left; width:105%; padding:0px 5px 0 0px;'>
										</div>
										
										<div style='float:left; width:250px; padding:5 7px 0px 0px;'>
											Tipo de pessoa:
											<div style="width:250px;  background-color:#FFF; height:28px; border-radius:3px; border:solid 1px #<?=COR?>">
												<table style="color:#<?=COR?>; font-size:15px" width="100%" height="100%">
													<tr>
														<td width="2%" align="right"><input onkeydown='return handleEnter(this, event)'  onchange="cnj(this.value);" <?=$CPOValue['TPO_PSS']!=2?"checked":""?> type="radio" name="TPO_PSS" value="1"></td>
														<td width="48%" align="left">Jurídica</td>
														<td width="2%" align="right"><input onkeydown='return handleEnter(this, event)'  onchange="cnj(this.value);" <?=$CPOValue['TPO_PSS']==2?"checked":""?> type="radio" name="TPO_PSS" value="2"></td>
														<td width="48%" align="left">Física</td>
													</tr>
												</table>
											</div>
										</div>
										
										<?
											if ($CPOValue['TPO_PSS'] != 2){
												$visibility_01 = "visibility:hidden;";
												$nameCNJ = "CNPJ";
											}else {
												$visibility_02 = "visibility:hidden;";
												$nameCNJ = "CPF";
											}
										
										?>
										
										<div  style='position:relative; float:left; width:150px; padding:5px 5px 0px 0px;'>
											<label id="cnj" ><?=$nameCNJ?>:</label>
											<?if ($_SESSION['STS_BREG'] == 0):?>
												<input onkeydown="return handleEnter(this, event); "  class="cnpjFormat" onkeyup="maskCNPJ(this, event); rc_autocompleter(event, 'obj_cnpj', 'preview', 'AutoCompleterDevedor', 'COD_DVD'); " onfocus=" document.getElementById('cnjID').style.width = '360px'; "  style="<?=$visibility_02?> text-align:center; color:#172b5a; position:absolute; left: 0px; bottom:0px; width:100%; <?=$CPOStyle['CNJ_CLN']?>" id='obj_cnpj' name='CNJ_CLN' type='text' value="<?=$CPOValue['CNJ_CLN']?>"  onblur="document.getElementById('cnjID').style.width = '165px'; window.setTimeout('rc_invisible(\'preview\')',200);">
												<input onkeydown="return handleEnter(this, event); "  class="cpfFormat"  onkeyup="maskCPF(this, event); rc_autocompleter(event, 'obj_cpf', 'preview', 'AutoCompleterDevedor', 'COD_DVD');  " onfocus=" document.getElementById('cnjID').style.width = '360px'; " style="<?=$visibility_01?> text-align:center; color:#172b5a; width:100%; <?=$CPOStyle['CNJ_CLN02']?>" id='obj_cpf' name='CNJ_CLN02' type='text' value="<?=$CPOValue['CNJ_CLN']?>" onblur="document.getElementById('cnjID').style.width = '165px'; window.setTimeout('rc_invisible(\'preview\')',200);">
											<?else :?>	
												<input onkeydown='return handleEnter(this, event)'  class="cnpjFormat" onkeyup="maskCNPJ(this, event);"  style="<?=$visibility_02?> text-align:center; color:#172b5a; position:absolute; bottom:0px; left: 0px; width:100%; <?=$CPOStyle['CNJ_CLN']?>" id='obj_cnpj' name='CNJ_CLN' type='text' <?=$CPO['CNJ_CLN']?>>
												<input onkeydown='return handleEnter(this, event)'  class="cpfFormat"  onkeyup="maskCPF(this, event);" style="<?=$visibility_01?> text-align:center; color:#172b5a; width:100%; <?=$CPOStyle['CNJ_CLN02']?>" id='obj_cpf' name='CNJ_CLN02' type='text' <?=$CPO['CNJ_CLN']?>>
											<?endif;?>
										</div>
										
										<div style='float:left; width:150px; padding:5 0px 0px 0px;'>
											Cliente Ativo:
											<div style="width:150px; background-color:#FFF; height:28px; border-radius:3px; border:solid 1px #181511">
												<table  style="color:#181511; font-size:15px" width="100%" height="100%">
													<tr>
														<td align="center" width="50%" align="right" >
															<select onkeydown='return handleEnter(this, event)'  name="ATV_CLN" style="height:25px; height:20px; color:#<?=COR?>; ">
																<option <?=$CPOValue['ATV_CLN']!=2?"selected":""?> value="1">SIM</option>
																<option <?=$CPOValue['ATV_CLN']==2?"selected":""?> value="2">NÃO</option>
															</select>
														</td>
													</tr>
												</table>
											</div>
										</div>	
										
										
										<div style='float:left; width:105%; padding:0px 5px 0 0px;'>
										</div>
										
										<div style='float:left; width:105%; padding:0px 5px 0 0px;'>
										</div>
										
										<div style='float:left; width:80px; padding:5px 5px 0 0px;'>
											CEP:<input onkeydown='return handleEnter(this, event)'  class="cepFormat" style="width:80px; text-align:center" id='formulario' name='CEP_CLN' type='text' size='9%' <?=$CPO['CEP_CLN']?>>
										</div>
										
										<div style='float:left; width:285px; padding:5px 5px 0 0px;'>
											Endereço:<input onkeydown='return handleEnter(this, event)'  style="width:285px" id='formulario' name='END_CLN' type='text' size='8%' <?=$CPO['END_CLN']?>>
										</div>
										<div style='float:left; width:45px; padding:5px 5px 0 0px;'>
											Número:<input onkeydown='return handleEnter(this, event)'  style="width:45px; text-align:center" id='formulario' name='NMR_END' type='text' size='9%' <?=$CPO['NMR_END']?>>
										</div>
										
										<div style='float:left; width:140px; padding:5px 0px 0 0px;'>
											Complemento:<input onkeydown='return handleEnter(this, event)'  style="width:140px" id='formulario' name='CMP_CLN' type='text' size='9%' <?=$CPO['CMP_CLN']?>>
										</div>
										
										<div style='float:left; width:105%; padding:0px 5px 0 0px;'>
										</div>
										
										
	
										<div style='float:left; width:251px; padding:5px 5px 0 0px;'>
											Bairro:<input onkeydown='return handleEnter(this, event)'  style="width:251px" id='formulario' name='BRR_CLN' type='text' size='8%' <?=$CPO['BRR_CLN']?>>
										</div>
										
																		
										<div style='float:left; width:255px; padding:5px 5px 0 0px;'>
											Cidade:<input onkeydown='return handleEnter(this, event)'  style="width:255px" id='formulario' name='CDD_CLN' type='text' size='8%' <?=$CPO['CDD_CLN']?>>
										</div>
										<div style='float:left; width:50px; padding:5px 0px 0 0px;'>
											UF:  <select onkeydown='return handleEnter(this, event)' id="formulario" style='color:#666666; width:50px' name='EST_CLN'>
													<?=$BDFB->FBObjSelect(empty($CPOValue['EST_CLN'])?11:$CPOValue['EST_CLN'],"",$estados)?>
												 </select>
										</div>
										
										<div style='float:left; width:105%; padding:0px 5px 0 0px;'>
										</div>
										
																		
										<div style='float:left; width:240px; padding:5px 5px 0 0px;'>
											E-mail: <input onkeydown='return handleEnter(this, event)'  style="width:240px" id='formulario' name='EML_CLN' type='text'  <?=$CPO['EML_CLN']?>>
										</div>
										
										<div style='float:left; width:102.5px; padding:5px 5px 0 0px;'>
											Telefone 01:<input onkeydown='return handleEnter(this, event)'  class="dddTelefoneFormat" style="width:102.5px" onkeyup='parenthesis(this, event)' id='formulario' name='TEL_001' type='text' <?=$CPO['TEL_001']?>>
										</div>
							
										<div style='float:left; width:103px; padding:5px 5px 0 0px;'>
											Celular:<input onkeydown='return handleEnter(this, event)'  class="dddTelefoneFormat" style="width:103px" onkeyup='parenthesis(this, event)' id='formulario' name='CEL_CLN' type='text' <?=$CPO['CEL_CLN']?>>
										</div>
										<div style='float:left; width:103px; padding:5px 0px 0 0px;'>
											Fax:<input onkeydown='return handleEnter(this, event)'  class="dddTelefoneFormat" style="width:103px" onkeyup='parenthesis(this, event)' id='formulario' name='FAX_CLN' type='text' <?=$CPO['FAX_CLN']?>>
										</div>
										<div style='float:left; width:-webkit-calc(100%); padding:10px 0px 0px 0px;'>
											Observação: 
							  				<font size="1" face="arial, helvetica, sans-serif"> ( Limite de 350 caracteres. Faltam&nbsp;<input onkeydown="return handleenter(this, event);" disabled type=text style="height:11px; border:none; text-align:center; font-size:9px" name=remLen size=2 maxlength=3 value="350"> )</font><br>
									  		<textarea onKeyDown="textCounter(this.form.OBS_CLN,this.form.remLen,350);" onKeyUp="textCounter(this.form.OBS_CLN,this.form.remLen,350);" style="width:100%; border-radius:5px; color:#666; height:74px" name='OBS_CLN'><?=$CPOValue["OBS_CLN"]?></textarea>
										</div>
									</div>
									<div  style='float:left; width:105%; padding:0px 5px 0 0px;'>
										<br>
									</div>
									
								</td>
							</tr>
						</table>
					</div>
			</fieldset>
			</div>
			</center>
			<!--<div style=" position:absolute; bottom:10px; right:10px;"><img src="/joystock/pcte-common/images/logo.jpg"></div>	-->
		</form>	
	</body>
</html>
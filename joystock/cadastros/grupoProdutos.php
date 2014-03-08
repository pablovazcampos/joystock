<?php
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	include_once(PATH_FUNCAO."/CTRLBDados.php");
	include_once(PATH_FUNCAO."/breg.php");
	
	$BDFB = new FB();
	$BDFB->Conecta();
	
	//LIMPA O CÓDIGO SE VIER DE FORA
	if ($_GET['new'] == 1){
		$_SESSION['COD'] = "";
		$_SESSION['msg'] = "";
	}
	
	$corMsg = "#".COR;
	
	
	$frmMaster			 = 'grupoProdutos.php';							
	$NME_FRM 			 = 'index';
	$DSC_FRM             = 'Cadastro de Categorias';
	$ACTION 			 = 'grupoProdutos.php';
	$_SESSION['NME_RTN'] = "/joystock/cadastros/grupoProdutos.php";
	$TABELA              = "upcsgpp_tb"; 
	
	
	require_once(PATH_FUNCAO."/CTRLReg.php");
	
	$select = "SELECT T1.COD_GPP,  T1.DSC_GPP ".
			  "FROM upcsgpp_tb T1 ".
			  "WHERE T1.EXC_UPCSGPP IS NULL";

	$_SESSION['query_lista'] = $select." ORDER BY DSC_GPP";
	
	$_SESSION['link_title'] = 'COD_GPP'; // fazer aparecer o title na lista
	
	/* Cria��o e Defini��o de colunas para lista */
	$_SESSION['prop'][0] = array('campo' => 'COD_GPP','format' => 'ZeroEsquerda','funcao' => '','link'=>'COD_GPP','propriedade' => 'vertical-align: top; text-align: center; width: 10%; font-size:100%;');
	$_SESSION['prop'][1] = array('campo' => 'DSC_GPP','funcao' => '','link'=>'COD_GPP','propriedade' => 'vertical-align: top; text-align: left; width: 45%; font-size:100%;');
	
?>


<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<script type="text/javascript" src="/joystock/pcte-common/_js/CTRLGeral.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/jquery.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/corner.js"></script>
	    <link rel="stylesheet" href="/joystock/pcte-common/e1024.css" type="text/css" media="all" />
	
	</head>
	
	<body>
		<center>
			<div style="margin-top:50px">
			<fieldset  style=" background-color:#FFFFFF;  width:600px; height:420px;  border-style: solid;border-width:2px; border-color:#<?=COR?>;  z-index: 11;">
				<legend  id="legend"  align="left"><?=$DSC_FRM?></legend>
				<div id="main" style="position:relative;  width:580px; margin-top:5px; height:350px">	
					<div style="position:absolute; top:-15px;  right:0px; width:550px;">
					<?
						$BREG = BarraRegistro();
						$breg = new Breg($BREG[$estado],$ACTION);
						$breg->defineBreg("novo","salvar","editar","cancelar","","");	
						
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
			  	<td style="padding-top:15px" width="40%" align="right">Categoria:</td>
			  	<td style="padding-top:15px">
			  		<input maxlength="80" style="width:160px" <?=$CPO['DSC_GPP']?>  type='text' name='DSC_GPP'>
			  	</td>
			  </tr>
			  <tr>	
			  	
			  </tr>
			  
			  
			  	
			</table>
			</div>
			<div style=" margin-top:10px;  background-color: #<?=COR?>; ">
				<table style="text-align: left; width: 100%;  border="0" cellpadding="2" cellspacing="2">
					<body>
						<tr>
							<td id="LBL_BLD" style="width: 10%;  text-align: center;">
								Código
							</td>
							<td id="LBL_BLD" style="width: 45%; ">
								Categoria
							</td>
							
						</tr>
					</body>
				</table>	
			</div>
			<div style=" top: <?=$topCorpo+=$Altura?>%; left: 1%; right: 1%; height:50%;">
				<iframe name="frm_lista" src="/joystock/pcte-common/frm_lista.php"  border="0" frameborder="0" height="100%" width="100%"></iframe>
			</div>
			
			
			</div>
			</fieldset>
			</div>
			</center>
		</form>	
	</body>
</html>
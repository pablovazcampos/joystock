<?
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	include_once(PATH_FUNCAO."/CTRLCodeSession.class.php");
	include_once(PATH_FUNCAO."/CSSPagination.class.php");
	include_once(PATH_FUNCAO."/class_pesquisa.php");
	
	
	
	
	$CodeSession = new CodeSession();
	$form = "pesquisaClientes.php";
	
	
	$colunas = new listaPesquisa("Pesquisa Clientes","/joystock/cadastros/clientes.php");
	$colunas->criaColuna("COD_CLN", $_POST['COD_CLN'], "Código", "80", "codigo", "fiveFormat", "", "center");
	$colunas->criaColuna("CNJ_CLN", $_POST['CNJ_CLN'], "CPF/CNPJ", "100", "texto", "", "FormatCPF", "center");
	$colunas->criaColuna("NME_CLN", $_POST['NME_CLN'], "Nome", "280", "texto", "", "", "center");
	$colunas->criaColuna("EML_CLN", $_POST['EML_CLN'], "Email", "190", "texto", "", "", "center");
	$colunas->criaColuna("TEL_001", $_POST['TEL_001'], "Telefone", "120", "texto", "", "", "center");
	$colunas->criaColuna("CEL_CLN", $_POST['CEL_CLN'], "Celular", "120", "texto", "", "", "center");
	$colunas->criaColuna("BRR_CLN", $_POST['BRR_CLN'], "Bairro", "250", "texto", "", "", "center");
	$colunas->criaColuna("CDD_CLN", $_POST['CDD_CLN'], "Cidade", "260", "texto", "", "", "center");
	$colunas->criaColuna("EST_CLN", $_POST['EST_CLN'], "Estado", "100", "texto", "", "SiglaEstadoFull", "center");
	
	
	
	$hg = $_SESSION['height'];
	$ifr = $hg - 260;
	$linhas = floor($ifr/18);
	
	
	
	$pag = new CSSPagination('',$linhas,$form,0,$dadosPost);
	$pag->setPage($_POST['page']);
	
	
	$query = "SELECT * FROM upcscln_tb T1 ".
			 "WHERE T1.EXC_UPCSCLN IS NULL AND ISS_CLN = '1' {$colunas->getWhere()} ORDER BY COD_CLN DESC LIMIT {$pag->getLimit()},{$linhas}";
	
	$colunas->criaLinhas($query);
	
	
	$pag->setSQL($query);
	$query = substr($query,0,(strpos($query, "DESC")+4));
	$sel = mysql_query($query);
	$pag->setTotal(@mysql_num_rows($sel));
	
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" href="/joystock/pcte-common/e1024.css" type="text/css" media="all" />
		<link rel="stylesheet" href="/joystock/pcte-common/messi.css" type="text/css" media="all" />
		<script type="text/javascript" src="/joystock/pcte-common/_js/jquery.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.maskedinput.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.maskMoney.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.validation.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/br.gov.dataprev.jquery.maskedinput.patterns.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/rc_autocompleter.js"></script>
	</head>

	<body style="overflow-y:hidden" onload="document.getElementById('role').style.width = document.body.clientWidth; document.getElementById('mLogin').style.height = document.body.clientHeight - 115; " >
		<form name="frmbreg" method="POST" action="<?=$form?>">
			<input type="hidden" name="page" value="<?=$_POST['page']?>">
			<center>	
					<div id="mLogin" style="height:100%; border-radius: 0px; width:100%; overflow-y:auto; overflow-x:hidden">
						<table width="100%" height="100%" cellpadding="0" cellspacing="0">
							<tr  style="position:relative;  " height="30px" width="100%" >
								<td valign="top" id="letra" style="font-size:30px; padding-left:10px " colspan="12" align="left">
									<div style="float:left; width:40px; padding-top:7px">
										<img style="vertical-align:text-bottom; height:30px" src="/joystock/pcte-common/images/lupa.png">
									</div>
									<div style="float:left; padding-top:4px; width:280px">
										<?=$colunas->getTitulo()?>
									</div>
								</td>
								<div style="position:absolute;right:5px; top:3px; padding:2px ">
									<img id="box1" title="Atualizar"    onclick="location.href='<?=$form?>'" style="width:28px; cursor:pointer; height:28px;" src="/joystock/pcte-common/images/actualiser.png">
									<img id="box1" title="Voltar"    onclick="location.href='<?=$colunas->getPath()?>'" style="width:28px; cursor:pointer; height:28px;" src="/joystock/pcte-common/images/back.png">
								</div>
							</tr>
							<tr>
								<td>
									<div id="role" style="width:100%; height:100%; overflow-x:scroll; overflow-y:hidden">
									<table width="100%" height="100%">
										<tr   id="letra" height="35px"  valign="top" style="font-size:13px" >
											<?=$colunas->getFiltros()?>
										</tr>
										<?= $colunas->getLinhas();?>
										
										
										
										<tr><td height="100%"></td></tr>
									</table>
									</div>					
								</td>
							</tr>
														
							<tr  id="letra" style="color:#FFFFFF" height="30px">
								<td align="center" colspan="12">
									<?=$pag->showPage();?>	
								</td>
							</tr>
							
							
							</table>
							
						
					</div>
			</center>
			
		</form>
		<div style="position: absolute; right: 2%; bottom:5%; font-size:75%;">
			<!--<img  src="/pcte-common/images/joyclinic.png">-->
		</div>		
	</body>
	
	
</html>	
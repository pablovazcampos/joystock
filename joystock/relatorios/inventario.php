<?	
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	
	
	
	$BDFB = new FB();
	$BDFB->Conecta();
	
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	
	
	$form = "inventario.php";
	
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
	</head>

	<body style="overflow-y:hidden" onload="form='frmbreg'; destino='vendas'" >
		<form name="frmbreg" method="POST" action="<?=$form?>">
			<center>	
				<div style="height:-webkit-calc(100% - 95px); width:100%; overflow-y:auto; overflow-x:hidden">
					<table width="100%" height="100%" cellpadding="0" cellspacing="0">
						<tr  style="position:relative;  height:45px " width="100%" >
							<td valign="top" id="letra" style="border-bottom:solid 2px #333; font-size:30px; padding-top:5px; " colspan="12" align="center">Relatório do Estoque</td>
							<div style="position:absolute;right:5px; top:5px; padding:2px ">
								<img id="box1" title="Atualizar"    onclick="location.href='/joystock/relatorios/inventario.php'" style="width:28px; cursor:pointer; height:28px;" src="/joystock/pcte-common/images/actualiser.png">
								<!--<img id="box1" title="Voltar"    onclick="location.href='/joystock/cadastros/produtos.php?new=1'" style="width:28px; cursor:pointer; height:28px;" src="/joystock/pcte-common/images/back.png">-->
							</div>
						</tr>
							
						<tr style="height:100%">
							<td align="center">
								<iframe scrolling="No" src="pdfInventario.php" name="FrmPrincipal" frameborder="0" border="0" height="100%" width="100%" ></iframe>
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
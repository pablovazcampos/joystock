<?
	session_start();
	$_SESSION['CONFIGINI'] = "config.ini.php";
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	include_once(PATH_FUNCAO."/CTRLCodeSession.class.php");
	include_once(PATH_FUNCAO."/IniFile.class.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	

	$_SESSION['link'] = "";
	$_SESSION['COD'] = "";
	$_SESSION['TIPO'] = "";
	
	// Iniciando cripto para session 
	$CodeSession = new CodeSession();
	$CodeSession->AutenticaPagina();
	$CodeSession->ValidaPagina();
	if (!empty($_POST['BTNFechar'])) {
		header("Location: /joystock/index.php");
		exit();
	}

	$BDFB = new FB();
	$BDFB->Conecta();
	$CodeSession = new CodeSession();
	
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	
	
	$query = "SELECT COD_GUS FROM upcsguu_tb WHERE COD_USR = {$CodeSession->decode("COD_USR")}";
	$dados = $BDFB->FBSelect($query);
	while ($row = @mysql_fetch_array($dados)){
		if ($row['COD_GUS'] == 1){
			$_SESSION['ADMIN'] = true;
		}
	}
	
	
	
	
	$DSC_FRM = "Agenda";	
	
	$query = "SELECT NME_USR nome ".
	         "FROM upcsusr_tb T1 ".
	         "WHERE COD_USR = ".$CodeSession->decode("COD_USR");
	$dados = $BDFB->FBSelect($query);
	$row = mysql_fetch_array($dados);

	$nome  = $row['nome'];
	
	
	$_SESSION['height'] = $_GET['height'];
	
	// Id da Aba do index 
	$FrmAba[0] ='/joystock/main/logo.php?new=1';
	$FrmAba[1] ='/joystock/cadastros/fornecedores.php?new=1';
	$FrmAba[2] ='/joystock/cadastros/clientes.php?new=1';
	$FrmAba[3] ='/joystock/cadastros/produtos.php?new=1';
	$FrmAba[4] ='/joystock/configuracao/koolPermissoes.php?new=1';
	$FrmAba[5] ='/joystock/cadastros/usuarios.php?new=1';
	$FrmAba[6] ='/joystock/cadastros/grupoProdutos.php?new=1';
	$FrmAba[7] ='/joystock/cadastros/unidadeMedida.php?new=1';
	$FrmAba[8] ='/joystock/cadastros/kits.php?new=1';
	$FrmAba[9] ='/joystock/movimentacao/venda.php?new=1&TIPO=1';
	$FrmAba[10] ='/joystock/movimentacao/venda.php?new=1&TIPO=2';
	$FrmAba[11] ='/joystock/cadastros/grupoClientes.php?new=1';
	$FrmAba[12] ='/joystock/movimentacao/pesquisaMovimento.php?new=1';
	$FrmAba[13] ='/joystock/plano/koolCentroCusto.php?new=1';
	$FrmAba[14] ='/joystock/relatorios/vendas.php?new=1&TIPO=2';
	$FrmAba[15] ='/joystock/relatorios/inventario.php?new=1';
	$FrmAba[16] ='/joystock/relatorios/detalhado.php?new=1&TIPO=2';
	$FrmAba[17] ='/joystock/movimentacao/movimentacaoManual.php?new=1';
	$FrmAba[18] ='/joystock/relatorios/vendas.php?new=1&TIPO=1';
	$FrmAba[19] ='/joystock/relatorios/detalhado.php?new=1&TIPO=1';
	
	
	if(!isset($_GET['r'])){     
		echo "<script language=\"JavaScript\">document.location=\"$PHP_SELF?frm={$_GET['frm']}&r=1&width=\"+screen.width+\"&height=\"+screen.height;</script>";     
	}
	
	
	if(!empty($_GET['frm']))
		$N = $_GET['frm'];
	else{
		$N = 0;
	}

	$aba[$N] = 'current';
	

	
	
	
	
		
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="/joystock/pcte-common/e1024.css" type="text/css" media="all" />
		<link rel="shortcut icon" href="http://enviarboletos.com.br/joystock/favicon.ico"/>
		<title>JOYSTOCK .::.<?=$nome?></title>
		<script language="JavaScript" type="text/javascript" src="/joystock/pcte-common/CTRLGeral.js"></script>
	</head>
	
	<body onload="resolucao(document.body.clientHeight, document.body.clientWidth);"  id="fundoBlue" style="overflow-x: hidden; overflow:hidden;">						
		<center>
			<div align="center" style="height:95px">
				<table cellpadding="0" cellspacing="0" height="80px" width="100%">
					<tr>
				  		<td bgcolor="White"  >
				  			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				      			<tr style="background:#56b6de url('/joystock/pcte-common/images/bg1.gif')" >
				        			<td  align="left" width="65%" height="70px" ><a href="/joystock/main/index.php" style="padding-left:10px"><img style="-webkit-box-shadow:0 0 10px rgba(255,255,255,255); border-radius:8px;" src="/joystock/pcte-common/images/<?=PATH_LOGO?>"  height="50px"></td>
				        			<td id="LBL" style="font-weight:bold; color:#e0d6c7; font-family: 'Arial Rounded MT bold', Helvetica, Arial, sans-serif; text-shadow: 0px 0px 2px #e0d6c7; padding:0px 10px 2px 0px" valign="bottom"  align="right">
				        				<?//$return = @file_get_contents("http://divinopolisplacas.com.br/joystock/pcte-common/conex.php");?>
										<?//if($return != "conectado"):?>
										
										<?//else:?>
										<!--<div style="text-align:center;width:112px;"><a href="javascript:void(window.open('http://www.joysystems.com.br/chat/chat.php','','width=590,height=610,left=0,top=0,resizable=yes,menubar=no,location=no,status=yes,scrollbars=yes'))"><img src="http://www.joysystems.com.br/chat/image.php?id=05&amp;type=inlay" width="112" height="36" border="0" ></a></div><div id="livezilla_tracking" style="display:none"></div><script type="text/javascript">
										var script = document.createElement("script");script.type="text/javascript";var src = "http://www.joysystems.com.br/chat/server.php?request=track&output=jcrpt&nse="+Math.random();setTimeout("script.src=src;document.getElementById('livezilla_tracking').appendChild(script)",1);</script><noscript><img src="http://www.joysystems.com.br/chat/server.php?request=track&amp;output=nojcrpt" width="0" height="0" style="visibility:hidden;" alt=""></noscript>-->
										<?//endif;?>
				        			</td>
				      			</tr>
				      			<tr id="menis">
				      				<td  colspan="2" width="100%">
			      						<?include(PATH_FUNCAO."/KoolPHPSuite/index.php");?>						
				      				<td>
				      			</tr>
				      		</table>	
				    	</td>
					</tr>
				</table>
			</div>
			<div style="height:100%;">
				<iframe src="<?=$FrmAba[$N]?>" name="FrmAgenda" frameborder="0" border="0" width="100%" height="100%"  scrolling="no"  style="z-index:1"></iframe>
			</div>
		</center>
		<div id="rodape" style="position:absolute; bottom:0px; width:100%; ">
			<div style="float:left; paddin-left:10px">
				<font style="font-size:10px"><b>Usuário:</b> <?=$nome?></font>
			</div>
			<div style="float:right">
				&copy; 2012 :: Todos os direitos reservados.&nbsp;
			</div>	
		</div>
	</body> 
</html>

<!--2b29d9d3934c7168cfbf789544adc2c4-->
<?
	session_start();
	include_once("CTRLBDados.php");
	
	
	if ($_POST["breg"] == 'fechar') {
		header("Location: {$_SESSION['close']}");
		exit(0);
	}
	
	
	if (empty($_SESSION['FrmImprimir'])){
     	$_SESSION['FrmImprimir'] = $_GET['paths'];
	}	
	
?>


<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="/joystock/pcte-common/e1024.css" type="text/css" media="all" />
		<script language="JavaScript" type="text/javascript" src="/joystock/pcte-common/CTRLGeral.js"></script>
	</head>	
	
	<body id="FND_ATN"  style="overflow-x: hidden; overflow-y: hidden;">
		<center>	
			<div align="center" style="margin-top:10px; width:90%; height:80%; z-index:99">
				<div id="letraAzul" style="position:relative; font-size:18px; padding:10px" align="left">
					Imprimir
					<div style="position:absolute; top:6px; right:6px">
						<a href="<?=$_SESSION['close']?>" target="_self"><img title="Voltar"  id="box1" style="width:30px; height:30px;" src="/joystock/pcte-common/images/back.png"></a>
					</div>
				</div>
				<div id="mLogin" style=" position:relative; border-radius:0px; height:100%; overflow-y:hidden; overflow-x:hidden">
				<form name="frm" action="print_index.php" method="POST" target="_top">
					<iframe scrolling="No" src="<?=$_SESSION['FrmImprimir']?>" name="FrmPrincipal" frameborder="0" border="0" height="100%" width="100%" ></iframe>
				</form>
			</div>
		</center>		
	</body>
</html>

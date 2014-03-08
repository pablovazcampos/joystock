<?
	session_start();
	
	$_SESSION['CONFIGINI'] = "config.ini.php";
	
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/IniFile.class.php");
	include_once(PATH_FUNCAO."/CTRLCodeSession.class.php");
	include_once(PATH_FUNCAO."/XAJAX/xajax_core/xajax.inc.php");
	include_once(PATH_FUNCAO."/functionAjax.php");
	
	$ajax = new xajax();
	$ajax->configure('javascript URI', 'pcte-common/XAJAX/');
	
	
	$ajax->registerFunction('confirmaSenha');
	$ajax->registerFunction('confirmaEmail');
	
	
	$ajax->processRequest();
	
	
	
	//print_r($_POST);
	
	
	/* Iniciando cripto para session */
	$CodeSession = new CodeSession();
	$NOME = "sts";
	
	$_SESSION['resolucao'] = "e1024.css";
	
	if($_GET['expira']==1){
		$_SESSION['msg'] = "Sessão finalizada por tempo expirado!";
	}
	
	
	$BDFB = new FB();
	$BDFB->Conecta();
	
	if(!empty($_POST['BTNLogin']) && !empty($_POST['usuario']) && !empty($_POST['senha'])){
		$senha = md5($_POST['senha']);
		$query = "UPDATE upcsusr_tb SET SNH_USR = '$senha' WHERE COD_USR  = {$_POST['COD_USR']}";
		$BDFB->EXECQuery($query);
		$_POST['ok'] = 1;
	}
	
	if(!empty($_POST['ok']) && !empty($_POST['usuario']) && !empty($_POST['senha'])){
		
		/* Selecionando usuario para autenticação */
		$LGN_USR = trim($_POST['usuario']);
		$SNH_USR = md5(trim($_POST['senha']));

		
			
		$query = "SELECT COD_USR, EML_USR, NME_USR ".
		         "FROM upcsusr_tb T1 ".
		         "WHERE EML_USR = '$LGN_USR' AND ".
		         "SNH_USR = '$SNH_USR' AND ".
		         "ATV_USR = 1";
		$dados = $BDFB->FBSelect($query);
		$row = @mysql_fetch_array($dados);
		
		
		if(!empty($row['COD_USR'])){
			$data_chave = date("IzYmdw", time()); // Forma��o: Se Bi-sexto, dia do ano (0-365), Ano, Mes, Dia da Semana
			$chave = md5($data_chave.$row->COD_USR);
			$tempo_expira = time() + TMP_EXPIRA;
			
			$data_chave = date("IzYmdw", time()); // Formação: Se Bi-sexto, dia do ano (0-365), Ano, Mes, Dia da Semana
			$chave = md5($data_chave.$row['COD_USR']);
			$tempo_expira = time() + TMP_EXPIRA;

			$CodeSession->encode("COD_USR",$row['COD_USR']);
			$CodeSession->encode("NME_USR",$row['NME_USR']);
			$CodeSession->encode("EML_USR",$row['EML_USR']);
			$CodeSession->encode("IDN_MQN",$_SESSION['SERIAL']);
			$_SESSION['SERIAL'] = "";
			$CodeSession->encode("expira", $tempo_expira);
			$CodeSession->encode("chave", $chave);
			if ($row['TPO_USR'] == 3){
				$NME_MOD  = "/joystock/status/index.php";
			}else {
				$NME_MOD  = "/joystock/main/index.php?user={$row['NME_USR']}";	
			}
			
			header("Location: ".$NME_MOD);
					
			exit();
		}else{ 
			$_SESSION['msg'] = "Senha inválida!";
		}	
		$BDFB->Fechar();	
	}elseif(!empty($_POST['BTNLogin'])){
		if(empty($_SESSION['msg']))
			$_SESSION['msg'] = 'Preencha os campos obrigatórios!';
	}
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>JOYSTOCK</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" href="/joystock/pcte-common/e1024.css" type="text/css" media="all" />
		<link rel="stylesheet" href="/joystock/pcte-common/assets/css/styles.css" />
		<link rel="shortcut icon" href="/joystock/favicon.ico"/>
		<script language="JavaScript" type="text/javascript" src="/joystock/pcte-common/CTRLGeral.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/rc_autocompleter.js"></script>
		<script>
			function click() {
				if (event.button==2||event.button==3) {
					oncontextmenu='return false';
				}
			}
			
			document.onmousedown=click
			document.oncontextmenu = new Function("return false;")
		</script>
		<script language='JavaScript'> 
		//Bloqueador de Botão Direito - iceBreaker http://www.icebreaker.com.br/ 
		function clique() {if (event.button==2||event.button==3) {oncontextmenu=&#39;return false&#39;;}} 
		document.onmousedown=clique 
		document.oncontextmenu = new Function(&quot;return false;&quot;) 
		</script>
		<?php $ajax->printJavascript();?>
	</head>
	  
	<body id="fundoBlue" onload=" window.resizeTo(screen.width,screen.height); " onresize="window.resizeTo(screen.width,screen.height)"  oncontextmenu='return false' style="overflow-x: auto; overflow-y: auto;">
		<!--<img src="logomethodo.gif">-->
		<div id="formContainer">
			<form name="FrmLogin" id="login" method="post" action="login.php">
				<input type="hidden" id="flipToRecover" class="flipLink">
				
				<center>
					<div style="margin-top:30px" >
					<img style="width:236px;  -webkit-border-radius: 8px; -webkit-box-shadow:0 0 10px #000;" src="/joystock/pcte-common/images/<?=PATH_LOGO?>">
					</div>
				</center>
				
				
				<center>
				<div id="LBL_MSG" style="font-size:12px" >
					<?=$_SESSION['msg'];?>
				</div>
				</center>
				
				<input onclick="this.select();" onblur="xajax_confirmaEmail(this.value)" type="text" name="usuario" id="loginEmail" class="loginEmail" value="Email"  autocomplete="off" style="text-transform:lowercase;" />
				
				<input onclick="this.select()" type="text" name="senha" id="loginPass" value="senha" onfocus="this.setAttribute('type', 'password'); this.value = '' " />
				
				<input  onclick=" xajax_confirmaSenha(this.form.usuario.value, this.form.senha.value); return false;" id="box1" style="width:236px" type="submit" name="BTNLogin" value="Login" />
				<input type="hidden" onclick="submit()" id="ok" name="ok" value="1">
				<!--<div style="position:absolute; left:15px;  font-size:11px; bottom:2px" >
					<div id="flipToForget" class="flipLinkForget"  >Esqueci minha senha?</div>
				</div>-->
				
				<!--<div style="position:absolute; left:144px;  font-size:11px; bottom:0px" >
					<img  style=" height:15px; width:2px" src="/joystock/pcte-common/images/menu_divider.jpg">
				</div>-->
				
				<!--<div style="position:absolute; right:15px;  font-size:11px; bottom:2px" >
					<div id="flipToRegister" class="flipLinkRegister"  >Quero me cadastrar.</div>
				</div>-->
			</form>
			<form name="FrmAcesso" id="recover" method="post" action="login.php">
				<input type="hidden" id="flipToLogin" class="flipLink">
				<input id="usuario" type="hidden" name="usuario">
				<input id="COD_USR" type="hidden" name="COD_USR">
				<center>
				<div id="LBL_MSG4" >
					Primeiro Acesso. Cadastre sua Senha
				</div>
				</center>
				
				<center>
				<div id="LBL_MSG3" >
					
				</div>
				</center>
				
				<input onclick="this.select()" type="text" name="senha"  value="senha" onfocus="this.setAttribute('type', 'password'); this.value = '' " />
				<input type="text" name="confirma" value="Confirme sua senha" id="loginPass" onblur="saidaConfirmacao(this.value, this.form)" onkeyup='confirmacao(this.value, this.form);' onfocus="this.setAttribute('type', 'password'); this.value = '' " />
				<center>
				<div id="LBL_MSG2" >
					
				</div>
				</center>
				<center>
				<input id="box1" style="display:none; " type="submit" name="BTNLogin" value="Cadastrar" />					
				</center>
			</form>
			
			<form name="FrmRegister" id="register" method="post" action="login.php">
				<center>
					<?include_once("cadastro/cadastro.php")?>
				</center>
			</form>
			
			
			<form name="FrmForget" id="forget" method="post" action="login.php">
				<center>
					<?include_once("forget/forget.php")?>
				</center>
			</form>
			
			
		</div>
		
		<?
			
			if (!empty($_SESSION['msg'])) {
				$fundo = "background-color:#e9e9e9; ";
			}
		?>
		
		<div id="LBL_Data">
			
		</div>
		
		<footer>
	        <div  title="" style="position: absolute;  left:20px; bottom:5px; font-size:75%;">
				<img   style="width:30px; height:30px" src="/joystock/pcte-common/images/tech.png">
			</div>
			
			<div  title="" style="position: absolute; width:90px; height:30; text-align:center; left:40px; color:#EEEEEE; bottom:7px; font-size:75%;">
				Suporte Telefone<br>(37) 3212-9988
				<!--Suporte Telefone<br>(37) 3222-5715-->
			</div>
			
			<div  title="" style="position: absolute; width:5px; height:30;  left:135px; bottom:5px; font-size:75%;">
				<img  style=" height:30px" src="/joystock/pcte-common/images/menu_divider.jpg">
			</div>
			
			<?//$return = @file_get_contents("http://divinopolisplacas.com.br/joystock/pcte-common/conex.php");?>
			<?//if($return != "conectado"):?>
			
			<?//else:?>
			<div  title="" style="position: absolute; left:145px; top:10px; font-size:75%;">
				<div style="text-align:center;width:112px;"><a href="javascript:void(window.open('http://www.joysystems.com.br/chat/chat.php','','width=590,height=610,left=0,top=0,resizable=yes,menubar=no,location=no,status=yes,scrollbars=yes'))"><img src="http://www.joysystems.com.br/chat/image.php?id=05&amp;type=inlay" width="112" height="36" border="0" ></a></div><div id="livezilla_tracking" style="display:none"></div><script type="text/javascript">
				var script = document.createElement("script");script.type="text/javascript";var src = "http://www.joysystems.com.br/chat/server.php?request=track&output=jcrpt&nse="+Math.random();setTimeout("script.src=src;document.getElementById('livezilla_tracking').appendChild(script)",1);</script><noscript><img src="http://www.joysystems.com.br/chat/server.php?request=track&amp;output=nojcrpt" width="0" height="0" style="visibility:hidden;" alt=""></noscript>
			</div>
			<?//endif;?>
			
			
			<div  title="" style="position: absolute; width:5px; height:30;  left:254px; bottom:5px; font-size:75%;">
				<img style=" height:30px; " src="/joystock/pcte-common/images/menu_divider.jpg">
			</div>
			
			<div  title="" style="position: absolute; width:30px; height:30;  left:264px; bottom:5px; font-size:75%;">
				<img style="width:30px; height:30px" src="/joystock/pcte-common/images/email32.png">
			</div>
			
			<div  title="" style="position: absolute; width:120px; height:30; text-align:left; left:298px; color:#EEEEEE; bottom:7px; font-size:75%;">
				Suporte Email<br>
				<!--<label style="color:#e3fe68">suporte@joysystems.com.br</label>-->
				<label style="color:#e3fe68">suporte@implantarsolucoes.com.br</label>
			</div>
			
			
			
			<div  style="position: absolute; height:30px; right:1%; bottom:8px;">
				<img id="sombra"  style="width:128px; background-color:transparent; border-radius:5px; height:100%" src="/joystock/pcte-common/images/logohorizonta.jpg">
				<!--<img id="sombra"  style="width:128px; background-color:transparent; border-radius:5px; height:100%" src="/joystock/pcte-common/images/joysystems.png">-->
			</div>
        </footer>
		<!--<div id="footer" style="position: absolute; width:100%; height:50px; bottom:0px; font-size:75%;">
			
		</div>-->
		
		
		
		<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.maskedinput.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.maskMoney.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.validation.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/br.gov.dataprev.jquery.maskedinput.patterns.js"></script>
		<script src="/joystock/pcte-common/assets/js/script.js"></script>
		
	</body> 
</html>
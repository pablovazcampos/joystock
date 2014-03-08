<?php
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	include_once(PATH_FUNCAO."/CTRLBDados.php");
	include_once(PATH_FUNCAO."/breg.php");
	
	$BDFB = new FB();
	$BDFB->Conecta();
	
	if ($_GET['new'] == 1){
		$_SESSION['COD'] = "";
		$_SESSION['STS_BREG'] = 0;
	}
	
	$sql = "SHOW TABLE STATUS FROM `".BANCO_DADOS."` LIKE 'upcsusr_tb'";
	$dados = $BDFB->FBSelect($sql);
	$rss = mysql_fetch_array($dados);
	$codigo = $rss['Auto_increment'];
	
	
	if ($_POST['RST_SNH'] == 1){
		$_POST['SNH_USR'] = "";
	}else {
		$query = "SELECT SNH_USR FROM upcsusr_tb WHERE COD_USR = {$_POST['COD_USR']}";
		$dados = $BDFB->FBSelect($query);
		$row = @mysql_fetch_array($dados);
		$_POST['SNH_USR'] = $row['SNH_USR'];
	}
	
	$corMsg = "#".COR;
	
	$frmMaster			 = 'usuarios.php';							
	$NME_FRM 			 = 'index';
	$DSC_FRM             = 'Cadastro de Usuários';
	$ACTION 			 = 'usuarios.php';
	$_SESSION['NME_RTN'] = "/joystock/cadastros/usuarios.php";
	$TABELA              = "upcsusr_tb";
	
	
	require_once(PATH_FUNCAO."/CTRLReg.php");
	
	$us = $usr = abs($CPOValue['COD_USR']);
	$usr = !empty($usr)?$usr:$_POST['COD_USR'];
	
	
	
	
	
	//SALVA SECUNDÁRIOS
	
	if (!empty($typeSave)){
		
		if (empty($erroEdit)){
			$_POST['COD_USR'] = $usr;
			
			//DELETA PERMISSÕES
			$query = "DELETE FROM upcsguu_tb WHERE COD_USR = $usr";
			$BDFB->EXECQuery($query);
			
			//SALVA PERMISSÕES
			$tabela = "upcsguu_tb";
			$fields = $BDFB->CamposTabela($tabela);
			
			//BUSCA A QUANTIDADE DE GRUPOS DE USUÁRIOS
			$query = "SELECT * FROM upcsgus_tb";
			$dados = $BDFB->FBSelect($query);
			while ($row = @mysql_fetch_array($dados)){
				if (!empty($_POST["GUS".$row['COD_GUS']])){
					$_POST["COD_GUS"] = $row['COD_GUS'];
					for($i=0;$i<count($fields);$i++){
						$COD_EDIT = strtoupper('COD_'.substr($tabela, 4, 3));
						if (trim($fields[$i]['NOME']) == $COD_EDIT){
							$VrCampo = 0;
						}else{ 
							$VrCampo = $_POST[trim($fields[$i]['NOME'])];
						}	
						$BDFB->IncCampo(trim($fields[$i]['NOME']), trim($fields[$i]['TIPO']), $VrCampo);
					}
					
					$ifErro = $BDFB->FBInsert($tabela);
					$_SESSION['msg'] = trim($ifErro[0]);
					if(empty($_SESSION['msg']))
						$_SESSION['msg'] = "Registro criado com sucesso!";
					else{
						$_SESSION['msg'] = $erroARS = "Falha ao registrar Permissões";
						$corMsg = "red";
						$x=99; //ERRO
					}
				}	
			}	
		}else {
			$_SESSION['msg'] = $erroARS = "Falha ao registrar usuário";
			$corMsg = "red";
			$x=99; //ERRO
		}
		
		
	}
	
	$query = "SELECT * FROM upcsguu_tb WHERE COD_USR = $us";
	$dados = $BDFB->FBSelect($query);
	while ($row = @mysql_fetch_array($dados)){
		$GUS[$row['COD_GUS']] = 1;
	}
	
	
	$query = "SELECT * FROM upcsgus_tb ";
    $dados = $BDFB->FBSelect($query);
    $aux = 4;
    while ($row = @mysql_fetch_array($dados)){
    	$aster .= "CASE WHEN T{$row['COD_GUS']}.COD_GUS = {$row['COD_GUS']} THEN 'SIM' ELSE 'NÃO' END AS GUS{$row['COD_GUS']}, "; 
   		$join .= "LEFT JOIN upcsguu_tb T{$row['COD_GUS']} ON T0.COD_USR = T{$row['COD_GUS']}.COD_USR AND T{$row['COD_GUS']}.COD_GUS = {$row['COD_GUS']} ";
   		$_SESSION['prop'][$aux] = array('campo' => 'GUS'.($aux-3),'funcao' => '','link'=>'COD_USR','propriedade' => 'vertical-align: top; text-align: center; width:100px; font-size:85%;');
   		$aux++;
    }
	
	
	$_SESSION['query_lista'] = "SELECT T0.COD_USR,  T0.NME_USR, T0.EML_USR, $aster CASE WHEN T0.ATV_USR = 1 THEN 'SIM' ELSE 'NÃO' END AS ATV_USR ";
	$_SESSION['query_lista'] .= "FROM upcsusr_tb T0 ";
    $_SESSION['query_lista'] .= $join;
	$_SESSION['query_lista'] .=  "WHERE T0.EXC_UPCSUSR IS NULL ORDER BY NME_USR";

	
	$_SESSION['link_title'] = 'COD_USR'; // fazer aparecer o title na lista
	
	/* Cria��o e Defini��o de colunas para lista */
	$_SESSION['prop'][0] = array('campo' => 'COD_USR','format' => 'ZeroEsquerda','funcao' => '','link'=>'COD_USR','propriedade' => 'vertical-align: top; text-align: center; width:98px; font-size:85%;');
	$_SESSION['prop'][1] = array('campo' => 'NME_USR','funcao' => '','link'=>'COD_USR','propriedade' => 'vertical-align: top; text-align: left; width:239px; font-size:85%;');
	$_SESSION['prop'][2] = array('campo' => 'EML_USR','funcao' => 'etc','link'=>'COD_USR','propriedade' => 'vertical-align: top; text-align: center; width:235px; font-size:85%;');
	$_SESSION['prop'][3] = array('campo' => 'ATV_USR','funcao' => '','link'=>'COD_USR','propriedade' => 'vertical-align: top; text-align: center; width:100px; font-size:85%;');
	
	
	
	
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
			<div style="margin-top:30px">
			<fieldset  style=" background-color:#FFFFFF;  width:900px; height:520px; border-style: solid;border-width:1px; border-color:#<?=COR?>;  z-index: 11;">
				<legend  id="legend"  align="left">Cadastro de Usuários</legend>
				<div id="main" style="position:relative; width:890px; margin-top:15px; height:500px">	
					<div style="position:absolute; top:-10px; right:0px; width:580px;">
						<?
							$BREG = BarraRegistro();
							$breg = new Breg($BREG[$estado],$ACTION);
							//$breg->bregFechar("menu_manutencao.php","POST","_top","",$_POST['COD_CDD']);
							$breg->defineBreg("novo","salvar","editar","cancelar","","fim");	
							
						?>
					</div>	
			
			<div style="padding-top:60px;">
			<table id="LBL" width='100%'>
			   <tr>
			   		<td colspan="2" id="msg"  bgcolor="<?=$corMsg?>"   style=" font-size:13px; font-weight:bold" colspan="6" align="center">
		   				<?=$_SESSION['msg']?>
			   		</td>
			  </tr>	
			  
			  <tr>	
			  	<td>
			  		<div style='float:left; width:80px;  padding:5px 5px 0px 0px;'>
						Código:<input id='numeracao' readonly name='COD_USR' type='text' value='<?=(empty($CPOValue['COD_USR']))?str_pad($codigo,6,0,STR_PAD_LEFT):str_pad($CPOValue['COD_USR'],6,0,STR_PAD_LEFT)?>'>
					</div>
					
					<div style='float:left; width:300px; padding:5px 5px 0 0px;'>
						Nome:
						<input autocomplete='off' style="width:100%; <?=$CPOStyle['NME_USR']?> onkeydown="return handleEnter(this, event)" type="text"  id="formulario"  name="NME_USR" <?=$CPO['NME_USR']?> >
					</div>
					
					<div style='float:left; width:280px; padding:5px 5px 0 0px;'>
						Email:
						<input autocomplete='off' style="width:100%; <?=$CPOStyle['EML_USR']?> onkeydown="return handleEnter(this, event)" type="text"  id="formulario"  name="EML_USR" <?=$CPO['EML_USR']?> >
					</div>
					
					<div style='float:left; width:100px; padding:5px 5px 0 0px;'>
						Ativo:
						<select id="formulario" name="ATV_USR" <?=$CPO['ATV_USR']?> style="width:100%">
							<option <?=$CPOValue['ATV_USR']!=2?"selected":""?> value="1">SIM</option>
							<option <?=$CPOValue['ATV_USR']==2?"selected":""?> value="2">NÃO</option>
						</select>
					</div>
					
					<div style='float:left; width:100px; padding:5px 0px 10px 0px;'>
						Resetar Senha:
						<select id="formulario" name="RST_SNH" style="width:100%">
							<option  value="1">SIM</option>
							<option selected value="2">NÃO</option>
						</select>
					</div>
					
					<div id="MRG" style='float:left; border:solid 1px #<?=COR?>;  border-radius:3px; width:100%; padding:5px 0px 0 0px;'>
					
						<div style='float:left; width:150px; padding:10px 0px 10px 10px;'>
							Acessos do usuário:
						</div>
						
						
						<?	
							$query = "SELECT * FROM upcsgus_tb";
							$dados = $BDFB->FBSelect($query);
							while ($row = @mysql_fetch_array($dados)):
							//print $GUS[$row['COD_GUS']];
						?>
						
						<div style='float:left; width:130px; padding:10px 10px 10px 0px;'>
							<input value="1" style="vertical-align:middle" <?=($GUS[$row['COD_GUS']]==1)?"checked":""?>  type="checkbox" name="GUS<?=$row['COD_GUS']?>">
							<?=$row['NME_GUS']?>
						</div>
						
						<?endwhile;?>	
					</div>	
			  	</td>
			  </tr>
			  
			  
			  
			  	
			</table>
			</div>
			<div style=" margin-top:10px;   ">
				<table style="text-align: left; width: 100%;"  border="0" cellpadding="2" cellspacing="2">
					<body>
						<tr bgcolor="#<?=COR?>">
							<td id="LBL_BLD" style="width:100px; border-radius:3px 3px 0px 0px; color:#e0d6c7; text-align: center;">
								Cód.
							</td>
							<td id="LBL_BLD" style="width:245px; border-radius:3px 3px 0px 0px; color:#e0d6c7; text-align: center;">
								Nome
							</td>
							<td id="LBL_BLD" style="width:245px; border-radius:3px 3px 0px 0px; color:#e0d6c7; text-align: center;">
								Email
							</td>
							<td id="LBL_BLD" style="width:100px; border-radius:3px 3px 0px 0px; color:#e0d6c7; text-align: center;">
								Ativo
							</td>
							
							<?	
								$query = "SELECT * FROM upcsgus_tb";
								$dados = $BDFB->FBSelect($query);
								while ($row = @mysql_fetch_array($dados)):
							?>
							
							<td id="LBL_BLD" style="width:100px; font-size:9px;  border-radius:3px 3px 0px 0px; color:#e0d6c7; text-align: center;">
								<?=etc($row['NME_GUS'],20)?>
							</td>
							
							<?endwhile;?>
							
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
<?php
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	include_once(PATH_FUNCAO."/CTRLBDados.php");
	include_once(PATH_FUNCAO."/breg.php");
	include_once(PATH_FUNCAO."/CTRLCodeSession.class.php");
	require $KoolControlsFolder."/KoolTreeView/kooltreeview.php";
	
	$BDFB = new FB();
	$BDFB->Conecta();
	
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	
	$corMsg = "#".COR;
	
	

	if (empty($_POST['COD_GUS'])){
		$_POST['COD_GUS'] = abs($_POST['GUS']);
	}
	
	$query = "SELECT PAI_MNU, IDN_MNU, NME_MNU, IMG_MNU, T1.COD_MNU, T2.COD_GUS FROM upcsmnu_tb T1 LEFT JOIN upcsprm_tb T2 ON T1.COD_MNU = T2.COD_MNU AND T2.COD_GUS = {$_POST['COD_GUS']}";
	$dados = $BDFB->FBSelect($query);
	$acesso = !empty($row['COD_GUS'])?true:false;
	while ($row = @mysql_fetch_array($dados)){
		$treearr[] = array($row['PAI_MNU'],$row['IDN_MNU'],$row['NME_MNU'],false,$row['IMG_MNU'],$row['COD_GUS'], $row['COD_MNU']);	
	}
	
	//SALVA AS PERMISSÕES
	if ($_POST['breg'] == "salvar"){
		$query = "DELETE FROM upcsprm_tb WHERE COD_GUS = {$_POST['COD_GUS']}";
		$BDFB->EXECQuery($query);
		for ( $i = 0; $i < sizeof($treearr) ; $i++){
			if (isset($_POST["cb_".$treearr[$i][1]]))
			{
				$query = "INSERT INTO upcsprm_tb VALUES({$_POST['COD_GUS']},{$treearr[$i][6]})";
				$BDFB->EXECQuery($query);
				$_SESSION['msg'] = "Permissões salvas com sucesso.";
				//echo $treearr[$i][6]."<br/>";
			}
		}
		
		//FAZ A RELEITURA
		$treearr = "";
		$query = "SELECT PAI_MNU, IDN_MNU, NME_MNU, IMG_MNU, T1.COD_MNU, T2.COD_GUS FROM upcsmnu_tb T1 LEFT JOIN upcsprm_tb T2 ON T1.COD_MNU = T2.COD_MNU AND T2.COD_GUS = {$_POST['COD_GUS']}";
		$dados = $BDFB->FBSelect($query);
		$acesso = !empty($row['COD_GUS'])?true:false;
		while ($row = @mysql_fetch_array($dados)){
			$treearr[] = array($row['PAI_MNU'],$row['IDN_MNU'],$row['NME_MNU'],false,$row['IMG_MNU'],$row['COD_GUS'], $row['COD_MNU']);	
		}
		
		
	}	
	
	
	
									
	$_node_template = "<input type='checkbox' id='cb_{id}' name='cb_{name}' {check} onclick='toogle(\"{id}\")'><label for='cb_{id}'>{text}</label>";
	
	$treeview = new KoolTreeView("treeview");
	$treeview->scriptFolder = $KoolControlsFolder."/KoolTreeView";
	$treeview->imageFolder=$KoolControlsFolder."/KoolTreeView/icons";
	$treeview->styleFolder = "default";
	$root = $treeview->getRootNode();
	
	$root->text = str_replace("{id}","treeview.root",$_node_template);
	$root->text = str_replace("{name}","treeview_root",$root->text);
	$root->text = str_replace("{text}","Acesso Total",$root->text);
	
	$root->expand=true;
	$root->image="cad.png";	
	for ( $i = 0 ; $i < sizeof($treearr) ; $i++)
	{
		$_text = str_replace("{id}",$treearr[$i][1],$_node_template);
		$_text = str_replace("{name}",$treearr[$i][1],$_text);
		$_text = str_replace("{text}",$treearr[$i][2],$_text);
		$_text = str_replace("{check}",isset($treearr[$i][5])?"checked":"",$_text);
		$treeview->Add($treearr[$i][0],$treearr[$i][1],$_text,$treearr[$i][3],$treearr[$i][4]);
		if (isset($treearr[$i][5])){
			$aux3++;
		}
	}
	
	$root->text = str_replace("{check}",(sizeof($treearr)==$aux3)?"checked":"",$root->text);
	
	$treeview->showLines = true;
	$treeview->selectEnable = false;
	$treeview->keepState = "onpage";	
	
	
	$query = "SELECT NME_GUS FROM upcsgus_tb WHERE COD_GUS = {$_POST['COD_GUS']}";
	$dados = $BDFB->FBSelect($query);
	$row4 = @mysql_fetch_array($dados);
	
	
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	</head>		
	<script language="javascript">
		function markChild(nodeId)
		{
			var status = document.getElementById("cb_" + nodeId ).checked;
			var childIds = treeview.getNode(nodeId).getChildIds();	
			for(var i in childIds)
	        {
				document.getElementById("cb_" + childIds[i] ).checked = status;
				markChild(childIds[i]);	
	        }		
		}
		
		function markParent(nodeId)
		{
			if (nodeId.indexOf(".root")<0)
			{
				var parentId = treeview.getNode(nodeId).getParentId();
				var siblingIds = treeview.getNode(parentId).getChildIds();
				var _parentSelected = true;
				for(var i in siblingIds)
					if (!document.getElementById("cb_" + siblingIds[i]).checked) 
						_parentSelected = false;
				document.getElementById("cb_" + parentId).checked = _parentSelected;
				markParent(parentId);
			}		
	
		}
		
		function toogle(nodeId)
		{
			markChild(nodeId);
	        markParent(nodeId);
		}
		
	</script>
	
	<body > 
		<center>
		
			<div style="margin-top:20px">
			<fieldset   style="  width:670px; height:530px;  border-style: solid;border-width:1px; border-color:#<?=COR?>;  z-index: 11;">
				<legend  id="legend"  align="left">Gerenciamento de Permissões</legend>
				<div id="main" style="position:relative;   width:570px; margin-top:0px; height:530px">	
					<div style="position:absolute; top:-10px; right:0px; width:580px;">
					<?
						$BREG = BarraRegistro();
						$breg = new Breg($BREG[0],$ACTION);
						//$breg->bregFechar("/joystock/main/index.php?frm=0","POST","_top","",$_POST['COD_BRR']);
						$breg->defineBreg("","salvar02","","","","","fim");	
					?>
					</div>
					<input type="hidden" name="GUS" value="<?=$_POST['COD_GUS']?>">
					
					<div style=" text-align:center; padding-top:70px;">
						<table align="center"   cellpadding="0px" cellspacing="0" width='100%' height="100%"  >
							<tr  >
						   		<td colspan="2" id="msg"  bgcolor="<?=$corMsg?>" height="5px" style=" font-size:13px; font-weight:bold; " align="center">
					   				<?=$_SESSION['msg']?>
						   		</td>
						  	</tr>
						  	<tr style="height:10px" >
						   		<td colspan="2">
					   				
						   		</td>
						  	</tr>
							<tr  style='font-size:75%; top:10px' >
								<td width="30%" valign="top" style="border-right:solid 0px #2c7dbe; -webkit-box-shadow:0 2px 3px rgba(0,0,0,.2);">
									<div id="legend2" style='float:left; width:100%;   padding:0px 0px 0px 0px;'>
										Grupos de Usuários
									</div>
									<div style='float:left; width:100%;   padding:0px 0px 0px 0px;'>
										<br>
									</div>
									<?
										$query = "SELECT * FROM upcsgus_tb";
										$dados = $BDFB->FBSelect($query);
										while ($row = @mysql_fetch_array($dados)):
									?>
									<div  style='float:left; width:100%;   padding:5px 0px 0px 0px;'>
										<button onclick="document.getElementById('carrega').style.visibility = 'visible'" id="box1" style="width:80%; cursor:pointer" name="COD_GUS" value="<?=$row['COD_GUS']?>"><?=$row['NME_GUS']?></button>
									</div>
									<?endwhile;?>
								</td>
								<td width="70%" align="left"   valign="top" style='font-weight:bold;  -webkit-box-shadow:0 2px 3px rgba(0,0,0,.2);  '  id='LBL'>
									<div id="legend2" style='float:left; width:100%;  padding:0px 0px 0px 0px;'>
										Permissões
									</div>
									<div style='float:left; width:100%;  padding:0px 0px 0px 0px;'>
										<br>
									</div>
									<?if (!empty($row4['NME_GUS'])):?>
										<div id="field2" style='float:left; text-align:center; font-size:13px; width:50%;  padding:0px 0px 0px 0px;'>
											<?=$row4['NME_GUS']?>
										</div>
									<?endif;?>	
									<div style="float:left; overflow:auto; width:100%; height:350px; padding:10px;">
										<?php echo $treeview->Render();?>
									</div>
								</td>
							</tr>
						</table>
					</div>
			</fieldset>
			</div>
			</center>
		</form>
		<div id="carrega" class="trans" style="position:absolute; background:#2c7dbe; opacity:0.5; visibility:hidden;  width:100%; height:110%; top:-10px; left:0% ">
			<div style="position:absolute; top:50%; left:50%; ">
				<img src="/joystock/pcte-common/KoolPHPSuite/koolControls/KoolAjax/loading/10.gif">
			</div>	
		</div>	
	</body>		
</html>
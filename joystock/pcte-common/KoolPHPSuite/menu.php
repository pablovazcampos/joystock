<?php
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLBDados.php");
	include_once(PATH_FUNCAO."/CTRLCodeSession.class.php");
	require $KoolControlsFolder."/KoolMenu/koolmenu.php";
	
	$BDFB = new FB();
	$BDFB->Conecta();
	$CodeSession = new CodeSession();
	
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	
	$km = new KoolMenu("km");
	$km->scriptFolder = $KoolControlsFolder."/KoolMenu";
	$km->styleFolder="default";
	
	$query = "SELECT * FROM upcsguu_tb WHERE COD_USR = {$CodeSession->decode('COD_USR')}   ";
	$dados = $BDFB->FBSelect($query);
	while ($row = @mysql_fetch_array($dados)){
		$aux[] = $row['COD_GUS'];
	}
	if (count($aux)>0){
		$gus = @implode(",",$aux);
	}	
	
	$query = "SELECT * FROM upcsmnu_tb ORDER BY ORD_MNU";
	$dados = $BDFB->FBSelect($query);
	while ($row = @mysql_fetch_array($dados)){
		if (!empty($row['IFR_MNU'])){
			$action = "javascript:location.href=\"index.php?frm={$row['IFR_MNU']}\"";
		}else {
			$action = $row['ACT_MNU'];
		}
		
		if ($row['PAI_MNU'] == 'root'){
			$query = "SELECT COD_MNU FROM upcsmnu_tb WHERE PAI_MNU = '{$row['IDN_MNU']}'";
			$dados3 = $BDFB->FBSelect($query);
			$aux2="";
			while ($row3 = @mysql_fetch_array($dados3)){
				$aux2[]=$row3['COD_MNU'];
			}
			
			
			if (!empty($aux2)){
				$filhos = implode(",",$aux2);
			}else {
				$filhos = $row['COD_MNU'];;
			}
		}else {
			$filhos = $row['COD_MNU'];
		}
		//print $filhos."<br>";
		
		$query = "SELECT * FROM upcsprm_tb WHERE COD_GUS IN ($gus) AND COD_MNU IN ($filhos) ";
		$dados2 = $BDFB->FBSelect($query);
		if ($row2 = @mysql_fetch_array($dados2)){
			$km->Add($row['PAI_MNU'],$row['IDN_MNU'],$row['NME_MNU'],$action,(!empty($row['IMG_MNU']))?"/joystock/pcte-common/KoolPHPSuite/koolControls/KoolTreeView/icons/".$row['IMG_MNU']:"");
		}	
	}
	
	//INCLUI AS LISTAS
	$query = "SELECT * FROM upcssts_tb WHERE LST_STS = '1'";
	$dados = $BDFB->FBSelect($query);
	while ($row = @mysql_fetch_array($dados)){
		$item = $km->Add("listas",$row['DSC_STS'],$row['DSC_STS'],"javascript:location.href=\"index.php?frm=6&tpo={$row['COD_STS']}\"","/joystock/pcte-common/KoolPHPSuite/koolControls/KoolTreeView/icons/".++$x.".gif");
	}
	
?>

<html>
<head>
</head>
<form id="form1" method="post">
	<div id="teste" style="padding-bottom:0px; ">
		<?php echo $km->Render();?>
	</div>
</form>
</html>
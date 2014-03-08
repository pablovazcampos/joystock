<?
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	include_once(PATH_FUNCAO."/CTRLCodeSession.class.php");
	include_once(PATH_FUNCAO."/CSSPagination.class.php");
	
	$BDFB = new FB();
	$BDFB->Conecta();
	
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	
	
	$CodeSession = new CodeSession();
	$form = "pesquisaKits.php";
	
	
	$preco = str_replace(",",".",str_replace(".","",$_POST['PRC_VND']));
	$quantidade = str_replace(",",".",str_replace(".","",$_POST['QTD_PRD']));
	if ($_POST['STS_PRD'] == 3){
		$_POST['STS_PRD'] = 0;
	}
	
	$pag = new CSSPagination('',20,$form,0,$dadosPost);
	$pag->setPage($_POST['page']);
	
	$where .= !empty($_POST['COD_PDT'])?" AND COD_PDT = {$_POST['COD_PDT']} ":"";
	$where .= !empty($_POST['STS_PRD'])?" AND STS_PRD = {$_POST['STS_PRD']} ":"";
	$where .= !empty($_POST['COD_GPP'])?" AND COD_GPP = {$_POST['COD_GPP']} ":"";
	$where .= !empty($_POST['COD_UMD'])?" AND COD_UMD = {$_POST['COD_UMD']} ":"";
	$where .= !empty($quantidade)?" AND QTD_PRD = {$quantidade} ":"";
	$where .= !empty($preco)?" AND PRC_VND = {$preco} ":"";
	$where .= !empty($_POST['NME_PRD'])?" AND NME_PRD LIKE ('%{$_POST['NME_PRD']}%') ":"";
	
	
	
	
	$estados = SiglaEstadoFull();
	$status = array(1=>"Habilitado",2=>"Desabilitado");
	
	$query = "SELECT * FROM upcsprd_tb T1 ".
			 "WHERE T1.EXC_UPCSPRD IS NULL AND TPO_PRD = '2' $where ORDER BY COD_PRD DESC LIMIT {$pag->getLimit()},20";
	$dados = $BDFB->FBSelect($query);
	
	$pag->setSQL($query);
	$query = substr($query,0,(strpos($query, "DESC")+4));
	$sel = @mysql_query($query);
	$pag->setTotal(@mysql_num_rows($sel));
	
	
	$query = "SELECT * FROM upcsgpp_tb";
	$dadosGPP = $BDFB->FBSelect($query);
	while ($rowGPP = @mysql_fetch_array($dadosGPP)){
		$categorias[$rowGPP['COD_GPP']] = $rowGPP['DSC_GPP'];
	}
	
	$query = "SELECT * FROM upcsumd_tb";
	$dadosUMD = $BDFB->FBSelect($query);
	while ($rowUMD = @mysql_fetch_array($dadosUMD)){
		$umedidas[$rowUMD['COD_UMD']] = $rowUMD['DSC_UMD'];
	}
	
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
		<title>JOYSTOCK</title>
	</head>

	<body style="overflow-y:hidden" onload="document.getElementById('role').style.width = document.body.clientWidth; " >
		<form name="frmbreg" method="POST" action="<?=$form?>">
			<input type="hidden" name="page" value="<?=$_POST['page']?>"
			<center>	
					<div id="mLogin" style="height:100%; width:100%; overflow-y:auto; overflow-x:hidden">
						<table width="100%" height="97%" cellpadding="0" cellspacing="0">
							<tr  style="position:relative;  " height="30px" width="100%" >
								<td valign="top" id="letra" style="font-size:30px;  " colspan="12" align="center">Pesquisa de Produtos Agrupados</td>
								<div style="position:absolute;right:5px; padding:2px ">
									<img id="box1" title="Atualizar"    onclick="location.href='/joystock/cadastros/pesquisaKits.php'" style="width:28px; cursor:pointer; height:28px;" src="/joystock/pcte-common/images/actualiser.png">
									<img id="box1" title="Voltar"    onclick="location.href='/joystock/cadastros/kits.php?new=1'" style="width:28px; cursor:pointer; height:28px;" src="/joystock/pcte-common/images/back.png">
								</div>
							</tr>
							<tr>
								<td>
									<div id="role" style="width:102px; height:100%; overflow-x:scroll; overflow-y:hidden">
									<table width="100%" height="100%">
										<tr   id="letra" height="30px"  valign="top" style="font-size:13px" >
											<td style="position:relative" align="center" width="70px">
												<div id="codigo" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													Código
													<input class="fiveFormat" autocomplete="off" onblur="setTimeout(function(){desFiltros('codigo', '30')},200)" name="COD_PRD" value="<?=$_POST['COD_PRD']?>" onclick="filtros('codigo', '65'); this.select();"  type="text" id="formularioLitle" style="width:98%; text-align:center ">
													<div onclick="document.forms['frmbreg'].elements['page'].value='';  submit();" id="codigoFiltrar" class="filtrar">Filtrar</div>
													<div onclick="document.forms['frmbreg'].elements['COD_PRD'].value=''; submit();" id="codigoLimpar" class="limpar" >Limpar</div>
												</div>
											</td>
											<td style="position:relative" align="center" width="240px">
												<div id="categoria" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													Categoria
													<select style="height:17px; font-size:11px; color:#666666; width:100%" name="COD_GPP" onchange="document.forms['frmbreg'].elements['page'].value=''; submit()">
														<option></option>
														<?=$BDFB->FBObjBDSelect($_POST['COD_GPP'],"","upcsgpp_tb","COD_GPP","DSC_GPP","","","");?>
													</select>
												</div>
											</td>
											<td style="position:relative" align="center" width="380px">
												<div id="nome" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													Nome
													<input autocomplete="off" onblur="setTimeout(function(){desFiltros('nome', '30')},200)" name="NME_PRD" value="<?=$_POST['NME_PRD']?>" onclick="filtros('nome', '65'); this.select();"  type="text" id="formularioLitle" style="width:98%; ">
													<div onclick="document.forms['frmbreg'].elements['page'].value='';  submit();" id="nomeFiltrar" class="filtrar">Filtrar</div>
													<div onclick="document.forms['frmbreg'].elements['NME_PRD'].value=''; submit();" id="nomeLimpar" class="limpar" >Limpar</div>
												</div>
											</td>
											<td style="position:relative" align="center" width="100px">
												<div id="status" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													Status
													<select style="height:17px; font-size:11px; color:#666666; width:100%" name="STS_PRD" onchange="document.forms['frmbreg'].elements['page'].value=''; submit()">
														<option></option>
														<?=$BDFB->FBObjSelect($_POST['STS_PRD'],"",$status)?>
													</select>
												</div>
											</td>
											
											<td style="position:relative" id="titlelist" align="center" width="120px">
												<div id="unidadeMedida" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													U. Medida
													<select style="height:17px; font-size:11px; color:#666666; width:100%" name="COD_UMD" onchange="document.forms['frmbreg'].elements['page'].value=''; submit()">
														<option></option>
														<?=$BDFB->FBObjBDSelect($_POST['COD_UMD'],"","upcsumd_tb","COD_UMD","DSC_UMD","","","");?>
													</select>
												</div>
											</td>
											
										</tr>
							<?
								
								
							
								
							
								while ($row = @mysql_fetch_array($dados)){
									
									
									$ver=1;
									
									if ($x==0){
										$color2 = "#".COR;
										$bgcolor = "#f7ecdc";
										$x++;
									}else {
										$color2 = "#".COR;
										$bgcolor = "#b6ada0";
										$x--;
									}
									
									
									$out .= '<tr onclick="window.location.href = \'/joystock/cadastros/kits.php?COD='.$row['COD_PRD'].'\';" bgcolor="'.$bgcolor.'" style="color:'.$color2.'; cursor:pointer;  font-size:11px" >'.
										    '<td  align="center" >'.str_pad($row['COD_PRD'],5,0,STR_PAD_LEFT).'</td>'.
										    '<td  align="center" >'.$categorias[$row['COD_GPP']].'</td>'.
										    '<td  align="center" >'.$row['NME_PRD'].'</td>'.
										    '<td  align="center" >'.$status[$row['STS_PRD']].'</td>'.
											'<td  align="center" >'.$umedidas[$row['COD_UMD']].'</td>'.
											'</tr>';
								}
								
								if (empty($ver)){
									$out = "<tr>".
												"<td colspan='18' valign='middle' align='left' style='color:red; padding-left:100px'>".
													"Não foi encontrado produtos com os filtros acima.".
												"</td>".
											"</tr>";
								}
								
								
								print $out;
							
							?>
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
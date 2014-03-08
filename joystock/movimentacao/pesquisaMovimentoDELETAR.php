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
	$form = "pesquisaMovimento.php";
	
	
	
	if ($_POST['TPO_MVM'] == 3 ){
		$_POST['TPO_MVM'] = 0;
		
	}
	
	if ($_POST['FRM_PGT'] == 4){
		$_POST['FRM_PGT'] = 0;
	}
	
	$pag = new CSSPagination('',20,$form,0,$dadosPost);
	$pag->setPage($_POST['page']);
	
	$where .= !empty($_POST['COD_MVM'])?" AND COD_MVM = {$_POST['COD_MVM']} ":"";
	$where .= !empty($_POST['DTA_VN1'])?" AND DTA_MVM >= '".VerifData($_POST['DTA_VN1'])."' ":"";
	$where .= !empty($_POST['DTA_VN2'])?" AND DTA_MVM <= '".VerifData($_POST['DTA_VN2'])."' ":"";
	$where .= !empty($_POST['FRM_PGT'])?" AND FRM_PGT = {$_POST['FRM_PGT']} ":"";
	$where .= !empty($_POST['NME_CLN'])?" AND NME_CLN LIKE ('%{$_POST['NME_CLN']}%') ":"";
	$where .= !empty($_POST['TPO_MVM'])?" AND TPO_MVM = {$_POST['TPO_MVM']} ":"";
	$where .= !empty($_POST['VLR_TT1'])?" AND VLR_TTL >= ".str_replace(",",".",str_replace(".","",$_POST['VLR_TT1'])):"";
	$where .= !empty($_POST['VLR_TT2'])?" AND VLR_TTL <= ".str_replace(",",".",str_replace(".","",$_POST['VLR_TT2'])):"";
	
	
	
	
	$formapgt = array(1=>"Dinheiro",2=>"Cheque",3=>"Cartão");
	$tipoMovimento = array(1=>"Compra",2=>"Venda");
	
	$query = "SELECT * FROM upcsmvm_tb T1 ".
			 "JOIN upcscln_tb T2 ON T1.COD_CLN = T2.COD_CLN ".
			 "WHERE T1.EXC_UPCSMVM IS NULL $where ORDER BY COD_MVM DESC LIMIT {$pag->getLimit()},20";
	$dados = $BDFB->FBSelect($query);
	
	$pag->setSQL($query);
	$query = substr($query,0,(strpos($query, "DESC")+4));
	$sel = @mysql_query($query);
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
		<title>JOYSTOCK</title>
	</head>

	<body style="overflow-y:hidden" onload="document.getElementById('role').style.width = document.body.clientWidth; " >
		<form name="frmbreg" method="POST" action="<?=$form?>">
			<input type="hidden" name="page" value="<?=$_POST['page']?>">
			<center>	
					<div id="mLogin" style="height:100%; width:100%; overflow-y:auto; overflow-x:hidden">
						<table width="100%" height="89%" cellpadding="0" cellspacing="0">
							<tr  style="position:relative;  " height="30px" width="100%" >
								<td valign="top" id="letra" style="font-size:30px;  " colspan="12" align="center">Pesquisa de Movimentos</td>
								<div style="position:absolute;right:5px; padding:2px ">
									<img id="box1" title="Atualizar"    onclick="location.href='/joystock/movimentacao/pesquisaMovimento.php'" style="width:28px; cursor:pointer; height:28px;" src="/joystock/pcte-common/images/actualiser.png">
									<img id="box1" title="Voltar"    onclick="location.href='/joystock/movimentacao/venda.php?new=1'" style="width:28px; cursor:pointer; height:28px;" src="/joystock/pcte-common/images/back.png">
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
													<input class="fiveFormat" autocomplete="off" onblur="setTimeout(function(){desFiltros('codigo', '30')},200)" name="COD_MVM" value="<?=$_POST['COD_MVM']?>" onclick="filtros('codigo', '65'); this.select();"  type="text" id="formularioLitle" style="width:98%; text-align:center ">
													<div onclick="document.forms['frmbreg'].elements['page'].value='';  submit();" id="codigoFiltrar" class="filtrar">Filtrar</div>
													<div onclick="document.forms['frmbreg'].elements['COD_MVM'].value=''; submit();" id="codigoLimpar" class="limpar" >Limpar</div>
												</div>
											</td>
											<td style="position:relative" id="titlelist" align="center" width="80px" >
												<div id="data" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													Data
													<input autocomplete="off" onblur="setTimeout(function(){desFiltros('data', '30')},200)" name="DTA_VN1" value="<?=$_POST['DTA_VN1']?>" onclick="setTimeout(function(){filtros('data', '90')},210); this.select();"  type="text" id="formularioLitle" style="width:98%; text-align:center " class="dataFormat">
													<div id="dataData" style="display:none">
														à
														<input autocomplete="off" onblur="setTimeout(function(){desFiltros('data', '30')},200)" name="DTA_VN2" value="<?=$_POST['DTA_VN2']?>" onclick="setTimeout(function(){filtros('data', '90')},210); this.select();"  type="text" id="formularioLitle" style="width:98%; text-align:center " class="dataFormat">
													</div>
													<div onclick="document.forms['frmbreg'].elements['page'].value='';  submit();" id="dataFiltrar" class="filtrar">Filtrar</div>
													<div onclick="document.forms['frmbreg'].elements['DTA_VN1'].value=''; document.forms['frmbreg'].elements['DTA_VN2'].value=''; submit();" id="dataLimpar" class="limpar" >Limpar</div>
												</div>
											</td>
											<td style="position:relative" align="center" width="380px">
												<div id="nome" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													Cliente/Fornecedor
													<input autocomplete="off" onblur="setTimeout(function(){desFiltros('nome', '30')},200)" name="NME_CLN" value="<?=$_POST['NME_CLN']?>" onclick="filtros('nome', '65'); this.select();"  type="text" id="formularioLitle" style="width:98%; ">
													<div onclick="document.forms['frmbreg'].elements['page'].value='';  submit();" id="nomeFiltrar" class="filtrar">Filtrar</div>
													<div onclick="document.forms['frmbreg'].elements['NME_CLN'].value=''; submit();" id="nomeLimpar" class="limpar" >Limpar</div>
												</div>
											</td>
											<td style="position:relative" align="center" width="100px">
												<div id="formaPagamento" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													Forma Pgt
													<select style="height:17px; font-size:11px; color:#666666; width:100%" name="FRM_PGT" onchange="document.forms['frmbreg'].elements['page'].value=''; submit()">
														<option></option>
														<?=$BDFB->FBObjSelect($_POST['FRM_PGT'],"",$formapgt)?>
													</select>
												</div>
											</td>
											
											<td style="position:relative" id="titlelist" align="center" width="120px">
												<div id="tipoMovimento" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													Tipo
													<select style="height:17px; font-size:11px; color:#666666; width:100%" name="TPO_MVM" onchange="document.forms['frmbreg'].elements['page'].value=''; submit()">
														<option></option>
														<?=$BDFB->FBObjSelect($_POST['TPO_MVM'],"",$tipoMovimento)?>
													</select>
												</div>
											</td>
											<td style="position:relative" id="titlelist" align="center" width="90px">
												<div id="valor" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													Valor
													<input autocomplete="off" onblur="setTimeout(function(){desFiltros('valor', '30')},200)" name="VLR_TT1" value="<?=$_POST['VLR_TT1']?>" onclick="setTimeout(function(){filtros('valor', '90')},210); this.select();"  type="text" id="formularioLitle" style="width:98%; text-align:center " class="moedaFormat">
													<div id="valorData" style="display:none">
														até
														<input autocomplete="off" onblur="setTimeout(function(){desFiltros('valor', '30')},200)" name="VLR_TT2" value="<?=$_POST['VLR_TT2']?>" onclick="setTimeout(function(){filtros('valor', '90')},210); this.select();"  type="text" id="formularioLitle" style="width:98%; text-align:center " class="moedaFormat">
													</div>
													<div onclick="document.forms['frmbreg'].elements['page'].value='';  submit();" id="valorFiltrar" class="filtrar">Filtrar</div>
													<div onclick="document.forms['frmbreg'].elements['VLR_TT1'].value=''; document.forms['frmbreg'].elements['VLR_TT2'].value=''; submit();" id="valorLimpar" class="limpar" >Limpar</div>
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
									
									
									$out .= '<tr onclick="window.location.href = \'/joystock/movimentacao/venda.php?new=1&TIPO='.$row['TPO_MVM'].'&COD='.$row['COD_MVM'].'\';" bgcolor="'.$bgcolor.'" style="color:'.$color2.'; cursor:pointer;  font-size:11px" >'.
										    '<td  align="center" >'.str_pad($row['COD_MVM'],5,0,STR_PAD_LEFT).'</td>'.
										    '<td  align="center" >'.$BDFB->MostrarCampo("date",$row['DTA_MVM']).'</td>'.
										    '<td  align="center" >'.$row['NME_CLN'].'</td>'.
										    '<td  align="center" >'.$formapgt[$row['FRM_PGT']].'</td>'.
											'<td  align="center" >'.$tipoMovimento[$row['TPO_MVM']].'</td>'.
											'<td  align="center" >'.number_format($row['VLR_TTL'],"2",",",".").'</td>'.
											'</tr>';
								}
								
								if (empty($ver)){
									$out = "<tr>".
												"<td colspan='18' valign='middle' align='left' style='color:red; padding-left:100px'>".
													"Não foi encontrado movimentos com os filtros acima.".
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
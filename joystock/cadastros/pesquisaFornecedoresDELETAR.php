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
	$form = "pesquisaFornecedores.php";
	
	if ($_POST['EST_CLN'] == 28){
		$_POST['EST_CLN'] = 0;
	}
	
	$pag = new CSSPagination('',20,$form,0,$dadosPost);
	$pag->setPage($_POST['page']);
	
	$where .= !empty($_POST['COD_CLN'])?" AND COD_CLN = {$_POST['COD_CLN']} ":"";
	$where .= !empty($_POST['EST_CLN'])?" AND EST_CLN = {$_POST['EST_CLN']} ":"";
	$where .= !empty($_POST['CNJ_CLN'])?" AND CNJ_CLN LIKE ('%{$_POST['CNJ_CLN']}%') ":"";
	$where .= !empty($_POST['NME_CLN'])?" AND NME_CLN LIKE ('%{$_POST['NME_CLN']}%') ":"";
	$where .= !empty($_POST['EML_CLN'])?" AND EML_CLN LIKE ('%{$_POST['EML_CLN']}%') ":"";
	$where .= !empty($_POST['BRR_CLN'])?" AND BRR_CLN LIKE ('%{$_POST['BRR_CLN']}%') ":"";
	$where .= !empty($_POST['CDD_CLN'])?" AND CDD_CLN LIKE ('%{$_POST['CDD_CLN']}%') ":"";
	$where .= !empty($_POST['TEL_001'])?" AND TEL_001 LIKE ('%{$_POST['TEL_001']}%') ":"";
	$where .= !empty($_POST['CEL_CLN'])?" AND CEL_CLN LIKE ('%{$_POST['CEL_CLN']}%') ":"";
	
	
	
	
	$estados = SiglaEstadoFull();
	
	$query = "SELECT * FROM upcscln_tb T1 ".
			 "WHERE T1.EXC_UPCSCLN IS NULL AND ISS_FRN = '1' $where ORDER BY COD_CLN DESC LIMIT {$pag->getLimit()},20";
	$dados = $BDFB->FBSelect($query);
	
	$pag->setSQL($query);
	$query = substr($query,0,(strpos($query, "DESC")+4));
	$sel = mysql_query($query);
	$pag->setTotal(mysql_num_rows($sel));
	
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
								<td valign="top" id="letra" style="font-size:30px;  " colspan="12" align="center">Pesquisa de Fornecedores</td>
								<div style="position:absolute;right:5px; padding:2px ">
									<img id="box1" title="Atualizar"    onclick="location.href='/joystock/cadastros/pesquisaFornecedores.php'" style="width:28px; cursor:pointer; height:28px;" src="/joystock/pcte-common/images/actualiser.png">
									<img id="box1" title="Voltar"    onclick="location.href='/joystock/cadastros/fornecedores.php'" style="width:28px; cursor:pointer; height:28px;" src="/joystock/pcte-common/images/back.png">
								</div>
							</tr>
							<tr>
								<td>
									<div id="role" style="width:102px; height:100%; overflow-x:scroll; overflow-y:hidden">
									<table width="1500px" height="100%">
										<tr   id="letra" height="30px"  valign="top" style="font-size:13px" >
											<td style="position:relative" align="center" width="80px">
												<div id="documento" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													Código
													<input class="fiveFormat" autocomplete="off" onblur="setTimeout(function(){desFiltros('documento', '30')},200)" name="COD_CLN" value="<?=$_POST['COD_CLN']?>" onclick="filtros('documento', '65'); this.select();"  type="text" id="formularioLitle" style="width:98%; text-align:center ">
													<div onclick="document.forms['frmbreg'].elements['page'].value='';  submit();" id="documentoFiltrar" class="filtrar">Filtrar</div>
													<div onclick="document.forms['frmbreg'].elements['COD_CLN'].value=''; submit();" id="documentoLimpar" class="limpar" >Limpar</div>
												</div>
											</td>
											<td style="position:relative" align="center" width="100px">
												<div id="cnj" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													CPF/CNPJ
													<input autocomplete="off" onblur="setTimeout(function(){desFiltros('cnj', '30')},200)" name="CNJ_CLN" value="<?=$_POST['CNJ_CLN']?>" onclick="filtros('cnj', '65'); this.select();"  type="text" id="formularioLitle" style="width:98%; ">
													<div onclick="document.forms['frmbreg'].elements['page'].value='';  submit();" id="cnjFiltrar" class="filtrar">Filtrar</div>
													<div onclick="document.forms['frmbreg'].elements['CNJ_CLN'].value=''; submit();" id="cnjLimpar" class="limpar" >Limpar</div>
												</div>
											</td>
											<td style="position:relative" align="center" width="280px">
												<div id="nome" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													Nome
													<input autocomplete="off" onblur="setTimeout(function(){desFiltros('nome', '30')},200)" name="NME_CLN" value="<?=$_POST['NME_CLN']?>" onclick="filtros('nome', '65'); this.select();"  type="text" id="formularioLitle" style="width:98%; ">
													<div onclick="document.forms['frmbreg'].elements['page'].value='';  submit();" id="nomeFiltrar" class="filtrar">Filtrar</div>
													<div onclick="document.forms['frmbreg'].elements['NME_CLN'].value=''; submit();" id="nomeLimpar" class="limpar" >Limpar</div>
												</div>
											</td>
											<td style="position:relative" id="titlelist" align="center" width="190px">
												<div id="email" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													E-mail
													<input autocomplete="off" onblur="setTimeout(function(){desFiltros('email', '30')},200)" name="EML_CLN" value="<?=$_POST['EML_CLN']?>" onclick="filtros('email', '65'); this.select();"  type="text" id="formularioLitle" style="width:98%; ">
													<div onclick="document.forms['frmbreg'].elements['page'].value='';  submit();" id="emailFiltrar" class="filtrar">Filtrar</div>
													<div onclick="document.forms['frmbreg'].elements['EML_CLN'].value=''; submit();" id="emailLimpar" class="limpar" >Limpar</div>
												</div>
											</td>
											<td style="position:relative" id="titlelist" align="center" width="120px">
												<div id="telefone" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													Telefone
													<input  autocomplete="off" onblur="setTimeout(function(){desFiltros('telefone', '30')},200)" name="TEL_001" value="<?=$_POST['TEL_001']?>" onclick="filtros('telefone', '65'); this.select();"  type="text" id="formularioLitle" style="width:98%; text-align:center ">
													<div onclick="document.forms['frmbreg'].elements['page'].value='';  submit();" id="telefoneFiltrar" class="filtrar">Filtrar</div>
													<div onclick="document.forms['frmbreg'].elements['TEL_001'].value=''; submit();" id="telefoneLimpar" class="limpar" >Limpar</div>
												</div>
											</td>
											<td style="position:relative" id="titlelist" align="center" width="120px">
												<div id="celular" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; text-align:center">	
													Celular
													<input autocomplete="off" onblur="setTimeout(function(){desFiltros('celular', '30')},200)" name="CEL_CLN" value="<?=$_POST['CEL_CLN']?>" onclick="filtros('celular', '65'); this.select();"  type="text" id="formularioLitle" style="width:98%; text-align:center">
													<div onclick="document.forms['frmbreg'].elements['page'].value='';  submit();" id="celularFiltrar" class="filtrar">Filtrar</div>
													<div onclick="document.forms['frmbreg'].elements['CEL_CLN'].value=''; submit();" id="celularLimpar" class="limpar" >Limpar</div>
												</div>
											</td>
											<td style="position:relative" id="titlelist" align="center" width="250px" >
												<div id="bairro" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													Bairro
													<input autocomplete="off" onblur="setTimeout(function(){desFiltros('bairro', '30')},200)" name="BRR_CLN" value="<?=$_POST['BRR_CLN']?>" onclick="filtros('bairro', '65'); this.select();"  type="text" id="formularioLitle" style="width:98%; ">
													<div onclick="document.forms['frmbreg'].elements['page'].value='';  submit();" id="bairroFiltrar" class="filtrar">Filtrar</div>
													<div onclick="document.forms['frmbreg'].elements['BRR_CLN'].value=''; submit();" id="bairroLimpar" class="limpar" >Limpar</div>
												</div>
												
											</td>
											<td style="position:relative" id="titlelist" align="center" width="260px" >
												<div id="cidade" class="titleBlt"  style="position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; ">	
													Cidade
													<input autocomplete="off" onblur="setTimeout(function(){desFiltros('cidade', '30')},200)" name="CDD_CLN" value="<?=$_POST['CDD_CLN']?>" onclick="filtros('cidade', '65'); this.select();"  type="text" id="formularioLitle" style="width:98%; ">
													<div onclick="document.forms['frmbreg'].elements['page'].value='';  submit();" id="cidadeFiltrar" class="filtrar">Filtrar</div>
													<div onclick="document.forms['frmbreg'].elements['CDD_CLN'].value=''; submit();" id="cidadeLimpar" class="limpar" >Limpar</div>
												</div>
											</td>
											
											<td id="titlelist" align="center" width="100px">
												Estado
												<select style="height:17px; font-size:11px; color:#666666; width:100%" name="EST_CLN" onchange="document.forms['frmbreg'].elements['page'].value=''; submit()">
													<option></option>
													<?=$BDFB->FBObjSelect($_POST['EST_CLN'],"",$estados)?>
												</select>
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
									
									
									$out .= '<tr onclick="window.location.href = \'/joystock/cadastros/fornecedores.php?COD='.$row['COD_CLN'].'\';" bgcolor="'.$bgcolor.'" style="color:'.$color2.'; cursor:pointer;  font-size:11px" >'.
										    '<td  align="center" >'.str_pad($row['COD_CLN'],5,0,STR_PAD_LEFT).'</td>'.
										    '<td  align="center" >'.FormatCPF($row['CNJ_CLN']).'</td>'.
										    '<td  align="center" >'.$row['NME_CLN'].'</td>'.
											'<td  align="center" >'.$row['EML_CLN'].'</td>'.
											'<td  align="center" >'.$row['TEL_001'].'</td>'.
											'<td  align="center" >'.$row['CEL_CLN'].'</td>'.
											'<td  align="center" >'.$row['BRR_CLN'].'</td>'.
											'<td  align="center" >'.$row['CDD_CLN'].'</td>'.
											'<td  align="center" >'.$estados[$row['EST_CLN']].'</td>'.
											'</tr>';
								}
								
								if (empty($ver)){
									$out = "<tr>".
												"<td colspan='18' valign='middle' align='left' style='color:red; padding-left:100px'>".
													"Não foi encontrado boletos com os filtros acima.".
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
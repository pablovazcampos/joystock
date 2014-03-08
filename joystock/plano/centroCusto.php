<?php 
	require $KoolControlsFolder."/KoolAjax/koolajax.php";
	require $KoolControlsFolder."/KoolTreeView/kooltreeview.php";
	
	$BDFB = new FB();
	$BDFB->Conecta();
	
	 mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	
	
	
	$_POST['DTA_INI'] = !empty($_POST['DTA_INI'])?$_POST['DTA_INI']:date("d/m/Y");
	$_POST['DTA_FIM'] = !empty($_POST['DTA_FIM'])?$_POST['DTA_FIM']:date("d/m/Y");
	
	
	
	$inicio = $BDFB->TratarCampo("date", $_POST['DTA_INI']);
	$fim = $BDFB->TratarCampo("date", $_POST['DTA_FIM']);
	
	
	if ($koolajax->isCallback==false)
	{
		//If it is not the callback, get tree data from database and render
		$query = "SELECT T1.COD_CDC, T1.TPO_CDC, T1.DSC_CDC, T1.COD_CTT, T1.CDC_LNC, T1.PSC_RNK, T1.IMG_CDC FROM upcscdc_tb T1 ".
	                   "ORDER BY PSC_RNK ASC ";
		$result = $BDFB->FBSelect($query);
		//$result = mysql_query("SELECT * FROM tbtreeviewdata order by rank asc");
	
		$arrtree = array();
	
		while($row = @mysql_fetch_array($result))
	  	{
	  		//print $row['COD_CTT'];
	  		
			array_push($arrtree,$row);
	  	}
	  	
	  	
	  	//SOMATÓRIO ANTERIOR
	  	$query = "SELECT T1.COD_CDC, T1.TPO_CDC, T1.DSC_CDC, T1.COD_CTT, T1.CDC_LNC, T1.PSC_RNK, T1.IMG_CDC, SUM(T2.VLR_MVM) AS SOMA FROM upcscdc_tb T1 ".
				 "LEFT JOIN upcsmcc_tb T2 ON T1.COD_CDC = T2.COD_CDC AND DTA_CDS < {$inicio} ".
				 "GROUP BY T1.COD_CDC ORDER BY COD_CDC DESC ";
		$result = $BDFB->FBSelect($query);
	  	while($row = @mysql_fetch_array($result)){
	  		$anterior[$row['COD_CDC']] += $row['SOMA'];	
  			$anterior[$row['COD_CTT']] += $anterior[$row['COD_CDC']];	
	  	}
	  	
	  	$saldoAnterior = $anterior[1] - $anterior[2];
	  	
	  	//SOMATÓRIOS
	  	$query = "SELECT T1.COD_CDC, T1.TPO_CDC, T1.DSC_CDC, T1.COD_CTT, T1.CDC_LNC, T1.PSC_RNK, T1.IMG_CDC, SUM(T2.VLR_MVM) AS SOMA FROM upcscdc_tb T1 ".
				 "LEFT JOIN upcsmcc_tb T2 ON T1.COD_CDC = T2.COD_CDC AND DTA_CDS >= {$inicio} AND DTA_CDS <= {$fim} ".
				 "GROUP BY T1.COD_CDC ORDER BY COD_CDC DESC ";
		$result = $BDFB->FBSelect($query);
	  	while($row = @mysql_fetch_array($result)){
	  		$soma[$row['COD_CDC']] += $row['SOMA'];	
  			$soma[$row['COD_CTT']] += $soma[$row['COD_CDC']];	
	  	}
	  	
	  	
	  	
		$arrtree = sortTree("root",$arrtree);
		
		//print_r($arrtree);
		
		//Init treeview
		$treeview = new KoolTreeView("treeview");
		$treeview->imageFolder=$KoolControlsFolder."/KoolTreeView/icons";
		$treeview->styleFolder="darkgray";		
		$treeview->selectEnable = true;
		$treeview->multipleSelectEnable = true;
		$treeview->DragAndDropEnable = true;
		$treeview->EditNodeEnable = false;
		
		$treeview->showLines = true;

		$caixa = $soma[1] - $soma[2];	
		if ($caixa > 0){
			$color = "#83b2ff";
		}else {
			$color = "#ff8383";
		}
		
		$caixaTotal = $saldoAnterior + $caixa;
 
		//Root
		$root = $treeview->getRootNode();
		$root->text = "SALDO DO PERÍODO - <label style='color:$color'>(".number_format($caixa,2,",",".").")</label>";
		$root->expand=true;
		$root->image="stop.png";
 
		//print_r($soma);
 
		//Add item from database
		foreach($arrtree as $node)
		{
			if ($soma[$node['COD_CDC']] > 0){
				$color = ($node['TPO_CDC'] == 1)?"#83b2ff":"#ff8383";
			}else {
				$color = "#333333";
			}
			
			$treeview->Add($node["COD_CTT"],$node["COD_CDC"],str_pad($node["COD_CDC"],5,0,STR_PAD_LEFT)." - ".$node["DSC_CDC"]." - <label style='color:$color'>(".number_format($soma[$node['COD_CDC']],2,",",".").")</label>",$node["CDC_LNC"],$node["IMG_CDC"]);		
		}	
	}
	
	
	
	
	
	function sortTree($nodeid,$arTree)
	{
		$res = array();
		for($i=0;$i<sizeof($arTree);$i++)
			if ($arTree[$i]["COD_CTT"]==$nodeid)
			{
				array_push($res,$arTree[$i]);
				$subres = sortTree($arTree[$i]["COD_CDC"],$arTree);
				for ($j=0;$j<sizeof($subres);$j++)
					array_push($res,$subres[$j]);
			}
		return $res;
	}
	
	
	
	
	function doCreateNode($parentid,$id,$text,$image,$rank, $expand)
	{
		if ($parentid=="treeview.root") $parentid="root";
		
		$BDFB = new FB();
		$BDFB->Conecta();
		
		$query = "SELECT TPO_CDC FROM upcscdc_tb WHERE COD_CDC = $parentid";
		$dados = $BDFB->FBSelect($query);
		$row = @mysql_fetch_array($dados);
		
		
		$query = "INSERT INTO upcscdc_tb VALUES ($id, '".$row['TPO_CDC']."', '$text', '$parentid', '$expand', $rank, '$image')";
		$BDFB->EXECQuery($query);
		
		$sql = "SHOW TABLE STATUS FROM `".BANCO_DADOS."` LIKE 'upcscdc_tb'";
		$dados = $BDFB->FBSelect($sql);
		$rss = mysql_fetch_array($dados);
		$codigo = $rss['Auto_increment'];
		$codigo--;
		
		$extend = str_pad($codigo,5,0,STR_PAD_LEFT);
		
		return array("ult"=>$codigo,"text"=>$text,"img"=>$image,"parentId"=>$parentid, "extend"=>$extend);
	}
	
	function doDeleteNode($id)
	{
		$BDFB = new FB();
		$BDFB->Conecta();
		$BDFB->EXECQuery("DELETE FROM upcscdc_tb WHERE COD_CDC='$id'");
		return 1;
	}
	function doSort($joinlist)
	{
		$list = explode("|",$joinlist);
		for($i=0;$i<sizeof($list);$i++)
		{
			$rank = $i+1;
			$BDFB = new FB();
			$BDFB->Conecta();
			$BDFB->EXECQuery("UPDATE upcscdc_tb SET PSC_RNK=$rank WHERE COD_CDC=$list[$i]");
		}
		return 1;
	}
	
	function doMove($id,$parentid,$oldparentid)
	{
		
		if ($parentid=="treeview.root") 
		{
			$_parentid="root";
		}
		else
		{
			$_parentid = $parentid;
		}
 		$BDFB = new FB();
		$BDFB->Conecta();
		$BDFB->EXECQuery("UPDATE upcscdc_tb SET COD_CTT=$_parentid WHERE COD_CDC=$id");
		return array("parentid"=>$parentid,"oldparentid"=>$oldparentid);
	}
	function doUpdate($id,$text,$expand)
	{
		$BDFB = new FB();
		$BDFB->Conecta();
		$text = substr($text,8,strpos($text," - <label")-8);
		$BDFB->EXECQuery("UPDATE upcscdc_tb SET DSC_CDC='$text',CDC_LNC='$expand' WHERE COD_CDC=$id");
		return 1;
	}
	
	//Enable ajax function to be called at client-side
	$koolajax->enableFunction("doCreateNode");
	$koolajax->enableFunction("ultInsert");
	$koolajax->enableFunction("doDeleteNode");
	$koolajax->enableFunction("doSort");
	$koolajax->enableFunction("doMove");
	$koolajax->enableFunction("doUpdate");	

	if ($saldoAnterior >= 0){
		$cor = "#83b2ff";
	}else {
		$cor = "#ff8383";
	}
	
	if ($caixaTotal >= 0){
		$cor2 = "#83b2ff";
	}else {
		$cor2 = "#ff8383";
	}
	
	if(!isset($_GET['r'])){     
		echo "<script language=\"JavaScript\">document.location=\"$PHP_SELF?r=1&width=\"+screen.width+\"&height=\"+screen.height;</script>";     
	}   	
	
	//print $_GET['height'];
 
?>

 
<form id="form1" method="post" action="">
	<center>
	<div style="margin-top:20px; height:<?=$_GET['height']-250?>px; width:100%;  ">
	<fieldset  style=" background-color:#FFFFFF; text-align:left; width:90%; height:100%;  border-style: solid;border-width:2px; border-color:#696867;  z-index: 11;">
		<legend  id="legend"  align="left">Fluxo de Caixa</legend>
		<div id="" style="float:left; background-color:#FFF; text-align:center;  width:100%; margin-top:5px; ">	
			Período:
			<input id="ini" value="<?=$_POST['DTA_INI']?>" style="width:100px; text-align:center" autocomplete="off" type="text" name="DTA_INI" class="dataFormat" >
			à
			<input id="fim2" value="<?=$_POST['DTA_FIM']?>" style="width:100px; text-align:center" autocomplete="off" type="text" name="DTA_FIM" class="dataFormat" >
			<input id="box1" type="submit" value="Filtrar">
		</div>
		<div id="legend2" style="float:left; text-align:left; padding:2px 0px 2px 5px;  width:50%; margin-top:5px; ">	
			Saldo Anterior: <label style="color:<?=$cor?>"> R$ <?=number_format($saldoAnterior,2,",",".");?></label>
		</div>
		
		<div id="main" style="float:right; width:-webkit-calc(50% - 10px); margin-top:5px; height:<?=$_GET['height']-82?>px ">	
			<iframe id="ifr" style="border:none" src="/joystock/plano/lancamentos.php?width=<?=$_GET['width']?>&height=<?=$_GET['height']?>&COD=treeview.root&INI=<?=$_POST['DTA_INI']?>&FIM=<?=$_POST['DTA_FIM']?>" name="FrmAgenda" height="100%" width="100%" scrolling="no"  ></iframe>
		</div>
		
		<div id="main" style="float:left; position:relative; overflow-y:scroll; width:50%; margin-top:5px; height:-webkit-calc(100% - 130px)">	
			<?php echo $koolajax->Render();?>
			<div style="padding:10px;">
				<?php echo $treeview->Render();?>
			</div>
			
			<!--<input type="button" id="btnRemove" value="Excluir Conta" onclick="deleteTreeNode()"/>-->
			
			<div id="status" style="position:absolute;right:5px;top:5px;background-color:#FFFFA0;color:black;font-weight:bold;padding-left:5px;padding-right:5px;display:none;">Editando...</div>
			
			<script type="text/javascript">
				var nodeselect = null;
				var ultSelect = null;
				
				
				function onCreateNodeDone(result)
				{
					
					nodeselect = result.ult;
					var texto = result.extend+" - "+result.text;
					treeview.getNode(result.parentId).addChildNode(nodeselect,texto,"<?php echo $KoolControlsFolder;?>/KoolTreeView/icons/"+result.img);					
					//alert(nodeselect);
					hideStatus();
				}
				
				function onDeleteDone(result)
				{
					hideStatus();
				}
				
				function onSaveDone(result)
				{
					ultSelect = result;
				}
		 
				function onSortDone(result)
				{
					hideStatus();
				}
				function onUpdateDone(result)
				{
					hideStatus();
				}
				
		 
				function onMoveDone(result)
				{
		 
					_childids = treeview.getNode(result.parentid).getChildIds();
					koolajax.callback(doSort(_childids.join("|")),onSortDone);
							
					_childids = treeview.getNode(result.oldparentid).getChildIds();
					koolajax.callback(doSort(_childids.join("|")),onSortDone);	
				}		
				
				//OnBefo#ff8383rop to handle node order
				treeview.registerEvent("OnBefo#ff8383rop",function(sender,arg){
					var _dropid = arg.NodeId;
					var _dragid = arg.DragNodeId;
					
					if (treeview.getNode(_dropid).getParentId()==treeview.getNode(_dragid).getParentId())
					{
						var parentid = treeview.getNode(_dropid).getParentId();
						var childids = treeview.getNode(parentid).getChildIds();
						var posdrag=0;posdrop = 0;
						for(var i=0;i<childids.length;i++)
						{
							if (childids[i]==arg.NodeId)
							{
								posdrop = i;
							}
							if (childids[i]==arg.DragNodeId)
							{
								posdrag = i;
							}	
						}
						if (posdrag<posdrop)
						{
							//Drag from above node to below node
							treeview.getNode(arg.DragNodeId).moveToBelow(arg.NodeId);
						}
						else
						{
							//Drag from below node to node above
							treeview.getNode(arg.DragNodeId).moveToAbove(arg.NodeId);
						}
						
						var childids = treeview.getNode(parentid).getChildIds();
						
						koolajax.callback(doSort(childids.join("|")),onSortDone);
						showStatus("Editando ...");
					}
					else
					{
					if (treeview.getNode(_dropid).getImageSrc().indexOf("square_greenS.gif")==-1 && treeview.getNode(_dragid).getImageSrc().indexOf("square_greenS.gif")>-1)
						{
							//Do move
							_dragparentid = treeview.getNode(_dragid).getParentId();
							treeview.getNode(_dragid).attachTo(_dropid);
							
							koolajax.callback(doMove(_dragid,_dropid,_dragparentid),onMoveDone);
							showStatus("Editando ...");
		 
						}
						else
						{
							setTimeout(function(){alert("Não é permitido");},10);
						}
		 
					}
					
					return false;
				});
				
				
				//OnSelect
				treeview.registerEvent("OnSelect",function(sender,arg){
					nodeselect = arg.NodeId;
					ini = document.getElementById('ini').value;
					fim = document.getElementById('fim2').value;
					document.getElementById('ifr').src="/joystock/plano/lancamentos.php?width=<?=$_GET['width']?>&height=<?=$_GET['height']?>&COD="+nodeselect+"&INI="+ini+"&FIM="+fim;
					//alert(treeview.getSelectedIds());
				});
				
				//OnExpand
				treeview.registerEvent("OnExpand",function(sender,arg){
					var _id = arg.NodeId;
					var _text = treeview.getNode(_id).getText();
					var _expand = 1;
					koolajax.callback(doUpdate(_id,_text,_expand),onUpdateDone);
					showStatus("Editando ...");
				});
				//OnCollapse
				treeview.registerEvent("OnCollapse",function(sender,arg){
					var _id = arg.NodeId;
					var _text = treeview.getNode(_id).getText();
					var _expand = 0;
					koolajax.callback(doUpdate(_id,_text,_expand),onUpdateDone);
					showStatus("Editando ...");
				});
				
				
				//OnEndEdit
				treeview.registerEvent("OnEndEdit",function(sender,arg){
					var _id = arg.NodeId;
					var _text = treeview.getNode(_id).getText();
					var _expand = 0;
					koolajax.callback(doUpdate(_id,_text,_expand),onUpdateDone);
					showStatus("Editando ...");
				});
				
				//Add new treenode
				function addTreeNode(exp)
				{
					if (nodeselect!=null)
					{
						var image = treeview.getNode(nodeselect).getImageSrc();
						if (image.indexOf("square_greenS.gif")==-1)
						{
							var nodetext = prompt("Nome da conta:","");
							
							if(exp == "1"){
								img = "tri.png";
							}else{
								img = "square_greenS.gif";
							}
							
												
							if (nodetext!=null && nodetext!="")
							{
								//var id = (new Date()).getTime();
								
								var rank = treeview.getNode(nodeselect).getChildIds().length;
								var aux = nodeselect;
								koolajax.callback(doCreateNode(nodeselect,0,nodetext,img,rank,exp),onCreateNodeDone);
								
								//koolajax.callback(ultInsert(nodeselect), onSaveDone);
								
								//alert(nodeselect);
								showStatus("Criando conta ...");
								
							}
							
						}
						else
						{
							alert("Favor selecione uma conta sintética");
						}
						
					}
					else
					{
						alert("Favor selecione uma conta sintética");
					}
				}
				
				
				
				//Delete a node
				function deleteTreeNode()
				{
					if (nodeselect != null) 
					{
						var image = treeview.getNode(nodeselect).getImageSrc();
						if (image.indexOf("square_greenS.gif")>-1)
						{
		 
							if(confirm("Deseja realmente excluir esta conta?"))
							{
								treeview.removeNode(nodeselect);
								koolajax.callback(doDeleteNode(nodeselect),onDeleteDone);
								nodeselect = null;
								showStatus("Excluindo Conta ...");
							}					
		 
						}
						else
						{
							alert("Favor, selecione uma conta.")
						}
						
					}
				}
				
				
				//Status
				function showStatus(_text)
				{
					document.getElementById("status").innerHTML = _text;
					document.getElementById("status").style.display="block";
				}
				function hideStatus()
				{
					document.getElementById("status").style.display="none";
				}
				
			</script>
		</div>
		<div id="legend2" style="float:left; text-align:left; padding:2px 0px 2px 5px;  width:50%; margin-top:5px; ">	
			Caixa: <label style="color:<?=$cor2?>"> R$ <?=number_format($caixaTotal,2,",",".");?></label>
		</div>
		
		<div id="main" style="float:left; width:50%; margin-top:5px; ">	
			<input type="button" id="box1" value="Criar Conta Sintética" onclick="addTreeNode('1')"/>
			<input type="button" id="box1" value="Criar Conta Analítica" onclick="addTreeNode('0')"/>
		</div>
		<div id="main" style="float:right; cursor:pointer; text-align:right; width:10%; margin-top:0px;">	
			<!--<img style="width:30px" src="/joystock/pcte-common/images/print.png" onclick="document.getElementById('ifr').src='/joystock/plano/pdffluxoCaixa.php?INI=<?=$_POST['DTA_INI']?>&FIM=<?=$_POST['DTA_FIM']?>';"/>-->
		</div>	
	</fieldset>
	</div>		
	</center>
</form>
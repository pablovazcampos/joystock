<?
	include_once("../pcte-common/CTRLFBclass.php");
	include_once("../pcte-common/CTRLGeral.php");
	include_once("../pcte-common/CTRLBDados.php");
	//include_once("../pcte-common/smtp.class.php");
	
	
	/* Lendo parametros do sistema */
	$arq = $_SERVER['DOCUMENT_ROOT']."/pcte-common/config.ini.php";

	if(file_exists($arq)){
		$ArqIni = new IniFile($arq);
	}
	
	
	$table[0]['NOME']   = $TABELA;
	$table[0]['ALIAS']  = 'T1';
	$query = "SHOW FIELDS FROM ".$TABELA;
	$dados = $BDFB->FBSelect($query);
	$x=0;
	while($row=@mysql_fetch_array($dados)) {
		if ($x==1){
			$NME_EXCLUIR[0]     = $row['Field'];		
		}
		if (strlen($row['Field']) == 11){
			$table[0]['WHERE'][] = $row['Field']." IS NULL $WHERE "; 	
		}else {
			$table[0]['CAMPOS'][] = $row['Field'];
		}	
		$x++;
	}
	
	
	//$COD_USR = $CodeSession->decode('COD_USR');
	/* Criando nova inst�ncia para o Banco de Dados */
	$BDFB = new FB();
	
	$BDFB->Conecta();
	
	mysql_query("SET NAMES 'utf8'");
	mysql_query('SET character_set_connection=utf8');
	mysql_query('SET character_set_client=utf8');
	mysql_query('SET character_set_results=utf8');
	
	$fields = $BDFB->CamposTabela($table[0]['NOME']);
	// Zeranda a Vari�vel de Mensagem 
	if(!empty($_SESSION['msg']))
		$_SESSION['msg'] = "";
		
		
	//IMPEDE GRAVA��O COM CAMPOS EM BRANCO
	//print_r($fields);
	if ($_POST['breg']=='editar' && $aux != 1){
		$COD_AUX = strtoupper('COD_'.substr($table[0]['NOME'], 4, 3));
		foreach ($fields as $chave=>$sub){
			foreach ($sub as $key=>$value){
				if ($key=="NULO" && $value=="NO"){
					if (empty($_POST[trim($sub['NOME'])]) && trim($sub['NOME'])!=$COD_AUX && empty($_GET['COD_CLN'])){// COD_CLN PARA O CONTATO
						//PRINT $sub['NOME'];
						$_POST['breg']='cancelar';
						$_SESSION['msg'] = "Pesquise o registro antes de editá-lo!";
						$corMsg = "red";
					}		
				}    
			}
		}
	}
	//print_r($field);
	
	if ($_POST['breg']=='salvar'){
		$COD_AUX = strtoupper('COD_'.substr($table[0]['NOME'], 4, 3));
		foreach ($fields as $chave=>$sub){
			foreach ($sub as $key=>$value){
				if ($key=="NULO" && $value=="NO"){//print $key.$value."<br>";
					if (empty($_POST[trim($sub['NOME'])]) && trim($sub['NOME'])!=$COD_AUX){
						//PRINT $sub['NOME']."<br>";
						$_SESSION['msg'] = "Preencha os campos obrigatórios em destaque.";
						$corMsg = "red";
						if ($_SESSION['status_sistema'] == "novo"){
							$_POST['breg']='novo';
						}elseif ($_SESSION['status_sistema'] == "editar"){
							$_POST['breg']='editar';
						}
						$fields[$chave]['NULO']="1";
						$branco = 1;
					}	
				}    
			}
		}
	}	
	
	
	// Zeranda a Vari�vel de Mensagem 
	
	// Verifica��o da barra de Registro 
	if(empty($_SESSION['STS_CPO']))													//Status dos campos
		$_SESSION['STS_CPO'] = "readonly=\"readonly\"";
		
	if(empty($_SESSION['STS_BREG']))												//Status da Barra
		$_SESSION['STS_BREG'] = 0;
		
	$BREG = BarraRegistro();
	
	// Verifica��o da op��o Exclus�o 
	if(!empty($_POST['btnExcSIM'])){
		
		$COD_DELETE = $_SESSION['COD'];
		$_POST['COD_CLN']="";
		$disable="disabled";
		$disabled="";
		if(strlen($table[0]['NOME']) == 10){
			$COD_AUX = strtoupper('COD_'.substr($table[0]['NOME'], 4, 3));
		}else{
			for($i=0;$i<count($fields);$i++){
				if(trim($fields[$i]['NOME']) == strtoupper('COD_'.substr($table[0]['NOME'], 0, -3))){
					$COD_AUX = strtoupper('COD_'.substr($table[0]['NOME'], 0, -3));
					$COD_DELETE = $_SESSION['SUB'];
					break;
				}else{
					$COD_AUX = strtoupper('COD_'.substr($table[0]['NOME'], 4, 3));
				}
			}
		}

		$BDFB->IncWhere("$COD_AUX", 'INTEGER', '=', "$COD_DELETE");
		$BDFB->FBDelete($table[0]['NOME']);
		
		$_SESSION['msg'] = $BDFB->erro_msg;
	}elseif(!empty($_POST['btnExcNAO'])){
		$_SESSION['msg'] = "Exclus�o cancelada";
	}
	
	switch($_POST["breg"]){
		case 'novo':
			    
				$_SESSION['status_sistema'] = 'novo';
				$_SESSION['STS_CPO'] = "";
				$_SESSION['STS_BREG'] = 1;
				if (empty($_SESSION['msg']))
					$_SESSION['msg'] = "Inserindo novo registro";
				
				// Verificando se � tabela Principal ou Secund�ria 
				if(strlen($table[0]['NOME'])==10){
					$_SESSION['COD'] = "";
					$_GET['COD'] = "";
				}else{
					for($i=0;$i<count($fields);$i++){
						
						if(trim($fields[$i]['NOME']) == strtoupper('COD_'.substr($table[0]['NOME'], 0, -3))){
							$tSec = $verif = 1;						// tSec - Informa que a tabela secund�ria possui campo COD_
							$tSec = 1;								
							break;
						}else{
							$tSec = $verif = 0;								// tSec - Informa que a tabela secund�ria N�O possui campo COD_
						}
					}
					
					if($verif==1){
						$_SESSION['SUB'] = "0";
					}else{//print $_POST['COD_'.substr($table[0]['NOME'], 4, 3)]."ssssssss";
						if (!empty($_POST[strtoupper('COD_'.substr($table[0]['NOME'], 4, 3))])){
							$_SESSION['msg'] = "Este registro pode ser somente alterado ou excluido!";
							$_SESSION['status_sistema'] = 'cancelar';
							$_SESSION['STS_CPO'] = "readonly=\"readonly\"";
							$_SESSION['STS_BREG'] = 0;
						}
					}
				}
			break;
			
		case 'salvar':
				for($i=0;$i<count($fields);$i++){//fields recebe os campos da tabela
					$COD_AUX = strtoupper('COD_'.substr($table[0]['NOME'], 4, 3));
					$SUB = strtoupper('COD_'.substr($table[0]['NOME'], 0, -3));
					if (trim($fields[$i]['NOME']) == $COD_AUX){
						/*if($COD == "COD_USR")
							$VrCampo = $CodeSession->decode('COD_USR');
						else*/ 					
						$VrCampo = $_SESSION['COD'];
					}elseif (trim($fields[$i]['NOME'] ) == $SUB ) //Este COD_GRP � para a troca da senha do usu�rio
						$VrCampo = $_SESSION['SUB'];
					else 
						$VrCampo = $_POST[trim($fields[$i]['NOME'])];
						trim($fields[$i]['NOME'])."-----".$_POST[trim($fields[$i]['NOME'])]."<BR>";
					/*if($_POST['COD_'.substr($table[0]['NOME'], 4, 3)] == "RegistroVazio")
						$vazio = "campoVazio";		*/
						//print $_POST['COD_CLN']."---nome-->".$fields[$i]['NOME']."-valor do campos-->".$VrCampo."-tipo->".$fields[$i]['TIPO']."<br><br>"; 
					$BDFB->IncCampo(trim($fields[$i]['NOME']), trim($fields[$i]['TIPO']), $VrCampo);
				}
				
				
				//print $_SESSION['COD'];
				if($_SESSION['status_sistema'] == 'editar'){	
					if(strlen($table[0]['NOME'])==10){
						$CodWhere = strtoupper('COD_'.substr($table[0]['NOME'], 4, 3));
						//if ($CodWhere == "COD_USR") alterado
					//		$BDFB->IncWhere($CodWhere, 'INTEGER','=',$CodeSession->decode('COD_USR'));
						//else	
							$BDFB->IncWhere($CodWhere, 'INTEGER','=',$_SESSION['COD']);
					}else{
						$CodWhere = strtoupper('COD_'.substr($table[0]['NOME'], 0, -3));
						for($i=0;$i<count($fields);$i++){
							if(trim($fields[$i]['NOME']) == $CodWhere){
								$verif = 1;
								break;
							}else
								$verif = 0;
						}
						if($verif == 1)
							$BDFB->IncWhere($CodWhere, 'INTEGER','=',$_SESSION['SUB']);
						elseif ($verif == 0){
							$CodWhere = strtoupper('COD_'.substr($table[0]['NOME'], 4, 3));
							$BDFB->IncWhere($CodWhere, 'INTEGER','=',$_SESSION['COD']);
						}
					}
					$_SESSION['msg'] = $BDFB->FBUpdate($table[0]['NOME']);
					$typeSave = 2;
					if(empty($_SESSION['msg']))					
						$_SESSION['msg'] = "Registro editado com sucesso!";
					
				}else{
					$ifErro = $BDFB->FBInsert($table[0]['NOME']);
					$_SESSION['msg'] = $ifErro[0];
					$typeSave = 1;
					if(empty($_SESSION['msg']))
						$_SESSION['msg'] = "Registro criado com sucesso!";
					else{
						//$smtp = new Smtp();
						//$smtp->email("ERRO: \n".$ifErro[0]."\n\n QUERY: \n".$ifErro[1]."\n\n USU�RIO: \n".$CodeSession->decode('NME_USR')."\n\n DATA: \n".date('d/m/Y')."\n\n HORA: \n".date("H:i:s"));
					
					}
					 	
						
					
					$where = ""	;
					for($i=0;$i<count($fields);$i++){
						$campo = trim($fields[$i]['NOME']);
						
						if(!empty($_POST[$campo]) && $campo != 'DTA_CDT'){
							$where .= "$campo = {$BDFB->TratarCampo(trim($fields[$i]['TIPO']),$_POST[$campo])} AND ";
						}
					}
						
					if(strlen($table[0]['NOME'])==10){
						$CampoSelect = "COD_".strtoupper(substr($table[0]['NOME'], 4, 3));
					}else{
						$CampoSelect = "COD_".strtoupper(substr($table[0]['NOME'], 0, -3));
						
						if($BDFB->CampoExiste($table[0]['NOME'], $CampoSelect) == 0){
							$CampoSelect = "COD_".strtoupper(substr($table[0]['NOME'], 4, 3));
						}
					}
					
					$where = substr($where, 0, -4);
					$query = "SELECT $CampoSelect FROM {$table[0]['NOME']} WHERE $where";
					$dados_cod = $BDFB->FBSelect($query);
					$row_cod = mysql_fetch_array($dados_cod);
					
					if(strlen($CampoSelect)==7)
						$_SESSION['COD'] = $row_cod[$CampoSelect];
					else 
						$_SESSION['SUB'] = $row_cod[$CampoSelect];
				}

				$_SESSION['status_sistema'] = 'salvar';
				$_SESSION['STS_CPO'] = "readonly=\"readonly\"";
				$_SESSION['STS_BREG'] = 0;
			break;
			
		case 'editar':
			//print 'COD_'.substr($table[0]['NOME'], 4, 3);
				/*if($_POST['COD_'.substr($table[0]['NOME'], 4, 3)] == "RegistroVazio"){
					$_SESSION['msg'] = "Selecione antes o registro � ser alterado.";
					$_SESSION['status_sistema'] = 'cancelar';
					$_SESSION['STS_CPO'] = "readonly=\"readonly\"";
					$_SESSION['STS_BREG'] = 0;
					break;	
				}*/			
				$_SESSION['status_sistema'] = 'editar';
				$_SESSION['STS_CPO'] = "";
				$_SESSION['STS_BREG'] = 1;
				$_SESSION['msg'] = "Registro aberto para edição";
			break;
			
		case 'cancelar':
			    foreach ($_POST as $key=>$value){
			    	$_POST[$key]="";
			    }
				$_SESSION['status_sistema'] = 'cancelar';
				$_SESSION['STS_CPO'] = "readonly=\"readonly\"";
				$_SESSION['STS_BREG'] = 0;
				if (empty($_SESSION['msg'])){
					$_SESSION['msg'] = "Registro atual cancelado.".$_SESSION['MSG'];
					$corMsg = "red";
				}	
			break;
			
		case 'pesquisar':
				$_SESSION['status_sistema'] = 'pesquisar';
				$_SESSION['msg'] = "Pesquisando um registro ...";
				header("Location: ../pesquisa.php");
				exit();
			break;
			
		case 'excluir':
				/*if($_POST['COD_'.substr($table[0]['NOME'], 4, 3)] == "RegistroVazio"){
					$_SESSION['msg'] = "Selecione antes o registro � ser exclu�do.";
					$_SESSION['status_sistema'] = 'cancelar';
					$_SESSION['STS_CPO'] = "readonly=\"readonly\"";
					$_SESSION['STS_BREG'] = 0;
					break;	
				}*/
				$_SESSION['status_sistema'] = 'excluir';
				if(!empty($_POST[$NME_EXCLUIR[0]])){
					for($i=1;$i<count($NME_EXCLUIR);$i++){
						$fraseExclusao .= $_POST[$NME_EXCLUIR[$i]]." ";
					}
					$_SESSION['msg'] = "Deseja realmente excluir o registro: ".$fraseExclusao."?";
					$_SESSION['msg'] .= "<div style='margin-top:10px' align='center'><cente><input  class=\"BTN_SIMNAO\" type=\"submit\" name=\"btnExcSIM\" value=\"Sim\">&nbsp;";
					$_SESSION['msg'] .= "<input class=\"BTN_SIMNAO\" type=\"submit\" name=\"btnExcNAO\" value=\"Não\"></center></div>";
					$corMsg = "red";
				}else{
					$_SESSION['msg'] .= "N�o existe registro a ser excluido ou <br>".
					                    "movido para lixeira.";
					$corMsg = "red";                    
				}
				
			break;
			
		case 'imprimir':
				$_SESSION['status_sistema'] = 'impresso';
				$_SESSION['msg'] = "Enviando os dados para impressora...";
				header("Location: /pcte-common/print.php");
				exit();
			break;			
			
		case 'fechar':
				//$_SESSION['status_sistema'] = 'Fechando formul�rio '.$_SESSION['close'];
				//print $_SESSION["close"];
				//exit();
			break;
		
		case 'SenhaInv�lida':
			$_SESSION['status_sistema'] = 'editar';
			$_SESSION['msg'] = 	$_POST['SNH_USR'];	
		
	}
	
	$estado = $_SESSION['STS_BREG'];
	if ($_SESSION['STS_BREG'] == 2)
		$_SESSION['STS_BREG'] = "";												//Estado da Barra
	// Seleciona o Registro 
	if(strlen($table[0]['NOME'])==10){//AC 16/03/2010
		if(!empty($_GET['COD'])){
			$_SESSION['COD'] = $_GET['COD'];
			$buscarReg = true;
		}elseif (!empty($_SESSION['COD'])){
			$buscarReg = true;
		}else 
			$buscarReg = false;
	}elseif (strlen($table[0]['NOME'])==13 && !empty($_SESSION['COD'])){
		if(!empty($_GET['SUB'])){
			$_SESSION['SUB'] = $_GET['SUB'];
			$buscarReg = true;
		}elseif(!empty($_SESSION['SUB'])){
			$buscarReg = true;
		}elseif (!empty($_SESSION['COD'])){
			$buscarReg = true;
		}else 
			$buscarReg = false;
	}else {
		$_SESSION['MSG'] = 'Selecione primeiro um registro';
		$buscarReg = false;
	}
	
	
	$cdg = $_SESSION['COD'];
	
	if (!empty($carrega)){
		$cdg = $carrega;	
		$buscarReg = true;
	}
	
	
	
	// Verificando se o Formul�rio � Principal ou Secund�rio 
	if(!empty($frmMaster)){
		if(empty($_SESSION['COD'])) {
			/*if(empty($_SESSION['msg']))
				$_SESSION['msg'] = "Pesquise ou adicione um registro!";*/
			$_SESSION['frm'] = 0;
		}else
			$_SESSION['frm'] = 1;
	}else{
		exit("Formul�rio mal programado! Voc� esqueceu de colocar a vari�vel frmMaster.");
	}
	
	// Buscando Registro 
	//print $buscarReg."fsdfsdfsf";
	if($buscarReg == true){
		
		
	
		// Construindo os Campos do SELECT 
		for($i=0;$i<count($table);$i++){
			$c1 = implode(", ", $table[$i]['CAMPOS']).", ";
			$c1 = $table[$i]['ALIAS'].".".$c1;
			$c2 = str_replace(", ", ", ".$table[$i]['ALIAS'].".", $c1);
			$campos .= substr($c2, 0, -5).", ";
		}
		$campos = substr($campos, 0, -2);
		
		$select = "SELECT $campos ";
		$from   = "FROM {$table[0]['NOME']} {$table[0]['ALIAS']} ";
		
		
		// Construindo JOINS com LEFT ou sem LEFT, se existir 
		if(count($table)>1){
			for ($i=1;$i<count($table);$i++){
				if($table[$i]['LEFTJOIN']=='SIM')
					$LEFTJOIN = 'LEFT';
					
				$joins .= " $LEFTJOIN JOIN {$table[$i]['NOME']} {$table[$i]['ALIAS']} ON ({$table[$i]['JOIN']}) ";
			}
		}
		
		
		if(strlen($table[0]['NOME']) == 10){
			$cod_table = strtoupper(substr($table[0]['NOME'], 4, 3));
			/*if ($cod_table == "USR")
				$where = "WHERE {$table[0]['ALIAS']}.COD_$cod_table = {$CodeSession->decode('COD_USR')} ";
			else*/
				$where = "WHERE {$table[0]['ALIAS']}.COD_$cod_table = {$cdg} ";
		}elseif(strlen($table[0]['NOME']) > 10){
			$sub_table = strtoupper(substr(trim($table[0]['NOME']), 0, -3));
			for($i=0;$i<count($fields);$i++){
				if(trim($fields[$i]['NOME'])=='COD_'.strtoupper($sub_table)){
					$verif = 1;
					break;
				}else{
					$verif = 0;
				}
			}
			
			// Verificando se a tabela � 1:1 ou 1:N -> Se $verif = 1 a tabela � 1:1 
			if($verif == 1){
				$where = "WHERE {$table[0]['ALIAS']}.COD_$sub_table = {$_SESSION['SUB']}";
			}else{
				$cod_table = strtoupper(substr($table[0]['NOME'], 4, 3));
				$where = "WHERE {$table[0]['ALIAS']}.COD_$cod_table = {$cdg} ";
				
			}
			
		}
		for($i=0;$i<count($table);$i++){
			$w1 = implode(", ", $table[$i]['WHERE']).", ";
			$w1 = $table[$i]['ALIAS'].".".$w1;
			$w2 = str_replace(", ", " AND ".$table[$i]['ALIAS'].".", $w1);
			$w3 .= substr($w2, 0, -4)." ";
		}		
		$w3 = substr($w3, 0, -4);
		$where .= " AND ".$w3;
		
		$query = $select.$from.$joins.$where;
		//print "<br><br><br>".$query;  //ERRO FRMLISTA
		$dados = $BDFB->FBSelect($query);
		$row = @mysql_fetch_array($dados);
	}
	
	//Montando os Campos com valores para Formul�rio 
	//$id_fixo = "style=\"background-color: rgb(255, 255, 235);\"";
	
	
	
	
	//print_r($fields);
	
	

	
		foreach ($fields as $Sub){
			//print_r($Sub);
			foreach ($Sub as $key => $value){
				if($key=="NOME"){
					$NME_CPO = trim($value);
					//para guardar post quando campos em branco
					if ($branco != 1){
						$ValueField = $row[$NME_CPO];
					}else {
						$ValueField = $_POST[$NME_CPO];
						
					}
					$vrpadrao = trim(substr($NME_CPO, 0, 3));
					$VLR_CPO = !empty($ValueField)?$ValueField:$ArqIni->Read('DADOSPADRAO',"$vrpadrao",'');
					//print $key.$VLR_CPO."----".$row->$NME_CPO."<br>";
				}elseif($key=="TIPO"){
					if (!strpos($VLR_CPO, "/"))
						$VLR_CPO = $BDFB->MostrarCampo(trim($value), $VLR_CPO);
					$propriedade .= " value=\"$VLR_CPO\" ";
				}elseif($key=="TAMANHO")
					$propriedade .= " maxlength=\"$value\" ".$_SESSION['STS_CPO'];
				elseif($key == "NULO"){
					if ($value == "1")	
					    $propriedade .= "style=\"background-color:red\"";
				}
			}
			//print $NME_CPO."---".$propriedade$propriedade."<br>";
			$CPO[$NME_CPO] = $propriedade;
			$CPOValue[$NME_CPO] = $VLR_CPO; // para serem usados nos selects
				//print $CPO[$NME_CPO];
			$propriedade = "";
		}
	
	// Destruindo o objeto 
	$ArqIni->Clear();
?>
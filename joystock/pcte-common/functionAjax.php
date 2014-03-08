<?
	function confirmaEmail($login){
		$objResponse = new xajaxResponse();
		
		if (!empty($login)){
			
			$BDFB = new FB();
		    $BDFB->Conecta();
		    
			$query = "SELECT COD_USR, SNH_USR FROM upcsusr_tb WHERE EML_USR = '".trim($login)."'";
			$dados = $BDFB->FBSelect($query);
			$row = @mysql_fetch_array($dados);
			
			if (empty($row['COD_USR'])){
				$out = utf8_decode("Email não cadastrado");
				$objResponse->assign("LBL_MSG","innerHTML",$out);
				$objResponse->script(" document.forms['FrmLogin'].elements['usuario'].select()");	
			}elseif (empty($row['SNH_USR'])) {
				//$objResponse->script("alert('sssssss')");	
				$objResponse->assign("LBL_MSG3","innerHTML",$login);
				$objResponse->script("document.getElementById('COD_USR').value = '{$row['COD_USR']}'");
				$objResponse->script("document.getElementById('usuario').value = '{$login}'");
				$objResponse->script("dispatch(document.getElementById('flipToRecover'));");	
			}else {	
				$objResponse->assign("LBL_MSG","innerHTML","");	
				$objResponse->script("document.forms['FrmLogin'].elements['confirma'].style.visibility = 'hidden';");	
				$objResponse->assign("conf","innerHTML","");
			}
		}	
		
		
		
		return $objResponse;
	}
	
	function confirmaSenha($login, $senha){
		$objResponse = new xajaxResponse();
		
		if (!empty($login)){
			
			$BDFB = new FB();
		    $BDFB->Conecta();
		    
			$query = "SELECT COD_USR, SNH_USR FROM upcsusr_tb WHERE EML_USR = '".trim($login)."' AND SNH_USR = '".md5($senha)."'";
			$dados = $BDFB->FBSelect($query);
			$row = @mysql_fetch_array($dados);
			
			if (empty($row['COD_USR'])){
				$out = utf8_decode("Email e senha não correspondem.");
				$objResponse->assign("LBL_MSG","innerHTML",$out);
				$objResponse->script("document.forms['FrmLogin'].elements['usuario'].focus(); document.forms['FrmLogin'].elements['usuario'].select()");	
				$objResponse->script("return false;");	
			}else {
				$objResponse->assign("LBL_MSG","innerHTML","");	
				$objResponse->script("document.forms['FrmLogin'].elements['confirma'].style.visibility = 'hidden';");	
				$objResponse->assign("conf","innerHTML","");
				$objResponse->script("dispatch(document.getElementById('ok'));");	
			}
		}	
		
		
		
		return $objResponse;
	}

 	function login($login = "", $senha = ""){
    	
    	$objResponse = new xajaxResponse();
    	
    	$BDFB = new FB();
		$BDFB->Conecta();
		
		$CodeSession = new CodeSession();
		
		$query = "SELECT CNJ_CLN, COD_USR, SNH_CLN, NEW_EML, TPO_CLN FROM upcseml_tb WHERE CNJ_CLN = '$login' AND SNH_CLN = '$senha'";
		$dados = $BDFB->FBSelect($query);
		$row = @mysql_fetch_array($dados);
    	
		if (empty($login)){
			$objResponse->assign("msg1","innerHTML",utf8_decode("O login não pode ser vazio!"));	
		}elseif (empty($senha)){
			$objResponse->assign("msg1","innerHTML",utf8_decode("A senha não pode ser vazio!"));	
		}elseif (empty($row['CNJ_CLN'])){
			$objResponse->assign("msg1","innerHTML",utf8_decode("Login ou senha inválidos!"));	
		}elseif ($row['NEW_EML'] == 1) {
			$objResponse->script("top.location.href='/blt/changeKey.php?CNJ={$row['CNJ_CLN']}'");
		}else {	
			$CodeSession->encode("COD_CLN",$row['CNJ_CLN']);
			$CodeSession->encode("COD_USR",$row['COD_USR']);
			$objResponse->assign("msg1","innerHTML",utf8_decode("Login efetuado com sucesso"));	
			if ($row['TPO_CLN'] == 1){
				$objResponse->script("top.location.href='/blt/boletosCliente.php'");				
			}else {
				$objResponse->script("top.location.href='/blt/relatorio.php'");				
			}
		}
		//$objResponse->alert("olá");
		
		
    	
    	return $objResponse;  
		
    }
	
    function AutoCompleterProduto($val, $t) {
        $objResponse = new xajaxResponse();
        
        $found = 0;
        $val = trim($val);
        if (!empty($val)){
	        $BDFB = new FB();
		    $BDFB->Conecta();
		    
		    mysql_query("SET NAMES 'utf8'");
			mysql_query('SET character_set_connection=utf8');
			mysql_query('SET character_set_client=utf8');
			mysql_query('SET character_set_results=utf8');
		    
		    $NomeMaiuscula = strtoupper(strtr($val ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));
		    $NomeMaiuscula = utf8_encode($NomeMaiuscula);
	        $query = "SELECT  NME_PRD, COD_PRD, COD_BRR FROM upcsprd_tb WHERE UPPER(NME_PRD) LIKE UPPER('%$NomeMaiuscula%') ".
	        		 " OR LPAD(COD_PRD,5,0) LIKE UPPER('%$NomeMaiuscula%') ".
	        		 " OR COD_BRR LIKE UPPER('%$NomeMaiuscula%') ".
	        		 "ORDER BY NME_PRD LIMIT 0,50";
			$dados = $BDFB->FBSelect($query);
			
			$s = '<table id="LBL" style="font-size:120%;" width="100%">';
			    
	        while ($row = @mysql_fetch_array($dados)) {
	        	   if (strpos(strtoupper($row['NME_PRD']),$NomeMaiuscula) !== false || strpos(strtoupper($row['COD_PRD']),$NomeMaiuscula) !== false || strpos(strtoupper($row['COD_BRR']),$NomeMaiuscula) !== false){
	        	   		$country_list =utf8_decode("Código: ").str_pad($row['COD_PRD'],5,0,STR_PAD_LEFT).utf8_decode(" | Código de Barras: ").$row['COD_BRR']."<br>Nome: ".utf8_decode($row['NME_PRD']);
	        	   }
	        	   //$teste = $row['NME_PRD'];
	               $p  = strripos($country_list, $val);
	               $s1 = substr($country_list, 0, $p);
	               $s2 = substr($country_list, $p, strlen($val));
	               $s3 = substr($country_list, ($p + strlen($val)));
	               $pos = strpos($s1.$s2.$s3,'&');
	               
	               if (!strpos($ret,$val)){
	                  	$r = ($s1.'<span>'.$s2.'</span>'.$s3);
	                   	if (strlen($r)>520){
	                   		$r = substr($r,0,52)."...";
	                   	}
	                   	$s .= '<tr style="cursor:hand;">'.
	    		                '<td ><li style="background-color:#E8E8E8; cursor:pointer "  onmouseout="this.style.backgroundColor=\'#E8E8E8\';" onmouseover="this.style.backgroundColor=\'#AAAAAA\';"   onclick="rc_autocompleter_Choice(\''.str_replace("''","",str_replace("\"","",utf8_decode($row['NME_PRD']))).'\', \'preview\', \''.$row['COD_PRD'].'\'); "  style=" height:49px;" >'.str_replace("''","\\\"",$r).'</li></td>'.
	 	              		  '</tr>';
	               }
	               $found++;
	               
	         }
	         $s .= '</table>';
	           
	             
	           $objResponse->script("rc_autocompleter_return('preview', $t, 'visible', '".str_replace('\'', '\\\'', $s)."')");
	           
        }
    
		//$objResponse->assign("msg","innerHTML",$teste);	
		
		return $objResponse;  
    }
    
    function AutoCompleterCliente($val, $t) {
        $objResponse = new xajaxResponse();
        
        $found = 0;
        $val = trim($val);
        if (!empty($val)){
	        $BDFB = new FB();
		    $BDFB->Conecta();
		    
		    mysql_query("SET NAMES 'utf8'");
			mysql_query('SET character_set_connection=utf8');
			mysql_query('SET character_set_client=utf8');
			mysql_query('SET character_set_results=utf8');
			
			if ($_SESSION['TIPO'] == 1){
				$tipo = "AND ISS_FRN = 1";
			}else {
				$tipo = "AND ISS_CLN = 1";
			}
		    
		    $NomeMaiuscula = strtoupper(strtr($val ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));
	        $query = "SELECT  NME_CLN, COD_CLN FROM upcscln_tb ".
	        		 "WHERE (UPPER(NME_CLN) LIKE UPPER('%$NomeMaiuscula%') ".
	        		 " OR LPAD(COD_CLN,5,0) LIKE UPPER('%$NomeMaiuscula%')) ".
	        		 "$tipo ". 
	        		 "ORDER BY NME_CLN LIMIT 0,50";
			$dados = $BDFB->FBSelect($query);
			
			$s = '<table id="LBL" style="font-size:120%;" width="100%">';
			    
	        while ($row = @mysql_fetch_array($dados)) {
	        	   if (strpos(strtoupper($row['NME_CLN']),$NomeMaiuscula) !== false || strpos(strtoupper($row['COD_CLN']),$NomeMaiuscula) !== false){
	        	   		$country_list =utf8_decode("Código: ").str_pad($row['COD_CLN'],5,0,STR_PAD_LEFT)."<br>Nome: ".utf8_decode($row['NME_CLN']);
	        	   }
	        	   
	               $p  = strripos($country_list, $val);
	               $s1 = substr($country_list, 0, $p);
	               $s2 = substr($country_list, $p, strlen($val));
	               $s3 = substr($country_list, ($p + strlen($val)));
	               $pos = strpos($s1.$s2.$s3,'&');
	               
	               if (!strpos($ret,$val)){
	                  	$r = ($s1.'<span>'.$s2.'</span>'.$s3);
	                   	if (strlen($r)>520){
	                   		$r = substr($r,0,52)."...";
	                   	}
	                   	$s .= '<tr style="cursor:hand;">'.
	    		                '<td ><li style="background-color:#E8E8E8; cursor:pointer "  onmouseout="this.style.backgroundColor=\'#E8E8E8\';" onmouseover="this.style.backgroundColor=\'#AAAAAA\';"   onclick="rc_autocompleter_Choice(\''.str_replace("''","",str_replace("\"","",utf8_decode($row['NME_CLN']))).'\', \'preview\', \''.$row['COD_CLN'].'\'); "  style=" height:49px;" >'.str_replace("''","\\\"",$r).'</li></td>'.
	 	              		  '</tr>';
	               }
	               $found++;
	               
	         }
	         $s .= '</table>';
	           
	             
	           $objResponse->script("rc_autocompleter_return('preview', $t, 'visible', '".str_replace('\'', '\\\'', $s)."')");
	           
        }
    
		//$objResponse->assign("msg","innerHTML",'www');	
		
		return $objResponse;  
    }
    
    function AutoCompleterGrupoClientes($val, $t) {
        $objResponse = new xajaxResponse();
        
        $found = 0;
        $val = trim($val);
        if (!empty($val)){
	        $BDFB = new FB();
		    $BDFB->Conecta();
		    
		    mysql_query("SET NAMES 'utf8'");
			mysql_query('SET character_set_connection=utf8');
			mysql_query('SET character_set_client=utf8');
			mysql_query('SET character_set_results=utf8');
			
		    $NomeMaiuscula = strtoupper(strtr($val ,"áéíóúâêôãõàèìòùç","ÁÉÍÓÚÂÊÔÃÕÀÈÌÒÙÇ"));
	        $query = "SELECT  DSC_GCL, COD_GCL FROM upcsgcl_tb ".
	        		 "WHERE (UPPER(DSC_GCL) LIKE UPPER('%$NomeMaiuscula%') ".
	        		 " OR LPAD(COD_GCL,5,0) LIKE UPPER('%$NomeMaiuscula%')) ".
	        		 "ORDER BY DSC_GCL LIMIT 0,50";
			$dados = $BDFB->FBSelect($query);
			
			$s = '<table id="LBL" style="font-size:120%;" width="100%">';
			    
	        while ($row = @mysql_fetch_array($dados)) {
	        	   if (strpos(strtoupper($row['DSC_GCL']),$NomeMaiuscula) !== false || strpos(strtoupper($row['COD_GCL']),$NomeMaiuscula) !== false){
	        	   		$country_list =utf8_decode("Código: ").str_pad($row['COD_GCL'],5,0,STR_PAD_LEFT)."<br>Nome: ".utf8_decode($row['DSC_GCL']);
	        	   }
	        	   
	               $p  = strripos($country_list, $val);
	               $s1 = substr($country_list, 0, $p);
	               $s2 = substr($country_list, $p, strlen($val));
	               $s3 = substr($country_list, ($p + strlen($val)));
	               $pos = strpos($s1.$s2.$s3,'&');
	               
	               if (!strpos($ret,$val)){
	                  	$r = ($s1.'<span>'.$s2.'</span>'.$s3);
	                   	if (strlen($r)>520){
	                   		$r = substr($r,0,52)."...";
	                   	}
	                   	$s .= '<tr style="cursor:hand;">'.
	    		                '<td ><li style="background-color:#E8E8E8; cursor:pointer "  onmouseout="this.style.backgroundColor=\'#E8E8E8\';" onmouseover="this.style.backgroundColor=\'#AAAAAA\';"   onclick="rc_autocompleter_Choice(\''.str_replace("''","",str_replace("\"","",utf8_decode($row['DSC_GCL']))).'\', \'preview\', \''.$row['COD_GCL'].'\'); "  style=" height:49px;" >'.str_replace("''","\\\"",$r).'</li></td>'.
	 	              		  '</tr>';
	               }
	               $found++;
	               
	         }
	         $s .= '</table>';
	           
	             
	           $objResponse->script("rc_autocompleter_return('preview', $t, 'visible', '".str_replace('\'', '\\\'', $s)."')");
	           
        }
    
		//$objResponse->assign("msg","innerHTML",'www');	
		
		return $objResponse;  
    }

    
    function incluiProduto($codigo = '', $descricao = '', $cliFor, $mov){
		 $objResponse = new xajaxResponse();
		 
		 if (empty($codigo) && empty($descricao)){
		 	$objResponse->assign("msg","innerHTML","Insira antes o produto a ser incluido na venda");	
		 	$objResponse->script("document.getElementById('msg').style.backgroundColor='red'");	
		 }else {
		 	$BDFB = new FB();
		    $BDFB->Conecta();
		    
		    mysql_query("SET NAMES 'utf8'");
			mysql_query('SET character_set_connection=utf8');
			mysql_query('SET character_set_client=utf8');
			mysql_query('SET character_set_results=utf8');
		 	
		 	if (!empty($codigo)){
		 		$query = "SELECT NME_PRD, PRC_VND, T2.PRC_CST, T3.PRC_VEN FROM upcsprd_tb T1 ".
		 				 "LEFT JOIN upcspcs_tb T2 ON T1.COD_PRD = T2.COD_PRD AND COD_CLN = $cliFor ".
		 				 "LEFT JOIN upcspvn_tb T3 ON T1.COD_PRD = T3.COD_PRD AND COD_GCL = (SELECT COD_GCL FROM upcscln_tb WHERE COD_CLN = $cliFor) ".
		 				 "WHERE T1.COD_PRD = $codigo ";
		 		$dados = $BDFB->FBSelect($query);
		 		$row = @mysql_fetch_array($dados);
		 		
		 		if ($mov == 2){
		 			$preco = $row['PRC_VEN'];
		 		}elseif ($mov == 1){
		 			$preco = $row['PRC_CST'];
		 		}
		 		
	 			$objResponse->script("displayResult($mov, $codigo,'1','0,00', '".utf8_decode($row['NME_PRD'])."', '".number_format($preco,2,',','.')."', '')");	
	 			
		 		$objResponse->assign("msg","innerHTML",utf8_decode("Ítem inserido com sucesso"));	
		 		$objResponse->script("document.getElementById('msg').style.backgroundColor='#".COR."'");	
		 		$objResponse->script("document.forms['frmbreg'].elements['NME_PRD'].value = ''");	
		 		$objResponse->script("document.forms['frmbreg'].elements['COD_PRD'].value = 0");
		 	}else{// (ereg("^[0-9]{14}$",$descricao))
		 		$cod = $descricao;
		 		$cod = abs($cod);
		 		$dsc = trim($descricao);
		 		
		 		$query = "SELECT NME_PRD, PRC_VND, T2.PRC_CST, T1.COD_PRD, T3.PRC_VEN FROM upcsprd_tb T1 ".
		 				 "LEFT JOIN upcspcs_tb T2 ON T1.COD_PRD = T2.COD_PRD AND COD_CLN = $cliFor ".
		 				 "LEFT JOIN upcspvn_tb T3 ON T1.COD_PRD = T3.COD_PRD AND COD_GCL = (SELECT COD_GCL FROM upcscln_tb WHERE COD_CLN = $cliFor) ".
		 				 "WHERE (T1.COD_PRD = $cod OR COD_BRR = '$dsc' OR UPPER(NME_PRD) = UPPER('$dsc')) ";
		 		$dados = $BDFB->FBSelect($query);
		 		$row = @mysql_fetch_array($dados);
		 		
		 		$codigo = $row['COD_PRD'];
		 		if (!empty($row['NME_PRD'])){
		 			
		 			if ($mov == 2){
			 			$preco = $row['PRC_VEN'];
			 		}elseif ($mov == 1){
			 			$preco = $row['PRC_CST'];
			 		}
		 			
		 			
		 			$objResponse->script("displayResult($mov, $codigo,'1','0,00', '".utf8_decode($row['NME_PRD'])."', '".number_format($preco,2,',','.')."', '')");	
		 			
			 		$objResponse->assign("msg","innerHTML",utf8_decode("Ítem inserido com sucesso"));	
			 		$objResponse->script("document.getElementById('msg').style.backgroundColor='#".COR."'");	
			 		$objResponse->script("document.forms['frmbreg'].elements['NME_PRD'].value = ''");
			 		$objResponse->script("document.forms['frmbreg'].elements['COD_PRD'].value = 0");
			 		$objResponse->script("window.setTimeout('rc_invisible(\'preview\')',100);");
		 		}else {
		 			$objResponse->assign("msg","innerHTML",utf8_decode("Código de barras inválido"));	
		 		$objResponse->script("document.getElementById('msg').style.backgroundColor='red'");	
		 		}
		 	}
		 	
		 	//$objResponse->assign("msg","innerHTML",$query);
		 	/*else{
		 		$objResponse->assign("msg","innerHTML",utf8_decode("Código de barras inválido"));	
		 		$objResponse->script("document.getElementById('msg').style.backgroundColor='red'");	
		 	}*/
		 }
		 
		 
		 //$objResponse->assign("msg","innerHTML",$codigo);	
		 return $objResponse;
	}

	function incluiFornecedor($codigo = '', $descricao = '', $preco = ''){
		 $objResponse = new xajaxResponse();
		 
		 if (empty($codigo) && empty($descricao)){
		 	$objResponse->assign("msg","innerHTML","Insira antes o Fornecedor");	
		 	$objResponse->script("document.getElementById('msg').style.backgroundColor='red'");	
		 }else {
		 	$BDFB = new FB();
		    $BDFB->Conecta();
		 	
		 	if (!empty($codigo)){
		 		
		 		$objResponse->script("displayResultFornecedor($codigo,'".$descricao."', '".$preco."')");	
		 		
		 		$objResponse->assign("msg","innerHTML",utf8_decode("Preço incluso com sucesso"));	
		 		$objResponse->script("document.getElementById('msg').style.backgroundColor='#".COR."'");	
		 		$objResponse->script("document.forms['frmbreg'].elements['NME_CLN'].value = ''");	
		 		$objResponse->script("document.forms['frmbreg'].elements['COD_CLN'].value = 0");
		 	}else{
		 		$cod = $descricao;
		 		$cod = abs($cod);
		 		$dsc = trim($descricao);
		 		
		 		$query = "SELECT NME_CLN, COD_CLN FROM upcscln_tb WHERE COD_CLN = $cod ";
		 		$dados = $BDFB->FBSelect($query);
		 		$row = @mysql_fetch_array($dados);
		 		
		 		$codigo = $row['COD_CLN'];
		 		if (!empty($row['NME_CLN'])){
		 			
		 			$objResponse->script("displayResultFornecedor($codigo,'".$descricao."', '".$preco."')");	
		 			
			 		$objResponse->assign("msg","innerHTML",utf8_decode("Preço incluso com sucesso"));	
			 		$objResponse->script("document.getElementById('msg').style.backgroundColor='#".COR."'");	
			 		$objResponse->script("document.forms['frmbreg'].elements['NME_CLN'].value = ''");
			 		$objResponse->script("document.forms['frmbreg'].elements['COD_CLN'].value = 0");
			 		$objResponse->script("window.setTimeout('rc_invisible(\'preview\')',100);");
		 		}else {
		 			$objResponse->assign("msg","innerHTML",utf8_decode("Fornecedor inválido".$query));	
		 		$objResponse->script("document.getElementById('msg').style.backgroundColor='red'");	
		 		}
		 	}
		 	/*else{
		 		$objResponse->assign("msg","innerHTML",utf8_decode("Código de barras inválido"));	
		 		$objResponse->script("document.getElementById('msg').style.backgroundColor='red'");	
		 	}*/
		 }
		 
		 
		 //$objResponse->assign("msg","innerHTML",$codigo);	
		 return $objResponse;
	}
	
	
	function incluiGrupoClientes($codigo = '', $descricao = '', $preco = ''){
		 $objResponse = new xajaxResponse();
		 
		 if (empty($codigo) && empty($descricao)){
		 	$objResponse->assign("msg","innerHTML","Insira antes Grupo de Clientes");	
		 	$objResponse->script("document.getElementById('msg').style.backgroundColor='red'");	
		 }else {
		 	$BDFB = new FB();
		    $BDFB->Conecta();
		 	
		 	if (!empty($codigo)){
		 		
		 		$objResponse->script("displayResultGrupoClientes($codigo,'".$descricao."', '".$preco."')");	
		 		
		 		$objResponse->assign("msg","innerHTML",utf8_decode("Preço incluso com sucesso"));	
		 		$objResponse->script("document.getElementById('msg').style.backgroundColor='#".COR."'");	
		 		$objResponse->script("document.forms['frmbreg'].elements['DSC_GCL'].value = ''");	
		 		$objResponse->script("document.forms['frmbreg'].elements['COD_GCL'].value = 0");
		 	}else{
		 		$cod = $descricao;
		 		$cod = abs($cod);
		 		$dsc = trim($descricao);
		 		
		 		$query = "SELECT DSC_GCL, COD_GCL FROM upcsgcl_tb WHERE COD_GCL = $cod ";
		 		$dados = $BDFB->FBSelect($query);
		 		$row = @mysql_fetch_array($dados);
		 		
		 		$codigo = $row['COD_GCL'];
		 		if (!empty($row['DSC_GCL'])){
		 			
		 			$objResponse->script("displayResultGrupoClientes($codigo,'".$descricao."', '".$preco."')");	
		 			
			 		$objResponse->assign("msg","innerHTML",utf8_decode("Preço incluso com sucesso"));	
			 		$objResponse->script("document.getElementById('msg').style.backgroundColor='#".COR."'");	
			 		$objResponse->script("document.forms['frmbreg'].elements['DSC_GCL'].value = ''");
			 		$objResponse->script("document.forms['frmbreg'].elements['COD_GCL'].value = 0");
			 		$objResponse->script("window.setTimeout('rc_invisible(\'preview\')',100);");
		 		}else {
		 			$objResponse->assign("msg","innerHTML",utf8_decode("Grupo de clientes inválido".$query));	
		 		$objResponse->script("document.getElementById('msg').style.backgroundColor='red'");	
		 		}
		 	}
		 	/*else{
		 		$objResponse->assign("msg","innerHTML",utf8_decode("Código de barras inválido"));	
		 		$objResponse->script("document.getElementById('msg').style.backgroundColor='red'");	
		 	}*/
		 }
		 
		 
		 //$objResponse->assign("msg","innerHTML",$codigo);	
		 return $objResponse;
	}

	function incluiKits($codigo = '', $descricao = '', $quantidade = ''){
		 $objResponse = new xajaxResponse();
		 
		 if (empty($codigo) && empty($descricao)){
		 	$objResponse->assign("msg","innerHTML","Insira antes o produto simples");	
		 	$objResponse->script("document.getElementById('msg').style.backgroundColor='red'");	
		 }else {
		 	$BDFB = new FB();
		    $BDFB->Conecta();
		 	
		 	if (!empty($codigo)){
		 		
		 		$objResponse->script("displayResultKits($codigo,'".$descricao."', '".$quantidade."')");	
		 		
		 		$objResponse->assign("msg","innerHTML",utf8_decode("Produto simples incluso com sucesso"));	
		 		$objResponse->script("document.getElementById('msg').style.backgroundColor='#".COR."'");	
		 		$objResponse->script("document.forms['frmbreg'].elements['DSC_KIT'].value = ''");	
		 		$objResponse->script("document.forms['frmbreg'].elements['COD_KIT'].value = 0");
		 	}else{
		 		$cod = $descricao;
		 		$cod = abs($cod);
		 		$dsc = trim($descricao);
		 		
		 		$query = "SELECT NME_PRD, COD_PRD FROM upcsprd_tb WHERE COD_PRD = $cod ";
		 		$dados = $BDFB->FBSelect($query);
		 		$row = @mysql_fetch_array($dados);
		 		
		 		$codigo = $row['COD_PRD'];
		 		if (!empty($row['NME_PRD'])){
		 			
		 			$objResponse->script("displayResultKits($codigo,'".$descricao."', '".$quantidade."')");	
		 			
			 		$objResponse->assign("msg","innerHTML",utf8_decode("Produto simples incluso com sucesso"));	
			 		$objResponse->script("document.getElementById('msg').style.backgroundColor='#".COR."'");	
			 		$objResponse->script("document.forms['frmbreg'].elements['DSC_KIT'].value = ''");
			 		$objResponse->script("document.forms['frmbreg'].elements['COD_KIT'].value = 0");
			 		$objResponse->script("window.setTimeout('rc_invisible(\'preview\')',100);");
		 		}else {
		 			$objResponse->assign("msg","innerHTML",utf8_decode("Produto simples inválido".$query));	
		 		$objResponse->script("document.getElementById('msg').style.backgroundColor='red'");	
		 		}
		 	}
		 	/*else{
		 		$objResponse->assign("msg","innerHTML",utf8_decode("Código de barras inválido"));	
		 		$objResponse->script("document.getElementById('msg').style.backgroundColor='red'");	
		 	}*/
		 }
		 
		 
		 //$objResponse->assign("msg","innerHTML",$codigo);	
		 return $objResponse;
	}
	
	
    function carregaMovimento($codi, $mov){
		
		$objResponse = new xajaxResponse();
		
		$BDFB = new FB();
		$BDFB->Conecta();
		
		$query = "SELECT T3.COD_PRD, HST_PRD, T2.QTD_PRD, T2.VLR_DPR, T2.VLR_UNT, T2.VLR_TPR, T3.NME_PRD FROM upcsmvm_tb T1 ".
				 "LEFT JOIN upcsmpr_tb T2 ON T1.COD_MVM = T2.COD_MVM ".
				 "LEFT JOIN upcsprd_tb T3 ON T2.COD_PRD = T3.COD_PRD ".
				 "WHERE T1.COD_MVM = $codi";
		$dados = $BDFB->FBSelect($query);
		while ($row = @mysql_fetch_array($dados)){
			$objResponse->script("displayResult($mov, {$row['COD_PRD']}, '".number_format($row['QTD_PRD'],4,',','.')."', '".number_format($row['VLR_DPR'],2,',','.')."', '".$row['NME_PRD']."', '".number_format($row['VLR_UNT'],2,',','.')."', '".$row['HST_PRD']."')");	
		}
		//$objResponse->assign("msg","innerHTML",$query);	
		return $objResponse;
		
	}
	
	function carregaMovimentoFornecedor($codi){
		
		$objResponse = new xajaxResponse();
		
		$BDFB = new FB();
		$BDFB->Conecta();
		
		$query = "SELECT T2.COD_CLN, T1.PRC_CST, T2.NME_CLN  FROM upcspcs_tb T1 ".
				 "JOIN upcscln_tb T2 ON T1.COD_CLN = T2.COD_CLN ".
				 "WHERE T1.COD_PRD = $codi";
		$dados = $BDFB->FBSelect($query);
		while ($row = @mysql_fetch_array($dados)){
			$objResponse->script("displayResultFornecedor({$row['COD_CLN']},'{$row['NME_CLN']}', '".number_format($row['PRC_CST'],2,',','.')."')");	
		}
		//$objResponse->assign("msg","innerHTML",$query);	
		return $objResponse;
		
	}
	
	function carregaMovimentoKits($codi){
		
		$objResponse = new xajaxResponse();
		
		$BDFB = new FB();
		$BDFB->Conecta();
		
		$query = "SELECT T2.NME_PRD, T1.COD_PRD, T1.QTD_PRD  FROM upcskpr_tb T1 ".
				 "JOIN upcsprd_tb T2 ON T1.COD_PRD = T2.COD_PRD ".
				 "WHERE T1.COD_KIT = $codi";
		$dados = $BDFB->FBSelect($query);
		while ($row = @mysql_fetch_array($dados)){
			$objResponse->script("displayResultKits({$row['COD_PRD']},'{$row['NME_PRD']}', '".number_format($row['QTD_PRD'],4,',','.')."')");	
		}
		//$objResponse->assign("msg","innerHTML",$query);	
		return $objResponse;
		
	}
	
    function carregaMovimentoGrupoClientes($codi){
		
		$objResponse = new xajaxResponse();
		
		$BDFB = new FB();
		$BDFB->Conecta();
		
		$query = "SELECT T2.COD_GCL, T1.PRC_VEN, T2.DSC_GCL  FROM upcspvn_tb T1 ".
				 "JOIN upcsgcl_tb T2 ON T1.COD_GCL = T2.COD_GCL ".
				 "WHERE T1.COD_PRD = $codi";
		$dados = $BDFB->FBSelect($query);
		while ($row = @mysql_fetch_array($dados)){
			$objResponse->script("displayResultGrupoClientes({$row['COD_GCL']},'{$row['DSC_GCL']}', '".number_format($row['PRC_VEN'],2,',','.')."')");	
		}
		//$objResponse->assign("msg","innerHTML",$query);	
		return $objResponse;
		
	}
    
    
    
    
    
    

	
	
?>
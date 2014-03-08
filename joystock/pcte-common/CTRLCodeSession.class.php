<?

	class CodeSession { 
	    /**
	     * Fun��o: encode -> Criptografa a entrada para session
	     *
	     * @param string $key: Informa a chave da sess�o
	     * @param string $value: Informa o valor da chave
	     * @return retorna o valor da chave criptografado
	     */
		public function encode($key, $value) { 
			//print $value;
			$_SESSION[$key] = base64_encode($value);
			
			return $_SESSION[$key];
	    }
		
		/**
		 * Fun��o: decode -> Descriptografa a chave informada
		 *
		 * @param string $key: 
		 * @return retorna o valor da chave solicitado
		 */		
	    public function decode($key){ 
			$decode_val = base64_decode($_SESSION[$key]);
	    	
			return $decode_val;
	    }
	    public function AutenticaPagina(){
	    	//print $chave." === ".$this->decode("chave")."---01<br>";
	    	$data_chave = date("IzYmdw", time()); // Forma��o: Se Bi-sexto, dia do ano (0-365), Ano, Mes, Dia da Semana
	    	$COD_USR = $this->decode("COD_USR");
	    	
	    	$chave = md5($data_chave.$COD_USR);
	    	//print $chave." === ".$this->decode("chave")."---01<br>";
	    	if($chave === $this->decode("chave")){
	    		return true;
	    	}else{
	    		//$_SESSION['msg'] = "Usu�rio sem autoriza��o de uso, Formul�rio n�o AUTENTICADO!";
	    		//header("Location: /index.php");
	    		//exit();
	    	}
	    }
	    
	     private function deletaBackup(){
	    	$hora = new ManipularHora();
	    	$backup = opendir(BD_BACKUP);
	    	while (false !== ($arquivo = readdir($backup))) {
	    		if ($creation = substr($arquivo,6,8)){
	    			$hoje = date('Ymd');
	    			$diff = $hoje - $creation;
	    			if (($diff > 100 && $diff < 8870) || $diff > 8900){
	    				unlink(BD_BACKUP.$arquivo);
	    			}	
	    		}	
			}
	    }
	    
	     private  function Backup(){
	     		
	     	if (file_exists(BD_BACKUP."Backup".date("Ymd-H").".sql"))
	     		return 0;
	     	$this->deletaBackup(); // ap�s 30 dias
		 	$arq_bck = BD_BACKUP."Backup".date("Ymd-H").".sql";
		 	
		 	mysql_connect(HOST,USUARIO,SENHA) or die(mysql_error());
			mysql_select_db(BANCO_DADOS) or die(mysql_error());
			
		 	/*$BDConex = @ibase_service_attach(HOST, USUARIO, SENHA);
		 	@ibase_backup($BDConex, BANCO_DADOS, $arq_bck, 0);
		 	@ibase_service_detach ($BDConex);*/
		 	$back = fopen($arq_bck,"w");
		 	
		 	$res = mysql_list_tables(BANCO_DADOS) or die(mysql_error());
		 	
		 	while ($row = mysql_fetch_row($res)) {
				$table = $row[0]; 
				$res2 = mysql_query("SHOW CREATE TABLE $table");
				while ( $lin = mysql_fetch_row($res2)){
					fwrite($back,"\n#\n# Criação da Tabela : $table\n#\n\n");
					fwrite($back,"$lin[1] ;\n\n#\n# Dados a serem incluídos na tabela\n#\n\n");
					$res3 = mysql_query("SELECT * FROM $table");
					while($r=mysql_fetch_row($res3)){ 
						$sql="INSERT INTO $table VALUES (";
						for($j=0; $j<mysql_num_fields($res3);$j++){
				            if(!isset($r[$j]))
				                $sql .= " '',";
				            elseif($r[$j] != "")
		                        $sql .= " '".addslashes($r[$j])."',";
				            else
				                $sql .= " '',";
				        }
		                $sql = ereg_replace(",$", "", $sql);
				        $sql .= ");\n";
						fwrite($back,$sql);
					}
				}
			}
			fclose($back);
			/*$arquivo = $dbname.".sql";
			Header("Content-type: application/sql");
 			Header("Content-Disposition: attachment; filename=$arquivo");
 			readfile($arquivo);*/
			
		 	
		 	if(file_exists($arq_bck))
		 		$result = 0;
		 	else
		 		$result = 1;
	
		 	return $result;
		 }
	    
	    public function ValidaPagina(){
	    	$this->Backup();
	    	if (!$this->decode('COD_USR')){
	    		header("Location: /crm/login.php");
	            exit("Finalizado");
	    	}
	        /*if($this->ValidaBrowser()==false){
	        	session_destroy();
	        }
	        
	    	if($this->decode('expira') < time()){
	           session_destroy();
	           header("Location: /index.php?expira=1");
	           exit("Finalizado");
	        }else{
	           $tempo = time() + TMP_EXPIRA;
	           $this->encode('expira', $tempo);
	        }*/
	    }
	    
		public function ValidaBrowser(){
	        $browser = array(1 => "Galeon", 2 => "Firefox");
	        
	        foreach ($browser as $key => $valor){
	        	$proc = trim($valor);
	        	$result = substr(strstr($_SERVER[HTTP_USER_AGENT], $proc),0,strlen($proc));
	        	if (!empty($result))
	        		break;
	        }

	        //return !empty($result)?true:false;
	        return true;
		}
	    
	} 
?>

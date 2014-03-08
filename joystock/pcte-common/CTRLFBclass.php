<?
	include_once("hora.class.php");
	

	class FB extends ManipularHora {
	
		/* publico: manipula��o erro */
		
		public $erro;
		public $erro_msg;
		public $string;
		public $query;
		public $status;
	
		/* publico: diversos */
		
		public $hora_servidor;
		public $generator;
		public $linhas;
		public $campo_tabela = array();
		public $intOffset;
		
		public $commit;
		
		/* privado: insert, update e delete */
		
		
		public $campo = array();
		public $where = array();
		
		/* privado: diversos */
		
		public  $conex;	
		private $result;
		private $intQuery;
		private $coln;
	
		private $tipocampo;
		private $tamcampo;
		

	
	 public function __construct() {
	   $this->erro=0;
	   $this->erro_msg='';   
	   $this->linhas=0;	
	   $this->coln=0;
	   $this->intQuery=0;
	   $campo = array();
	   $where = array();
	 }
	
	
	 
	// public function __destruct() {
	   //echo "Destruindo objeto...\n";
	// }
	
	/*--------------------------------------------------------------*/
	
	/**
	     * fb::Conecta()
	     * Abre uma conex�o com banco
		 * 
	     * @return id da conexao
	     */
	
	public function Conecta()
	{
		$this->erro=0;
		$this->commit = 0;
		
		$this->conex = @mysql_connect(HOST, USUARIO, SENHA);
		if(!$this->conex) die("** (1) Erro ao conectar ao MySQL **");
		if(!mysql_select_db(BANCO_DADOS, $this->conex)) die("** (2) Erro ao selecionar base MySQL **");
		return $this->conex;
	}
	
	/*--------------------------------------------------------------*/
	
	/**
	     * fb::Fechar()
	     * Abre uma conex�o com banco
		 * 
	     * @return null
	     */
	
	public function Fechar(){
	  $this->erro=0;
	  $this->erro_msg='Conex�o finalizada com sucesso';	
	  if(@ibase_commit($this->conex)==false) {
	    $this->erro=ibase_errcode();
	    $this->erro_msg=ibase_errmsg();
	  }	
	  if($this->erro==0) {
	    if(@ibase_close($this->conex)==false) {
	      $this->erro=ibase_errcode();
	      $this->erro_msg=ibase_errmsg();
	    }
	  }  
	}
	
	/*--------------------------------------------------------------*/
	
	/**
	     * fb::FBSelect()
	     * Executa uma select em um banco de dados
		 * 
	     * @return result set da sele��o
	     */
	
	public function FBSelect($query){
		$result = 0;
		$this->query = $query;
	//	PRINT $query;
		if($this->conex) { // verifica se existe conex�o ao banco	
			
  			$this->result = @mysql_query($query, $this->conex);
			if($this->result) {
				$this->erro = 0;	
				$this->erro_msg = 'Select executada com sucesso';
				
				$result = $this->result;
			} else {
				  $this->erro=mysql_errno();
				  $this->erro_msg=mysql_error();
			}
		}else{
	        $this->erro=99;
	    	$this->erro_msg='Instrução de Comando não reconhecido';
		}
	  	
	  	return $result;
	}

	/**
	 * Fun��o: IncCampo - Permite adicionar dentro de uma matriz,
	 * valores para serem usados na fun��o FBInsert
	 * 	 
	 * @param $campo: indica o nome do campo da tabela 
	 * 		  $tipo: indica qual o tipo do campo
	 *        $valor: Indica o valor do campo correspondente
	 * 
	 * @return sem retorno
	 */
	
	public function IncCampo($campo, $tipo, $valor) { 
		$this->campo[$campo] = array('tipo' => $tipo, 'valor' => $valor);
	}
	
	/*-----------------------------------------------*/
	
	/**
	 * Fun��o IncWhere - Permite adicionar parametros para condicionar
	 * uma query
	 *
	 * @param $campo: indica o nome do campo da tabela 
	 *        $Sinal: indica o sinal de equivalencia a ser tratado
	 *        $tipo: indica o tipo do campo da tabela
	 *        $valor: indica o valor do campo correspondente
	 */
	
	public function IncWhere($campo, $tipo, $sinal='=', $valor) { 
		$this->where[] = array('campo' => $campo, 
		                       'tipo' => $tipo , 
		                       'sinal' => $sinal,
		                       'valor' => $valor);
	}
	
	/*-----------------------------------------------*/
		
	public function FBInsert($NomeTabela) {	
		$query = "INSERT INTO $NomeTabela(";
		$campos = $this->CamposTabela($NomeTabela);
		for($i=0;$i<count($campos);$i++){
			$c = trim($campos[$i]['NOME']);
			if($i==count($campos)-1)
				$sep = ')';
			else
				$sep = ',';
			$query .= "$c$sep ";
		}
		
		$query .= ' VALUES(';
		
		for($i=0;$i<count($campos);$i++){
			$c = trim($campos[$i]['NOME']);
			$i==count($campos)-1?$sep = ')':$sep = ',';

			$ct = $this->TratarCampo($this->campo[$c]['tipo'], $this->campo[$c]['valor']);
			
			$query .= $ct.$sep;
		}
		
		
		
		$this->campo = array();
		$this->query = $query;
	    //print $query; //INSERT
		$result = @mysql_query($query, $this->conex);
		if (!$result){
			$this->erro = mysql_errno();
			$this->erro_msg[0] = mysql_error();
			$this->erro_msg[1] = $query;
			
		
		}else{
			$this->commit;
			$this->erro_msg[0] = "";
		}
		
		return $this->erro_msg;
	}
	
	public function FBUpdate($NomeTabela) {	
		
		$campos = $this->CamposTabela($NomeTabela);
		$query = "UPDATE $NomeTabela SET ";
		for($i=0;$i<count($campos);$i++){
			$c = trim($campos[$i]['NOME']);
			$i==count($campos)-1?$sep = ' ':$sep = ',';
			
			$ct = $this->TratarCampo($this->campo[$c]['tipo'], $this->campo[$c]['valor']);
			$query .= $c."=".$ct.$sep;
		}
		$query .= " WHERE ";
		
		for($i=0;$i<count($this->where);$i++){
			$i==count($this->where)-1?$sep = '':$sep = ' AND ';
			
			$c = $this->TratarCampo($this->where[$i]['tipo'], $this->where[$i]['valor']);
			
			$ct = "{$this->where[$i]['campo']} {$this->where[$i]['sinal']} $c ";
			
			$query .= $ct;
		}
		$this->campo = array();
		$this->where = array();
		$this->query = $query;
       //print $query;
		$result = @mysql_query($query, $this->conex);
		if (!$result){
			$this->erro = mysql_errno();
			$this->erro_msg = mysql_error();	
		}else{
			$this->commit;
			$this->erro_msg = "";
		}
		
		return $this->erro_msg;
	}

	public function FBDelete($NomeTabela) {	
		
		$query = "DELETE FROM $NomeTabela ";
		if(!empty($this->where)){
			$query .= "WHERE ";
			foreach ($this->where as $dados){
				if(strcmp(trim($this->InfoCampo($dados['tipo'])),"VARCHAR") == 0){
					$query .= "{$dados['campo']} {$dados['sinal']} '{$dados['valor']}' AND ";
				}else{
					$query .= "{$dados['campo']} {$dados['sinal']} {$dados['valor']} AND ";
				}
			}
			$query = substr($query, 0, strlen($query)-5);
		}
		
		$this->where = array();
		$this->sinal = array();
		$this->query = $query;
		
		$result = @mysql_query($query, $this->conex);
		if (!$result){
			$this->erro = mysql_errno();
			$this->erro_msg = mysql_error();	
		}else{
			$this->commit;
			$this->erro_msg = "Registro excluido com sucesso!";			
		}
	}
	
	/*--------------------------------------------------------------*/

	/**
	 * FBObjSelect($VrCampo,$VrPadrao, $matriz)
	 * Cria o objeto Select em HTML
	 *
	 * @param string $VrCampo
	 * @param string $VrPadrao
	 * @param array $matriz
	 * @return unknown
	 */
	
	public function FBObjSelectTempo($MOD, $VrCampo, $VrPadrao, array $matriz,$RTRorCST){  //Retorna o Campo
		$padrao = empty($VrCampo)?$VrPadrao:$VrCampo;
		for($i=1;$i<=count($matriz);$i++){
			list(,$HRA_MATRIZ,) = explode(":",$matriz[$i]);
			if ($RTRorCST == 2) {
				if (($HRA_MATRIZ % $MOD)==0)
					if($i == $padrao)
		     			$ObjSelect .= "<option value=\"$matriz[$i]\" selected=\"selected\">$matriz[$i]</option>";
			 		else 
			 			$ObjSelect .= "<option value=\"$matriz[$i]\">$matriz[$i]</option>";
			}elseif ($RTRorCST == 1)
				if (($MOD % $HRA_MATRIZ)==0)
					if($i == $padrao)
		     			$ObjSelect .= "<option value=\"$matriz[$i]\" selected=\"selected\">$matriz[$i]</option>";
			 		else
			 			$ObjSelect .= "<option value=\"$matriz[$i]\">$matriz[$i]</option>";
 			}
		
		if(empty($padrao)){
			$ObjSelect .= "<option value=\"$matriz[$i]\" selected=\"selected\"></option>";
		}
		return $ObjSelect;
	}
	
	public function FBObjSelect($VrCampo, $VrPadrao, array $matriz){  //Retorna o �ndice
		$padrao = empty($VrCampo)?$VrPadrao:$VrCampo;
		//print $matriz[1];
		
		for($i=1;$i<=count($matriz);$i++){
		  if($i == $padrao)
		     $ObjSelect .= "<option value=\"$i\" selected=\"selected\">$matriz[$i]</option>";
		  else
		     $ObjSelect .= "<option value=\"$i\">$matriz[$i]</option>";
		}
		
		/*if(empty($padrao)){
			$ObjSelect .= "<option value=\"$i\" selected=\"selected\"></option>";
		}*/
		
		return $ObjSelect;
	}
	
	/* -------------------------------------------------------------*/

	/**
	 * FBObjBDSelect($VrCampo,$VrPadrao, $matriz)
	 * Cria o objeto Select em HTML comunicando 
	 * com banco de dados
	 *
	 * @param string $VrCampo
	 * @param string $VrPadrao
	 * @param array $matriz
	 * @return unknown
	 */
	
	public function FBObjBDSelect($VrCampo, $VrPadrao, $tabela, $codigo, $descricao, $where, $orderby, $join){
		$padrao = empty($VrCampo)?$VrPadrao:$VrCampo;
		$EXC = "EXC_".substr($tabela, 0,-3);

		if(!empty($where)){
			$where = " AND ".$where;
		}

		if(empty($orderby)){
			$orderby = $descricao;
		}	
		
		
		
		$query = "SELECT T1.$codigo, T1.$descricao FROM $tabela AS T1 $join WHERE $EXC IS NULL $where ORDER BY $orderby";
		
		
		
		$dados = $this->FBSelect($query);
		$padrao = empty($VrCampo)?$VrPadrao:$VrCampo;
		
		/*if ($tabela == "USDKCNS_TB")
			$ObjBDSelect .= "<option value=\"todos\" selected=\"selected\">CONV�NIOS</option>";*/
			
		while ($row = @mysql_fetch_array($dados)) {
			if ($row[$codigo]==$padrao) {
				$ObjBDSelect .= "<option value=\"{$row[$codigo]}\" selected=\"selected\">".$row[$descricao]."</option>";
			}else{
				$ObjBDSelect .= "<option value=\"{$row[$codigo]}\">".$row[$descricao]."</option>";
			}
		}
		
		if(empty($padrao))
			$ObjBDSelect .= "<option value=\"\" selected=\"selected\"></option>";
		
        return $ObjBDSelect;
	}
	
	/* -------------------------------------------------------------*/	
	
	/**
	     * fb::Commit()
	     * Executa um commit no banco de dados
		 * 
	     * @return null
	     */
	
	private function Commit() {
		@ibase_commit($this->conex);	 
	}  	

	/**
	     * fb::LeRegistro()
	     * L� um registro da tabela de acordo com o result set
	     * Mostrando todos os campos da tabela
		 * 
	     * @return id do registro
	     */
	
	public function LeRegistro($result){
		if($this->erro==0) {	
			$this->registro = @ibase_fetch_object($result);
			$this->result = $result;
			
			return $this->registro;
		}
	}   
	
	/*--------------------------------------------------------------*/
	
	/**
	     * fb::InfoCampo()
	     * Mostra campo de uma tabela
		 * 
	     * @return campo do registro
	     */
	
	public function InfoCampo($field, $info='type'){
		for ($i = 0; $i < $this->coln; $i++) {
	  		$col_info = ibase_field_info($this->result, $i);  	
	  		if($col_info['name']==$field){
	  	   		$campo = $col_info[$info];
		  	   	break;
	  		}
	  	}
		
	 	return $campo;
	}	
	
	/*--------------------------------------------------------------*/
	
	public function result_object(){
	  if ($this->erro==0) {
	    return $result=@ibase_fetch_object($this->result);
	  }  
	}
	
	public function result_assoc(){
		return $result=@ibase_fetch_assoc($this->query);
	}
	
	/*--------------------------------------------------------------*/
	
	/**
	     * fb::Paginar()
	     * Faz uma consulta select retornando registros paginados
		 * 
	     * @return result set da sele��o
	     */
	
	public function Paginar($tabela, $listaCampos, $strclausula='', $intMax, $intOffset) {
	  $this->intMax=$intMax;
	  $this->tabela=$tabela;
	  $this->listaCampos=$listaCampos;
	  $this->intOffset=$intOffset;
	  $this->strSQL="select first ".$this->intMax." skip ".$this->intOffset." ".$this->listaCampos." from ".$this->tabela." ".$strclausula;
	  $this->intQuery=$this->FBSelect($this->strSQL);  
	  return $this->intQuery;
	}
	
	/*--------------------------------------------------------------*/
	
	/**
	     * fb::TabelaExiste()
	     * Verifica se uma tabela existe
		 * 
	     * @return boolean
	     */
	
	private function TabelaExiste($tabela) {	
		$sql="SHOW TABLES WHERE Tables_in_".BANCO_DADOS." = '".$tabela."'";
		$result = mysql_query($sql, $this->conex); 
		$valido = mysql_fetch_object($result);
		if(!empty($valido)){ 
			return true; 
		}else{ 
			return false; 
		}
	}
	
	/*--------------------------------------------------------------*/
	
	/**
	     * fb::CamnpoExiste()
	     * Verifica se um campo de uma tabela existe
		 * 
	     * @return tipo, tamanho campo
	     */
	
	public function CampoExiste($tabela, $campo) {PRINT "FORA";
		if($this->TabelaExiste($tabela)) {
	  		
			$this->tipocampo=null;
		  	$this->tamcampo=null;
		  	$sql = "SHOW FIELDS FROM ".$tabela;
		  	
		  	/*$sql="select distinct ";
		  	$sql.="A.RDB\$FIELD_NAME as F_NAME, ";
		  	$sql.="case ";  	 
		  	$sql.="when B.RDB\$FIELD_PRECISION > 0 then 'FLOAT' ";
		  	$sql.="when C.RDB\$TYPE_NAME='LONG' then 'INTEGER' ";
		  	$sql.="when C.RDB\$TYPE_NAME='SHORT' then 'INTEGER' ";
		  	$sql.="when C.RDB\$TYPE_NAME='VARYING' then 'STRING' ";
		  	$sql.="when C.RDB\$TYPE_NAME='TEXT' then 'STRING' ";
		  	$sql.="when C.RDB\$TYPE_NAME='BLOB' then 'STRING' ";
		  	$sql.="else ";
		  	$sql.="C.RDB\$TYPE_NAME ";
		  	$sql.="end as F_TIPO, ";
		  	$sql.="case ";
		  	$sql.="when B.RDB\$FIELD_PRECISION > 0 then ";
		  	$sql.="''||cast(B.RDB\$FIELD_PRECISION as "; 
		  	$sql.="varchar(2))||','||cast(B.RDB\$FIELD_SCALE*-1 as varchar(2))||''";  	
		  	$sql.="when B.RDB\$CHARACTER_LENGTH is null then  '0' ";
		  	$sql.="else ";
		  	$sql.="B.RDB\$CHARACTER_LENGTH ";
		  	$sql.="end as F_TAMANHO ";
		  	$sql.="from ";
		  	$sql.="RDB\$RELATION_FIELDS A ";
		  	$sql.="left join RDB\$FIELDS  B on A.RDB\$FIELD_SOURCE=B.RDB\$FIELD_NAME ";
		  	$sql.="left join RDB\$TYPES C on C.RDB\$FIELD_NAME='RDB\$FIELD_TYPE' and  ";
		  	$sql.="B.RDB\$FIELD_TYPE=C.RDB\$TYPE ";
		  	$sql.="left join RDB\$RELATION_CONSTRAINTS E on A.RDB\$RELATION_NAME=E.RDB\$RELATION_NAME ";
		  	$sql.="left join RDB\$INDEX_SEGMENTS F on E.RDB\$INDEX_NAME=F.RDB\$INDEX_NAME and ";
		  	$sql.="A.RDB\$FIELD_NAME=F.RDB\$FIELD_NAME ";
		  	$sql.="where ";
		  	$sql.="A.RDB\$RELATION_NAME = '$tabela' and A.RDB\$FIELD_NAME = '$campo'";
			PRINT $sql;*/
		  	$res=@mysql_query($sql, $this->conex);
		    $row=@mysql_fetch_object($res);
		    
		    if(!empty($row->Type)) {
		    	if (strpos($row->Type,"(")){
			      	$nome = trim(substr($row->Type,0,strpos($row->Type,"(")));
			      }else{
			      	$nome = $row->Type;
			      }
		    	PRINT $this->tipocampo=trim($nome);
		    	PRINT $this->tamcampo=substr($row->Type,strpos($row->Type,"(")+1,strpos($row->Type,")")-(strpos($row->Type,"(")+1));
		    	$result = 1;
		    }else 
		    	$result = 0;
		    
		    @mysql_free_result($res);
		} 
		
		return $result;
	}    
	
	/*--------------------------------------------------------------*/
	
	/**
	     * fb::ChavePrimaria()
	     * Retorna a(s) chave(s) primaria(s) de uma tabela
		 * 
	     * @return tipo, tamanho campo
	     */
	
	private function ChavePrimaria($tabela) {
		
	  $chaves = array();  
	  $i=0;
	  	
	  if($this->TabelaExiste($tabela)) {
	  	
	  	$this->tipocampo=null;
	  	$this->tamcampo=null;
	  	
	  	$sql="select I.RDB\$FIELD_NAME as CAMPO ";
	  	$sql.="from RDB\$RELATION_CONSTRAINTS R ";
	  	$sql.="join RDB\$INDEX_SEGMENTS I on ";
	  	$sql.="(R.RDB\$INDEX_NAME = I.RDB\$INDEX_NAME) ";
	  	$sql.="where (R.RDB\$RELATION_NAME = '$tabela') ";
	  	$sql.="and (R.RDB\$CONSTRAINT_TYPE = 'PRIMARY KEY')";  	
	  	
	    $res=@ibase_query($this->conex,$sql);
	    
	    while($dados = @ibase_fetch_object($res)) {
	    	$chaves[$i]=$dados->CAMPO;
	    	$i++; 	
	    }
	    
	    @ibase_free_result($res);     
	    
	    if(count($chaves)>0) {
	       return $chaves;
	    } else {
	       return null;	
	    }
	  } 
	}    
	
	/*--------------------------------------------------------------*/
	
	/**
	     * fb::CamnposTabela()
	     * Retorna campos de uma tabela
		 * 
	     * @return array lista campos
	     */
	
	public function CamposTabela($tabela) {
		if($this->TabelaExiste($tabela)) {
		  	$this->tipocampo=null;
		  	$this->tamcampo=null;
		  	
		  	$sql = "SHOW FIELDS FROM ".$tabela;
		  	
		  	/*$sql="select distinct ";
		  	$sql.="A.RDB\$FIELD_POSITION as F_ID, ";
		  	$sql.="A.RDB\$FIELD_NAME as F_NAME, ";
		  	$sql.="case ";  	 
		  	$sql.="when B.RDB\$FIELD_PRECISION > 0 then 'FLOAT' ";
		  	$sql.="when C.RDB\$TYPE_NAME='LONG' then 'INTEGER' ";
		  	$sql.="when C.RDB\$TYPE_NAME='SHORT' then 'INTEGER' ";
		  	$sql.="when C.RDB\$TYPE_NAME='VARYING' then 'STRING' ";
		  	$sql.="when C.RDB\$TYPE_NAME='TEXT' then 'STRING' ";
		  	$sql.="when C.RDB\$TYPE_NAME='BLOB' then 'STRING' ";
		  	$sql.="else ";
		  	$sql.="C.RDB\$TYPE_NAME ";
		  	$sql.="end as F_TIPO, ";
		  	$sql.="case ";
		  	$sql.="when B.RDB\$FIELD_PRECISION > 0 then ";
		  	$sql.="''||cast(B.RDB\$FIELD_PRECISION as "; 
		  	$sql.="varchar(2))||','||cast(B.RDB\$FIELD_SCALE*-1 as varchar(2))||''";  	
		  	$sql.="when B.RDB\$CHARACTER_LENGTH is null then  '0' ";
		  	$sql.="else ";
		  	$sql.="B.RDB\$CHARACTER_LENGTH ";
		  	$sql.="end as F_TAMANHO, ";
		  	$sql.="F.RDB\$FIELD_NAME as F_CHAVE, ";
		  	$sql.="A.rdb\$null_flag as F_NULO ";
		  	$sql.="from ";
		  	$sql.="RDB\$RELATION_FIELDS A ";
		  	$sql.="left join RDB\$FIELDS  B on A.RDB\$FIELD_SOURCE=B.RDB\$FIELD_NAME ";
		  	$sql.="left join RDB\$TYPES C on C.RDB\$FIELD_NAME='RDB\$FIELD_TYPE' and  ";
		  	$sql.="B.RDB\$FIELD_TYPE=C.RDB\$TYPE ";
		  	$sql.="left join RDB\$RELATION_CONSTRAINTS E on A.RDB\$RELATION_NAME=E.RDB\$RELATION_NAME ";
		  	$sql.="and E.RDB\$CONSTRAINT_TYPE='PRIMARY KEY' ";
		  	$sql.="left join RDB\$INDEX_SEGMENTS F on E.RDB\$INDEX_NAME=F.RDB\$INDEX_NAME and ";
		  	$sql.="A.RDB\$FIELD_NAME=F.RDB\$FIELD_NAME ";
		  	$sql.="where ";
		  	$sql.="A.RDB\$RELATION_NAME = '$tabela'";*/
		  	
		  	unset($this->campos_tabela);
		  	$res=@mysql_query($sql,$this->conex);
		    $tam = "0";
		    $aux = 1;
		    		
		    while($row=@mysql_fetch_object($res)) {
		      if($row->Key=="PRI") {	
		        $chave='S';	
		      } else {
		      	$chave='N';
		      }      
		      
		      //PRINT strpos($row->Type,")")."<BR>";
		      //print substr($row->Type,strpos($row->Type,"(")+1,strpos($row->Type,")")-(strpos($row->Type,"(")+1))."<br>";
		      if (strpos($row->Type,"(")){
		      	$nome = trim(substr($row->Type,0,strpos($row->Type,"(")));
		      }else{
		      	$nome = $row->Type;
		      }
		      //print $nome."<br>";
		      switch ($nome){
		      		case "timestamp": 
		      			$tam = 19;
		      		break;

		      		case "date": 
		      			$tam = 10;
		      		break;

		      		case "time": 
		      			$tam = 5;
		      		break;		      		
		      		
		      		case "int": 
		      			$tam = substr($row->Type,strpos($row->Type,"(")+1,strpos($row->Type,")")-(strpos($row->Type,"(")+1));
		      		break;
		      		
		      		case "varchar": 
		      			$tam = substr($row->Type,strpos($row->Type,"(")+1,strpos($row->Type,")")-(strpos($row->Type,"(")+1));
		      		break;
		      		
		      		case "float": 
		      			$tam = 12;
		      		break;	 
		      		
		      		case "decimal": 
		      			$tam = 12;
		      		break;
		      }
		      
		      $this->campos_tabela[]=array('ID' => $aux, 
		                                   'NOME' => $row->Field, 
		                                   'TIPO' => $nome,
		                                   'TAMANHO' => $tam,
		                                   'CHAVE' => $chave,
		                                   'NULO' => $row->Null,
		                                   'EXTRA' => $row->Extra);
		      $aux++;                             
		    }
		    
		    @mysql_free_result($res);
		}else{
			$this->campos_tabela = "";
		}

		return $this->campos_tabela;
	}
	
	/*--------------------------------------------------------------*/
	
	
	/**
	 * Executa query  
	 * 
	 */
	
	public function EXECQuery($query){
		if($this->conex) {
			$this->query = $query;
			$result = @mysql_query($query, $this->conex);
			//$this->result  = @ibase_fetch_object($result); 
			return  $this->query;
		}
	}
	
	/**
	     * fb::ValorGenerator()
	     * Retorna valor do generator de uma tabela
		 * 
	     * @return @generator
	     */
	
	public function ValorGenerator($tabela) {
		
	  if($this->TabelaExiste($tabela))	{
	  	
	  $this->generator=0;	
	  
	  $sql="select rdb\$trigger_source as TEXTO from rdb\$triggers where rdb\$relation_name = '$tabela'";
	  
	  $this->erro=0;
	  $this->erro_msg='Valor generator tabela '.$tabela.' retornado com sucesso';	  
	  
	  $result=@ibase_query($this->conex,$sql);
	  
	  if($result) {
	  
	  while($res = @ibase_fetch_object($result))  {   
	  
	    //$res=@ibase_fetch_object($result);   
	    $blob_data = @ibase_blob_info($res->TEXTO); 
	    $blob_id = @ibase_blob_open($res->TEXTO); 
	  
	    $texto = @ibase_blob_get($blob_id, $blob_data[0]);
	    
	    $texto=strtoupper($texto);
	    
	    if(stripos($texto,'GEN_ID')) {
	      $st=explode('GEN_ID',$texto);
	      $st=$st[1];
	      $st=explode(',',$st);
	      $st=$st[0];   
	      $generator=substr($st,1,strlen($st));
	    }      
	  }
	  
	    if(empty($generator)) {
	      $this->erro=99;
	      $this->erro_msg='Nenhum GENERATOR para a tabela '.$tabela.'.';
	    } else {  
	       $sql="select GEN_ID($generator,0) as AID from RDB\$DATABASE";
	       $res=@ibase_query($this->conex,$sql);
	       $res=@ibase_fetch_object($res);
	       $this->generator=$res->AID;   
	    }  
	     
	  } else {  
	      $this->erro=ibase_errcode();
	      $this->erro_msg=ibase_errmsg();
	  }
	 } else {   
	    $this->erro=99;
	    $this->erro_msg='Tabela '.$tabela.' n�o existe.';
	 } 
	}
	
	/*--------------------------------------------------------------*/
	
	/**
	     * fb::DataHoraServidor()
	     * Retorna Data/Hora do servidor Firebird
		 * 
	     * @return timestamp
	     */
	
	public function DataHoraServidor() {	
		$result=@ibase_query($this->conex,'select CURRENT_TIMESTAMP AS DATETIME FROM RDB$DATABASE');
		if ($result) {
		   $result = @ibase_fetch_object($result);
		   $this->hora_servidor=$this->data_br($result->DATETIME);
		   $this->erro=0;
	   	   $this->erro_msg='Data/Hora servidor retornada';
		} else {	
		   $this->hora_servidor='00/00/0000 00:00';
	       $this->erro=ibase_errcode();
	       $this->erro_msg=ibase_errmsg();
		}
	}
	
	/*--------------------------------------------------------------*/
	
	/**
	     * fb::Data_br()
	     * Converte data/hora Firebird para humano
		 * 
	     * @return timestamp
	     */
	
	public  function data_br( $data )
	{
	  $qual = "/";	
	  $aVet = Explode('-',$data );
	  $ano = $aVet[0];
	  $mes = $aVet[1];
	  $dia = $aVet[2];    
	  $hora=substr($data,11,5);   
	  return date("d".$qual."m".$qual."Y",mktime(0,0,0,$mes,$dia,$ano))." ".$hora;
	}
	
	/*--------------------------------------------------------------*/
	
	/**
	     * fb::TrataCampo()
	     * Trata campo de acordo com seu tipo
		 * 
	     * @return campo tratado
	     */
	
	public function TratarCampo($tipo, $campo) {
		switch ($tipo)  {
			case 'varchar': 
				$campo = empty($campo)?'NULL':"'".trim($campo)."'";
			break;
	     
			case 'blob': 
				$campo = empty($campo)?'NULL':"'".trim($campo)."'";
			break;
			
			case 'int': 
	     		$campo = empty($campo)?'0':$campo;
			break;
	     
			case 'float':
				if(empty($campo)){
					$campo = '0,00';
				}
				$campo = str_replace(".","",$campo);
				$campo = str_replace(",",".",$campo);
			break;     
			
			case 'decimal':
				if(empty($campo)){
					$campo = '0,00';
				}
				$campo = str_replace(".","",$campo);
				$campo = str_replace(",",".",$campo);
			break;
	     
			case 'date':
		        $data = explode("/",$campo);
		        if (@checkdate($data[1],$data[0],$data[2])) {
					$campo = "'".$data[2].'-'.$data[1].'-'.$data[0]."'";
		        }else{
					$campo = 'NULL';
		        }  
			break;     

			case 'time':
		        if(!empty($campo)){
		        	$hora = explode(":",$campo);
					$campo = "'".$hora[0].':'.$hora[1].':'.$hora[2]."'";
		        }else 
					$campo = '';
			break;
			
			case 'timestamp':
		        $data = explode("/",trim(substr($campo,0,10)));
		        if (@checkdate($data[1],$data[0],$data[2])) {
					$campo = "'".$data[2].'-'.$data[1].'-'.$data[0].' '.substr($campo,11,8)."'";
		        } else {
					$campo = 'NULL';
		        }  
			break;          
	      
		}
		
		return $campo;
	}
	
	/*--------------------------------------------------------------*/

	/**
	     * fb::MostrarCampo()
	     * Prepara o campo para mostrar no formul�rio
		 * 
	     * @return campo tratado
	     */
	
	public function MostrarCampo($tipo, $campo) {
		switch ($tipo)  {
			case 'varchar': 
			break;
			
			case 'blob': 
			break;
	     
			case 'int': 
	     		$campo = empty($campo)?'0':str_pad($campo, 5, '0', STR_PAD_LEFT);
			break;
	     
			case 'float':
				if(empty($campo)){
					$campo = '0,00';
				}	
				$campo = number_format($campo,2,',','.');           
			break;     
			
			case 'decimal':
				if(empty($campo)){
					$campo = '0,00';
				}	
				$campo = number_format($campo,2,',','.');           
			break;
	     
			case 'date':
		        $data = explode("-",$campo);
		        if (@checkdate($data[1],$data[2],$data[0])) {
					$campo = $data[2].'/'.$data[1].'/'.$data[0];
		        }else{
					$campo = '';
		        }
			break;     
			
			case 'DDMMAA':
		        $data = explode("-",$campo);
		        if (@checkdate($data[1],$data[2],$data[0])) {
					$campo = $data[2].'/'.$data[1].'/'.substr($data[0],2);
		        }else{
					$campo = '';
		        }
			break;

			case 'TIME':
		        if(!empty($campo)){
					$hora = explode(":",$campo);
					$campo = $hora[0].':'.$hora[1];
		        }else 
					$campo = '';
			break;
			
			case 'timestamp':
		        $data = explode("-",trim(substr($campo,0,10)));
		        if (@checkdate($data[1],$data[2],$data[0])) {
					$campo = $data[2].'/'.$data[1].'/'.$data[0].' '.substr($campo,11,8);
		        } else {
					$campo = '';
		        }  
			break;          
	      
		}
		
		return $campo;
	}	

	/*--------------------------------------------------------------*/
	
	/**
	     * fb::valdata()
	     * Valida data
		 * 
	     * @return date
	     */
	
	private function valdata($data){
	  if(isset($data)){	
	    $datatrans=explode("-",$data);
	    $valida = checkdate($datatrans[1],$datatrans[2],$datatrans[0]);
	  } else {  
	    $valida=false; 	
	  }
	  return $valida;
	}
	
	/*--------------------------------------------------------------*/
	
	/**
	     * fb::TempoDecorrido()
	     * Retorna tempo decorrido de um processo
		 * 
	     * @return string
	     */
	
	public function tempo_decorrido($opt='I') {
		if($opt=='I') {
			$t1=(double)microtime();     	
		    $t2=0;
		    return null;
		} else {
	     	$t2 = (double)microtime(); 
	        ($t2>$t1)?$t=substr($t2-$t1,0,4):$t=substr($t1-$t2,0,4);
	  		return "$t.ms";
		}
	 }
	
	
	public function FBSelectCSV($query,$nomearq='arq',$separador='#'){
	
	  $result=0;
	  
	  $saltalinha=chr(13).chr(10);
	  
	  $nomearq.=date('Ymd-hi').'.csv';
	  
	  if($this->conex) { // verifica se existe conex�o ao banco	
	  
	    $query_array =explode(" ",strtolower($query));
	
	  	  if(in_array('select',$query_array)) {
	  		
	  		  $result=@ibase_query($this->conex,$query);
	  		
	  		  if($result) {  		  	
	  		  	
	  		  	  $campo=null;
	  		  	  $campo=null;
	  		  	  
	  		  	  flush();
	  		  	  
	              $coln = ibase_num_fields($result);              
	              for ($i=0; $i<$coln; $i++) {
	              	$col_info = ibase_field_info($result, $i);
	              	$campo.=$col_info['name'];
	              	if($i<$coln-1) {
	              	  $campo.=$separador;
	              	}
	              }
	              
	              $campo=explode($separador,$campo);
	              
	              $fp=@fopen($nomearq, 'w');
	              
	              if($fp) {
	
	                while($dados = ibase_fetch_object($result))  {
	                   $linha=null;		
	              	   for($i=0; $i<count($campo); $i++) {  
	              	 	  if (!empty($dados->$campo[$i])) {
	                	      $linha.=$dados->$campo[$i];
	              	 	  } else {
	              	 		  $linha.=' ';
	              	 	  }
	             	 	  if($i<count($campo)-1) {
	              	 		$linha.=$separador;
	              	 	    }	
	             	  }
	              	 
	              	$linha.=$saltalinha; 
	                @fwrite($fp,$linha);
	                } 
	              
	                @fclose($fp);
	                
	               $this->erro=00;
	               $this->erro_msg='Arquivo CSV gerado com sucesso'; 
	 
	               return $nomearq;
	               
	           } else { 
	              $this->erro=99;
	              $this->erro_msg='N�o consegui gerar arquivo CSV'; 
	              return null;
	             }
	
	  		  } else {
	   			  $this->erro=ibase_errcode();
	  			  $this->erro_msg=ibase_errmsg();
	  			  return null;
	  		  }
	  		
		  } else {
	        $this->erro=99;
	        $this->erro_msg='Instru��o/Comando n�o reconhecido';
	        return null;
	    }    
	  } 
	}

	
	public function Versao($opt='I') {
		$versao='1.80';
		return $versao;	
	 }
	 
	}

?>
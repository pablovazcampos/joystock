<?php 

	class listaPesquisa{
		
		
		protected $titulo;
		protected $filtros;
		protected $filtrosPLus;
		protected $contador=1;
		protected $contadorPLus=1;
		protected $totalTamanhoPlus;
		public  $where;
		protected $path;
		protected $campos;
		protected $camposPlus;
		protected $linhas;
		protected $linhasPLus;
		protected $pathPesquisa;
		protected $tituloPlus;
		protected $queryPlus;
		protected $groupByPlus;
		protected $objeto;
		protected $funcObjeto;
		protected $objetoPlus;
		protected $funcObjetoPlus;
		
		public function __construct($titulo, $path){
			$this->titulo = $titulo;
			$this->path = $path;
		}
		
		private function  criaWhere($campo,$valor,$tipo,$valor2){
			
			$BDFB = new FB();
			$BDFB->Conecta();
			
			if ($tipo == "codigo" || $tipo == "select"){
				$this->where .= !empty($valor)?" AND $campo = {$valor} ":""; 
			}elseif ($tipo == "texto" || $tipo == "objeto"){
				$this->where .= !empty($valor)?" AND UPPER($campo) LIKE UPPER('%{$valor}%') ":""; 
			}elseif ($tipo == "data"){
				$this->where .= !empty($valor)?" AND T1.$campo >= ".$BDFB->TratarCampo("date", $valor):"";
				$this->where .= !empty($valor2)?" AND T1.$campo <= ".$BDFB->TratarCampo("date", $valor2):"";
			}elseif ($tipo == "valor"){
				$this->where .= !empty($valor)?" AND T1.$campo >= ".$BDFB->TratarCampo("float", $valor):"";
				$this->where .= !empty($valor2)?" AND T1.$campo <= ".$BDFB->TratarCampo("float", $valor2):"";
			}
		}
		
		
		public function criaColuna($campo, $valor, $titulo, $tamanho, $tipo="texto", $mascaraFiltro="",$mascaraLinha="",$align="center",$valor2=""){
			
			$BDFB = new FB();
			$BDFB->Conecta();
			
			
			if ($tipo == "texto" || $tipo == "codigo" || $tipo == "objeto"){
				$this->filtros .=  "<td style='position:relative' align='center' width='{$tamanho}px'>".
									"<div id='campo$this->contador' class='titleBlt'  style='position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; '>".	
										"$titulo".
										"<input class='$mascaraFiltro' autocomplete='off' onblur='setTimeout(function(){desFiltros(\"campo$this->contador\", \"30\")},200)' name='$campo' value='$valor' onclick='filtros(\"campo$this->contador\", \"65\"); this.select();'  type='text' id='formularioLitle' style='width:98%; text-align:center '>".
										"<div onclick='document.forms[\"frmbreg\"].elements[\"page\"].value=\"\";  document.forms[\"frmbreg\"].submit();' id='campo{$this->contador}Filtrar' class='filtrar'>Filtrar</div>".
										"<div onclick='document.forms[\"frmbreg\"].elements[\"$campo\"].value=\"\"; document.forms[\"frmbreg\"].submit();' id='campo{$this->contador}Limpar' class='limpar' >Limpar</div>".
									"</div>".
							  "</td>";	
			}elseif ($tipo == "data"){
				$this->filtros .=  "<td style='position:relative' id='titlelist' align='center' width='{$tamanho}px'>".
								   	"<div id='campo$this->contador' class='titleBlt'  style='position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; '>".	
										"$titulo".
										"<input autocomplete='off' onblur='setTimeout(function(){desFiltros(\"campo$this->contador\", \"30\")},200)' name='$campo' value='$valor' onclick='setTimeout(function(){filtros(\"campo$this->contador\", \"100\")},520); this.select();'  type='text' id='formularioLitle' style='width:98%; text-align:center' class='dataFormat'>".
										"<div id='campo{$this->contador}Data' style='display:none'>".
											"à".
											"<input autocomplete='off' onblur='setTimeout(function(){desFiltros(\"campo$this->contador\", \"30\")},200)' name='{$campo}2' value='{$valor2}' onfocus='setTimeout(function(){filtros(\"campo$this->contador\", \"100\")},520); this.select();'  type='text' id='formularioLitle' style='width:98%; text-align:center' class='dataFormat'>".
										"</div>".
										"<div onclick='document.forms[\"frmbreg\"].elements[\"page\"].value=\"\";  document.forms[\"frmbreg\"].submit();' id='campo{$this->contador}Filtrar' class='filtrar'>Filtrar</div>".
										"<div onclick='document.forms[\"frmbreg\"].elements[\"$campo\"].value=\"\"; document.forms[\"frmbreg\"].elements[\"{$campo}2\"].value=\"\"; document.forms[\"frmbreg\"].submit();' id='campo{$this->contador}Limpar' class='limpar' >Limpar</div>".
									"</div>".
								   "</td>";
			}elseif ($tipo == "valor"){
				$this->filtros .=  "<td style='position:relative' id='titlelist' align='center' width='{$tamanho}px'>".
								   	"<div id='campo$this->contador' class='titleBlt'  style='position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; '>".	
										"$titulo".
										"<input autocomplete='off' onblur='setTimeout(function(){desFiltros(\"campo$this->contador\", \"30\")},200)' name='$campo' value='$valor' onclick='setTimeout(function(){filtros(\"campo$this->contador\", \"100\")},520); this.select();'  type='text' id='formularioLitle' style='width:98%; text-align:center' class='moedaFormat'>".
										"<div id='campo{$this->contador}Data' style='display:none'>".
											"à".
											"<input autocomplete='off' onblur='setTimeout(function(){desFiltros(\"campo$this->contador\", \"30\")},200)' name='{$campo}2' value='{$valor2}' onfocus='setTimeout(function(){filtros(\"campo$this->contador\", \"100\")},520); this.select();'  type='text' id='formularioLitle' style='width:98%; text-align:center' class='moedaFormat'>".
										"</div>".
										"<div onclick='document.forms[\"frmbreg\"].elements[\"page\"].value=\"\";  document.forms[\"frmbreg\"].submit();' id='campo{$this->contador}Filtrar' class='filtrar'>Filtrar</div>".
										"<div onclick='document.forms[\"frmbreg\"].elements[\"$campo\"].value=\"\"; document.forms[\"frmbreg\"].elements[\"{$campo}2\"].value=\"\"; document.forms[\"frmbreg\"].submit();' id='campo{$this->contador}Limpar' class='limpar' >Limpar</div>".
									"</div>".
								   "</td>";
			}elseif($tipo == "select"){
				$this->filtros .=  "<td style='position:relative' align='center' width='{$tamanho}px'>".
									"<div id='campo$this->contador' class='titleBlt'  style='position:absolute; width:100%; height:30px; border-radius:0px 0px 2px 2px; left:0px; top:0px; '>".	
										"$titulo".
										"<select style='height:17px; font-size:11px; color:#666666; width:100%' name='$campo' onchange=\"document.forms['frmbreg'].elements['page'].value=''; document.forms['frmbreg'].submit()\">".
										"<option></option>".
										$BDFB->FBObjSelect($valor,"",$mascaraFiltro).
										"</select>".
										"<div onclick='document.forms[\"frmbreg\"].elements[\"page\"].value=\"\";  document.forms[\"frmbreg\"].submit();' id='campo{$this->contador}Filtrar' class='filtrar'>Filtrar</div>".
										"<div onclick='document.forms[\"frmbreg\"].elements[\"$campo\"].value=\"\"; document.forms[\"frmbreg\"].submit();' id='campo{$this->contador}Limpar' class='limpar' >Limpar</div>".
									"</div>".
							  "</td>";	
			}elseif ($tipo == "plus"){
				$this->filtros .=  "<td style='position:relative' align='center' width='{$tamanho}px'>".
							  	   "</td>";	
			}
			
			$this->campos[$this->contador] = array($campo,$tipo,$mascaraLinha,$align,$tamanho);		
			$this->contador++;
			$this->totalTamanho += $tamanho;

			if (($tipo != "botao")){
				$this->criaWhere($campo, $valor, $tipo,$valor2);
			}	
					
		}
		
		
		public  function criaLinhas($query){
			
			$BDFB = new FB();
			$BDFB->Conecta();
			
			mysql_query("SET NAMES 'utf8'");
			mysql_query('SET character_set_connection=utf8');
			mysql_query('SET character_set_client=utf8');
			mysql_query('SET character_set_results=utf8');
			
			$dados = $BDFB->FBSelect($query);
			$aux=1;
			
			while ($row = @mysql_fetch_array($dados)){
									
				if ($x==0){
					$color2 = "#".COR;
					$bgcolor = "#f7ecdc";
					$x++;
				}else {
					$color2 = "#".COR;
					$bgcolor = "#FFFFFF";
					$x--;
				}
				
				
				if (!empty($this->objeto)){
					$fnc = $this->funcObjeto;
					$this->objeto->$fnc($row[$this->campos[1][0]]);
				}
			
				$this->linhas .= "<tr class='more' id='more{$row[$this->campos[1][0]]}' onmouseover='this.style.backgroundColor=\"#01bf63\"' onmouseout='this.style.backgroundColor=\"$bgcolor\"'  bgcolor='$bgcolor' style='color:$color2; cursor:pointer;  font-size:11px; display:table-row;' >";
				
				foreach ($this->campos as $campo){
					
					if ($campo[1] == "codigo"){
						$this->linhas .=  "<td width='{$campo[4]}px' onclick='window.location.href = \"$this->path?COD={$row[$this->campos[1][0]]}\";' align='{$campo[3]}' >".str_pad($row[$campo[0]],5,0,STR_PAD_LEFT)."</td>";
					}elseif ($campo[1] == "texto" || $campo[1] == "select"){
						$this->linhas .=  "<td width='{$campo[4]}px' onclick='window.location.href = \"$this->path?COD={$row[$this->campos[1][0]]}\";' align='{$campo[3]}' >".(!empty($campo[2])?call_user_func($campo[2],$row[$campo[0]]):$row[$campo[0]])."</td>";
					}elseif ($campo[1] == "data"){
						$this->linhas .=  "<td width='{$campo[4]}px' onclick='window.location.href = \"$this->path?COD={$row[$this->campos[1][0]]}\";' align='{$campo[3]}' >".$BDFB->MostrarCampo("date",$row[$campo[0]])."</td>";
					}elseif ($campo[1] == "valor"){
						$this->linhas .=  "<td width='{$campo[4]}px' onclick='window.location.href = \"$this->path?COD={$row[$this->campos[1][0]]}\";' align='{$campo[3]}' >".FormatoMoeda($row[$campo[0]])."</td>";
					}elseif ($campo[1] == "objeto"){
						list($funcao, $parametro) = explode("|",$campo[2]);
						$this->linhas .=  "<td width='{$campo[4]}px' onclick='window.location.href = \"$this->path?COD={$row[$this->campos[1][0]]}\";' align='{$campo[3]}' >".$this->objeto->$funcao($parametro)."</td>";
					}elseif ($campo['' == "plus"]){
						$this->linhas .=  "<td id='boxAzulQuadrado' width='{$campo[4]}px'  align='{$campo[3]}' ><img onclick='openPlus(\"{$row[$this->campos[1][0]]}\")' id='img{$row[$this->campos[1][0]]}' style='width:15px' src='/sts/pcte-common/images/{$campo[2]}'></td>";
						$plus = 1;
					}
				}	
									
				$this->linhas .= "</tr>";
				$this->criaLinhasPlus($row[$this->campos[1][0]]);
				if ($plus == 1){
					$this->linhas .= "<tr  id='plus{$row[$this->campos[1][0]]}'  style='   display:none;' >";
					$this->linhas .= "<td width='100%' align='center' colspan='$this->contador' >";
					$this->linhas .= "<table width='100%'; height='100%'>";
					$this->linhas .= "<tr bgcolor='#b3b3b3'>";
					$this->linhas .= "<td colspan='$this->contadorPlus' align='center' style='font-size:16px; color:#FFF;'>$this->tituloPlus</td>";
					$this->linhas .= "</tr>";
					$this->linhas .= "<tr bgcolor='#b3b3b3' style='font-size:16px; color:#FFF;'>";
					$this->linhas .= $this->filtrosPLus;
					$this->linhas .= "</tr>";
					$this->linhas .= $this->linhasPLus;
					$this->linhas .= "</table>";
					$this->linhas .= "</td>";
					$this->linhas .= "</tr>";
				}
				
				$aux++;					 		 
			}
			
			if ($aux == 1){
				$this->linhas = "<tr>".
									"<td colspan='18' valign='middle' align='left' style='color:red; padding-left:100px'>".
										"Não foi encontrado registro com os filtros acima.".
									"</td>".
								"</tr>";
			}
			
			
			
		}
		
		public function plus($tituloPlus, $queryPlus, $groupByPlus = ""){
			$this->tituloPlus = $tituloPlus; 
			$this->queryPlus = $queryPlus; 
			$this->groupByPlus = $groupByPlus; 
		}
		
		public function criaColunaPlus($campo, $valor, $titulo, $tamanho, $tipo="texto", $mascaraFiltro="",$mascaraLinha="",$align="center",$valor2=""){
			$this->filtrosPLus .=  "<td style='position:relative; ' align='center' width='{$tamanho}px'>".
										"$titulo".
	                               "</td>";	
			$this->camposPlus[$this->contadorPlus] = array($campo,$tipo,$mascaraLinha,$align);		
			$this->contadorPlus++;
			$this->totalTamanhoPlus += $tamanho;
		}
		private  function criaLinhasPlus($id){
			$BDFB = new FB();
			$BDFB->Conecta();
			mysql_query("SET NAMES 'utf8'");
			mysql_query('SET character_set_connection=utf8');
			mysql_query('SET character_set_client=utf8');
			mysql_query('SET character_set_results=utf8');
			$id = abs($id);
			$query = $this->queryPlus." AND T1.{$this->campos[1][0]} = $id $this->groupByPlus "; 
			$dados = $BDFB->FBSelect($query);
			$aux=1;
			$this->linhasPLus = "";
			
			while ($row = @mysql_fetch_array($dados)){
				
				
				if (!empty($this->objetoPlus)){
					list($funcao, $parametro) = explode("|",$this->funcObjetoPlus);
					$this->objetoPlus->$funcao($row[$parametro]);
				}
				
				if ($x==0){
					$color2 = "#333333";
					$bgcolor = "#d9d9d9";
					$x++;
				}else {
					$color2 = "#333333";
					$bgcolor = "#e5e5e5";
					$x--;
				}
				$this->linhasPLus .= "<tr bgcolor='$bgcolor' style='color:$color2;   font-size:11px' >";
				foreach ($this->camposPlus as $campo){
					if ($campo[1] == "codigo"){
						$this->linhasPLus .=  "<td  align='{$campo[3]}' >".str_pad($row[$campo[0]],5,0,STR_PAD_LEFT)."</td>";
					}elseif ($campo[1] == "texto"){
						$this->linhasPLus .=  "<td  align='{$campo[3]}' >".(!empty($campo[2])?call_user_func($campo[2],$row[$campo[0]]):$row[$campo[0]])."</td>";
					}elseif ($campo[1] == "data"){
						$this->linhasPLus .=  "<td  align='{$campo[3]}' >".$BDFB->MostrarCampo("date",$row[$campo[0]])."</td>";
					}elseif ($campo[1] == "valor"){
						$this->linhasPLus .=  "<td  align='{$campo[3]}' >".FormatoMoeda($row[$campo[0]])."</td>";
					}elseif ($campo[1] == "objeto"){
						list($funcao, $parametro) = explode("|",$campo[2]);
						$this->linhasPLus .=  "<td  align='{$campo[3]}' >".$this->objetoPlus->$funcao($parametro)."</td>";
					}
				}	
				$this->linhasPLus .= "</tr>";
				$aux++;					 		 
			}
			
			//$this->linhasPLus = $this->camposPlus[1][0]."SSSS";
		}
		
		public function setObjeto($objeto,$funcao){
			$this->objeto = $objeto;
			$this->funcObjeto = $funcao;
		}
		
		public function setObjetoPlus($objetoPlus,$funcao){
			$this->objetoPlus = $objetoPlus;
			$this->funcObjetoPlus = $funcao;
		}
		
		
		public function getFiltros(){
			return $this->filtros;
		}
		public function getFiltrosPlus(){
			return $this->filtrosPLus;
		}
		
		public function getWhere(){
			return $this->where;
		}
		
		public function getTitulo(){
			return $this->titulo;
		}
		
		public function getPath(){
			return $this->path;
		}
		
		public function getLinhas(){
			return $this->linhas;
		}
		
		
		
		
	}





?>
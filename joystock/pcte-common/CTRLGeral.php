<?
	
	include_once("config.inc.php");
	include_once("CTRLFormatos.php");
	
	function statusMovimento(){
		$return = array(1=>'Pago',
						2=>'A Pagar',
						3=>'Pgto em atraso');
		return $return;
	}
	
	function quant_dias($data1, $data2){
  		return round(abs(strtotime($data1) - 
     	strtotime($data2)) / (24 * 60 * 60), 0); 
	}
	
	function tabelaPlano($idade){
		if ($idade < 19){
			return "P00_P18";
		}elseif ($idade > 18 && $idade < 24){
			return "P19_P23";
		}elseif ($idade > 23 && $idade < 29){
			return "P24_P28";
		}elseif ($idade > 28 && $idade < 34){
			return "P29_P33";
		}elseif ($idade > 33 && $idade < 39){
			return "P34_P38";
		}elseif ($idade > 38 && $idade < 44){
			return "P39_P43";
		}elseif ($idade > 43 && $idade < 49){
			return "P44_P48";
		}elseif ($idade > 48 && $idade < 54){
			return "P49_P53";
		}elseif ($idade > 53 && $idade < 59){
			return "P54_P58";
		}elseif ($idade > 58){
			return "PRT_M59";
		}else {
			return 0;
		}
		
	}
	
	function RemoveAcentos($Msg){
		
		
 		$a = array(utf8_decode("/[ÂÀÁÄÃ]/")=>"A",
   				   utf8_decode("/[âãàáä]/")=>"a",
	  			   utf8_decode("/[ÊÈÉË]/")=>"E",
				   utf8_decode("/[êèéë]/")=>"e",
				   utf8_decode("/[ÎÍÌÏ]/")=>"I",
				   utf8_decode("/[îíìï]/")=>"i",
				   utf8_decode("/[ÔÕÒÓÖ]/")=>"O",
				   utf8_decode("/[ôõòóö]/")=>"o",
				   utf8_decode("/[ÛÙÚÜ]/")=>"U",
			 	   utf8_decode("/[ûúùü]/")=>"u",
			 	   utf8_decode("/ç/")=>"c",
				   utf8_decode("/Ç/")=> "C");

     $Msg = utf8_decode($Msg);				   
	 return preg_replace(array_keys($a), array_values($a), $Msg);
 }
	
	
	function formatoNumero($x){
		$x = number_format($x,2,',','.');
		return $x;
	}
	
	function etc($str,$tam=28){
		if (strlen($str)>$tam)
			return substr($str,0,$tam)."...";
		else 				
			return $str;
	}
	
	function tipoProduto($x){
		$pdt = array(1=>'Peça',2=>'Serviço');
		return $pdt[$x];
	}
	
	function movimentacao($x){
		$pdt = array(1=>'Entrada',2=>'Devolução');
		return $pdt[$x];
	}
	
	function status_movimento($x){
		$sts = array(1=>'Fechado',2=>'Aberto');
		return $sts[$x];
	}
	
	
	function remove_matriz($indice,$nb_elements,$liste) {
        if ($indice > 0 && $indice < $nb_elements) {
            for ($i = $indice; $i != $nb_elements - 1; $i++) {
                $liste[$i] = $liste[$i +1];
            }
            array_pop($liste);
            $nb_elements = count($liste);
            print "$indice,$nb_elements,$liste<br>";
            return true;
        } else {
            return false;
        }
    }
    
    
    
    function array_trim($arr, $indice) {
        if(!isset($indice)) {
           $indice = count($arr)-1;
        }
        unset($arr[$indice]);
        array_shift($arr);
        return $arr;
	} 
	
	function IMP(){
	 	$arq = $_SERVER['DOCUMENT_ROOT']."/pcte-common/config.ini.php";
		$TImprimir = new IniFile($arq);
		$IMPRIMIR = $TImprimir->ReadSections("IMPRIMIR");
		for($i=1;$i<=count($IMPRIMIR);$i++){
			list($IMP[$i],,,,) = explode(":", $TImprimir->Read("IMPRIMIR","$i",""));
		}
		return $IMP;
	}
	
	function IDE_GOB(){
		$return = array(1=>'RNT', 2 => 'RNPT');
		return $return;
	}
	function cordao(){
		$return = array(1=>'Com 2 art�rias e 1 veia.', 2 => 'Com 1 art�ria e 1 veia');
		return $return;
	}
	
	function carater(){
		$return = array(1=>'Eletiva', 2 => 'Urg�ncia');
		return $return;
	}
	
	function parto(){
		$return = array(1=>'Ces�rea', 2 => 'Vaginal', 3 => 'F�rceps');
		return $return;
	}
	
	function grau(){
		$return = array(1=>'0', 2 => 'I', 3 => 'II');
		return $return;
	}
	
	function tonus(){
		$return = array(1=>'np', 2 => 'Preservado', 3 => 'N�o preservado');
		return $return;
	}
	
	function mov(){
		$return = array(1=>'np', 2 => 'Presente', 3 => 'Ausente');
		return $return;
	}
	
	function apresentacao(){
		$return = array(1=>'Cef�lica', 2 => 'P�lvica', 3 => 'Pod�lica', 4 => 'Acr�mica');
		return $return;
	}
	
	function perfil(){
		$return = array(1=>'np', 2 => '2', 3 => '4', 4 => '6', 5 =>'8');
		return $return;
	}
	function posicao(){
		$return = array(1=>'Dorso lateral � direita', 2 => 'Dorso lateral � esquerda', 3 => 'Dorso posterior', 4 => 'Dorso anterior');
		return $return;
	}
	
	function aleitamento(){
		$leite = array(1=>'Materno', 2 => 'Industrial');
		return $leite;
	}
	
	function escolaridade(){
		$escola = array(1=>'Ensino fundamental (1� Grau)', 2 => 'Ensino m�dio (2� Grau)',
						3 => 'T�cnico/ Profissionalizante', 4 => 'Superior incompleto',
						5 => 'Superior incompleto', 6 => 'Superior completo',
						7 => 'Doutorado', 8 => 'P�s-gradua��o/ Master/ MBA', 9 => 'Mestrado');
		return $escola;
	}
	
    function SelectMes(){
        $Mes = array(1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril', 5 => 'maio', 
                     6 => 'junho', 7 => 'julho', 8 => 'agosto', 9 => 'setembro', 10 => 'outubro', 
                     11 =>'novembro', 12 =>'dezembro');
        return $Mes;
    }
    
    function SelectDia(){
        $dia = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31);
        return $dia;
    }

    function SelectTMA(){
        $tma = array(1=>31, 2=>28, 3=>31, 4=>30, 5=>31, 6=>30, 7=>31, 8=>31, 9=>30, 10=>31, 11=>30, 12=>31);
        return $tma;
    }

    function SelectAno(){
        $ano = array(2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020);
        return $ano;
    }

	function DiaSemana(){
		$ds = array('domingo', 'segunda-feira', 'ter�a-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 's�bado');
		
		return $ds;
	}
    
	function MesAno(){
		$Mes = array(1 => 'janeiro', 2 => 'fevereiro', 3 => 'mar�o', 4 => 'abril', 5 => 'maio', 
                     6 => 'junho', 7 => 'julho', 8 => 'agosto', 9 => 'setembro', 10 => 'outubro', 
                     11 =>'novembro', 12 =>'dezembro');
		
		return $Mes;
	}
	
	function DataAtualCompleta(){
       $DiaSemana = array('domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado');
       $MesAno = array('janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro');
       $data = date('w-d-n-Y');
       list($semana, $dia, $mes, $ano) = split('[-]',$data);
    
       $result = $DiaSemana[$semana].", ".$dia." de ".$MesAno[$mes-1]." de ".$ano;
       
       return $result;
    }
       
    function VerifCPF($num){
       if(strlen($num) == 11)
       {
            $pos = 10;
            for($i=0;$i<9;$i++)
            {
               $N1 += $num[$i]*$pos;
               $pos--;
            }
        
            $N1 = 11 - $N1 % 11;
        
            if($N1>9)
                $N1=0;
        
            $pos = 11;
            for($i=0;$i<9;$i++)
            {
                $N2+= $num[$i]*$pos;
                $pos--;
            }
           
            $N2= 11 - ($N2+2*$N1) % 11;
        
                 
            if($N2>9)
                   $N2=0;
    
            if(($N1==$num[9])&($N2==$num[10]))
                   $R = $num;
            else
                   $R = 0;
       }else{
          $R = 0;
       }
    
       return $R;
    }
    
    function LerArquivo($Arquivo){
       $abrir = fopen($Arquivo, "a+");
       $arq = file($Arquivo);
       fclose($abrir);
       return $arq;
    }
    
    function GravarArquivo($Arquivo, $dados){
        $abrir = fopen($Arquivo, "a");
        $arq = fwrite($abrir, $dados);   
        fclose($abrir);
    }
    
    function VerifEMail($Mail){
       $mascara = ".+@.+\..+";
       if(!eregi($mascara, $Mail))
          $Mail = "erro:0:VerifEMail";
       
       return $Mail;   
    }
    
    function ValidaBrowser(){
            $browser = array("Galeon");
            for($i=0;$i<count($browser);$i++){
                $PROC = trim($browser[$i]);
                if(!empty($PROC)){
                    $result = substr(strstr($_SERVER[HTTP_USER_AGENT], $PROC),0,strlen($PROC));
                    break;
                }
            }
    
            if(empty($result))
               $result = 0;
            else
               $result = 1;
            return $result;
    }
    
    function SiglaEstado(){
       $sigla = array(1 => "AC",2 => "AL",3 => "AM",4 => "AP",5 => "BA",6 => "CE",7 => "DF",8 => "ES",
                      9 => "GO",10 => "MA",11 => "MG",12 => "MS",13 => "MT",14 => "PA",15 => "PB",16 => "PE",
                      17 => "PI",18 => "PR",19 => "RJ",20 => "RN",21 => "RO",22 => "RR",23 => "RS",24 => "SC",
                      25 => "SE",26 => "SP",27 => "TO");
       return $sigla;
    }
    
    function SiglaEstadoFull($estado){
       $sigla = array(1 => "ACRE",2 => "ALAGOAS",3 => "AMAZONAS",4 => "AMAPÁ",5 => "BAHIA",6 => "CEARÁ",7 => "DISTRITO FEDERAL",8 => "ESPÍRITO SANTO",
                      9 => "GOIÁS",10 => "MARANHÃO",11 => "MINAS GERAIS",12 => "MATO GROSSO DO SUL",13 => "MATO GROSSO",14 => "PARÁ",15 => "PARAÍBA",16 => "PERNAMBUCO",
                      17 => "PIAUÍ",18 => "PARANÁ",19 => "RIO DE JANEIRO",20 => "RIO GRANDE DO NORTE",21 => "RONDÔNIA",22 => "RORAIMA",23 => "RIO GRANDE DO SUL",24 => "SANTA CATARINA",
                      25 => "SERGIPE",26 => "SÃO PAULO",27 => "TOCANTINS");
                      
       if (!empty($estado)){
       	 return $sigla[$estado];
       }else {
       	 return $sigla;		
       }
       
    }
    
    function TipoJuros(){
       $result = array(1 => "Acréscimo",
                       2 => "Desconto");
       return $result;
    }
    
    function statusPaciente(){
       $status = array(1 => "Abandono",
                     2 => "Controle",
                     3 => "Em tratamento",
                     4 => "Faltoso",
                     5 => "Finalizado",
                     6 => "Paralizado",
                     7 => "Trasferido",
                     8 => utf8_decode("Não Autorizado"));
       return $status;
    }
    
    function Sexualidade(){
       $sexo = array(1 => "Masculino",
                     2 => "Feminino");
       return $sexo;
    }
    
    function StatusCompra(){
       $status = array(1 => "Solicita��o de Compra",
                       2 => "Compra autorizada",
                       3 => "Cota��o de Pre�o",
                       4 => "Compra Efetuada",
                       5 => "Estoque: Completo",
                       6 => "Estoque: Incompleto",
                       7 => "Cancelado",
                       8 => "Compra Direta");
       return $status;
    }
    
    
    function Medicamento(){
       $status = array(1 => "ampolas",
					   2 => "c�psulas",
					   3 => "comprimidos",
					   4 => "dr�geas",
					   5 => "frasco",
					   6 => "tubo",
					   7 => "uso cont�nuo");
       return $status;
    }    
    
    function StatusCotacao(){
       $status = array(1 => "Aberto",
                       2 => "Fechado",
                       3 => "Analisado");
       return $status;
    }
    
    
    function PedidoCancelado(){
       $status = array(1 => "Este produto n�o ser� mais comercializado",
                       2 => "Produto muito caro",
                       3 => "Produto n�o encontrado",
                       4 => "Produto n�o esta de acordo com o pedido",
                       5 => "Compra n�o autorizada");
       return $status;
    }
    
    function PlanoVenda(){
       $status = array(1 => "Varejo",
                       2 => "Atacado");
       return $status;
    }
    
    function Status(){
       $status = array(1 => "Produto em Estoque",
                       2 => "Fazer Pedido");
       return $status;
    }
    
    function StatusCIT(){
       $status = array(1 => "Livre",
                       2 => "Compras",
                       3 => "Caixa",
                       4 => "Empacotamento",
                       5 => "Cancelado",
                       6 =>	"Bloqueado");
       return $status;
    }    
    
    function StatusCheque(){
       $status = array(1 => "Caixa",
                       2 => "Financeiro",
                       3 => "Dep�sito",
                       4 => "Dep�sito Confirmado",
                       5 => "Ch Devolvido - M11",
                       6 => "Ch Devolvido - M12",
                       7 => "Pagamento",
                       8 => "Cancelado");
       return $status;
    }
    
    function Tipo(){
       $tipo = array(1 => "F�sica",
                     2 => "Jur�dica");
       return $tipo;
    }
    
    function TipoEstacao(){
       $tipo = array(1 => "Administra��o",
                     2 => "Terminal de Venda",
                     3 => "Terminal de Caixa");
       return $tipo;
    }    
    
    function TipoCheque(){
       $tipo = array(1 => "Pr�prio",
                     2 => "Terceiro");
       return $tipo;
    }
    
    function FormaPgto(){
       $tipo = array(1 => "A Vista",
                     2 => "A Prazo C/ Entrada",
                     3 => "A Prazo S/ Entrada");
       return $tipo;
    }
    
    function FinanceiroTipoPgto($tipoPgt=""){
       $tipo = array(1 => "Dinheiro",
                     2 => "Cheque",
                     3 => "Cartão", 
                     4 => "Carnê");
       if (!empty($tipoPgt)){              
       	return $tipo[$tipoPgt];
       }else {
       	return $tipo;
       }
    }
    
    function FinanceiroTipoPgtoCaixaVenda(){
       $tipo = array(1 => "Cheque",
                     2 => "Cr�dito Terceiro");
       return $tipo;
    }
    
    function TipoFinanceiro(){
       $tipo = array(1 => "Receita",
                     2 => "Despesas Administrativas",
                     3 => "Despesas Operacionais",
                     4 => "Conta de Movimentação",
                     5 => "Obriga��es");
       return $tipo;
    }
    
    function Tecnologia(){
       $tipo = array(1 => "TDMA",
                     2 => "GSM Edge");
       return $tipo;
    }
    
    function TipoCelular(){
       $tipo = array(1 => "Novo",
                     2 => "Usado");
       return $tipo;
    }
    
    function Natureza($tipoNat=""){
       $tipo = array(1 => "Entrada",
                     2 => "Saída");
       if (!empty($tipoNat)){
       	return $tipo[$tipoNat];	
       }else {
       	return $tipo;
       }
       
    }
    
    function Sessao(){
       $tipo = array(1 => "Artesanato",
                     2 => "Bicicleta",
                     3 => "Brinquedos",
                     4 => "Cd",
                     5 => "Celular",
                     6 => "Esporte",
                     7 => "Papelaria",
                     8 => "Pesca",
                     9 => "Presentes",
                     10 => "Utilidades",
                     11 => "Lanchonete");
       return $tipo;
    }
    
    function EstadoCivil(){
       $ecivil = array(1 => "Solteiro(a)", 
                             2 =>  "Casado(a)", 
                             3 =>  "Separado(a)",
                             4 =>  "Divorciado(a)",
                             5 =>  "Viúvo(a)");
    
       return $ecivil;
    }
    
    function GrauInstrucao(){
       $ginstrucao = array(1 => "N�o Alfabetizado",
                                      2 => "Ensino Fundamental incompleto",
                                      3 => "Ensino Fundamental completo",
                                      4 => "M�dio incompleto",
                                      5 => "M�dio completo",
                                      6 => "Superior incompleto",
                                      7 => "Superior completo",
                                      8 => "Especializa��o",
                                      9 => "Mestrado",
                                    10 => "Doutorado");
       return $ginstrucao;                                 
    }
    
    function TipoResidencia(){
       $tipo = array(1 => "Casa pr�pria",
                     2 => "Aluguel");
       return $tipo;
    }
    
    function TipoContato(){
       $tipo = array(1 => utf8_decode("Telefone Residêncial"),
                     2 => "Telefone Contato",
                     3 => "Telefone Comercial",
                     4 => "Celular",
                     5 => "Fax",
                     6 => "E-Mail",
                     7 => "Page",
                     8 => "Ramal",
                     9 => "Telefone Favor");
       return $tipo;
    }
    
    function VerifData($data){
        list($dia, $mes, $ano) = split('[/.-]', $data);
    
        if(($dia <= 31) && ($mes <= 12) && (strlen($ano)==4)){
           $dt = "$ano-$mes-$dia";
        }
        
        return $dt;
    }
    
    function MostraData($data){    
        list($mes, $dia, $ano, $hora, $min, $seg) = split('[/.-. .:]', $data);
    
        if(($dia <= 31) && ($mes <= 12) && (strlen($ano)==4)){
           $dt = "$dia/$mes/$ano";
        }
        
        return $dt;
    }
    
    function DataTexto($data){    
        list($ano, $mes, $dia) = split('[/.-]', $data);
    
        if(($dia <= 31) && ($mes <= 12) && (strlen($ano)==4)){
           $dt = "$dia/$mes/$ano";
        }
        
        return $dt;
    }
    
    function firstMonth($data){
    	list($ano, $mes, $dia) = split('[/.-]', $data);
    
        if(($dia <= 31) && ($mes <= 12) && (strlen($ano)==4)){
           $dt = "$mes/$dia/$ano";
        }
        
        return $dt;
    }
    
    function ultimoDia($mes){
    	list($ano, $mes, $dia) = split('[/.-]', date("Y-m-d", mktime(0, 0, 0, $mes, 0, '2000')));
    	return $dia;
    }
    
    function SelecionaData($data){
        list($dia, $mes, $ano) = split('[/.-]', $data);
    
        if(($dia <= 31) && ($mes <= 12) && (strlen($ano)==4)){
           $dt = "$mes-$dia-$ano";
        }
        
        return $dt;
    }
    
    function FormatoMoeda($Valor){
    	str_replace(".",",",$Valor);
        $result = number_format($Valor, 2, ',', '.');
        return $result;
    }
    
    function FormatoQuantidade($Valor){
    	str_replace(".",",",$Valor);
        $result = number_format($Valor, 4, ',', '.');
        return $result;
    }
    
    function TipoReferencia(){
       $tipo = array(1 => "Ruim",
                     2 => "Boa",
                     3 => "Útima");
       return $tipo;
    }
    
    function TipoAnamnese(){
       $tipo = array(1 => "Anamnese",
       				 2 => "Imagens",
                     3 => "Atestado",
                     4 => "Exame",
                     5 => "Consentimentos",
                     6 => "Receituário",
                     7 => "Resumo",
                     8 => "Vídeos");
                     
       return $tipo;
    }
    
    function QtdTipo(){
    	$restult = array(1 => " ",
    					 2 => "01",
    					 3 => "05",
    				     4 => "10",
    				     5 => "15",
    				     6 => "20",
    				     7 => "50"); 
    	return $restult;
    }
    
       
    function TempoAtendimento($tipo){
    	$tempoRetorno = array(1=>"00:05:00",
    						  2=>"00:10:00",
    						  3=>"00:15:00",
    						  4=>"00:20:00",
    						  5=>"00:25:00",
    						  6=>"00:30:00");
    						  
    	$tempoConsulta = array(1=>"00:05:00",
    						   2=>"00:10:00",
    						   3=>"00:15:00",
    						   4=>"00:20:00",
    						   5=>"00:30:00");
    						   
    	$horaLimite = array(1=>"17:00:00",
    						   2=>"18:00:00",
    						   3=>"18:30:00",
    						   4=>"19:00:00",
    						   5=>"19:30:00",
    						   6=>"20:00:00",
    						   7=>"20:30:00",
    						   8=>"21:00:00",
    						   9=>"21:30:00",
    						   10=>"22:00:00");
    						   
    	switch ($tipo){
	    	case 1:
	    		return $tempoRetorno;
	    	case 2:
	    		return $tempoConsulta;
	    	case 3:	
	    		return $horaLimite;
    	}	
    }
    
    function LimparRegistro(){    
        session_start();
        $_SESSION['COD_RTN'] = "";
        $_SESSION['COD_RTN_SEG'] = "";
        $_SESSION['COD_RTN_TER']="";
        $_SESSION['NME'] = "";
        $_SESSION['NME02'] = "";
        $_SESSION['NME03'] = "";
        $_SESSION['PFJ']="";
        $_SESSION['BTN']="";
        $_SESSION['frm']="";    
        $_SESSION['STS_CTO'] = "";
        $_SESSION['STS_CMP'] = "";
        $_SESSION['PRD_SEL'] = "";
        $_SESSION['query'] = "";
        $_SESSION['voltar'] = "";
        $_SESSION['lista_qry'] = "";
        $_SESSION['NME_REL'] = "";
        $_SESSION['CAL_DIA'] = ""; 
        $_SESSION['STATUS'] = ""; 
        $_SESSION['ESTADO'] = "";
        $_SESSION['site'] = ""; 
        $_SESSION['AUX'] = "";
        $_SESSION['DTA_INI'] = "";
        $_SESSION['DTA_FIM'] = "";
        $_SESSION['ETQ_PRD_ALT'] = "";
	    $_SESSION['ETQ_PRD_LAR'] = "";
	    $_SESSION['ETQ_PRD_QTD'] = "";
	    $_SESSION['VOLTAR'] = "";
	    $_SESSION['LEGENDA_GRAFICO'] = "";
	    $_SESSION['DADOS_GRAFICO'] = "";
	    $_SESSION['INI_Dia'] = "";
	    $_SESSION['FIM_Dia'] = "";
	    $_SESSION['INI_Mes'] = "";
	    $_SESSION['FIM_Mes'] = "";
	    $_SESSION['INI_Ano'] = "";
	    $_SESSION['FIM_Ano'] = "";
	    $_SESSION['PesqProduto'] = "";
	    $_SESSION['RDO'] = "";
	    $_SESSION['link_title'] = "";
    }
    
    function DadosPadrao(){
        $padrao = array("CEP" => "35588000",
                        "CDE" => "Arcos",
                        "PAS" => "Brasil",
                        "NTL" => "Arcos",
                        "NCL" => "Brasileira",
                        "OES" => "SSP"
                       );
        return $padrao;
    }
    
    function Dia(){
       for($i=1;$i<32;$i++){
            $dia[$i]=$i;
       }
       return $dia;
    }
    
    function Mes(){
        $MesAno = array(1=>'janeiro', 
                        2=>'fevereiro', 
                        3=>'mar�o', 
                        4=>'abril', 
                        5=>'maio', 
                        6=>'junho', 
                        7=>'julho', 
                        8=>'agosto', 
                        9=>'setembro', 
                        10=>'outubro', 
                        11=>'novembro', 
                        12=>'dezembro'
                       );
       return $MesAno;
    }
      
    
    function StatusCaixa(){
        $padrao = array(1 => "Iniciado",
                        2 => "Suspenso",
                        3 => "Aberto",
                        4 => "Fechado",
                        5 => "Cancelado"
                       );
        return $padrao;
    }
    
    function StatusVenda(){
        $padrao = array(1 => "Fechado",
                        2 => "Iniciado",
                        3 => "Cancelado"
                       );
        return $padrao;
    }
    
    
    function habilitado($tipo=""){
        $vr = array(1=>'Habilitado', 
                    2=>'Desabilitado');
       if (!empty($tipo)){
       		return $vr[$tipo];
       }else{
       		return $vr;
       }
       
    }
    
    
    function Logico($tipo=""){
        $vr = array(1=>'Não', 
                    2=>'Sim');
       if (!empty($tipo)){
       		return $vr[$tipo];
       }else{
       		return $vr;
       }
       
    }
    
    function contrato(){
        $vr = array(1=>'Sem Contrato', 
                    2=>'Com Contrato');
       return $vr;
    }
    
    function statusChamado(){
        $vr = array(1=>'Pendente', 
                    2=>'Encerrado');
       return $vr;
    }
    
    function LogicoContra(){
        $vr = array(1=>'Sim', 
                    2=>'Não');
       return $vr;
    }
    
    function LogicoCartao(){
        $vr = array(1=>'não', 
                    2=>'sim',
                    3=>'desativado');
       return $vr;
    }
    
    function RemessaCartao(){
        $vr = array(1=>'Solicitado', 
                    2=>'Enviado',
                    3=>'Loja',
                    4=>'Cliente');
       return $vr;
    }
    
    function STSCliente(){
        $vr = array(1=>'Bloqueado');
       return $vr;
    }
    
    function MotivoCartao(){
        $vr = array(1=>'Motivo 01', 
                    2=>'Motivo 02',
                    3=>'Motivo 03',
                    4=>'Motivo 04');
       return $vr;
    }   
    
    function tipoAtend(){
        $vr = array(1=>'Atendimento Online', 
                    2=>'Telefone',
                    3=>'Email',
                    4=>'Remoto',
                    5=>'Presencial');
       return $vr;
    } 
    
    
    
    function TipoCartao(){
        $vr = array(1=>'Funcion�rio', 
                    2=>'P.F.: Titular',
                    3=>'P.F.: Conjuge',
					4=>'C.F.: 1� Filho',
					5=>'C.F.: 2� Filho',
					6=>'C.F.: 3� Filho',
					7=>'C.F.: 4� Filho',
					8=>'C.F.: 5� Filho',
                    9=>'P.J.: Empresa');
       return $vr;
    }
    
    function Aprovacao(){
        $vr = array(1=>'N�o Consultado',
                    2=>'N�o Aprovado',
                    3=>'Aprovado');
       return $vr;
    }
    
    function Ano(){
       for($i=1;$i<33;$i++){
            $ano[$i]=1999+$i;
       }
       
       return $ano;
    }
    
    function PermissaoErro($msg, $retorno,$local){
        $TITULO = "Erro de Permiss�o";
        $ICONE = "erro";
        $LOCAL = $local;
        $CX_DIALOGO = "OK";
        $MSG = $msg;
        $RETORNO=$retorno;
        header("Location: /erros/index.php?local=$LOCAL&titulo=$TITULO&msg=$MSG&icone=$ICONE&cx_dialogo=$CX_DIALOGO&retorno=$RETORNO");
        exit();
    }
    
    function Permissao($TPO_ACS){
        $NME_FRM = $_SERVER["PHP_SELF"];
    	list(,,$FRM_PMS) = explode('/', $NME_FRM);
        switch($TPO_ACS){
            case "PMS_ACS": if($_SESSION["PMS_FNR"][$FRM_PMS][$TPO_ACS]!='2'){
                                $msg = $_SESSION['NME_FNR'].", voc� n�o tem autoriza��o para executar este formul�rio";
                                $retorno = "";
                                //$local = "/main/main.php";
                                $local = $NME_FRM;
                                PermissaoErro($msg, $retorno, $local);
                           }else
                              break;
            
            case "PMS_LTR": if($_SESSION["PMS_FNR"][$FRM_PMS][$TPO_ACS]!='2'){
                                $msg = $_SESSION['NME_FNR'].", voc� n�o tem autoriza��o para ler este registro";
                                $retorno = "_parent";
                                $local = $NME_FRM;
                                PermissaoErro($msg, $retorno, $local);
                           }else
                              break;
    
            case "PMS_PSQ": if($_SESSION["PMS_FNR"][$FRM_PMS][$TPO_ACS]!='2'){
                                $msg = $_SESSION['NME_FNR'].", sem autoriza��o para pesquisar";
                                $retorno = "";
                                $local = $NME_FRM;
                                PermissaoErro($msg, $retorno, $local);
                           }else
                              break;
    
            case "PMS_GRV": if($_SESSION["PMS_FNR"][$FRM_PMS][$TPO_ACS]!='2'){
                                $msg = $_SESSION['NME_FNR'].", voc� n�o tem autoriza��o para gravar este registro";
                                $retorno = "";
                                $local = $NME_FRM;
                                PermissaoErro($msg, $retorno, $local);
                           }else
                              break;
    
            case "PMS_EDO": if($_SESSION["PMS_FNR"][$FRM_PMS][$TPO_ACS]!='2'){
                                $msg = $_SESSION['NME_FNR'].", voc� n�o tem autoriza��o para alterar este registro";
                                $retorno = "";
                                $local = $NME_FRM;
                                PermissaoErro($msg, $retorno, $local);
                           }else
                              break;
    
            case "PMS_EXC": if($_SESSION["PMS_FNR"][$FRM_PMS][$TPO_ACS]!='2'){
                                $msg = $_SESSION['NME_FNR'].", voc� n�o tem autoriza��o para excluir este registro";
                                $retorno = "";
                                $local = $NME_FRM;
                                PermissaoErro($msg, $retorno, $local);
                           }else
                              break;
            }
    
    }
    
    
    function CustoProduto($VLR_PRD, $PCM_NTF, $VLR_ICM, $VLR_IPI, $OTS_DSP, $FRT_FCD, $QTD_CMP, $VLR_DCT){
        $custo = ($VLR_PRD + 
                 ( $VLR_PRD * $PCM_NTF / 100 * $VLR_ICM / 100) + 
                 ( $VLR_PRD * $PCM_NTF / 100 * $VLR_IPI / 100) + 
                 $OTS_DSP + ( $FRT_FCD/ $QTD_CMP)) - $VLR_DCT;
        return $custo;
    }
    
    function SaldoCaixa($COD_MFR){
        
        $TipoFinanceiro = TipoFinanceiro();
        
        $query_MFR = "SELECT T3.COD_TFR, T2.NTZ_PCT, T1.VLR_PGT, T1.DTA_PGT, (T1.VLR_MFR + T1.VLR_ASM) - T1.VLR_DCT AS TOTAL
                      FROM USDKMFR_TB T1
                      JOIN USDKPCT_TB T2 ON (T1.COD_PCT = T2.COD_PCT)
                      JOIN USDKTFR_TB T3 ON (T1.COD_TFR = T3.COD_TFR)
                      WHERE T1.COD_MFR = $COD_MFR";
        $dados_MFR = GeraDados($query_MFR);
        $row_MFR = ibase_fetch_object($dados_MFR);
    
        if($row_MFR->VLR_PGT==0.00){        
            $query_update_MFR = "UPDATE USDKMFR_TB SET VLR_PGT = $row_MFR->TOTAL WHERE COD_MFR = $COD_MFR";
            RegBDados($query_update_MFR);
            
            if(trim($row_MFR->NTZ_PCT) == "1")
            	$SINAL = '+';

            elseif($row_MFR->NTZ_PCT == "2")
                $SINAL = '-';
            
            $query_update_TFR = "UPDATE USDKTFR_TB SET SLD_TFR = (SELECT SLD_TFR $SINAL $row_MFR->TOTAL 
                		                                          FROM USDKTFR_TB
                		                                          WHERE COD_TFR = $row_MFR->COD_TFR)
                                 WHERE COD_TFR = $row_MFR->COD_TFR";
         	RegBDados($query_update_TFR);
         	
         	
         	$hoje = date("Y-m-d",time());
         	$query_saldo = "SELECT DTA_MVD, SLD_TFR 
							FROM USDKTFRSLD_TB
							WHERE COD_TFR = $row_MFR->COD_TFR AND
							      DTA_MVD = '$hoje'";

			$dados_saldo = GeraDados($query_saldo);
			
			$row_saldo = ibase_fetch_object($dados_saldo);

			if(empty($row_saldo->DTA_MVD)){
				$query_saldo_anterior = "SELECT FIRST 1 COD_USDKTFRSLD, SLD_TFR 
										 FROM USDKTFRSLD_TB
										 WHERE COD_TFR = $row_MFR->COD_TFR
										 ORDER BY DTA_MVD DESC";
				$dados_saldo_anterior = GeraDados($query_saldo_anterior);
				$row_saldo_anterior = ibase_fetch_object($dados_saldo_anterior);
				
				empty($row_saldo_anterior->SLD_TFR)?$SLD_TFR = 0.00:$SLD_TFR = $row_saldo_anterior->SLD_TFR;

				$query_insert_saldo = "INSERT INTO USDKTFRSLD_TB VALUES($row_MFR->COD_TFR, '$hoje', $SLD_TFR , NULL, NULL, $row_saldo_anterior->COD_USDKTFRSLD)";
				RegBDados($query_insert_saldo);
			}
			
			$query_update_saldo = "UPDATE USDKTFRSLD_TB SET SLD_TFR = (SELECT SLD_TFR $SINAL $row_MFR->TOTAL
						               								   FROM USDKTFRSLD_TB
						               								   WHERE COD_TFR = $row_MFR->COD_TFR AND DTA_MVD = '$hoje')
						           WHERE COD_TFR = $row_MFR->COD_TFR AND DTA_MVD = '$hoje'";
         	RegBDados($query_update_saldo);

            return 0;
        }else{
            return 1; //Erro: J� baixado        
        }
    }
    
    function FatorVencimento($datastr)
    {
       #Desmembrando a Data e ajustando a data ao formato: mm/dd/yyyy
       //$data = substr($datastr,3,2)."/".substr($datastr,0,2)."/".substr($datastr,6,4);
       list($dia, $mes, $ano) = explode("/",$datastr);
       $data = "$dia/$mes/$ano";
       
       $dt = strtotime($data);  
       $DtVencimento = getdate($dt);
          $dia = $DtVencimento['mday'];
          $vmes = $mes = $DtVencimento['mon'];
          $vano = $ano = $DtVencimento['year'];
    
       # Data Base #
       $diaB=7;
       $mesB=10;
       $anoB=1997;
       
       # Verificar Quantos anos Bissexto ocorreu #
       $QtBissexto=0;
       
       for($AnoInicial=$anoB;$AnoInicial<=$ano;$AnoInicial++)
       {
          if(($AnoInicial%4==0)||($AnoInicial%400==0))
             $QtBissexto++;
       }
       
    
       # Subtra��o das Data #
       
       if($dia<$diaB)     // Subra��o do Dia
       {
          $dia+=30;
          $mes-=1;
       }
       $dia-=$diaB;
               
    
       if($mes<$mesB)     // Achando o mes
       {
          $mes+=12;
          $ano-=1;
       }
       
       
       $ano-=$anoB;       // Subtra��o do Ano
    
       # Convers�o dos Meses em Dias  #
       
       $Meses[1] = 31;$Meses[2] = 31;$Meses[3] = 28;$Meses[4] = 31;$Meses[5] = 30;$Meses[6] = 31;
       $Meses[7] = 30;$Meses[8] = 31;$Meses[9] = 31;$Meses[10] = 30;$Meses[11] = 31;$Meses[12] = 30;
       
       
       $i = 1;
       for($cont=1;$cont<=$mes;$cont++)
       {
          $TotalMes += $Meses[$i];
          if($i==12)
             $i=1;
          else
             $i++;	  
       }
    
       // Convertendo o mes padr�o  
       $j=1;
       for($cont=1;$cont<=$mesB;$cont++)
       {
          $TotalMesBase += $Meses[$j];
          $j++;
       }   
       
       $SomaMes = $TotalMes - $TotalMesBase;
    
       # Convers�o dos Anos em Dias #
       
       $ano*=365;
       
       # Convers�o dos Anos em Dias #
    
       if (($vano%4==0)&&(($vmes==1)||($vmes==2)))
          $vmes=1;
       else 
          $vmes=0;
          
       $VrFinal = $dia + ($SomaMes - $vmes)+ $ano + $QtBissexto;
    
       return $VrFinal;
    }
    
    function QtDia($data){
        list($dia_v, $mes_v, $ano_v) = explode("/", $data);
		list($ano, $mes, $dia) = explode("/", date("Y/m/d", time()));
       
        intval($ano_v); intval($mes_v); intval($dia_v);
        intval($ano); intval($mes); intval($dia);
        
        
        $t_dia = ($dia_v - $dia) + (($mes_v - $mes) * 30) + (($ano_v - $ano) * 365);
        
        if($t_dia > 0)
            return "Falta: ".intval($t_dia);
        else
            return "Atraso: ".intval($t_dia*(-1));
    }
    
    function SubtraiData($QtDia){
    	list($d,$m,$a) = explode("-",date("d-m-Y",time()));
    	$data = date("d-m-Y", mktime(0, 0, 0, $m, $d-$QtDia ,$a));
    	return $data;
    }
    
    
    function SomaData($QtDia, $data){
		$tma = array(1=>31, 2=>28, 3=>31, 4=>30, 5=>31, 6=>30, 7=>31, 8=>31, 9=>30, 10=>31, 11=>30, 12=>31);
		list($dtDia, $dtMes, $dtAno) = explode("/", $data);
		$dt = $dtDia + $QtDia;
		$VrMes = intval($dtMes);

		while($dt>intval($tma[$VrMes])){
			$dt -= $tma[$VrMes];
			if($VrMes==12){
				$VrMes = 1;
				$dtAno++;
			}else
				$VrMes++;
		}
		
		return "$dt/$VrMes/$dtAno";
    }

    function GeraCartao($TPO_CCR, $COD_CLN, $NMR_VIA, $DtCadastro){
        if(empty($DtCadastro)){
        	$DtCadastro = date("d/m/Y", time());
        }

        $DtCadastro = FatorVencimento($DtCadastro);
        $DV = 0;
        
        $NCartao = strval($TPO_CCR.ZeroEsquerda($NMR_VIA, 2).ZeroEsquerda($COD_CLN, 7).ZeroEsquerda($DtCadastro, 5));

        for($i=0;$i<strlen($NCartao);$i++){
            if($i%2==0){
                $DV += $NCartao[$i]*2;
            }else{
                $DV += $NCartao[$i]*1;
            }
        }
        $DV = $DV%11;
        if($DV > 9)
            $DV = 0;
            
        $NCartao = ZeroEsquerda($DtCadastro, 5).$DV.$TPO_CCR.ZeroEsquerda($NMR_VIA, 2).ZeroEsquerda($COD_CLN, 7);
        
        return $NCartao;
    }
    
    function ValorVenda($COD_PRD, $COD_PLN, $VLR_PRD, $VLR_ICM, $VLR_IPI){
        
        // Inicializando Variaveis 
        $CSO = 0;
        $VLR_VND = 0;
        
        // Levantamento do custo 
        $CSO_PRD = $VLR_PRD * $VLR_ICM / 100;
        $CSO_PRD += $VLR_PRD * $VLR_IPI / 100;
        $CSO_PRD += $VLR_PRD;
        
        // Levantamento do Lucro 
        $query_Lucro = "SELECT T1.CMS_PRD, T1.MLC_PRD
                        FROM USDKPRDFPO_TB T1
                        JOIN USDKPRDPLN_TB T2 ON (T1.COD_USDKPRDPLN = T2.COD_USDKPRDPLN)
                        WHERE T1.COD_PRD = $COD_PRD AND
                              T2.COD_PLN = $COD_PLN";
        $dados_Lucro = GeraDados($query_Lucro);
        $rowLucro = ibase_fetch_object($dados_Lucro);
        $MLC_PRD = $CSO_PRD * $rowLucro->MLC_PRD / 100;
        
        // Levantamento dos Custos 
        $query_custos = "SELECT T2.VLR_CSO
                         FROM USDKPRDCSO_TB T1
                         JOIN USDKCSO_TB T2 ON (T1.COD_CSO = T2.COD_CSO)
                         JOIN USDKPRDPLN_TB T3 ON (T1.COD_USDKPRDPLN = T3.COD_USDKPRDPLN)
                         WHERE T1.COD_PRD = $COD_PRD AND
                               T3.COD_PLN = $COD_PLN";
        $dados_custos = GeraDados($query_custos);
            
        while($row_custos = ibase_fetch_object($dados_custos)){
            $CSO += $CSO_PRD * $row_custos->VLR_CSO / 100;
        }
        
        $result['custo'] = $CSO_PRD;
        $result['mlucro'] = $MLC_PRD;
        
        $CSO_PRD += $MLC_PRD + $CSO;
    
        // Levantando os Impostos q Inside sobre o pre�o de venda 
        $query_VrImpostos = "SELECT T2.VLR_IMP
                             FROM USDKPRDIMP_TB T1
                             JOIN USDKIMP_TB T2 ON (T1.COD_IMP = T2.COD_IMP)
                             JOIN USDKPRDPLN_TB T3 ON (T1.COD_USDKPRDPLN = T3.COD_USDKPRDPLN)
                             WHERE T2.EXC_USDKIMP IS NULL AND
                                   T1.COD_PRD = $COD_PRD AND
                                   T3.COD_PLN = $COD_PLN";
        $dados_VrImpostos = GeraDados($query_VrImpostos);
        
        while($row_VrImpostos = ibase_fetch_object($dados_VrImpostos)){
            $VLR_VND += $CSO_PRD * $row_VrImpostos->VLR_IMP/100;
        }
        $VLR_VND+=$CSO_PRD;    
        
        $VLR_VND += $VLR_VND * $rowLucro->CMS_PRD / 100;
        $result['venda'] = $VLR_VND;
        
        return $result;
    }
    
    function comDecimal($valor){
    	$parte1 = substr($valor,0,-2);
    	$parte2 = substr($valor,-2);
    	$valor = $parte1.".".$parte2;
    	$result = number_format($valor, 2, ',', '.');
        return $result;
    }
    
    function NomeBancos($x){
        $bnc = array(756 => 'bancoob', 104 => 'caixa');
        return $bnc[$x];
    }

    function NovaImage(){
        header("Content-type: image/png");
        $img = @imagecreate(500, 100) or die("A biblioteca GD image n�o pode ser aberta!");
        $fundo = imagecolorallocate($img, 0, 0, 0);
        $text_color = imagecolorallocate($img, 255, 255, 255);
        imagestring($img, 8, 100, 30, "SDK - Desenvolvimento de Software LTDA.", $text_color);
        imagepng($img);
        imagerotate($img, 180, -1);
        imagedestroy($img);
    }

	function LeCheque($num){
		$ch['banco'] = substr($num, 1,3);
		$ch['agencia'] = substr($num, 4,4);
		$ch['ncheque'] = substr($num, 13,6);
		$ch['nconta'] = substr($num, 22,10);
		return $ch; 
	}
	
	function Atendimento(){
        $result = array(1=>'Agendado', 
	                    2=>'Confirmado', //verde forte
	                    3=>'Encaminhado', //azul forte
						4=>'Atendendo', //vermelho
						5=>utf8_decode('Concluído'), //verde claro com fundo
						6=>'Faltou',
						7=>'Espera',
						8=>'Remanejar');
       return $result;
	}
	
	function TamFolha(){
        $result = array(1=>'0:0:0', 
	                    2=>'10:10:10',
	                    3=>'20:20:20',
						4=>'30:30:30');
       return $result;
	}
	
	function FRMNivel(){
        $result = array(1=>'Desenvolvimento', 
        				2=>'Suporte',
	                    3=>'Suporte T�cnico',
	                    4=>'Administrador',
						5=>'Usu�rio');
       return $result;
	}


		function extenso($valor = 0, $maiusculas = false) { 
		
		$singular = array("centavo", "real", "mil", "milh�o", "bilh�o", "trilh�o", "quatrilh�o"); 
		$plural = array("centavos", "reais", "mil", "milh�es", "bilh�es", "trilh�es", 
		"quatrilh�es"); 
		
		$c = array("", "cem", "duzentos", "trezentos", "quatrocentos", 
		"quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"); 
		$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", 
		"sessenta", "setenta", "oitenta", "noventa"); 
		$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", 
		"dezesseis", "dezesete", "dezoito", "dezenove"); 
		$u = array("", "um", "dois", "tr�s", "quatro", "cinco", "seis", 
		"sete", "oito", "nove"); 
		
		$z = 0; 
		$rt = "";
		
		$valor = number_format($valor, 2, ".", "."); 
		$inteiro = explode(".", $valor); 
		for($i=0;$i<count($inteiro);$i++) 
		for($ii=strlen($inteiro[$i]);$ii<3;$ii++) 
		$inteiro[$i] = "0".$inteiro[$i]; 
		
		$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2); 
		for ($i=0;$i<count($inteiro);$i++) { 
		$valor = $inteiro[$i]; 
		$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]]; 
		$rd = ($valor[1] < 2) ? "" : $d[$valor[1]]; 
		$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : ""; 
		
		$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && 
		$ru) ? " e " : "").$ru; 
		$t = count($inteiro)-1-$i; 
		$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : ""; 
		if ($valor == "000")$z++; elseif ($z > 0) $z--; 
		if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t]; 
		if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && 
		($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r; 
		} 
		
		if(!$maiusculas){ 
		return($rt ? $rt : "zero"); 
		} else { 
		
		if ($rt) $rt=ereg_replace(" E "," e ",ucwords($rt));
		return (($rt) ? ($rt) : "Zero"); 
		} 
		
		} 
		
		

	
?>

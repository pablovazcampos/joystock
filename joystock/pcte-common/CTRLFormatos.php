<?

	function ZeroEsquerda($num, $qtde=5){
	   for($i=strlen($num);$i<$qtde;$i++)
	      $zero .= '0';
	   $num = $zero.$num;
	   return $num;
	}
	
	function FormatCEP($num){
	    if(!empty($num)){
	        if(strlen($num)==8)
	         $num = substr($num, 0, 2).".".substr($num, 2, 3)."-".substr($num,5,3);
	        else
	          $num = "erro:0:FormatCEP";
	    }   
	    return $num;
	}
	
	function FormatCPF($cpf){
	  if(!empty($cpf)){
	        if(strlen($cpf)==11)
	         $cpf = substr($cpf,0,3).".".substr($cpf,3,3).".".substr($cpf,6,3)."-".substr($cpf,9,2);
	        elseif(strlen($cpf)==14)
	            $cpf = substr($cpf,0,2).".".substr($cpf,2,3).".".substr($cpf,5,3)."/".substr($cpf,8,4)."-".substr($cpf,12,2);
	        /*else
	         $cpf = "erro:0:VerifCPF";*/
	    }    
	    return $cpf;
	} 
	
	function FormatTel($num){
	   if(!empty($num)){
	        if(strlen($num)==10)
	          $num = "(".substr($num, 0, 2).") ".substr($num, 2, 4)."-".substr($num,6,4);
	        else
	          $num = "erro:FormatTel";
	    }
	    return $num;
	}
	
	function FormatCartao($num){
		if(!empty($num))
			if(strlen($num)==16)
				$result = substr($num, 0, 4)." ".substr($num, 4, 4)." ".substr($num, 8, 4)." ".substr($num, 12, 4);
		return $result;
	}
	
	function valorPorExtenso($valor=0) {
	$singular = array("centavo", "real", "mil", "milh�o", "bilh�o", "trilh�o", "quatrilh�o");
	$plural = array("centavos", "reais", "mil", "milh�es", "bilh�es", "trilh�es","quatrilh�es");

	$c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
	$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
	$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
	$u = array("", "um", "dois", "tr�s", "quatro", "cinco", "seis","sete", "oito", "nove");

	$z=0;

	$valor = number_format($valor, 2, ".", ".");
	$inteiro = explode(".", $valor);
	for($i=0;$i<count($inteiro);$i++)
		for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
			$inteiro[$i] = "0".$inteiro[$i];

	// $fim identifica onde que deve se dar jun��o de centenas por "e" ou por "," ;)
	$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
	for ($i=0;$i<count($inteiro);$i++) {
		$valor = $inteiro[$i];
		$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
		$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
		$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
	
		$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
		$t = count($inteiro)-1-$i;
		//$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
		if ($valor == "000")$z++; elseif ($z > 0) $z--;
		if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t]; 
		if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
	}

	return($rt ? $rt : "zero");
}

?>
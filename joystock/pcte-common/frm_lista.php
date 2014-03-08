<?

	session_start();

	

	include_once("config.inc.php");

	include_once(PATH_FUNCAO."/CTRLFBclass.php");

	include_once(PATH_FUNCAO."/CTRLGeral.php");

	

	/* Conex�o com o Banco de Dados */



	$BDFB = new FB();

	$BDFB->Conecta();

	

	mysql_query("SET NAMES 'utf8'");

	mysql_query('SET character_set_connection=utf8');

	mysql_query('SET character_set_client=utf8');

	mysql_query('SET character_set_results=utf8');

	

	unset($_GET);

	

?>

<html>

	<head>

		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<link rel="stylesheet" href="/joystock/pcte-common/estilos.css" type="text/css" media="all" />

	</head>

	<script language="JavaScript" type="text/javascript" src="/joystock/pcte-common/_js/CTRLGeral.js"></script>

	<body id="scroll" style="background-color: transparent;">

		<table style="text-align: left; width: 100%; height: 3%; font-size:100%;" border="0"  cellpadding="2" cellspacing="2">

		  <tbody >

		    <?

		    	if(!empty($_SESSION['query_lista'])){

		    		

			    	$query_lista = $_SESSION['query_lista'];

			    	$dados_lista = $BDFB->FBSelect($query_lista);


			    	while($row = mysql_fetch_array($dados_lista)){

			    			$FecharA = "";
			    			

			    			$linhas .="<tr bgcolor='#DFDFDF' onclick='{$_SESSION['click'][0]}({$row[$_SESSION['click'][1]]});' style='cursor:pointer' onmouseover=\"this.style.backgroundColor='#C9C9C9'\"; ". 

					                  "onmouseout=\"this.style.backgroundColor='#DFDFDF'\";>";


							for($i=0;$i<count($_SESSION['prop']);$i++){

							    $linhas .= "<td id=\"LST\" style=\"{$_SESSION['prop'][$i]['propriedade']}\" title=\"$DSC_CNT\">";

							    if(strlen($_SESSION['prop'][$i]['link'])==7){

							    		$linhas .= "<a href=\"{$_SESSION['NME_RTN']}?COD={$row[$_SESSION['prop'][$i]['link']]}\" target=\"".(empty($_SESSION['prop'][$i]['target'])?"_parent":$_SESSION['prop'][$i]['target'])."\">";

							    		$FecharA = "</a>";

							    }else{

							    	if(!empty($_SESSION['prop'][$i]['link'])){

							    		$linhas .= "<a href=\"{$_SESSION['NME_RTN']}?SUB={$row[$_SESSION['prop'][$i]['link']]}\" target=\"".(empty($target)?"_parent":$_SESSION['prop'][$i]['target'])."\">";

							    		$FecharA = "</a>";

							    	}

							    }

							    if(!empty($_SESSION['prop'][$i]['funcao'])){

							    	$linhas .= "{$_SESSION['prop'][$i]['funcao']($row[$_SESSION['prop'][$i]['campo']])}</a></td>";

							    		

							    }elseif(!empty($_SESSION['prop'][$i]['format'])){

							    	$linhas .= $_SESSION['prop'][$i]['format']($row[$_SESSION['prop'][$i]['campo']]);

							    }else{

							    	$linhas .= "{$row[$_SESSION['prop'][$i]['campo']]}$FecharA</td>";

							    }

							}

						$linhas .="</tr>";	

			    	  }

		    	}

		    	

		    	/* Mostrando o Resultado da Lista */

		    	print $linhas;

				

				

		   

		    	/* Limpando a Query, Propriedades e Formul�rio de Retorno */

		    	$_SESSION['query_lista'] 	= "";

		    	$_SESSION['prop'] 			= "";

		    	

		    	

		    	/* Fechando Conex�o com Banco de Dados */

		    	//$BDFB->Fechar();

		    ?>

		  </tbody>

		</table>

	</body>

</html>




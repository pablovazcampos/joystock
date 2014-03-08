<?
	session_start();
	include_once("IniFile.class.php");
	
	if (empty($_SESSION['CONFIGINI'])){
		print utf8_decode("Acesso inválido. Entre em contato com o nosso suporte: suporte@joysystems.com.br ou (37) 3222-5715");
		exit();
	}
	
	$arq = $_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/".$_SESSION['CONFIGINI'];

	if(file_exists($arq)){
		$ArqIni = new IniFile($arq);
	
		$PATH_FUNCAO = $ArqIni->Read('PATH','PATH_FUNCAO','');
		$PATH_LOGO = $ArqIni->Read('PATH','PATH_LOGO','');
		$PATH_PRINT = $ArqIni->Read('PATH','PATH_PRINT','');
		$HOST = $ArqIni->Read('BD','HOST','');
		$BANCO_DADOS = $ArqIni->Read('BD','BANCO_DADOS','');
		$BD_SYSTEMA = $ArqIni->Read('BD','BD_SYSTEMA','');
		$BD_BACKUP = $ArqIni->Read('BD','BD_BACKUP','');
		$USUARIO = $ArqIni->Read('BD','USUARIO','');
		$SENHA = $ArqIni->Read('BD','SENHA','');
		$CHARSET = $ArqIni->Read('BD','CHARSET','');
		$BUFFER = $ArqIni->Read('BD','BUFFER','');
		$DIALECT = $ArqIni->Read('BD','DIALECT','');
		$TMP_EXPIRA = $ArqIni->Read('TEMPO','TMP_EXPIRA','');
		$LIC_NAME = $ArqIni->Read('LIC','NAME','');
		$TOPCLIDP = $ArqIni->Read('BORDAS','TOPCLIDP','');
		$BLOCK = $ArqIni->Read('BORDAS','BLOCK','');
		$FONTNAME = $ArqIni->Read('BORDAS','FONTNAME','');
		$FONTREQUEST = $ArqIni->Read('BORDAS','FONTREQUEST','');
		$FONTEXAMINATION = $ArqIni->Read('BORDAS','FONTEXAMINATION','');
		$TOPEXAMINATION = $ArqIni->Read('BORDAS','TOPEXAMINATION','');
		$FONTJUST = $ArqIni->Read('BORDAS','FONTJUST','');
		$FONTEJUST = $ArqIni->Read('BORDAS','FONTEJUST','');
		$CDE = $ArqIni->Read('DADOSPADRAO','CDE','');
		$COR = $ArqIni->Read('DADOSPADRAO','COR','');

		$ArqIni->Clear();
	}else 
		print "Erro";
	
	define('TRANS_READ', IBASE_COMMITTED | IBASE_NOWAIT | IBASE_READ);
	define('TRANS_WRITE', IBASE_COMMITTED | IBASE_NOWAIT | IBASE_WRITE);
	
	/* Defini��es dos PATH's */
	define("PATH_FUNCAO",$PATH_FUNCAO);
	define("PATH_LOGO",$PATH_LOGO);
	define("PATH_PRINT",$PATH_PRINT);
	
	/* Defini��es do Banco de Dados */
	define("HOST", $HOST);
	define("BANCO_DADOS", $BANCO_DADOS);
	define("BD_SYSTEMA", $BD_SYSTEMA);
	define("BD_BACKUP", $BD_BACKUP);
	define("USUARIO", $USUARIO);
	define("SENHA", $SENHA);
	define("CHARSET", $CHARSET);
	define("BUFFER", $BUFFER);
	define("DIALECT", $DIALECT);
	define("LIC_NAME", $LIC_NAME);
	define("TOPCLIDP", $TOPCLIDP);
	define("BLOCK", $BLOCK);
	define("FONTNAME", $FONTNAME);
	define("FONTREQUEST", $FONTREQUEST);
	define("FONTEXAMINATION", $FONTEXAMINATION);
	define("TOPEXAMINATION", $TOPEXAMINATION);
	define("FONTJUST", $FONTJUST);
	define("FONTEJUST", $FONTEJUST);
	define("CDE", $CDE);
	define("COR", $COR);
	
	/* Defini��o do Tempo de expira��o */
	define("TMP_EXPIRA",$TMP_EXPIRA);
	
	/* Defini��o dos Stylos de Advert�ncias */
	define("STYLE_RED", "<span style=\"font-family: Tahoma;color: red;font-weight: bold;text-align: right;\">");
	define("STYLE_BLUE","<span style=\"font-family: Tahoma;color: blue;font-weight: bold;text-align: right;\">");
	define("STYLE_AZULESCURO","<span style=\"font-family: Tahoma;color: #333399;font-weight: bold;text-align: right;\">");
	define("STYLE_EXCLUIDO", "<span style=\"text-decoration: line-through;\">");
	
	
	/*include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLCodeSession.class.php");
	$CodeSession = new CodeSession();
	$BDFB = new FB();
	$BDFB->Conecta();
	$query = "SELECT * FROM USDKPRM_TB WHERE COD_GRP = (SELECT COD_GRP FROM USDKUSRGRP_TB WHERE COD_USR = {$CodeSession->decode('COD_USR')})";
	$dados = $BDFB->FBSelect($query);
	$row = @ibase_fetch_object($dados);
	define("PRM_ANO",$row->PRM_ANO);
	define("PRM_PSQ",$row->PRM_PSQ);
	define("PRM_CBH",$row->PRM_CBH);
	define("PRM_VDO",$row->PRM_VDO);
	define("PRM_IMG",$row->PRM_IMG);
	define("PRM_CID",$row->PRM_CID);
	define("PRM_PRT",$row->PRM_PRT);
	define("PRM_EXC",$row->PRM_EXC);
	define("PRM_HST",$row->PRM_HST);
	define("PRM_ANM",$row->PRM_ANM);
	define("PRM_EXM",$row->PRM_EXM);
	define("PRM_ATT",$row->PRM_ATT);
	define("PRM_RCT",$row->PRM_RCT);
	define("PRM_CST",$row->PRM_CST);
	define("PRM_RSM",$row->PRM_RSM);
	define("PRM_DAD",$row->PRM_DAD);
	define("PRM_NME",$row->PRM_NME);
	define("PRM_VLR",$row->PRM_VLR);
	define("PRM_VLA",$row->PRM_VLA);
	define("PRM_DNS",$row->PRM_DNS);
	*/
	
	
?>

<?php 
	ob_start();
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	
	if ($_GET['new'] == 1){
		$_SESSION['COD'] = "";
		$_SESSION['msg'] = "";
		$_SESSION['CLIENTE'] = "";
		$_SESSION['STS_BREG'] = 0;
		$_SESSION['suporte'] = '';
		
	}
	
	$KoolControlsFolder = "../pcte-common/KoolPHPSuite/koolControls";
		
	$example = array();
	if (date("s") == "00" || date("s") == "15" || date("s") == "30" || date("s") == "45" || date("s") == "14" || date("s") == "29" || date("s") == "44" || date("s") == "59"){
		sleep(2);
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="/joystock/pcte-common/e1024.css" type="text/css" media="all" />	
<script type="text/javascript" src="/joystock/pcte-common/CTRLGeral.js"></script>
<script type="text/javascript" src="/joystock/pcte-common/_js/rc_autocompleter.js"></script>
<script type="text/javascript" src="/joystock/pcte-common/_js/jquery.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body  style="overflow:auto">
		<? include("permissoes.php");?>
                        
</body>
</html>
<?php 
$all_html = ob_get_contents();
ob_end_clean();
$example_title.= " - ".$example["title"];
$all_html = str_replace("{example_title}",$example_title,$all_html);
echo $all_html;	

?>
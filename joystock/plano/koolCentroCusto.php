<?php 
	ob_start();
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT']."/joystock/pcte-common/config.inc.php");
	include_once(PATH_FUNCAO."/CTRLFBclass.php");
	include_once(PATH_FUNCAO."/CTRLGeral.php");
	
	$KoolControlsFolder = "../pcte-common/KoolPHPSuite/koolControls";
		
	$example = array();
	if (date("s") == "00" || date("s") == "15" || date("s") == "30" || date("s") == "45" || date("s") == "14" || date("s") == "29" || date("s") == "44" || date("s") == "59"){
		sleep(2);
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<script type="text/javascript" src="/joystock/pcte-common/CTRLGeral.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/jquery.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.maskedinput.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.maskMoney.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/com.jquery.validation.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/br.gov.dataprev.jquery.maskedinput.patterns.js"></script>
		
		<script type="text/javascript" src="/joystock/pcte-common/_js/corner.js"></script>
		<script type="text/javascript" src="/joystock/pcte-common/_js/rc_autocompleter.js"></script>
	    <link rel="stylesheet" href="/joystock/pcte-common/estilos.css" type="text/css" media="all" />
	    <link rel="stylesheet" href="/joystock/pcte-common/e1024.css" type="text/css" media="all" />
	</head>
	
<body  style="overflow:auto">
		<? include("centroCusto.php");?>
                        
</body>
</html>
<?php 
$all_html = ob_get_contents();
ob_end_clean();
$example_title.= " - ".$example["title"];
$all_html = str_replace("{example_title}",$example_title,$all_html);
echo $all_html;	

?>
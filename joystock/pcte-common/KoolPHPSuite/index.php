<?php 
ob_start();
session_start();
	$KoolControlsFolder = "../pcte-common/KoolPHPSuite/koolControls";
		
	$example 			= array();
	if (date("s") == "00" || date("s") == "59" || date("s") == "58" || date("s") == "15" || date("s") == "14" || date("s") == "30" || date("s") == "29" || date("s") == "45" || date("s") == "44" || date("s") == "46"){
		sleep(4);
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{example_title}</title>

</head>
<body>
		<?php include("menu.php");?>
                        
</body>
</html>
<?php 
$all_html = ob_get_contents();
ob_end_clean();
$example_title.= " - ".$example["title"];
$all_html = str_replace("{example_title}",$example_title,$all_html);
echo $all_html;	
	
	

?>
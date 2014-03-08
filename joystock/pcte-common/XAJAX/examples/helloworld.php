<?php

require ('../xajax_core/xajax.inc.php');
$xajax = new xajax();


$xajax->configure('javascript URI', '../');


function helloWorld($isCaps)
{
	if ($isCaps)
		$text = 'HELLO WORLD!';
	else
		$text = 'Hello World!';
		
	$objResponse = new xajaxResponse();
	$objResponse->assign('div1', 'innerHTML', $text);
	
	return $objResponse;
}


function setColor($sColor)
{
	$objResponse = new xajaxResponse();
	$objResponse->assign('div1', 'style.color', $sColor);
	
	return $objResponse;
}


$reqHelloWorldMixed =& $xajax->registerFunction('helloWorld');
$reqHelloWorldMixed->setParameter(0, XAJAX_JS_VALUE, 0);

$reqHelloWorldAllCaps =& $xajax->registerFunction('helloWorld');
$reqHelloWorldAllCaps->setParameter(0, XAJAX_JS_VALUE, 1);

$reqSetColor =& $xajax->registerFunction('setColor');
$reqSetColor->setParameter(0, XAJAX_INPUT_VALUE, 'colorselect');

$xajax->processRequest();

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>xajax example</title>
<?php $xajax->printJavascript(); ?>
	<script type='text/javascript'>
		/* <![CDATA[ */
		window.onload = function() {
			// call the helloWorld function to populate the div on load
			<?php $reqHelloWorldMixed->printScript(); ?>;
			// call the setColor function on load
			<?php $reqSetColor->printScript(); ?>;
		}
		/* ]]> */
	</script>
</head>
<body style="text-align:center;">
	<div id="div1">&#160;</div>
	<br/>
	
	<button onclick='<?php $reqHelloWorldMixed->printScript(); ?>' >Click Me</button>
	<button onclick='<?php $reqHelloWorldAllCaps->printScript(); ?>' >CLICK ME</button>
	<select id="colorselect" name="colorselect"
		onchange='<?php $reqSetColor->printScript(); ?>;'>
		<option value="black" selected="selected">Black</option>
		<option value="red">Red</option>
		<option value="green">Green</option>
		<option value="blue">Blue</option>
	</select>
</body>
</html>
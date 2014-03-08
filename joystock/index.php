<?
	session_start();
	if ($_GET['check'] == "valido"){
		header("Location: login.php");
		exit();	
	}
	
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" href="/joystock/pcte-common/e1024.css" type="text/css" media="all" />
		<title>JOYSTOCK</title>
		<script>
			function click() {
				if (event.button==2||event.button==3) {
					oncontextmenu='return false';
				}
			}
			document.onmousedown=click
			document.oncontextmenu = new Function("return false;")
		</script>
		<script language="JavaScript1.2">
			window.moveTo(0,0);
			if (document.all) {
			top.window.resizeTo(screen.availWidth,screen.avail Height);
			}
			else if (document.layers||document.getElementById) {
			if (top.window.outerHeight<screen.availHeight||top.wi ndow.outerWidth<screen.availWidth){
			top.window.outerHeight = screen.availHeight;
			top.window.outerWidth = screen.availWidth;
			}
			}
		</script>
	</head>
	<body id="FND"  oncontextmenu='return false' style="text-align: center; " onload="document.forms['FrmLogin'].elements['usuario'].focus()"; style="overflow-x: hidden; overflow-y: hidden;">
		<center>
			<div id="mlogin2" align="center" style="margin-top:10%; padding-top:20px; height:200px; width:550px;  z-index:99">
				<label style="color:red">JOYSTOCK</label>
				<br><br><br>
				<label style="color:#2c7dbe">Faça o download do arquivo de acesso ao sistema.</label>
				<br><br><br>
				<a href="/joystock/client/joystock.exe">
					<input style="width:100px; height:30px" type="submit" id="box1" value="Download">
				</a>
			</div>
		</center>
	</body> 
</html>
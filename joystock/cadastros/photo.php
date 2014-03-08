<?

	$cod = $_GET['COD'];
	
	
	if ($_GET['DEL'] == 1){
		
		@unlink($_SERVER['DOCUMENT_ROOT']."/joystock/images/$cod.jpg");
		@unlink($_SERVER['DOCUMENT_ROOT']."/joystock/images/$cod.gif");
		@unlink($_SERVER['DOCUMENT_ROOT']."/joystock/images/$cod.png");
		@unlink($_SERVER['DOCUMENT_ROOT']."/joystock/images/$cod.jpeg");
	}

	if (isset($_FILES[ARQ_DCM])) {
		if($_FILES[ARQ_DCM][size] > 5242880) { 
			 $_SESSION['msg'] =  "O tamanho do arquivo é maior que o limite permitido.";
			 $corMsg = "red";
		}elseif (is_file($_FILES[ARQ_DCM][tmp_name])) {
				$arquivo = $_FILES[ARQ_DCM][tmp_name];
				//$caminho="c:/develop/birj/anexos/";
				$caminho=$_SERVER['DOCUMENT_ROOT']."/joystock/images/";
				/* Defina aqui o tipo de arquivo suportado */
				if (eregi("(.JPG|.GIF|.PNG|.JPEG)$", strtoupper($_FILES[ARQ_DCM][name]))) {
					if (file_exists($_SERVER['DOCUMENT_ROOT']."/joystock/images/$cod.jpg") || file_exists($_SERVER['DOCUMENT_ROOT']."/joystock/images/$cod.gif") || file_exists($_SERVER['DOCUMENT_ROOT']."/joystock/images/$cod.png") || file_exists($_SERVER['DOCUMENT_ROOT']."/joystock/images/$cod.jpeg")){
						unlink($_SERVER['DOCUMENT_ROOT']."/joystock/images/$cod.jpg");
					}
		    		$caminho=$caminho.abs($cod).".jpg";
		    		move_uploaded_file($arquivo,$caminho) or
					die("<p>Erro durante a manipula&ccedil;&atilde;o do arquivo '$arquivo'</p>".'<p><a href="'.$_SERVER["PHP_SELF"].'">Voltar</a></p>');
				} else {
					$msg = "Arquivo Inválido";
				}
		}else {
			$msg =  "Tamanho excedido.";
		}
		
		$onload = "setTimeout(function(){submit()},200);";

	}



?>
<html>
	<head>
		 <link rel="stylesheet" href="/joystock/pcte-common/e1024.css" type="text/css" media="all" />
		 <script type="text/javascript" src="/joystock/pcte-common/_js/rc_autocompleter.js"></script>
	</head>
	<body onload="<?=$onload?>">
		<form action="photo.php?COD=<?=$cod?>" method="post" enctype="multipart/form-data">
			<div style='position:relative; float:left; width:132px; height:174px;  padding:0 5px 0px 0px;'>
				<div style="position:absolute; left:5px;">
					<input onchange="submit();" id="photo" style="width:1px;  border-radius:3px;" onkeydown="return handleEnter(this, event);" type="file" size="40px" name="ARQ_DCM">
				</div>
					
					<?if (file_exists($_SERVER['DOCUMENT_ROOT']."/joystock/images/$cod.jpg") || file_exists($_SERVER['DOCUMENT_ROOT']."/joystock/images/$cod.gif") || file_exists($_SERVER['DOCUMENT_ROOT']."/joystock/images/$cod.png") || file_exists($_SERVER['DOCUMENT_ROOT']."/joystock/images/$cod.jpeg")):?>			
						<div onmouseover="document.getElementById('exc').style.visibility='visible'" id="capaphoto2" onclick="dispatch(document.getElementById('photo'));" style="position:absolute; cursor:pointer; width:-webkit-calc(100% - 5px); border-radius:3px;  height:100%">
							<label style="color:red"><?=$msg?></label>
							<img id="pho" style="max-width:132px; border-radius:5px; height:174px" src="/joystock/images/<?=$cod?>.jpg">
						</div>
						<div onclick="window.location.href = 'photo.php?COD=<?=$cod?>&DEL=1';" id="exc" title="Excluir Foto" style="position:absolute; visibility:hidden; cursor:pointer; top:3px; right:8px"></div>
							
					<?else :?>	
						<div id="capaphoto" onclick="dispatch(document.getElementById('photo'));" style="position:absolute; cursor:pointer; width:-webkit-calc(100% - 5px); border-radius:3px;  height:100%">
							<label style="color:red"><?=$msg?></label>
						</div>
					<?endif;?>
					
					
				
			</div>
		</form>
	</body>	
</html>
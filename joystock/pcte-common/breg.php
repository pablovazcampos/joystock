<?





	class Breg{

		

		private $form;

		private $BREG;

		

		public function Breg($BREG,$action,$target="",$method="POST"){

			$this->BREG = $BREG;

			$this->form =  "<form enctype=\"multipart/form-data\" name=\"frmbreg\" target=\"".$target."\" action=\"".$action."\" method=\"".$method."\" >";

						

		}

				

		public function defineBreg($novo="",$salvar="",$editar="",$cancelar="",$pesquisar="",$excluir="",$ficha="", $fim="", $formulario=true){

            $widht = (!empty($novo)?1:0)+

            		 (!empty($salvar)?1:0)+

            		 (!empty($cancelar)?1:0)+

            		 (!empty($pesquisar)?1:0)+

            		 (!empty($ficha)?1:0)+

            		 (!empty($excluir)?1:0)+

            		 (!empty($editar)?1:0);

            $widht = $widht*59;

            if ($formulario){
            	$formulario = $this->form;
            }else {
            	$formulario='';
            }			

			$table ="<div id=\"BRegistro\" style=\"float:right; width:0px;  margin-right:".$widht."px; *margin-right:0%;  margin-top:2.5%; height:8.5%;  \">"	 

					."<table  style=\"text-align:right;  height: 12%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">"

					."<tr>"

						.$this->BREG['inicio']

						.$this->form

						.$this->BREG[$novo]

						.$this->BREG[$salvar]

						.$this->BREG[$editar]

						.$this->BREG[$cancelar]

						.$this->BREG[$pesquisar]

						.$this->BREG[$ficha]

						.$this->BREG[$excluir]

						.$this->BREG[$fim]

					."</tr>"

					."</table>"

					."</div>";

		print $table;

		}

		

		function bregFechar($action,$method="POST",$target="",$inicio="",$CLI_AGT=""){

			$div = "<div id=\"BRegistro\" style=\"float:right;  text-align: left; width:0px; margin-top:2.5%; height:8.5%; margin-right:25px; z-index:1 \">"

				   ."<table  style=\"text-align:left;  margin-top:2%; height: 12%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">"

				      ."<tr>"	

				       ."<form name=\"frmFechar\" action=\"".$action."\" method=\"".$method."\" target=\"".$target."\" >"

				       ."<input type=\"hidden\" name=\"CLI_AGT\" value=\"$CLI_AGT\">"

				         .$this->BREG[$inicio]

				         .$this->BREG['fechar']

				         .$this->BREG['fim']

				       ."</form>"

				     ."</tr>"

					."</table>"    

				   ."</div>";

			       

			 print $div;      

		}



		

		function bregImprimir($action,$target="",$CloseImpressao,$telaImpressao,$inicio="",$imprimir="imprimir",$method="POST", $paraEnvelope=""){

			$_SESSION['close'] =$CloseImpressao;

			$div = "<div id=\"BRegistro\" style=\"float:right; width:10%;  margin-top:2.5%; height:8.5%;\">"

				   ."<table style=\"text-align: left; *margin-left:43%; *margin-top:2%; width: 50%;  height: 12%;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">"

				      ."<tr>"	


				         ."<input type=\"hidden\" name=\"paraEnvelope\" value=\"$paraEnvelope\">"

				         .$this->BREG[$inicio]

				         .$this->BREG[$imprimir]
				         
				         .$this->BREG['fim']


				     ."</tr>"

					."</table>"    

				   ."</div>";

			       

			 print $div;      

		}

	}

?>
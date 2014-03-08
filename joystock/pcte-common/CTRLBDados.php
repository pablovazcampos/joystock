<?
	function BarraRegistro(){
   		//$evento = "onmouseover=\"this.style.border='outset 1px #765'\"; onmouseout=\"this.style.border='none'\"\"";
		$BREG[0] = array("inicio" => "<td> <input type=\"image\" style=\"height:45px; border:none\" name=\"breg\" value=\"inicio\" src=\"/joystock/pcte-common/images/inicio.png\"></td>", 
						 "novo" => "<td><input id=\"novo\"  name=\"breg\" type=\"submit\"  value=\"novo\"></td>",
	                     "salvar" => "<td><input id=\"salvarDisable\"  disabled style=\"text-align: center; \" name=\"breg\" type=\"submit\"  value=\"salvar\" ></td>",
	                     "salvar02" => "<td><input id=\"salvar02\"  name=\"breg\" type=\"submit\"  value=\"salvar\"></td>",
	                     "editar" => "<td><input id=\"editar\"  name=\"breg\" type=\"submit\"  value=\"editar\"></td>",
	                     "cancelar" => "<td><input id=\"cancelarDisable\"  disabled style=\"text-align: center; \"  name=\"breg\" type=\"submit\"  value=\"cancelar\"></td>",
	                     "pesquisar" => "<td><input id=\"pesquisar\"  name=\"breg\" type=\"submit\"  value=\"pesquisar\"></td>",
	                     "excluir" => "<td><input id=\"excluir\"  name=\"breg\" type=\"submit\"  value=\"excluir\"></td>",
	                     "imprimir" => "<td><input id=\"imprimir\"  name=\"breg\" type=\"submit\"  value=\"imprimir\"></td>",
	                     "envelope" => "<td><input id=\"envelope\"  name=\"breg\" type=\"submit\"  value=\"envelope\"></td>",
	                     "suspender" => "<td><input id=\"suspender\"  name=\"breg\" type=\"submit\"  value=\"suspender\"></td>",
	                     "fechar" => "<td><input id=\"fechar\"  name=\"breg\" type=\"submit\"  value=\"fechar\"></td>",
	                     "fechar_min" => "<td><input id=\"fechar_min\"  name=\"breg\" type=\"submit\"  value=\"fechar\"></td>",
	                     "fim" => "<td> <input type=\"image\" style=\"height:45px; border:none\" name=\"breg\" src=\"/joystock/pcte-common/images/fim.png\"></td>");
    
		$BREG[1] = array("inicio" => "<td> <input type=\"image\" style=\"height:45px; border:none\" name=\"breg\" value=\"inicio\" src=\"/joystock/pcte-common/images/inicio.png\"></td>", 
						 "novo" => "<td><input id=\"novoDisable\"  disabled style=\"text-align: center; \"  name=\"breg\" type=\"submit\"  value=\"novo\"></td>",
		                 "salvar" => "<td><input id=\"salvar\"  name=\"breg\" type=\"submit\"  value=\"salvar\"></td>",
		                 "editar" => "<td><input id=\"editarDisable\"  disabled style=\"text-align: center; \"  name=\"breg\" type=\"submit\"  value=\"editar\"></td>",
		                 "cancelar" => "<td><input id=\"cancelar\"  name=\"breg\" type=\"submit\"  value=\"cancelar\"></td>", 
		                 "pesquisar" => "<td><input id=\"pesquisarDisable\"  disabled style=\"text-align: center; \"  name=\"breg\" type=\"submit\"  value=\"pesquisar\"></td>",
		                 "excluir" => "<td><input id=\"excluirDisable\"  disabled style=\"text-align: center; \"  name=\"breg\" type=\"submit\"  value=\"excluir\"></td>",
		                 "imprimir" => "<td><input id=\"imprimirDisable\"  disabled style=\"text-align: center; \"  name=\"breg\" type=\"submit\"  value=\"imprimir\"></td>",
		                 "envelope" => "<td><input id=\"envelope\"  name=\"breg\" type=\"submit\"  value=\"envelope\"></td>",
		                 "fechar" => "<td><input id=\"fecharDisable\"  disabled style=\"text-align: center; \"  name=\"breg\" type=\"submit\"  value=\"fechar\"></td>",
		                 "fechar_min" => "<td><input id=\"fechar_min\"  disabled style=\"text-align: center; \"  name=\"breg\" type=\"submit\"  value=\"fechar\"></td>",
		                 "fim" => "<td> <input type=\"image\" style=\"height:45px; border:none\" name=\"breg\" value=\"fim\" src=\"/joystock/pcte-common/images/fim.png\"></td>");
       return $BREG;
   }
?>
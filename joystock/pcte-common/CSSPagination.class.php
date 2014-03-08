<?php








class CSSPagination


{


	private $totalrows;


	private $rowsperpage;


	private $website;


	private $page;


	private $sql;


	private $conex;


	private $nome;


		


	public function __construct($sql, $rowsperpage, $website, $count, $nome)


	{


		$this->sql = $sql;


		$this->website = $website;


		$this->rowsperpage = $rowsperpage;


		$this->totalrows = $count;


		$this->nome = $nome;


	}


	


	public function getNome(){


		return $this->nome;


	}


	


	


	


	public function setPage($page)


	{


		if (!$page) {


			$this->page=1; 


		} else  {


			$this->page = $page; 


		}


	}


	


	public function setSQL($sql)


	{


		if (!$sql) {


			$this->sql=''; 


		} else  {


			$this->sql = sql; 


		}


	}


	


	public function setTotal($total)


	{


		


		if (!$total) {


			$this->totalrows=0; 


		} else  {


			$this->totalrows = $total; 


		}


	}


	


	public function getLimit()


	{


		return ($this->page - 1) * $this->rowsperpage;


	}


	


	private function getTotalRows()


	{


		if ($this->bank == 'firebird'){


			$result = ibase_query($this->conex,$this->sql);	


			$row = ibase_fetch_object($result);


			$this->totalrows = $row->COUNT;


		}else{


			$result = @mysql_query($this->sql) or die ("query failed!");	


			$this->totalrows = mysql_num_rows($result);


		}


		


	}


	


	public function getLastPage()


	{


		return ceil($this->totalrows / $this->rowsperpage);


	}


	


	public function showPage()


	{


		//$this->getTotalRows();


		


		$pagination = "";


		$lpm1 = $this->getLastPage() - 1 ;


		$prev = $this->page - 1 ;


		$page = $this->page ;


		$next = $this->page + 1;


		
		

		$pagination .= "<div  class=\"pagination\"";


		if($margin || $padding)


		{


			$pagination .= " style=\"";


			if($margin)


				$pagination .= "margin: $margin;";


			if($padding)


				$pagination .= "padding: $padding;";


			$pagination .= "\"";


		}


		$pagination .= ">";


		


		if ($this->getLastPage() > 1)


		{


			if ($page > 1) 


				$pagination .= "<label onclick=\"this.form.page.value='$prev'; submit();\" >Anterior</label>";


			else


				$pagination .= "<span class=\"disabled\">Anterior</span>";


			


			


			if ($this->getLastPage() < 9)


			{	


				for ($counter = 1; $counter <= $this->getLastPage(); $counter++)


				{


					if ($counter == $page)


						$pagination .= "<span class=\"current\">".$counter."</span>";


					else


						$pagination .= "<label onclick=\"this.form.page.value='$counter'; submit();\" >".$counter."</label>";					


				}


			}


			elseif($this->getLastPage() >= 9)


			{


				if($page < 4)		


				{


					for ($counter = 1; $counter < 6; $counter++)


					{


						if ($counter == $page)


							$pagination .= "<span class=\"current\">".$counter."</span>";


						else


							$pagination .= "<label onclick=\"this.form.page.value='$counter'; submit();\" >".$counter."</label>";


					}


					$pagination .= "...";


					$pagination .= "<label onclick=\"this.form.page.value='$lpm1'; submit();\" >".$lpm1."</label>";


					$pagination .= "<label onclick=\"this.form.page.value='{$this->getLastPage()}'; submit();\" >{$this->getLastPage()}</label>";


				}


				elseif($this->getLastPage() - 3 > $page && $page > 1)


				{


					$pagination .= "<label onclick=\"this.form.page.value='1'; submit();\" >1</label>";


					$pagination .= "<label onclick=\"this.form.page.value='2'; submit();\" >2</label>";


					$pagination .= "...";


					for ($counter = $page - 1; $counter <= $page + 1; $counter++)


					{	


						if ($counter == $page)


							$pagination .= "<span class=\"current\">".$counter."</span>";


						else


							$pagination .= "<label onclick=\"this.form.page.value='$counter'; submit();\" >".$counter."</label>";


					}


					$pagination .= "...";


					$pagination .= "<label onclick=\"this.form.page.value='$lpm1'; submit();\" >".$lpm1."</label>";


					$pagination .= "<label onclick=\"this.form.page.value='{$this->getLastPage()}'; submit();\" >{$this->getLastPage()}</label>";


				}


				else


				{


					$pagination .= "<label onclick=\"this.form.page.value='1'; submit();\" >1</label>";


					$pagination .= "<label onclick=\"this.form.page.value='2'; submit();\" >2</label>";


					$pagination .= "...";


					for ($counter = $this->getLastPage() - 4; $counter <= $this->getLastPage(); $counter++)


					{


						if ($counter == $page)


							$pagination .= "<span class=\"current\">".$counter."</span>";


						else


							$pagination .= "<label onclick=\"this.form.page.value='$counter'; submit();\" >".$counter."</label>";


					}


				}


			}


		


		if ($page < $counter - 1) 


			$pagination .= "<label onclick=\"this.form.page.value='$next'; submit();\" >Próximo</label>";


		else


			$pagination .= "<span class=\"disabled\">Próximo</span>";


		$pagination .= "</div>\n";			


		}	


					


		return $pagination;


	}


}


?>
<?

	   class   ManipularHora{
		private  $hora;
		
		public $dia;
		public $mes;
		public $ano;
		
		public  function SetData($dia,$mes,$ano){
			$this->dia = $dia;
			$this->mes = $mes;
			$this->ano = $ano;
		}
		
		/* Inserir a data conforme banco de dados Y-m-d */
		function __construct($hora=""){
			if($hora==""){
				$hora = date("H:i:s");
			}else{
			    $this->hora = explode(":",$hora);
			}
		}
		
		 function calcular_tempo_trasnc($hora1,$hora2){ 
		    $separar[1]=explode(':',$hora1); 
		    $separar[2]=explode(':',$hora2); 
		
		$total_minutos_trasncorridos[1] = ($separar[1][0]*60)+$separar[1][1]; 
		$total_minutos_trasncorridos[2] = ($separar[2][0]*60)+$separar[2][1]; 
		$total_minutos_trasncorridos = $total_minutos_trasncorridos[1]-$total_minutos_trasncorridos[2]; 
		//if($total_minutos_trasncorridos<=59) return($total_minutos_trasncorridos.' Minutos'); 
		//if($total_minutos_trasncorridos>59){ 
		$HORA_TRANSCORRIDA = round($total_minutos_trasncorridos/60); 
		if($HORA_TRANSCORRIDA<=9) $HORA_TRANSCORRIDA='0'.$HORA_TRANSCORRIDA; 
		$MINUTOS_TRANSCORRIDOS = $total_minutos_trasncorridos%60; 
		return $total_minutos_trasncorridos;
		if($MINUTOS_TRANSCORRIDOS<=9) $MINUTOS_TRANSCORRIDOS='0'.$MINUTOS_TRANSCORRIDOS; 
		//return ($HORA_TRANSCORRIDA.':'.$MINUTOS_TRANSCORRIDOS.' Horas'); 
		
		} //}
		
		public function InseriData($data=''){
			if(empty($data)){
				$data = date("Y-m-d", time());
			}
						
			list($this->ano, $this->mes, $this->dia) = explode("-",$data);
			return $this->dia."/".$this->mes."/".$this->ano;  //aki
		}
		
		public function InserirHora($hora){
			$this->hora = explode(":",$hora);
		}
		
		public function somaSegundo($segundos=1){
			$this->hora = strftime("%H:%M:%S",mktime($this->hora[0],$this->hora[1],$this->hora[2]+$segundos, 0, 0, 0));
			
			return $this->hora;
		}
		
		public function somaMinuto($minutos=1){
			$this->hora = strftime("%H:%M:%S",mktime($this->hora[0],$this->hora[1]+$minutos,$this->hora[2], 0, 0, 0));
			
			return $this->hora;
		}
		
		public function subtraiMinuto($minutos=1){
			$this->hora = strftime("%H:%M:%S",mktime($this->hora[0],$this->hora[1]-$minutos,$this->hora[2], 0, 0, 0));
			
			return $this->hora;
		}
		
		public function somaHora($horas=1){
			$this->hora = strftime("%H:%M:%S",mktime($this->hora[0]+$horas,$this->hora[1],$this->hora[2], 0, 0, 0));
			
			return $this->hora;
		}
		
		public function getHora(){
			
			return $this->hora;
		}

		public function getData(){
			
			return $this->data;
		}		
		
		public function DataAtualCompleta($dia, $mes, $ano){
			$DiaSemana = array('domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado');
			$MesAno = array('janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro');
			
			if(!empty($dia) && !empty($mes) && !empty($ano))
				$data = mktime(0,0,0,$mes,$dia,$ano);
			else 
				$data = time();
				
			$data = date('w-d-n-Y', $data);
			list($semana, $dia, $mes, $ano) = split('[-]',$data);
			$result = $DiaSemana[$semana].", ".$dia." de ".$MesAno[$mes-1]." de ".$ano;
		   
			return $result;
		}
		
	    public function idade($data){
	    	list($ano,$mes,$dia) = explode('-', $data);

	    	if (!checkdate($mes, $dia, $ano)) {
				print "A data que você informou está errada <b>[ $dia/$mes/$ano ]</b>";
				exit;
			}
			
			$dia_atual = date("d");
			$mes_atual = date("m");
			$ano_atual = date("Y");
			$idade = $ano_atual - $ano;
			
			if ($mes > $mes_atual) {
				$idade--;
			}
			
			if ($mes == $mes_atual and $dia_atual < $dia) {
				$idade--;
			}
			
			return $idade;
	    }
	    
		public function somaData($QtMes=0, $QtDia=0, $QtAno=0){
			$result = date("Y-m-d", mktime(0, 0, 0, $this->mes + $QtMes, $this->dia + $QtDia, $this->ano + $QtAno));
			
			return $result;
		}
	}

?>

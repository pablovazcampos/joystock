<?
	class IniFile{
		public $FileName;
		public $IniParsed;
		public $PrivateMessage;
		
		public function IniFile($FileName){
			//$this->PrivateMessage = "Arquivo de Configuração Privado";
			$this->FileName = $FileName;
			if(file_exists($FileName)){
				$this->IniParsed = parse_ini_file($FileName, true);
				if(count($this->IniParsed) == 0)
					$this->IniParsed = false;
			}  
				//$this->IniParsed = false;
		}

		public function Clear(){
			unset($this->IniParsed);
			$this->IniParsed = array();
		}
		
		public function DeleteKey($Section, $Ident){
			if($this->ValueExists($Section, $Ident))
				unset($this->IniParsed[$Section][$Ident]);
		}
		
		public function EraseSection($Section){
			if($this->SectionExists($Section))
				unset($this->IniParsed[$Section]);
		}
		
		public function GetStrings(&$Content){
			$Content = '';
			$sections = '';
			foreach($this->IniParsed as $key => $item){
				if(is_array($item)){
					$sections .= "\n[{$key}]\n";
					foreach($item as $key2 => $item2){
						if(is_numeric($item2) || is_bool($item2))
							$sections .= "{$key2} = {$item2}\n";
						else
							$sections .= "{$key2} = \"{$item2}\"\n";
					}     
				}else{
					if(is_numeric($item) || is_bool($item))
						$Content .= "{$key} = {$item}\n";
					else
						$Content .= "{$key} = \"{$item}\"\n";
		    	}
			}
			$Content .= $sections;
		}
		
		public function ReadSection($Section, &$Strings){
			if($this->SectionExists($Section)){
				$Strings = array_keys($this->IniParsed[$Section]);
			}else
				$Strings = null;
				
				return $Strings;
		}
		
		public function ReadSections($Section){
			if($this->IniParsed){
				$Strings = @array_values($this->IniParsed[$Section]);
			}else
				$Strings = null;
			return $Strings;
		}
		
		public function ReadSectionValues($Section, &$Strings){
			if($this->SectionExists($Section)){
				$Strings = array_values($this->IniParsed[$Section]);
			}else
				$Strings = null;
		}
		
		public function Read($Section, $Ident, $Default){
			if($this->ValueExists($Section, $Ident))
				return $this->IniParsed[$Section][$Ident];
			else
				return $Default;
		}
		
		public function Rename($FileName, $DeleteOld = false){
			$this->GetStrings($Content);
			$Content = ";<?php exit('".$this->PrivateMessage."'); ?>\n".$Content;
			if(!$handle = fopen($FileName, 'w'))
				return false;
			if(!fwrite($handle, $Content))
				return false;
			fclose($handle);
			if($DeleteOld)
				unlink($this->FileName);
			$this->FileName = $FileName;
			return true;
		}
		
		public function SectionExists($Section){
			if($this->IniParsed && strlen($Section) > 0)
				return array_key_exists($Section, $this->IniParsed);
			else
				return false;
		}
		
		public function UpdateFile(){
			$this->GetStrings($Content);
			if(!$handle = fopen($this->FileName, 'w'))
				return false;
			if(!fwrite($handle, $Content))
				return false;
			fclose($handle);
			return true;
		}
					
		public function ValueExists($Section, $Ident){
			if($this->SectionExists($Section) && strlen($Ident) > 0)
				return array_key_exists($Ident, $this->IniParsed[$Section]);
			else
				return false;
		}
		
		public function Write($Section, $Ident, $Value){
			$this->IniParsed[$Section][$Ident] = $Value;
		}
		
		public function WriteSection($Section){
			$this->IniParsed[$Section] = array();
		}
		
		public function RenameSection($Section, $NewSection){
			if($this->SectionExists($Section) && !$this->SectionExists($NewSection)){
				$this->IniParsed[$NewSection] = $this->IniParsed[$Section];
				unset($this->IniParsed[$Section]);
			}
		}
	}
?>
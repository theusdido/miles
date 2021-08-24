<?php
include PATH_ADO . 'sqlfiltro.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que auxilia a chamada das classes 
    * Data de Criacao: 20/01/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class tdClass {	
	/*  
		* Método construtor
	    * Data de Criacao: 19/01/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	*/		
	public static function Criar($classe)
	{
		if (strpos($classe,".") > -1){
			include PATH_CLASS . str_replace(".","/",$classe). '.class.php';
			$classeArray = explode(".",$classe);
			$classe = end($classeArray);
		}
		$argumentos = "";
		if (func_num_args() > 1){
			if (is_array(func_get_arg(1))){
				foreach (func_get_arg(1) as $i => $arg){
					$arg = (getType($arg)=="array"?"array":"") . SqlFiltro::transformar($arg);					
					$arg = substr_count($arg,'DATE_FORMAT') > 0? '"RETIRAR ' . $arg . ' RETIRAR"':$arg;
					$argumentos .= ($argumentos=="")?$arg:','.$arg;
				}
			}
		}
		eval('$obj = new '.$classe.'('.$argumentos.');');
		return $obj;
	}
	/*
		* Método Write
	    * Data de Criacao: 19/01/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Imprime String ( Substitui o comando "echo","print" e "var_dump")
	*/	
	public static function write($parms,$retorno = false){
		if (is_object($parms) || is_array($parms)){
			var_dump($parms);
		}else{
			$cur_encoding = mb_detect_encoding($parms);
			if($cur_encoding == "UTF-8" && mb_check_encoding($parms,'UTF-8')){
				if ($retorno) return $parms;
				else echo $parms;
			}elseif($cur_encoding == "ISO 8859-1" && mb_check_encoding($parms,'ISO 8859-1')){
				if ($retorno) return $parms;
				else echo utf8charset($parms);
			}else{
				if ($retorno) return $parms;
				else echo utf8_encode($parms);
				// Não testado:
				// return iconv($cur_encoding, "ISO 8859-1", $in_str);
				//throw new Exception('Codificação não suportada.');
			}
		}
	}
	
	/*
		* Método Read
	    * Data de Criacao: 19/01/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Lê String ( Substitui o comando "$_GET","$_POST" e "$_FILES")
	*/	
	public static function read($parms){
		$parms = trim($parms);
		$parms = strip_tags($parms);
		$parms = addslashes($parms);
	
		if (isset($_GET[$parms])){
			return $_GET[$parms];
		}else if (isset($_POST[$parms])){
			return $_POST[$parms];
		}else if (isset($_FILES[$parms])){
			return $_FILES[$parms];
		}else{
			return false;
		}
	}
}
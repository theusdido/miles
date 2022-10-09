<?php
	include_once 'conexao.php';

	$request_schema = isset($_SERVER["HTTP_X_FORWARDED_PROTO"])?$_SERVER["HTTP_X_FORWARDED_PROTO"]:$_SERVER["REQUEST_SCHEME"];
	$protocolo = 'http' . '://';
	if ($_SERVER["HTTP_HOST"] == "localhost" || $_SERVER["HTTP_HOST"] == "127.0.0.1"){
		$host = $protocolo . 'localhost/' . $config["PROJETO_FOLDER"];
	}else{
		$host = $protocolo . $_SERVER["HTTP_HOST"] . '/miles/';
	}
	
	function executefunction($function,$parms){

		if ($function == "utf8charset"){
			if (isutf8($parms[0])){
				if (isset($parms[1])){
					return conversionCharset($parms[0],$parms[1]);
				}else{
					return $parms[0];
				}	
			}else{
				if ($parms[0] == "" || $parms[0] == null && empty($parms[0]) && !is_string($parms[0])){
					return "";
				}else{
					return iconv("iso-8859-1","utf-8",$parms[0]);
				}
			}
			if (!CharSetBDCorreto()){ // Testa se a utilização do charset está com erro
				return $parms[0];
			}
		}

		$parms = http_build_query( 
			array(
				"controller" => "executefunction" , 
				"key" => "k" , 
				"function" => $function , 
				"parms" => $parms ,
				"currentproject" => $_SESSION["currentproject"]
			)
		);
		global $host;
		$retornoconteudo = file_get_contents($host . '/index.php?' . $parms);
		$retornofunction = json_decode($retornoconteudo,true);
		$retorno = null;
		$retornodado = $retornofunction["dado"];
		if ($retornofunction["tipo"] == "boolean"){
			$retorno = (boolean)$retornodado;
		}else{
			$retorno = $retornodado;
		}
		return $retorno;
	}

	function CharSetBDCorreto($str = null){		
		global $conn;
		$sqlVCharset = 'SELECT testecharset FROM td_config WHERE id = 1';
		$queryVCharset = $conn->query($sqlVCharset);
		$linhaVCharset = $queryVCharset->fetch();
		$iscorreto = ord($linhaVCharset["testecharset"]) == 195 ? false : true;
		return $iscorreto;
	}

	function getCurrentProjectConfig(){
		global $config;
		$filepath = "../../project/config/current_config.inc";
		if (file_exists($filepath)){
			return parse_ini_file($filepath);
		}else{
			return null;
		}
	}
	
	function isutf8($str) {
		$c=0; $b=0;
		$bits=0;
		$len=strlen($str);
		for($i=0; $i<$len; $i++){
			$c=ord($str[$i]);
			if($c > 128){
				if(($c >= 254)) return false;
				elseif($c >= 252) $bits=6;
				elseif($c >= 248) $bits=5;
				elseif($c >= 240) $bits=4;
				elseif($c >= 224) $bits=3;
				elseif($c >= 192) $bits=2;
				else return false;
				if(($i+$bits) > $len) return false;
				while($bits > 1){
					$i++;
					$b=ord($str[$i]);
					if($b < 128 || $b > 191) return false;
					$bits--;
				}
			}
		}
		return true;
	}	
	
	function conversionCharset($string,$tipo){
		switch($tipo){
			case 5:
				return $string;
			break;			
			case 6:
				return iconv("utf-8","iso-8859-1",$string);
			break;
			default:
				return iconv("iso-8859-1","utf-8",$string);
		}
	}
	
	function getProxIdMDM($entidade,$atributo = "id",$where = null){		
		global $conn;
		$PREFIXO = "td_";
		if (gettype($where) == "array"){
			$where = " WHERE {$where[0]} {$where[1]} {$where[2]} "; 
		}
		$sql = "SELECT IFNULL(MAX({$atributo}),0) + 1 FROM ".$PREFIXO.$entidade.$where;
		$query = $conn->query($sql);
		$linha = $query->fetch();
		return $linha[0];
	}
	function getURLParamsProject($concatenador = ""){
		return $concatenador . "currentproject=" . $_SESSION["currentproject"];
	}
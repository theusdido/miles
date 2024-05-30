<?php
if (isset($_GET["function"])){
	$params = '';
	$retornojson = array();
	if (isset($_GET["parms"])){
		if (is_array($_GET["parms"])){
			foreach ($_GET["parms"] as $p){
				$params .= ($params==''?'':',') . retornaValorTipo($p);
			}
		}else{
			$params = retornaValorTipo($_GET["parms"]);
		}
	}
	eval('$retorno =  ' . $_GET["function"] . '('.$params.');');
	$retornojson["tipo"] = gettype($retorno);
	if (isutf8($retorno)){
		$retornojson["dado"] = $retorno;
		$retornojson["isconvertididoutf8"] = false;
	}else{
		$retornojson["dado"] = convertecharset($retorno,1);
		$retornojson["isconvertididoutf8"] = true;
	}
	echo json_encode($retornojson);
	
}
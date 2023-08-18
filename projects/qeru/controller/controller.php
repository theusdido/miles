<?php
	$dados 		= json_decode(tdc::r("dados"),true);
	$file		= tdc::r("file");

	$pathfile 	= PATH_CURRENT_CONTROLLER . $file . ".php";	
	$op 		= isset($dados["op"]) ? $dados["op"] : '';

	if (tdc::r("token") == '' && $file != "token" && $file != "upload"){
		echo json_encode(array(
			"status" => 1,
			"msg" => "Token de acesso não encontrado"
		));
		exit;
	}
	
	if (_ENVIROMMENT == 'dev'){
		$angular_port = is_exists('$mjc->enviroment->frameworks->angular->port','4200');
		$_url_root	= 'http://localhost:'.$angular_port.'/';
	}else{
		$_url_root	= REQUEST_PROTOCOL . 'app.' . $_domain . $_full_port . '/';
	}

	$url_miles 	= URL_MILES;

	if (file_exists($pathfile)){
		include $pathfile;
	}else{
		json_encode(array(
			"status" => 2,
			"msg" => "End Point não encontrado"
		));
	}
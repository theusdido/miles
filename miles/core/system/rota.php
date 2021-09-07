<?php
	
	$controller 		= tdc::r("controller");
	$systemcontroller 	= PATH_MVC_CONTROLLER . $controller .  '.php';	
	$systemrequisicoes	= PATH_MVC_CONTROLLER . 'requisicoes.php';
	$systemautentica	= PATH_MVC_CONTROLLER . 'autentica.php';
	$systemmain			= PATH_MVC_CONTROLLER . 'main.php';

	$customcontroller 	= Session::Get("PATH_CURRENT_CONTROLLER") . $controller . '.php';	
	$customautentica	= Session::Get('PATH_CURRENT_CONTROLLER') . 'autentica.php';
	$custommain			= Session::Get('PATH_CURRENT_CONTROLLER') . 'main.php';

	$systemview 		= '';
	$customview			= '';

	if ($controller == "gerarpagina" || tdClass::Read("key") == "k"){
		if (file_exists($customcontroller)) include $customcontroller;
		if (file_exists($systemcontroller)) include $systemcontroller;
		exit;
	}

	if ($controller == "permissaoinicial"){
		if (file_exists($systemrequisicoes)) include $systemrequisicoes;
		exit;
	}

	if ($controller == "" && AMBIENTE == 'SISTEMA'){
		if (file_exists($custommain)) include $custommain;
		if (file_exists($systemmain)) include $systemmain;
	}else{
		if (file_exists($customcontroller)) include $customcontroller;
		if (file_exists($systemcontroller)) include $systemcontroller;
	}
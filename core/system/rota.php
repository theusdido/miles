<?php

	$controller 		= tdc::r("controller");
	$systemcontroller 	= PATH_MVC_CONTROLLER . $controller .  '.php';	
	$systemrequisicoes	= PATH_MVC_CONTROLLER . 'requisicoes.php';
	$systemautentica	= PATH_MVC_CONTROLLER . 'autentica.php';
	$systemmain			= PATH_MVC_CONTROLLER . 'main.php';

	$customcontroller 	= PATH_CURRENT_CONTROLLER . $controller . '.php';
	$customautentica	= PATH_CURRENT_CONTROLLER . 'autentica.php';
	$custommain			= PATH_CURRENT_CONTROLLER . 'main.php';
	
	// Tratamento para requisição a uma página - by @theusdido 02/10/2021
	$_page				= tdc::r("page");
	if (strpos($_page,"/") > -1){
		$_page_e		= explode("/",$_page);
		$_page			= $_page . "/" . end($_page_e);
	}	
	$_systempage		= PATH_SYSTEM_PAGE . $_page . ".php";
	$_custumpage		= PATH_CURRENT_PAGE . $_page . ".php";

	$systemview 		= '';
	$customview			= '';

	if ($controller == "gerarcadastro" || tdClass::Read("key") == "k"){
		if (file_exists($customcontroller)) include $customcontroller;
		if (file_exists($systemcontroller)) include $systemcontroller;
		exit;
	}

	if ($controller == "permissaoinicial"){
		if (file_exists($systemrequisicoes)) include $systemrequisicoes;
		exit;
	}

	switch(AMBIENTE){
		case 'SISTEMA':
			if ($controller == "" && $_page == ""){
				if (file_exists($custommain)) include $custommain;
				if (file_exists($systemmain)) include $systemmain;
			}else{
				if (file_exists($customcontroller)) include $customcontroller;
				if (file_exists($systemcontroller)) include $systemcontroller;
			}
		break;
		case 'BIBLIOTECA':
			switch($mjc->system->package){
				case 'ecommerce':
					$ecommerce_main_controller = PATH_MVC_CONTROLLER_ECOMMERCE . 'main.php';
					if (file_exists($ecommerce_main_controller))
					{
						include $ecommerce_main_controller;
					}
				break;
			}
		break;
	}	
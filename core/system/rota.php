<?php
	$controller 		= tdc::r("_controller") == '' ? tdc::r("controller") : tdc::r("_controller");
	$_controller		= tdc::r("_controller"); # Novo padrão com _ na frente
	$systemcontroller 	= PATH_MVC_CONTROLLER . $controller .  '.php';
	$systemrequisicoes	= PATH_MVC_CONTROLLER . 'requisicoes.php';
	$systemautentica	= PATH_MVC_CONTROLLER . 'autentica.php';
	$systemmain			= PATH_MVC_CONTROLLER . 'main.php';

	$customcontroller 	= PATH_CURRENT_CONTROLLER . $controller . '.php';
	$customautentica	= PATH_CURRENT_CONTROLLER . 'autentica.php';
	$custommain			= PATH_CURRENT_CONTROLLER . 'main.php';

	// Tratamento para requisição ao componente - by @theusdido 17/09/2022
	$_component				= tdc::r("_component");
	if (strpos($_component,"/") > -1){
		if (preg_match("/{{[a-z)]+}}/i",$_component,$match)){
			$_component_path	= str_replace(array('{{','}}'),'',$_component);
		}else{
			$_component_e		= explode("/",$_component);
			$_component_path	= $_component . "/" . end($_component_e);
		}
	}else{
		$_component_path		= $_component . '/' . $_component;
	}

	$_systemcomponent			= PATH_SYSTEM_COMPONENT . $_component_path . ".php";
	$_customcomponent			= PATH_CURRENT_COMPONENT . $_component_path . ".php";
	// Fim Component

	// Tratamento para requisição a uma página - by @theusdido 02/10/2021
	$_page				= tdc::r("page");
	if (strpos($_page,"/") > -1){
		if (preg_match("/{{[a-z)]+}}/i",$_page,$match)){
			$_page			= str_replace(array('{{','}}'),'',$_page);
		}else{
			$_page_e		= explode("/",$_page);
			$_page			= $_page . "/" . end($_page_e);
		}
	}

	$_systempage		= PATH_SYSTEM_PAGE . $_page . ".php";
	$_custompage		= PATH_CURRENT_PAGE . $_page . ".php";
	// Fim Page

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
			if ($controller == '' && $_page == '' && $_component == ''){
				if (file_exists($custommain)) include $custommain;
				if (file_exists($systemmain)) include $systemmain;
			}else{
				if (file_exists($customcontroller)) include $customcontroller;
				if (file_exists($systemcontroller)) include $systemcontroller;
			}
		break;
		case 'BIBLIOTECA':
			foreach($mjc->system->packages as $p){
				switch($p){
					case 'ecommerce':
						$ecommerce_main_controller = PATH_MVC_CONTROLLER_ECOMMERCE . 'main.php';
						if (file_exists($ecommerce_main_controller))
						{
							include $ecommerce_main_controller;
						}
					break;
				}
			}
		break;
	}
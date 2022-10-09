<?php
	
	$_full_port 	= (PORT == '' ? '' : ':') . PORT;
	$_root_folder	= (isset($_env->root) ? $_env->root : '');
	$_domain	 	= HTTP_HOST;

    // URL ROOT
	if (isset($_env->url->domain)){
		$_mainHost 	= REQUEST_PROTOCOL . $_env->url->domain . '/';
		$_domain	= $_env->url->domain;
	}else if (isset($_SERVER['SERVER_NAME'])){
		$_mainHost 	= REQUEST_PROTOCOL . $_SERVER['SERVER_NAME'] . '/';
		$_mainHost	= str_replace('www.','', $_mainHost);
	}else{
		$_mainHost	= REQUEST_PROTOCOL . $_domain . $_full_port . $_root_folder;
	}

	define('URL_ROOT', $_mainHost);

	// URL ALIAS
	if (isset($_GET["alias"])){
		define('URL_ALIAS',REQUEST_PROTOCOL . $_domain . "/" . $_GET["alias"]);
	}else{
		define('URL_ALIAS',URL_ROOT);
	}

	// URL API	
	define('URL_API', REQUEST_PROTOCOL . $_domain . $_full_port . '/' . FOLDER_MILES . "index.php");

	if (isset($mjc->folder)){
		$request_uri_dir 	= (isset($_env->root) ? $_env->root  : '/') . $mjc->folder;
	}else{
		$ruri 				= $_SERVER['REQUEST_URI'];
		$request_uri 		= explode('?',(strpos($ruri,'index.php') > -1 ? dirname($ruri).'/' : $ruri));
		$request_uri_dir	= str_replace("index.php","",$request_uri[0]);
	}

	// URL MILES
	$_url_miles	= REQUEST_PROTOCOL . $_domain . $_full_port .  $request_uri_dir;

	define('URL_MILES',$_url_miles);

	// URL NODEJS
	define('URL_NODEJS', REQUEST_PROTOCOL . $_domain . ':2711/');

	// URL CORE
	define('URL_CORE', URL_MILES . FOLDER_CORE . '/');

	// URL SYSTEM
	define('URL_SYSTEM',URL_CORE. FOLDER_SYSTEM . '/');

	// URL PAGE
	define('URL_PAGE', URL_CORE . FOLDER_PAGE . '/');

	// URL COMPONENT
	define('URL_COMPONENT', URL_CORE . FOLDER_COMPONENT . '/');

	
	define('URL_PROJECT',URL_MILES . FOLDER_PROJECT . '/');
	define('URL_CURRENT_PROJECT',URL_PROJECT);

	// URL CLASSES	
	define('URL_CLASS', URL_CORE . FOLDER_CLASSES . '/');

	// URL MDM
	define('URL_MDM', URL_CORE . 'mdm/');

	// URL INSTALL
	define('URL_INSTALL', URL_CORE . 'install/');

	// CLASSE TDC
	define('URL_CLASS_TDC',URL_CLASS . 'tdc/');

	// CURRENT PROJECT THEME
	define('URL_CURRENT_PROJECT_THEME', URL_PROJECT . PATH_THEME);

	// SYSTEM THEME
	define('URL_SYSTEM_THEME', URL_CORE . PATH_THEME);

	// CONFIG
	define('URL_CURRENT_CONFIG_PROJECT', URL_CURRENT_PROJECT . FOLDER_CONFIG . '/');
    
	// FILE
	define('URL_CURRENT_FILE', URL_PROJECT. "arquivos/");
    
	define('URL_CURRENT_FILE_TEMP', URL_CURRENT_FILE . 'temp/');
	define('URL_CURRENT_PAGE',URL_PROJECT . "page/");
	define('URL_CURRENT_IMG',URL_PROJECT . "images/");
	define('URL_CURRENT_COMPONENT',URL_PROJECT . FOLDER_COMPONENT .'/');

	// URL da Biblioteca
	define('URL_LIB',$mjc->system->url->lib);

	// URL dos arquivos de cadastro
	define('URL_FILES_CADASTRO', URL_PROJECT . 'files/cadastro/');
    
	// URL dos arquivos de movimentação
	define('URL_FILES_MOVIMENTACAO', URL_PROJECT . 'files/movimentacao/');

	// URL padrão para as classes de WIDGETS
	define('URL_CLASS_WIDGETS', URL_CLASS . 'widgets/');

	// Loading 
	define('URL_LOADING', URL_SYSTEM_THEME . 'loading.gif');
	define('URL_LOADING2', URL_SYSTEM_THEME . 'loading2.gif');

	// URL SYSTEM ECOMMERCE
	define('URL_ECOMMERCE', URL_CORE . 'controller/ecommerce' . '/' );

	// Arquivo MDM JavaScript Compilado
    define("URL_MDM_JS_COMPILE", URL_CURRENT_PROJECT . FOLDER_BUILD . '/js/');

	define('URL_CURRENT_BUILD',URL_PROJECT . FOLDER_BUILD . '/');

	define('URL_TDWEBSERVICE', URL_MILES . 'webservice/');

	define('URL_ASSETS' , URL_CORE . 'assets/');

	define('URL_IMG', URL_CORE . 'images/');

	define('URL_PICTURE', URL_IMG . 'picture/');
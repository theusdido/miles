<?php

    // URL ROOT
	Session::append("URL_ROOT",REQUEST_PROTOCOL . HTTP_HOST . "/" . FOLDER_PROJECT . "/");
	define('URL_ROOT', REQUEST_PROTOCOL . HTTP_HOST . ':' . PORT . "/" . FOLDER_PROJECT . "/");

	// URL ALIAS
	if (isset($_GET["alias"])) Session::append("URL_ALIAS",REQUEST_PROTOCOL . HTTP_HOST . "/" . $_GET["alias"]);


	if (isset($mjc->folder)){
		$request_uri_dir 	= '/' . $mjc->folder;
	}else{
		$ruri 				= $_SERVER['REQUEST_URI'];
		$request_uri 		= explode('?',(strpos($ruri,'index.php') > -1 ? dirname($ruri).'/' : $ruri));
		$request_uri_dir	= str_replace("index.php","",$request_uri[0]);
	}

	// URL MILES
	$_url_miles	= REQUEST_PROTOCOL . HTTP_HOST . (PORT==''?'':':'.PORT) .  $request_uri_dir;
	Session::append("URL_MILES",$_url_miles);
	define('URL_MILES',$_url_miles);

	// URL NODEJS
	define('URL_NODEJS', REQUEST_PROTOCOL . HTTP_HOST . ':2711/');

	// URL CORE
	Session::append("URL_CORE",Session::Get('URL_MILES') . FOLDER_CORE . '/');
	define('URL_CORE', URL_MILES . FOLDER_CORE . '/');

	// URL SYSTEM
	Session::append("URL_SYSTEM",Session::Get('URL_CORE') . FOLDER_SYSTEM . '/');
	define('URL_SYSTEM',URL_CORE. FOLDER_SYSTEM . '/');

	// URL PAGE
	Session::append("URL_PAGE",Session::Get('URL_CORE') . FOLDER_PAGE . '/');
	define('URL_PAGE', URL_CORE . FOLDER_PAGE . '/');

	// URL IMAGE
	define('URL_IMG', URL_CORE . FOLDER_IMAGES . '/');

	Session::append("URL_CURRENT_PROJECT",Session::Get("URL_MILES") . FOLDER_PROJECT . '/' . CURRENT_PROJECT_ID . "/");
	define('URL_PROJECT',Session::Get("URL_MILES") . FOLDER_PROJECT . '/');

	// URL CLASSES	
	Session::append('URL_CLASS',Session::Get('URL_CORE') . FOLDER_CLASSES . '/');

	// URL MDM
	Session::append('URL_MDM',Session::Get('URL_CORE') . 'mdm/');

	// URL INSTALL
	Session::append('URL_INSTALL',Session::Get('URL_CORE') . 'install/');

	// CLASSE TDC
	Session::append('URL_CLASS_TDC',Session::Get('URL_CLASS') . 'tdc/');

	Session::append("URL_CURRENT_PROJECT_THEME",Session::Get("URL_CURRENT_PROJECT") . PATH_THEME);
	define('URL_CURRENT_PROJECT_THEME', Session::Get("URL_CURRENT_PROJECT") . PATH_THEME);

	Session::append("URL_SYSTEM_THEME",Session::Get('URL_CORE') . PATH_THEME);
	define('URL_SYSTEM_THEME', Session::Get('URL_CORE') . PATH_THEME);
    
	Session::append("URL_CURRENT_CONFIG_PROJECT",Session::Get("URL_CURRENT_PROJECT"). FOLDER_CONFIG . "/");
    
	define('URL_CURRENT_CONFIG_PROJECT',Session::Get("PATH_CURRENT_PROJECT"). "config/");
    
	Session::append("URL_CURRENT_FILE",Session::Get("URL_CURRENT_PROJECT") . "arquivos/");
	define('URL_CURRENT_FILE',Session::Get("URL_CURRENT_PROJECT") . "arquivos/");
    
	Session::append("URL_CURRENT_FILE_TEMP",Session::Get("URL_CURRENT_FILE") . "temp/");
	define('URL_CURRENT_FILE_TEMP', URL_CURRENT_FILE . 'temp/');

	define('URL_CURRENT_PAGE',Session::Get("URL_CURRENT_PROJECT") . "page/");
	define('URL_CURRENT_IMG',Session::Get("URL_CURRENT_PROJECT") . FOLDER_IMAGES . "/");

	// URL da Biblioteca
	define('URL_LIB',$mjc->system->url->lib);
	Session::append("URL_LIB",$mjc->system->url->lib);

	// URL dos arquivos de cadastro
	define('URL_FILES_CADASTRO', Session::Get('URL_CURRENT_PROJECT') . 'files/cadastro/');
    
	// URL dos arquivos de movimentação
	define('URL_FILES_MOVIMENTACAO', Session::Get('URL_CURRENT_PROJECT') . 'files/movimentacao/');

	// URL padrão para as classes de WIDGETS
	define('URL_CLASS_WIDGETS', Session::Get('URL_CLASS') . 'widgets/');

	// Loading 
	Session::append("URL_LOADING", Session::Get("URL_SYSTEM_THEME") . 'loading.gif');
	Session::append("URL_LOADING2", Session::Get("URL_SYSTEM_THEME") . 'loading2.gif');
	define('URL_LOADING2', Session::Get("URL_SYSTEM_THEME") . 'loading2.gif');

	// URL SYSTEM ECOMMERCE
	Session::append('URL_ECOMMERCE',Session::Get('URL_CORE') . 'controller/ecommerce' . '/');

	// Arquivo MDM JavaScript Compilado
    define("URL_MDM_JS_COMPILE",Session::Get("URL_CURRENT_PROJECT") . FOLDER_BUILD . '/js/');

	define('URL_CURRENT_BUILD',Session::Get('URL_CURRENT_PROJECT') . FOLDER_BUILD . '/');

	define('URL_TDWEBSERVICE', URL_MILES . 'webservice/');

	define('URL_ASSETS' , URL_CORE . 'assets/');

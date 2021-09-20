<?php
    // URL ROOT
	Session::append("URL_ROOT",REQUEST_PROTOCOL . HTTP_HOST . "/" . PROJETO_FOLDER . "/");

	// URL ALIAS
	if (isset($_GET["alias"])) Session::append("URL_ALIAS",REQUES_PROTOCOL . HTTP_HOST . "/" . $_GET["alias"]);

	$ruri 				= $_SERVER['REQUEST_URI'];	
	$request_uri 		= explode('?',(strpos($ruri,'index.php') > -1 ? dirname($ruri).'/' : $ruri));
	$request_uri_dir	= str_replace("index.php","",$request_uri[0]);

	// URL MILES
	Session::append("URL_MILES",REQUEST_PROTOCOL . HTTP_HOST . $request_uri_dir);
	
	// URL CORE
	Session::append("URL_CORE",Session::Get('URL_MILES') . FOLDER_CORE . '/');

	// URL SYSTEM
	Session::append("URL_SYSTEM",Session::Get('URL_CORE') . FOLDER_SYSTEM . '/');

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

	Session::append("URL_SYSTEM_THEME",Session::Get('URL_CORE') . PATH_THEME);	
    
	Session::append("URL_CURRENT_LOGO_PADRAO",Session::Get("URL_CURRENT_PROJECT_THEME") . FILE_LOGO );
    
	Session::append("URL_CURRENT_FAVICON",Session::Get("URL_CURRENT_PROJECT_THEME") . FILE_CURRENT_FAVICON);

	Session::append("URL_FAVICON",Session::Get("URL_SYSTEM_THEME") . FILE_FAVICON);	
    
	Session::append("URL_CURRENT_CONFIG_PROJECT",Session::Get("URL_CURRENT_PROJECT"). FOLDER_CONFIG . "/");
    
	define('URL_CURRENT_CONFIG_PROJECT',Session::Get("PATH_CURRENT_PROJECT"). "config/");
    
	Session::append("URL_CURRENT_FILE",Session::Get("URL_CURRENT_PROJECT") . "arquivos/");
    
	Session::append("URL_CURRENT_FILE_TEMP",Session::Get("URL_CURRENT_FILE") . "temp/");
    
	// URL da Biblioteca
	define('URL_LIB',$mjc->system->url->lib);
	Session::append("URL_LIB",$mjc->system->url->lib);

	define('URL_FILES_CADASTRO', Session::Get('URL_CURRENT_PROJECT') . 'files/cadastro/');
    
	// URL padrão para as classes de WIDGETS
	define('URL_CLASS_WIDGETS', Session::Get('URL_CLASS') . 'widgets/');

	// Loading 
	Session::append("URL_LOADING", Session::Get("URL_SYSTEM_THEME") . 'loading.gif');
	Session::append("URL_LOADING2", Session::Get("URL_SYSTEM_THEME") . 'loading2.gif');

    define("URL_MDM_JS_COMPILE",Session::Get("URL_CURRENT_PROJECT") . FILE_MDM_JS_COMPILE);    
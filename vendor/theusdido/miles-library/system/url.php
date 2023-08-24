<?php

	$_full_port 		= (PORT == '' ? '' : ':') . PORT;
	$_root_folder		= (isset($_env->root) ? $_env->root : '');

	if ($_is_installed){
		$_is_fixed_domain	= isset($_env->system->is_fixed_domain) ? $_env->system->is_fixed_domain : false;
		$_domain			= isset($_env->url->domain) ? $_env->url->domain : 'localhost';
	}else{
		$_is_fixed_domain	= false;
		$_domain			= $_miles_config_root->project->url;
	}

	// Determina a URL principal se for fixa ou não
	if ($_is_fixed_domain){
		$_url_miles			= REQUEST_PROTOCOL . 'miles.' .$_domain . $_full_port . '/';
		$_mainHost 			= REQUEST_PROTOCOL . $_domain . $_full_port . '/';
	}else{
		$_url_miles			= REQUEST_PROTOCOL .$_domain . $_full_port . '/miles/';
		if (isset($_SERVER['SERVER_NAME'])){
			$_mainHost 	= REQUEST_PROTOCOL . $_SERVER['SERVER_NAME'] . '/';
			$_mainHost	= str_replace('www.','', $_mainHost);
			$_domain	= HTTP_HOST;
		}
	}

    // URL ROOT
	if (isset($_env->url->domain)){
		$_mainHost 	= REQUEST_PROTOCOL . $_env->url->domain . '/';
		$_domain	= $_env->url->domain;
	}else if (isset($_SERVER['SERVER_NAME'])){
		$_mainHost 	= REQUEST_PROTOCOL . $_SERVER['SERVER_NAME'] . '/';
		$_mainHost	= str_replace('www.','', $_mainHost);
		$_domain	= HTTP_HOST;
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

	// if (isset($mjc->folder)){
	// 	$request_uri_dir 	= (isset($_env->root) ? $_env->root  : '/') . $mjc->folder;
	// }else{
	// 	$ruri 				= $_SERVER['REQUEST_URI'];
	// 	$request_uri 		= explode('?',(strpos($ruri,'index.php') > -1 ? dirname($ruri).'/' : $ruri));
	// 	$request_uri_dir	= str_replace("index.php","",$request_uri[0]);
	// }
	// $_url_miles	 = REQUEST_PROTOCOL .$_domain . $_full_port .  $request_uri_dir;

	// URL MILES
	define('URL_MILES',$_url_miles);

	define('URL_AUTOLOAD',$_url_miles . 'autoload.php');

	define('URL_MILES_LIBRARY',URL_MILES . str_replace('\\','/',PATH_REPOSITORY) . FOLDER_MILES_LIBRARY . '/');

	// URL API	
	define('URL_API', $_url_miles . "index.php");

	// URL NODEJS
	define('URL_NODEJS', REQUEST_PROTOCOL . $_domain . ':2711/');

	// URL SYSTEM
	define('URL_SYSTEM',URL_MILES_LIBRARY. FOLDER_SYSTEM . '/');

	// URL PAGE	
	define('URL_PAGE', URL_MILES_LIBRARY . FOLDER_PAGE . '/');

	// URL COMPONENT
	define('URL_COMPONENT', URL_MILES_LIBRARY . FOLDER_COMPONENT . '/');

	define('URL_PROJECT',URL_MILES . $_url_relative_project );
	define('URL_CURRENT_PROJECT',URL_MILES . $_url_relative_project);

	// URL CLASSES	
	define('URL_CLASS', URL_MILES_LIBRARY . FOLDER_CLASSES . '/');

	// URL MDM
	define('URL_MDM', URL_MILES . 'mdm/');

	// URL INSTALL
	define('URL_INSTALL', URL_MILES . 'install/');

	// CLASSE TDC
	define('URL_CLASS_TDC',URL_CLASS . 'tdc/');
	
	// CURRENT PROJECT THEME
	define('URL_CURRENT_PROJECT_THEME', URL_PROJECT . FOLDER_THEME . '/' . $mjc->theme . '/');

	// SYSTEM THEME
	define('URL_SYSTEM_THEME', URL_MILES_LIBRARY . FOLDER_THEME . '/' . $mjc->theme . '/');

	// CONFIG
	define('URL_CURRENT_CONFIG_PROJECT', URL_CURRENT_PROJECT . FOLDER_CONFIG . '/');
    
	// FILE
	define('URL_CURRENT_FILE', URL_CURRENT_PROJECT. "arquivos/");
    
	define('URL_CURRENT_FILE_TEMP', URL_CURRENT_FILE . 'temp/');
	define('URL_CURRENT_PAGE',URL_PROJECT . "page/");
	define('URL_CURRENT_IMG',URL_PROJECT . "images/");
	define('URL_CURRENT_COMPONENT',URL_PROJECT . FOLDER_COMPONENT .'/');

	// URL da Biblioteca
	define('URL_LIB',$mjc->system->url->lib);

	define('URL_FILES',URL_PROJECT . 'files/');

	// URL dos arquivos de cadastro
	define('URL_FILES_CADASTRO', URL_FILES . 'cadastro/');
    
	// URL dos arquivos de movimentação
	define('URL_FILES_MOVIMENTACAO', URL_FILES . 'movimentacao/');

	// URL padrão para as classes de WIDGETS
	define('URL_CLASS_WIDGETS', URL_CLASS . 'widgets/');

	// Loading 
	define('URL_LOADING', URL_SYSTEM_THEME . 'loading.gif');
	define('URL_LOADING2', URL_SYSTEM_THEME . 'loading2.gif');

	// URL SYSTEM ECOMMERCE
	define('URL_ECOMMERCE', URL_MILES_LIBRARY . 'controller/ecommerce' . '/' );

	// Arquivo MDM JavaScript Compilado
    define("URL_MDM_JS_COMPILE", URL_CURRENT_PROJECT . FOLDER_BUILD . '/js/');

	define('URL_CURRENT_BUILD',URL_PROJECT . FOLDER_BUILD . '/');

	define('URL_TDWEBSERVICE', URL_MILES . 'webservice/');

	define('URL_ASSETS' , URL_MILES_LIBRARY . 'assets/');
	define('URL_CURRENT_ASSETS' , URL_PROJECT . 'assets/');

	define('URL_IMG', URL_MILES_LIBRARY . 'images/');

	define('URL_PICTURE', URL_MILES_LIBRARY . 'picture/');
	
	define('URL_CURRENT_DATA',URL_PROJECT . "data/");
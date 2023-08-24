<?php
	/* *********************************

		##### IMPORTANTE #####

		O arquivo .htacces envia por 
		parâmetro as variáveis:
		project_name_identifify_params e env
		essas variáveis são globais e devem
		prevalecer sobre quaisquer outra.
		
		*Alias, deveriam ser uma constante :P

		Autor: @theusdido
		Data: 11/07/2023

	********************************* */

	$_project_name_identifify_params 	= !getenv('_PROJECT_NAME_IDENTIFY_PARAMS') ? '' : getenv('_PROJECT_NAME_IDENTIFY_PARAMS');

	$_env_params						= !getenv('_ENV') ? '' : getenv('_ENV');
	$_path_main_miles_json 				= 'miles.json';
	$_folder_project					= 'projects';
	$_path_relative_project				=  $_folder_project . DIRECTORY_SEPARATOR . $_project_name_identifify_params . DIRECTORY_SEPARATOR;
	$_path_project_miles_json			=  $_path_relative_project . DIRECTORY_SEPARATOR . $_env_params . '.'  . $_path_main_miles_json;
	$_url_relative_project				=  $_folder_project . '/' . $_project_name_identifify_params . '/';
	$_url_project_miles_json			=  $_url_relative_project . $_env_params . '.miles.json';

	

	// Caso as variáveis venham por parametro
	if (isset($_GET['project_name_identifify_params']) && isset($_GET['env'])){
		$_project_name_identifify_params 	= $_GET['project_name_identifify_params'];
		$_path_project_miles_json			= $_path_relative_project . $_GET['env'] . '.'  . $_path_main_miles_json;
	}

	// Se o arquivo miles.json do projeto não for encontrado
	$_miles_json_root_file = file_exists($_path_project_miles_json) && sizeof(file($_path_project_miles_json)) > 0 ? $_path_project_miles_json : $_path_main_miles_json;

	$_is_installed						= file_exists($_path_project_miles_json);

	// URL da chamada do MILES
	$_http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

	$_url_install_miles 	= $_http_host . $_SERVER['REQUEST_URI'];

	$_is_miles_json_root_file = file_exists($_miles_json_root_file);

	// Config Miles ( miles.json ) na raiz
	if (!$_is_miles_json_root_file){
		
        $fpMilesJSON 			= fopen($_miles_json_root_file,"w");
        fwrite($fpMilesJSON,'
{
    "version": 2.0,
    "project":{
        "id":"miles",
        "name":"Miles",
        "path":"miles/",
        "url":"'.$_url_install_miles.'"
    },
    "currentproject": 1,
    "folder":"miles/",
    "system": {
        "url": {
            "lib": "https://teia.tec.br/miles/repository/lib/"
        },
        "request_protocol": "http",
        "packages": []
    },
    "theme": "desktop",
    "prefix": "td",
    "is_show_error_message": false,
    "is_show_warn_message": false,
    "is_transaction_log": false,
    "database_current": "desenv",
    "port": "hidden",
    "enviromment":"dev"
}
');

		fclose($fpMilesJSON);
	}

	$_miles_config_root 	= json_decode(file_get_contents($_miles_json_root_file));
	$_current_project_name	= isset($_miles_config_root->project->id) ? $_miles_config_root->project->id : $_project_name_identifify_params;
	$_current_environment 	= $_miles_config_root->enviromment;

	if ($_project_name_identifify_params == ''){
		$project_name_identify = str_replace(array('dev.','.com','.br','miles.','www.','loja.'),'',$_http_host);
	}else{
		$project_name_identify = $_project_name_identifify_params;
	}

	define('MILES_JSON_PROJECT',$project_name_identify);
	define('FOLDER_REPOSITORY', 'vendor'.DIRECTORY_SEPARATOR.'theusdido');
	define('PATH_REPOSITORY', FOLDER_REPOSITORY . DIRECTORY_SEPARATOR);
	define('FOLDER_MILES_LIBRARY','miles-library');
	define('FOLDER_LIBRARY_SYSTEM','system');
	define('PROJECT_NAME_IDENTIFY_PARAMS',$_project_name_identifify_params);

	// Raiz do servidor web, corresponde ao documentRoot
	define('PATH_ROOT',$_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR);

	if (!defined('AMBIENTE')) define('AMBIENTE','BIBLIOTECA');

	// Global com o nome do diretório da instalação do MILES
	if (AMBIENTE == 'SISTEMA'){
		$_path_miles 			= str_replace(array(PATH_ROOT,FOLDER_REPOSITORY),'',dirname(__DIR__));
		if (!file_exists($_path_miles)){
			$_path_miles = str_replace('miles'.DIRECTORY_SEPARATOR,'',$_path_miles);
		}
	}else{
		$_path_miles 			= str_replace(array(FOLDER_REPOSITORY),'',dirname(__DIR__));
	}

	$_path_miles_library 	= $_path_miles . PATH_REPOSITORY .  FOLDER_MILES_LIBRARY . DIRECTORY_SEPARATOR;
	$_path_library_system	= $_path_miles_library . FOLDER_LIBRARY_SYSTEM . DIRECTORY_SEPARATOR;	
	$_path_root_project		= $_path_miles . $_path_relative_project;

	// Onde está o Miles Framework, index.php
	define('PATH_MILES',$_path_miles);

	// Define o diretório da biblioteca dentro do vendor
	define ('PATH_MILES_LIBRARY',$_path_miles_library);

	// Define o diretório da biblioteca para include e require
	define('PATH_LIBRARY_SYSTEM', $_path_library_system);

	// Tratamento de Erros
	$_path_exception = PATH_LIBRARY_SYSTEM . 'exception.php';
	if (file_exists($_path_exception)){
		include $_path_exception;
	}else{
		echo 'Arquivo de Exceção não encontrado.';
		exit;
	}

	// Seta as constantes
	$_path_constantes = PATH_LIBRARY_SYSTEM . 'constantes.php';
	if (file_exists($_path_constantes)){
		include $_path_constantes;
	}else{
		echo 'Arquivo de Constantes não encontrado.';
		exit;
	}

	// Diretório de sistema para carregar as principais funcionaliades
	$_path_system				= PATH_LIBRARY_SYSTEM;
	$_path_config				= PATH_MILES_LIBRARY . FOLDER_CONFIG . DIRECTORY_SEPARATOR;
	$_path_class				= PATH_MILES_LIBRARY . FOLDER_CLASSES . DIRECTORY_SEPARATOR;
	$_path_controller			= PATH_MILES_LIBRARY . FOLDER_CONTROLLER . DIRECTORY_SEPARATOR;
	$_path_controller_install	= $_path_controller . FOLDER_INSTALL . DIRECTORY_SEPARATOR;
	$_path_config_project		= $_path_root_project . FOLDER_CONFIG . DIRECTORY_SEPARATOR;

	// Carrega biblioteca de funções independentes
	require $_path_system . 'functions.php';
	
	// Carrega as configurações do arquivo miles.json
	require $_path_system . 'miles.json.php';
	
	// Carrega os arquivos de configuração do sistema	
	require $_path_system . 'config.php';

	// Arquivo de compatibilidade entre versões
	include $_path_system . 'compatibilidade.php';

	// Conexão com banco de dados
	require $_path_system . 'connection.php';
	
	// Rotas
	require $_path_system . 'rota.php';

	if (AMBIENTE == 'SISTEMA'){
		// Fecha a transação com o banco de dados
		Transacao::Commit();
	}
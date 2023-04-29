<?php
	
	$_current_project_name	= 'opticaadolfo';
	$_current_environment 	= 'dev';

	$_project_name_identifify_params = isset($_GET['project_name_identifify_params']) 
	? $_GET['project_name_identifify_params']
	: (isset($_POST['project_name_identifify_params']) ? $_POST['project_name_identifify_params'] : 
	( isset($_project_name_identifify_params) ? $_project_name_identifify_params : $_current_project_name)
	);

	//$_project_name_identifify_params = 'villafrancioni';
	if ($_project_name_identifify_params == ''){
		$project_name_identify = str_replace(array('dev.','.com','.br','miles.','www.','loja.'),'',$_SERVER['HTTP_HOST']);
	}else{
		$project_name_identify = $_project_name_identifify_params;
	}

	define('MILES_JSON_PROJECT',$project_name_identify);
	define('FOLDER_REPOSITORY', 'vendor/theusdido');
	define('PATH_REPOSITORY', FOLDER_REPOSITORY . '/');
	define('FOLDER_MILES_LIBRARY','miles-library');
	define('FOLDER_LIBRARY_SYSTEM','system');

	// Raiz do servidor web, corresponde ao documentRoot
	define('PATH_ROOT',$_SERVER['DOCUMENT_ROOT'] . '/');

	if (!defined('AMBIENTE')) define('AMBIENTE','BIBLIOTECA');

	// Global com o nome do diretório da instalação do MILES
	if (AMBIENTE == 'SISTEMA'){
		$_path_miles 			= str_replace(array(PATH_ROOT,FOLDER_REPOSITORY),'',dirname(__DIR__));
		if (!file_exists($_path_miles)){
			$_path_miles = str_replace('miles/','',$_path_miles);
		}
	}else{
		$_path_miles 			= str_replace(array(FOLDER_REPOSITORY),'',dirname(__DIR__));
	}	
	
	$_path_miles_library 	= $_path_miles . PATH_REPOSITORY .  FOLDER_MILES_LIBRARY . '/';
	$_path_library_system	= $_path_miles_library . FOLDER_LIBRARY_SYSTEM . '/';
	$_path_root_project		= $_path_miles . $_project_name_identifify_params . '/';

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
	$_path_config				= PATH_MILES_LIBRARY . FOLDER_CONFIG . '/';
	$_path_class				= PATH_MILES_LIBRARY . FOLDER_CLASSES . '/';
	$_path_controller			= PATH_MILES_LIBRARY . FOLDER_CONTROLLER . '/';
	$_path_controller_install	= $_path_controller . FOLDER_INSTALL . '/';

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
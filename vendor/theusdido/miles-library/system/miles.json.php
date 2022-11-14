<?php	
	$script_uri				= isset($_SERVER["SCRIPT_URI"]) ? $_SERVER["SCRIPT_URI"] : '';
	$request_uri			= isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '';
	$uri 					= $script_uri != '' ? $script_uri : $request_uri;

	$path_miles_json 		= PATH_MILES . "miles.json";

	if (!file_exists($path_miles_json)){
		include $_path_controller_install . 'criarmilesjson.php';
	}

	if (sizeof(file($path_miles_json)) <= 0){
		echo 'Arquivo <strong>miles.json</strong> está comrrompido.';
		exit;
	}

	$_project_name	= '';
	$_enviromment	= 'dev';
	$_env 			= '';	
	$miles_json 	= file_get_contents($path_miles_json);

	if ($miles_json != false){

		// MILES JSON CONFIG
		$mjc = json_decode($miles_json);

		// Seta o projeto no MILES 
		define('MILES_PROJECT',isset($mjc->currentproject)?$mjc->currentproject:1);

		// Ambiente selecionado
		$_enviromment = isset($mjc->enviromment) ? $mjc->enviromment : '';
		
		// Carrega os dados do ambiente selecionado
		$_env = isset($mjc->enviromments->{$_enviromment}) ? $mjc->enviromments->{$_enviromment} : $mjc;

		// Diretório da instalação do MILES FRAMEWORK
		define("FOLDER_MILES",$mjc->folder);

		// Atualiza o nome do diretório de instalação do MILES
		$_folder_miles = FOLDER_MILES;

		// Nome do Projeto
		$_project_name = isset($mjc->project->name)?$mjc->project->name:'';

	}	

	define('PROJECT_NAME',$_project_name);

	// Define o ambiente do sistema
	define('_ENVIROMMENT',$_enviromment);

	if (!isset($mjc->system->request_protocol)){
		showMessage('Parametro <b>system:"request_protocol"</b> em miles.json não especificado.');
		exit;
	}

	if (!isset($mjc->system->packages)){
		showMessage('Parametro <b>system:"packages"</b> em miles.json não especificado.');
		exit;
	}

	if (!isset($mjc->is_show_error_message)){
		showMessage('Parametro <b>is_show_error_message</b> em miles.json não especificado.');
		exit;
	}

	if (!isset($mjc->is_transaction_log)){
		showMessage("Parametro <b>is_transaction_log</b> em miles.json não especificado.");
		exit;
	}
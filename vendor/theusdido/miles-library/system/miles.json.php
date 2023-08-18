<?php
	$script_uri				= isset($_SERVER["SCRIPT_URI"]) ? $_SERVER["SCRIPT_URI"] : '';
	$request_uri			= isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '';
	$uri 					= $script_uri != '' ? $script_uri : $request_uri;

	$_project_name		= 
	$_project_path 		= 
	$_project_folder	= 
	$_env 				= '';
	$_enviromment		= isset($_GET['env']) ? $_GET['env'] : (isset($_POST['env']) ? $_POST['env'] : (isset($_enviromment) ? $_enviromment : $_current_environment));

	// Deveria criar esse arquivo apenas na instalação ?
	if (!file_exists($_miles_json_root_file)){
		include $_path_controller_install . 'criarmilesjson.php';
	}

	if (sizeof(file($_miles_json_root_file)) <= 0){
		echo 'Arquivo <strong>miles.json</strong> está comrrompido.';
		exit;
	}

	$miles_json 		= file_get_contents($_miles_json_root_file);

	if ($miles_json != false){

		// MILES JSON CONFIG
		$mjc = json_decode($miles_json);

		// Seta o projeto no MILES 
		define('MILES_PROJECT',isset($mjc->currentproject)?$mjc->currentproject:1);

		// Ambiente selecionado
		if ($_enviromment == ''){
			$_enviromment = isset($mjc->enviromment) ? $mjc->enviromment : '';
		}

		// Carrega os dados do ambiente selecionado
		$_env = isset($mjc->enviromments->{$_enviromment}) ? $mjc->enviromments->{$_enviromment} : $mjc;

		$_folder_miles_ = PATH_MILES;
		if (AMBIENTE == 'SISTEMA'){

			// Diretório da instalação do MILES FRAMEWORK
			define("FOLDER_MILES",$mjc->folder);

			// Atualiza o nome do diretório de instalação do MILES
			$_folder_miles = FOLDER_MILES;

			$_folder_miles_ = '';

		}

		// Nome do Projeto
		$_project_name 		= isset($mjc->project->name)?$mjc->project->name:'';
		$_project_folder	= isset($mjc->project->path)?$mjc->project->path:'';
		$_project_path 		= $_folder_miles_ . $_project_folder;
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
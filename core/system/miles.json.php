<?php	
	$script_uri				= isset($_SERVER["SCRIPT_URI"]) ? $_SERVER["SCRIPT_URI"] : '';
	$request_uri			= isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '';
	$uri 					= $script_uri != '' ? $script_uri : $request_uri;
	$path_miles_json 		= PATH_MILES . "miles.json";

	if (!file_exists($path_miles_json)){
<<<<<<< HEAD
		include PATH_MILES . 'core/controller/install/criarmilesjson.php';
	}

	if (sizeof(file($path_miles_json)) <= 0){
		echo 'Arquivo <strong>miles.json</strong> está comrrompido.';
		exit;
=======
		include 'core/controller/install/criarmilesjson.php';
		#echo 'Arquivo <b>miles.json</b> não encontrado no diretório raiz';
		#exit;
>>>>>>> 287b430 (instalação góes)
	}

	$miles_json = file_get_contents($path_miles_json);
	if ($miles_json !== false){

		// MILES JSON CONFIG
		$mjc = json_decode($miles_json);
		// Seta o projeto no MILES 
		define('MILES_PROJECT',$mjc->currentproject);
		
		// Diretório da instalação do MILES FRAMEWORK
		define("FOLDER_MILES",$mjc->folder);
	}

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
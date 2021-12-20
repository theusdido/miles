<?php	
	$uri 					= isset($_SERVER["SCRIPT_URI"]) ? $_SERVER["SCRIPT_URI"] : $_SERVER["REQUEST_URI"];
	$path_miles_json 		= PATH_MILES . "miles.json";

	if (!file_exists($path_miles_json)){
		include PATH_MILES . 'core/controller/install/criarmilesjson.php';
	}

	if (sizeof(file($path_miles_json)) <= 0){
		echo 'Arquivo <strong>miles.json<</strong> está comrrompido.';
		exit;
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
		echo 'Parametro <b>system:"request_protocol"</b> em miles.json não especificado.';
		exit;
	}

	if (!isset($mjc->system->package)){
		echo 'Parametro <b>system:"package"</b> em miles.json não especificado.';
		exit;
	}
	if (!isset($mjc->system->packages)){
		echo 'Parametro <b>system:"packages"</b> em miles.json não especificado.';
		exit;
	}

	if (!isset($mjc->is_show_error_message)){
		echo 'Parametro <b>is_show_error_message</b> em miles.json não especificado.';
		exit;
	}
	

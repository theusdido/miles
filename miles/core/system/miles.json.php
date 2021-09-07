<?php	
	$uri = isset($_SERVER["SCRIPT_URI"]) ? $_SERVER["SCRIPT_URI"] : $_SERVER["REQUEST_URI"];
	$path_miles_json = PATH_MILES . "miles.json";

	if (!file_exists($path_miles_json)){
		echo 'Arquivo <b>miles.json</b> não encontrado no diretório raiz';
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
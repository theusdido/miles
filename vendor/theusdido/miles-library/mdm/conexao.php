<?php
	
	$relative_path	= '../../../../';
	$miles_json 	= json_decode(file_get_contents($relative_path . 'miles.json'));
	date_default_timezone_set('America/Sao_Paulo');	

	if (isset($miles_json->currentproject)){
		$_currentproject_id = $miles_json->currentproject;
	}else{
		$_currentproject_id = isset($_GET["currentproject"])?$_GET["currentproject"]:(isset($_POST["currentproject"])?$_POST["currentproject"]:1);
	}

	if (@session_id() == ""){
		session_name("miles_SISTEMA_" . $_currentproject_id);
		session_start();
	}
	$request_schema = isset($_SERVER["HTTP_X_FORWARDED_PROTO"])?$_SERVER["HTTP_X_FORWARDED_PROTO"]:$_SERVER["REQUEST_SCHEME"];
	
	
	define('CURRENT_PROJECT_ID',$_currentproject_id);
	define('IS_SHOW_ERROR_MESSAGE',true);
	define('URL_FAVICON','');
	define('URL_LIB', $miles_json->system->url->lib);
	define('URL_MILES',$request_schema . '://'.$_SERVER["HTTP_HOST"].'/' . $miles_json->folder);
	define('PATH_CONFIG','../config/');
	define('PREFIXO',$miles_json->prefix . '_');
	define('URL_API',$relative_path . 'index.php');
	define('FOLDER_PROJECT','project');
	define('URL_PROJECT', URL_MILES . FOLDER_PROJECT .'/');
	define('PATH_PROJECT',$relative_path . FOLDER_PROJECT .'/');
	define('PATH_CURRENT_CONFIG_PROJECT',PATH_PROJECT . 'config');
	define('URL_CURRENT_THEME', URL_PROJECT . 'tema/' . $miles_json->theme .'/');
	

	$config_file 		= PATH_CURRENT_CONFIG_PROJECT. "current_config.inc";

	require '../classes/bd/conexao.class.php';
	$currenttypedatabase 	= $miles_json->database_current;
	$currentprojectname 	= $miles_json->project->name;

	define("DATABASECONNECTION",$currenttypedatabase);
	
	
	$conn = Conexao::Abrir($currenttypedatabase);

	// Conexão com a base central foi descontinuada
	$connMiles = $conn;

	switch($currenttypedatabase){
		case 'desenv': $type = 1; break;
		case 'producao': $type = 4; break;
		default: $type = 1;
	}

	$sqlProductionDB = "SELECT * from td_connectiondatabase WHERE projeto = {$_currentproject_id} AND type = 4";
	$queryProductionDB = $connMiles->query($sqlProductionDB);
	if ($queryProductionDB->rowCount() > 0){
		if ($linhaProductionDB = $queryProductionDB->fetch()){
			$tipo 		= "mysql";
			$host 		= $linhaProductionDB["host"];
			$porta 		= $linhaProductionDB["port"];
			$base 		= $linhaProductionDB["base"];
			$usuario 	= $linhaProductionDB["user"];
			$senha 		= $linhaProductionDB["password"];
			try{
				$connProducao = new PDO("$tipo:host=$host;port=$porta;dbname=$base",$usuario,$senha);
			}catch(Exception $e){
				$connProducao = null;
			}
		}else{
			$connProducao = null;
		}
	}else{
		$connProducao = null;
	}
	
	$sqlCurrentDB = "SELECT * from td_connectiondatabase WHERE projeto = {$_currentproject_id} AND type = {$type}";
	$queryCurrentDB = $connMiles->query($sqlCurrentDB);
	if ($linhaCurrentDB = $queryCurrentDB->fetch()){
		$tipo 		= "mysql";
		$host 		= $linhaCurrentDB["host"];
		$porta 		= $linhaCurrentDB["port"];
		$base		= $linhaCurrentDB["base"];
		$usuario 	= $linhaCurrentDB["user"];
		$senha 		= $linhaCurrentDB["password"];
		$conn 		= new PDO("$tipo:host=$host;port=$porta;dbname=$base",$usuario,$senha);
	}
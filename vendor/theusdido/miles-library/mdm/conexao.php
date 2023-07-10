<?php

	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);

	$relative_path	= '../../../../';
	
	// Config Miles ( miles.json ) na raiz
	$_miles_json_root_file = $relative_path . 'miles.json';
	if (!file_exists($_miles_json_root_file)){
		echo 'Arquivo miles.json não encontrado !';
		exit;
	}

	$_miles_config_root 	= json_decode(file_get_contents($_miles_json_root_file));
	$_current_project_name	= $_miles_config_root->project->id;
	$_current_environment 	= $_miles_config_root->enviromment;

	$miles_json 	= json_decode(file_get_contents($relative_path . $_current_project_name . '/'.$_current_environment.'.miles.json'));
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

	if (!isset($_SESSION['userid'])){
		echo 'Sessão foi encerrada!';
		exit;
	}

	$request_schema 	= isset($_SERVER["HTTP_X_FORWARDED_PROTO"])?$_SERVER["HTTP_X_FORWARDED_PROTO"]:$_SERVER["REQUEST_SCHEME"];
	$_env 				= $miles_json->enviromments->{$miles_json->enviromment};
	$is_fixed_domain 	= isset($_env->system->is_fixed_domain) ? $_env->system->is_fixed_domain : false;

	define('CURRENT_PROJECT_ID',$_currentproject_id);
	define('IS_SHOW_ERROR_MESSAGE',true);
	define('URL_FAVICON','');
	define('URL_LIB', $miles_json->system->url->lib);
	define('URL_MILES',$request_schema . '://'.$_SERVER["HTTP_HOST"].'/' . ($is_fixed_domain ? '' : $miles_json->folder));
	define('URL_MILES_LIBRARY',URL_MILES . 'vendor/theusdido/miles-library/');
	define('PATH_CONFIG','../config/');
	define('PREFIXO',$miles_json->prefix . '_');
	define('URL_API',$relative_path . 'index.php');
	define('FOLDER_PROJECT',$miles_json->project->path);
	define('URL_PROJECT', URL_MILES . $miles_json->project->path);
	define('PATH_PROJECT',$relative_path . $miles_json->project->path);
	define('PATH_CURRENT_CONFIG_PROJECT',PATH_PROJECT . 'config/');
	define('URL_CURRENT_THEME', URL_PROJECT . 'tema/' . $miles_json->theme .'/');
	define('URL_THEME',URL_MILES_LIBRARY . 'tema/' . $miles_json->theme .'/');
	define('PATH_TMP', $relative_path . 'tmp/');
	define('URL_CURRENT_FILES', URL_PROJECT . 'files/');

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
<?php

	$miles_json 	= json_decode(file_get_contents('../../miles.json'));
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

	define('CURRENT_PROJECT_ID',$_currentproject_id);
	define('IS_SHOW_ERROR_MESSAGE',true);
	define('URL_FAVICON','');

	$currentproject 	= $_currentproject_id;	
	$config_path 		= "../../projects/{$currentproject}/config/";
	$config_file 		= $config_path . "current_config.inc";

	require '../classes/bd/conexao.class.php';
	if (file_exists($config_file)){
		$config = parse_ini_file($config_file);
	}else{
		throw new Exception("Arquivo configuração não existe");
	}

	$currenttypedatabase 	= $miles_json->database_current;
	$currentprojectname 	= $miles_json->project->name;

	define("DATABASECONNECTION",$currenttypedatabase);
	define("PATH_CURRENT_CONFIG_PROJECT",$config_path);	
	
	$conn = Conexao::Abrir($currenttypedatabase);

	// Conexão com a base central foi descontinuada
	$connMiles = $conn;

	switch($currenttypedatabase){
		case 'desenv': $type = 1; break;
		case 'producao': $type = 4; break;
		default: $type = 1;
	}

	$sqlProductionDB = "SELECT * from td_connectiondatabase WHERE projeto = {$currentproject} AND type = 4";
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
	
	$sqlCurrentDB = "SELECT * from td_connectiondatabase WHERE projeto = {$currentproject} AND type = {$type}";
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

	define('URL_LIB', $miles_json->system->url->lib);
	define('URL_MILES','https://primodass.com.br/miles/');
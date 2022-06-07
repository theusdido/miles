<?php
	date_default_timezone_set('America/Sao_Paulo');	
	if (@session_id() == ""){
		$currentProjectParams = isset($_GET["currentproject"])?$_GET["currentproject"]:(isset($_POST["currentproject"])?$_POST["currentproject"]:1);
		session_name("miles_SISTEMA_" . $currentProjectParams);
		session_start();
	}
	
	if (isset($_SESSION["currentproject"])){
		$currentproject = $_SESSION["currentproject"];
	}else{
		$_SESSION["currentproject"] = $currentProjectParams;
		$currentproject 			= $currentProjectParams;
	}
	
	$config_path = "../../projects/".$currentproject."/config/";
	$config_file = $config_path . "current_config.inc";

	require '../classes/bd/conexao.class.php';
	if (file_exists($config_file)){
		$config = parse_ini_file($config_file);
	}else{
		throw new Exception("Arquivo configuração não existe");
	}

	$currenttypedatabase 	= isset($_SESSION["currenttypedatabase"]) ? $_SESSION["currenttypedatabase"] : $config["CURRENT_DATABASE"];
	$currentprojectname 	= isset($_SESSION["currentprojectname"]) ? $_SESSION["currentprojectname"] : $config["PROJETO_DESC"];


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

	define('URL_MILES',$_SESSION['URL_MILES']);

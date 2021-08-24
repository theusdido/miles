<?php
	
	// Projecto atual
	$currentproject 		= isset($_GET["currentproject"])?$_GET["currentproject"]:(isset($_POST["currentproject"])?$_POST["currentproject"]:1);

	// Ambiente atual
	$ambiente			= isset($_GET["ambiente"])?$_GET["ambiente"]:(isset($_POST["ambiente"])?$_POST["ambiente"]:'SISTEMA');
		
	// Abre a sessão
	if (!isset($_SESSION)){
		session_name("miles_" . $ambiente . "_" . $currentproject);
		session_start();
	}	

	$currenttypedatabase 	= isset($_SESSION["currenttypedatabase"])?$_SESSION["currenttypedatabase"]:'desenv';
	$pathconfig = $_SESSION["PATH_CURRENT_PROJECT"] . "config/current_config.inc";
	$bdPathFile = $_SESSION["PATH_CURRENT_PROJECT"] . "config/{$currenttypedatabase}_mysql.ini";


	$config 	= parse_ini_file($pathconfig);	
	$bdConfig 	= parse_ini_file($bdPathFile);

	$usuario 	= $bdConfig["usuario"];
	$senha 		= $bdConfig["senha"];
	$base 		= $bdConfig["base"];
	$host		= $bdConfig["host"];
	$tipo		= $bdConfig["tipo"];
	$porta		= $bdConfig["porta"];

	if (isset($_POST["installsystem"])){
		if ((int)$_POST["installsystem"] == 1){
			$installsystem = 1;
			$usuario 	= $_POST["usuario"];
			$senha 		= $_POST["senha"];
			$base 		= $_POST["base"];
			$host		= $_POST["host"];
			$tipo		= $_POST["tipo"];
			$porta		= $_POST["porta"];
		}else{
			$installsystem = 0;
		}
	}else{
		$installsystem = 0;
	}
	try{
		$conn = new PDO("$tipo:host=$host;port=$porta;dbname=$base",$usuario,$senha);
	}catch(Exception $e){
		echo 'Não conectou na base de dados. Verifique a configuração do arquivo de banco de dados.';
		exit;
	}
	
	if (!defined("SCHEMA")){
		define("SCHEMA",$base);
	}
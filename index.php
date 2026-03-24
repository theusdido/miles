<?php
	
	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);
	ini_set('error_log', '/var/www/miles/php_error.log');

	// Permitir Acesso Externo
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: *");
	header('Access-Control-Allow-Methods: GET, POST');
	header("Access-Control-Allow-Headers: X-Requested-With");
	
	// Define o ambiente como sistema	
	define('AMBIENTE','SISTEMA');

	// Carrega a biblioteca MILES FRAMEWORK PH
	require __DIR__ . '/vendor/theusdido/miles-library/autoload.php';
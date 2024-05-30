<?php

	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);

	// Permitir Acesso Externo
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: *");
	header('Access-Control-Allow-Methods: GET, POST');
	header("Access-Control-Allow-Headers: X-Requested-With");
	
	// Define o ambiente como sistema	
	define('AMBIENTE','SISTEMA');

	// Carrega a biblioteca MILES FRAMEWORK PHP
	require __DIR__ . '/vendor/theusdido/miles-library/autoload.php';
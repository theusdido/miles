<?php
	// Permitir Acesso Externo
	header("Access-Control-Allow-Origin: *");

	// Define o ambiente como sistema
	define('AMBIENTE','SISTEMA');

	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);

	// Carrega a biblioteca MILES FRAMEWORK PHP
	require 'vendor/theusdido/miles-library/autoload.php';

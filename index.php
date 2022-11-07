<?php
	// Permitir Acesso Externo
	header("Access-Control-Allow-Origin: *");

	// Define o ambiente como sistema
	define('AMBIENTE','SISTEMA');

	// Carrega a biblioteca MILES FRAMEWORK PHP
	require __DIR__ . '/vendor/theusdido/miles-library/autoload.php';

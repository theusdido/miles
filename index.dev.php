<?php

	// Permitir Acesso Externo
	header("Access-Control-Allow-Origin: *");

	// Define o ambiente como sistema
	define('AMBIENTE','SISTEMA');

	// Carrega a biblioteca MILES FRAMEWORK PHP
	require '/miles-library/autoload.php';
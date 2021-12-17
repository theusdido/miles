<?php

	#***************************************
	# SISTEMA
	#*************************************** 

	// Seta o ambiente da requisição
	if (!defined("AMBIENTE")) define("AMBIENTE","BIBLIOTECA");
	
	// HTTP HOST - URL da chamada da aplicação
	define("HTTP_HOST",$_SERVER["HTTP_HOST"]);
	
	#***************************************
	# FOLDER
	#*************************************** 

	// FOLDER CORE
	define("FOLDER_CORE","core");

	// FOLDER PROJECT
	define("FOLDER_PROJECT","projects");
	
	// FOLDER PROJECT
	define("FOLDER_SYSTEM","system");
	
	// FOLDER CLASSES
	define("FOLDER_CLASSES","classes");
	
	// FOLDER CONFIG
	define("FOLDER_CONFIG","config");
	
	// FOLDER BUILD
	define("FOLDER_BUILD","build");	

	// FOLDER PAGE
	define("FOLDER_PAGE","page");

	include 'path.php';
	
	#***************************************
	# SQL
	#***************************************

	// Operador E
	define('E','AND ');

	// Operador OU
	define('OU','OR ');

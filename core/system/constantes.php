<?php

	#***************************************
	# SISTEMA
	#*************************************** 

	// Seta o ambiente da requisição
	if (!defined("AMBIENTE")) define("AMBIENTE","BIBLIOTECA");
	
	// HTTP HOST - URL da chamada da aplicação
	define("HTTP_HOST", isset($_SERVER["HTTP_HOST"]) ? preg_replace('/:[0-9]+/i','',$_SERVER["HTTP_HOST"])  : '');
	
	#***************************************
	# FOLDER
	#*************************************** 

	// FOLDER CORE
	define("FOLDER_CORE","core");

	// FOLDER PROJECT
	define("FOLDER_PROJECT","project");

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

	// FOLDER WEBSITE
	define("FOLDER_WEBSITE","website");

	// FOLDER COMPONENT
	define('FOLDER_COMPONENT', 'component');
	
	#***************************************
	# SQL
	#***************************************

	// Operador E
	define('E','AND ');

	// Operador OU
	define('OU','OR ');

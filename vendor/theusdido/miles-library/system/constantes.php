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

	// FOLDER WEBSITE
	define("FOLDER_WEBSITE","website");

	// FOLDER COMPONENT
	define('FOLDER_COMPONENT', 'component');

	// FOLDER CONTROLLER
	define('FOLDER_CONTROLLER', 'controller');

	// FOLDER INSTALL
	define('FOLDER_INSTALL', 'install');

	// FOLDER BUILD JS
	define('FOLDER_BUILD_JS', FOLDER_BUILD . '/js/');

	// FOLDER INSTALL
	define('FOLDER_THEME', 'tema');


	#***************************************
	# SQL
	#***************************************

	// Operador E
	define('E','AND ');

	// Operador OU
	define('OU','OR ');

	#***************************************
	# NOME DE ATRIBUTOS ADICIONAIS 
	#***************************************

	// Atributo formatado como Data
	define("ATTR_DATEFORMATTED",'_dateformatted');

	// Atributo formatado como Data e Hora
	define("ATTR_DATETIMEFORMATTED",'_datetimeformatted');

	// Atributo formatado como Moeda
	define("ATTR_MONEYFORMATTED",'_moneyformatted');

	// Atributo Objeto
	define("ATTR_OBJ",'_obj');

	// Atributo Descrição
	define("ATTR_DESC",'_desc');

	// Atributo SRC
	define("ATTR_SRC",'_src');

	// Atributo Traduções
	define("ATTR_TRADUCOES",'_traducoes');

	// Tipos de dados para campos adicionais
	define("ADDITIONAL_FIELD_TYPES",[ATTR_DESC,ATTR_SRC,ATTR_DATEFORMATTED,ATTR_DATETIMEFORMATTED,ATTR_MONEYFORMATTED,ATTR_OBJ]);



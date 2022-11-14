<?php

	// Nome do arquivo do Favicon do sistema
	define('FILE_FAVICON','favicon.png');

	// Caminho do Favicon do projeto
	define("FILE_CURRENT_FAVICON",isset($config["FAVICON"])?$config["FAVICON"]:FILE_FAVICON);

	// Logo Padrão
	define("FILE_LOGO", 'logo.png');

	// Logo Padrão do Rodapé
	define("FILE_LOGO_RODAPE", isset($config["LOGO_RODAPE"]) ? $config["LOGO_RODAPE"] : 'logo.png');

	// Arquivo atual de configuração 
	define('FILE_CURRENT_CONFIG_PROJECT',PATH_CURRENT_CONFIG_PROJECT. "current_config.inc");

	// MDM Javascript File Compile
	define("FILE_MDM_JS_COMPILE", 'mdm.js');

	// Logo padrão do sistema
    define('PATH_CURRENT_LOGO_PADRAO', PATH_CURRENT_PROJECT_THEME . FILE_LOGO);

	// Imagem de rodapé padrão do sistema
    define('PATH_IMG_RODAPE_PADRAO', FILE_LOGO_RODAPE);

    define('PATH_CURRENT_FAVICON', PATH_CURRENT_PROJECT_THEME . FILE_CURRENT_FAVICON);  

	define('FILE_SYSTEM_FAVICON', URL_SYSTEM_THEME . FILE_FAVICON);

	define('URL_NOIMAGE', URL_MILES . 'assets/img/noimage.png');

	define('FILE_BACKGROUND','background.jpg');

	define('URL_CURRENT_LOGO_PADRAO', URL_CURRENT_PROJECT_THEME . FILE_LOGO);

	define('URL_CURRENT_FAVICON', URL_CURRENT_PROJECT_THEME . FILE_CURRENT_FAVICON);
	
	define('URL_FAVICON', URL_SYSTEM_THEME . FILE_FAVICON);

	define('URL_LOGO', URL_SYSTEM_THEME . FILE_LOGO);
	define('URL_BACKGROUND',URL_SYSTEM_THEME . FILE_BACKGROUND);
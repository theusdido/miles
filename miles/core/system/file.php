<?php
	// Caminho do Favicon do projeto
	define("FILE_CURRENT_FAVICON",isset($config["FAVICON"])?$config["FAVICON"]:'');

	// Nome do arquivo do Favicon do sistema
	define('FILE_FAVICON','logo-favicon.png');

	// Logo Padrão
	define("FILE_LOGO", isset($config["LOGO"]) ? $config["LOGO"] : 'logo.png');

	// Logo Padrão do Rodapé
	define("FILE_LOGO_RODAPE", isset($config["LOGO_RODAPE"]) ? $config["LOGO_RODAPE"] : 'logo.png');

	// Arquivo atual de configuração 
	define('FILE_CURRENT_CONFIG_PROJECT',PATH_CURRENT_CONFIG_PROJECT. "current_config.inc");

	// MDM Javascript File Compile
	define("FILE_MDM_JS_COMPILE",FOLDER_BUILD . "/js/mdm.js");


	// Logo padrão do sistema
    define('PATH_CURRENT_LOGO_PADRAO', PATH_CURRENT_PROJECT_THEME . FILE_LOGO );

	// Imagem de rodapé padrão do sistema
    define('PATH_IMG_RODAPE_PADRAO', FILE_LOGO_RODAPE);

    define('PATH_CURRENT_FAVICON', PATH_CURRENT_PROJECT_THEME . FILE_CURRENT_FAVICON);
    
    define("PATH_MDM_JS_COMPILE", PATH_CURRENT_PROJECT  . FILE_MDM_JS_COMPILE);

	define('FILE_SYSTEM_FAVICON',Session::Get("URL_SYSTEM_THEME") . "logo-favicon.png");

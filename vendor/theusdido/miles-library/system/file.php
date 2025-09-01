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

	define('URL_NOIMAGE', URL_ASSETS . 'img/noimage.png');

	define('FILE_BACKGROUND','background.jpg');

	define('URL_CURRENT_LOGO_PADRAO', URL_CURRENT_PROJECT_THEME . FILE_LOGO);

	define('URL_CURRENT_FAVICON', URL_CURRENT_PROJECT_THEME . FILE_CURRENT_FAVICON);
	
	define('URL_FAVICON', URL_SYSTEM_THEME . FILE_FAVICON);

	define('URL_LOGO', URL_SYSTEM_THEME . FILE_LOGO);

	define('URL_BACKGROUND',URL_SYSTEM_THEME . FILE_BACKGROUND);

	// Arquivos de layout do sistema
	define('FILE_LAYOUT_STYLE_THEME',PATH_SYSTEM_THEME . 'layout.css');
	define('FILE_COLOR_STYLE_THEME',PATH_SYSTEM_THEME . 'color.css');
	define('FILE_COLOR_STYLE_MENULEFT',PATH_SYSTEM_THEME . 'menu-left.css');

	define('URL_FILE_LAYOUT_STYLE_THEME',URL_SYSTEM_THEME . 'layout.css');
	define('URL_FILE_COLOR_STYLE_THEME',URL_SYSTEM_THEME . 'color.css');
	define('URL_FILE_COLOR_STYLE_MENULEFT',URL_SYSTEM_THEME . 'menu-left.css');

	// Arquivos de layout do projeto
	define('FILE_CURRENT_LAYOUT_STYLE_THEME',PATH_CURRENT_PROJECT_THEME . 'layout.css');
	define('FILE_CURRENT_COLOR_STYLE_THEME',PATH_CURRENT_PROJECT_THEME . 'color.css');
	define('FILE_CURRENT_COLOR_STYLE_MENULEFT',PATH_CURRENT_PROJECT_THEME . 'menu-left.css');

	define('URL_CURRENT_FILE_LAYOUT_STYLE_THEME',URL_CURRENT_PROJECT_THEME . 'layout.css');
	define('URL_CURRENT_FILE_COLOR_STYLE_THEME',URL_CURRENT_PROJECT_THEME . 'color.css');
	define('URL_CURRENT_FILE_COLOR_STYLE_MENULEFT',URL_CURRENT_PROJECT_THEME . 'menu-left.css');	

	// Caminho padrão para os arquivos de classe do arquivo do JSON do Firebase
	define("PATH_CURRENT_FIREBASE_JSON_CONFIG",PATH_CURRENT_CONFIG_PROJECT . 'firebase.json');

	define("FILE_CURRENT_LAYOUT_STYLE_THEME_GERAL",FILE_CURRENT_LAYOUT_STYLE_THEME . "geral.css");
	define("URL_CURRENT_FILE_CURRENT_LAYOUT_STYLE_THEME_GERAL",URL_CURRENT_PROJECT_THEME . "geral.css");

	
	define("FILE_SYSTEM_THEME_GERAL",PATH_SYSTEM_THEME . "geral.css");
	define("URL_SYSTEM_THEME_GERAL",URL_SYSTEM_THEME . "geral.css");

	define("FILE_SYSTEM_GRADEDEDADOS",PATH_SYSTEM_THEME . "gradesdedados.css");
	define("URL_SYSTEM_GRADEDEDADOS",URL_SYSTEM_THEME . "gradesdedados.css");
	
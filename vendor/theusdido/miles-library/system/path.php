<?php
	#***************************************
	# PATH
	#***************************************

	// Diretório dos arquivos de sistema
	define('PATH_SYSTEM', PATH_MILES_LIBRARY . FOLDER_SYSTEM . DIRECTORY_SEPARATOR);

	// * 
	// * Caminhos estáticos do sistema
	// *

	// Diretório dos arquivos de configuração
	define('PATH_CONFIG', PATH_MILES_LIBRARY . FOLDER_CONFIG . DIRECTORY_SEPARATOR);

	// Caminho padrão para a classes de models do MVC ( MODEL )
	define('PATH_MVC_MODEL',PATH_MILES_LIBRARY.'model' . DIRECTORY_SEPARATOR);

	// Caminho padrão para a classes de views do MVC ( VIEW )
	define('PATH_MVC_VIEW',PATH_MILES_LIBRARY.'view' . DIRECTORY_SEPARATOR);

	// Caminho padrão para a classes de controllers do MVC ( CONTROLLER )
	define('PATH_MVC_CONTROLLER', PATH_MILES_LIBRARY . 'controller' . DIRECTORY_SEPARATOR);
	
	// Caminho padrão para as classes
	define('PATH_CLASS',PATH_MILES_LIBRARY . 'classes' . DIRECTORY_SEPARATOR);

	// Caminho padrão para as blibiotecas
	define('PATH_LIB',PATH_MILES_LIBRARY . 'lib' . DIRECTORY_SEPARATOR);

	// Caminho padrão para as imagens
	define('PATH_IMG',PATH_MILES_LIBRARY . 'images' . DIRECTORY_SEPARATOR);

	// Caminho padrão para a blibioteca ADO ( ADVANCED DATA OBJECT )
	define('PATH_ADO',PATH_CLASS.'ado' . DIRECTORY_SEPARATOR);

	// Caminho padrão para a blibioteca BD ( BANCO DE DADOS )
	define('PATH_BD',PATH_CLASS.'bd' . DIRECTORY_SEPARATOR);

	// Caminho padrão para a biblioteca TDC ( THEUSDIDO CLASSES )
	define('PATH_TDC',PATH_CLASS.'tdc' . DIRECTORY_SEPARATOR);

	// Caminho padrão para as classes personalizadas
	define('PATH_CLASS_CUSTOM',PATH_CLASS.'custom' . DIRECTORY_SEPARATOR);

	// Caminho padrão para as classes personalizadas
	define('PATH_CLASS_INSTALL',PATH_CLASS.'install' . DIRECTORY_SEPARATOR);
	
	// Caminho padrão para a biblioteca WIDGETS ( ELEMENTOS VISUAIS )
	define('PATH_WIDGETS',PATH_CLASS.'widgets' . DIRECTORY_SEPARATOR);

	// Caminho padrão para os componentes do BOOTSTRAP
	define('PATH_BOOTSTRAP',PATH_CLASS.'widgets'.DIRECTORY_SEPARATOR.'bootstrap' . DIRECTORY_SEPARATOR);

	// Caminho padrão para os arquivos de log
	define('PATH_LOG',PATH_MILES . "log" . DIRECTORY_SEPARATOR);

	// Caminho padrão para os arquivos de debug
	define('PATH_DEBUG',PATH_MILES . "debug" . DIRECTORY_SEPARATOR);
	
	// Caminho padrão para os arquivos de cada projeto
	define('PATH_PROJECT',$_project_path == '' ? PATH_MILES . FOLDER_PROJECT . DIRECTORY_SEPARATOR : FOLDER_PROJECT . DIRECTORY_SEPARATOR . $_project_path);

	// Caminho padrão para arquivos do E-Commerce
	define('PATH_ECOMMERCE', PATH_MVC_CONTROLLER . "ecommerce" . DIRECTORY_SEPARATOR);

	// Caminho padrão para os arquivos de classe Interface
	define('PATH_CLASS_INTERFACE',PATH_CLASS.'interface' . DIRECTORY_SEPARATOR);

	// Caminho padrão para os arquivos de classe do E-Commerce
	define('PATH_CLASS_ECOMMERCE',PATH_CLASS.'ecommerce' . DIRECTORY_SEPARATOR);

	// Caminho padrão para os arquivos de classe do Sistema
	define('PATH_CLASS_SYSTEM',PATH_CLASS.'system' . DIRECTORY_SEPARATOR);

	// Caminho da Páginas
	define("PATH_SYSTEM_PAGE",PATH_MILES_LIBRARY . "page" . DIRECTORY_SEPARATOR);

	// Caminho dos Componentes
	define("PATH_SYSTEM_COMPONENT",PATH_MILES_LIBRARY . "component" . DIRECTORY_SEPARATOR);

	// Caminho de padrão para os arquivos de instalação
	define('PATH_INSTALL', PATH_MILES_LIBRARY . 'install' . DIRECTORY_SEPARATOR);

	// Caminho dos arquivos de instalação de pacotes ( módulos )
	define('PATH_PACKAGE', PATH_INSTALL . 'package' . DIRECTORY_SEPARATOR);

	// Caminho dos arquivos de registros padrão
	define('PATH_REGISTRO', PATH_INSTALL . 'registro' . DIRECTORY_SEPARATOR);

	// * 
	// * Caminhos estáticos do projeto
	// *

	// Caminho para o tema
	define("PATH_THEME",'tema' . DIRECTORY_SEPARATOR . CURRENT_THEME . DIRECTORY_SEPARATOR);
	define('PATH_THEME_SYSTEM', PATH_MILES_LIBRARY.PATH_THEME);

	// Caminho padrão para o diretório do tema do projeto
	define('PATH_CURRENT_PROJECT_THEME', PATH_PROJECT . PATH_THEME . DIRECTORY_SEPARATOR);

	// Tema do Sistema
	define('PATH_SYSTEM_THEME', PATH_THEME_SYSTEM . DIRECTORY_SEPARATOR);

	// Caminho atual para configuração
	define('PATH_CURRENT_CONFIG_PROJECT',PATH_PROJECT . "config" . DIRECTORY_SEPARATOR);

	// Caminho padrão para o armazenamento de arquivos
	define("PATH_CURRENT_FILE",PATH_PROJECT. "arquivos" . DIRECTORY_SEPARATOR);

	// Caminho padrão para o armazenamento de arquivos tempor?rios
	define("PATH_CURRENT_FILE_TEMP", PATH_CURRENT_FILE . "temp" . DIRECTORY_SEPARATOR);
	
	// Caminho padrão para o armazenamento das imagens
	define("PATH_CURRENT_IMG", PATH_PROJECT . "images" . DIRECTORY_SEPARATOR);

	// Caminho padrão para as classes personalizadas
	define("PATH_CURRENT_CLASS_PROJECT", PATH_PROJECT . "classes" . DIRECTORY_SEPARATOR);
		
	// Caminho padrão para os controllers personalizados
	define("PATH_CURRENT_CONTROLLER", PATH_PROJECT  . "controller" . DIRECTORY_SEPARATOR);
	
	// Caminho padrão para as views personalizadas
	define("PATH_CURRENT_VIEW", PATH_PROJECT . "view" . DIRECTORY_SEPARATOR);

	// Caminho padrão para as páginas personalizadas
	define("PATH_CURRENT_PAGE", PATH_PROJECT . "page" . DIRECTORY_SEPARATOR);

	// Caminho padrão para os componentes personalizadas
	define("PATH_CURRENT_COMPONENT", PATH_PROJECT . "component" . DIRECTORY_SEPARATOR);
	
	// Caminho padrão para os arquivos de log
	define("PATH_CURRENT_LOG", PATH_PROJECT . "log" . DIRECTORY_SEPARATOR);

	// Caminho padrão para os arquivos do WebSite
	define("PATH_CURRENT_WEBSITE",PATH_PROJECT . "website" . DIRECTORY_SEPARATOR);

	// Caminho padrão para os arquivos da páginas de cadastro
	define('PATH_FILES_PAGE', PATH_PROJECT . 'files'.DIRECTORY_SEPARATOR.'cadastro' . DIRECTORY_SEPARATOR);

	// Caminho padrão para os arquivos da páginas de cadastro
	define('PATH_FILES_CADASTRO', PATH_PROJECT . 'files'.DIRECTORY_SEPARATOR.'cadastro' . DIRECTORY_SEPARATOR);

	// Caminho padrão para os arquivos da páginas de consulta
	define('PATH_FILES_CONSULTA', PATH_PROJECT . 'files'.DIRECTORY_SEPARATOR.'consulta' . DIRECTORY_SEPARATOR);

	// Caminho padrão para os arquivos da páginas de relatório
	define('PATH_FILES_RELATORIO', PATH_PROJECT . 'files'.DIRECTORY_SEPARATOR.'relatorio'. DIRECTORY_SEPARATOR);

	// Caminho padrão para os arquivos da páginas de movimentação
	define('PATH_FILES_MOVIMENTACAO', PATH_PROJECT . 'files'.DIRECTORY_SEPARATOR.'movimentacao' . DIRECTORY_SEPARATOR);

	// Caminho padrão para os arquivos de menu de conceito
	define('PATH_FILES_CONCEITO', PATH_PROJECT . 'files'.DIRECTORY_SEPARATOR.'conceito' . DIRECTORY_SEPARATOR);

	// Caminho dos arquivos de instalação de pacotes ( módulos )
	define('PATH_CURRENT_PACKAGE', PATH_PROJECT . 'install'.DIRECTORY_SEPARATOR.'package' . DIRECTORY_SEPARATOR);
	
	// Caminho padrão para os arquivos do build do projeto
	define("PATH_CURRENT_BUILD", PATH_PROJECT . FOLDER_BUILD . DIRECTORY_SEPARATOR);

	define ('PATH_MDM', PATH_MILES_LIBRARY . 'mdm' . DIRECTORY_SEPARATOR);
	
	define("PATH_MDM_JS_COMPILE", PATH_PROJECT  . FOLDER_BUILD_JS);	

	define('PATH_MDM_CONTROLLER' , PATH_MVC_CONTROLLER . 'mdm' . DIRECTORY_SEPARATOR);

	define('PATH_MVC_CONTROLLER_ECOMMERCE', PATH_MVC_CONTROLLER . 'ecommerce' . DIRECTORY_SEPARATOR);

	// Caminho das classes do Website
	define('PATH_CLASS_WEBSITE', PATH_CLASS . "website" . DIRECTORY_SEPARATOR);
	
	define('PATH_ASSETS' , PATH_MILES_LIBRARY . 'assets' . DIRECTORY_SEPARATOR);

	// Caminho padrão para os arquivos de banco de dados
	define("PATH_CURRENT_DATA", PATH_PROJECT . "data" . DIRECTORY_SEPARATOR);
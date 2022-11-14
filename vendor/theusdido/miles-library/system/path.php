<?php
	#***************************************
	# PATH
	#***************************************

	// Diretório dos arquivos de sistema
	define('PATH_SYSTEM', PATH_MILES_LIBRARY . FOLDER_SYSTEM . '/');

	// * 
	// * Caminhos estáticos do sistema
	// *

	// Diretório dos arquivos de configuração
	define('PATH_CONFIG', PATH_MILES_LIBRARY . FOLDER_CONFIG . '/');

	// Caminho padrão para a classes de models do MVC ( MODEL )
	define('PATH_MVC_MODEL',PATH_MILES_LIBRARY.'model/');

	// Caminho padrão para a classes de views do MVC ( VIEW )
	define('PATH_MVC_VIEW',PATH_MILES_LIBRARY.'view/');

	// Caminho padrão para a classes de controllers do MVC ( CONTROLLER )
	define('PATH_MVC_CONTROLLER', PATH_MILES_LIBRARY . 'controller/');
	
	// Caminho padrão para as classes
	define('PATH_CLASS',PATH_MILES_LIBRARY . 'classes/');

	// Caminho padrão para as blibiotecas
	define('PATH_LIB',PATH_MILES_LIBRARY . 'lib/');

	// Caminho padrão para as imagens
	define('PATH_IMG',PATH_MILES_LIBRARY . 'images/');

	// Caminho padrão para a blibioteca ADO ( ADVANCED DATA OBJECT )
	define('PATH_ADO',PATH_CLASS.'ado/');

	// Caminho padrão para a blibioteca BD ( BANCO DE DADOS )
	define('PATH_BD',PATH_CLASS.'bd/');

	// Caminho padrão para a biblioteca TDC ( THEUSDIDO CLASSES )
	define('PATH_TDC',PATH_CLASS.'tdc/');

	// Caminho padrão para as classes personalizadas
	define('PATH_CLASS_CUSTOM',PATH_CLASS.'custom/');

	// Caminho padrão para as classes personalizadas
	define('PATH_CLASS_INSTALL',PATH_CLASS.'install/');
	
	// Caminho padrão para a biblioteca WIDGETS ( ELEMENTOS VISUAIS )
	define('PATH_WIDGETS',PATH_CLASS.'widgets/');

	// Caminho padrão para os componentes do BOOTSTRAP
	define('PATH_BOOTSTRAP',PATH_CLASS.'widgets/bootstrap/');

	// Caminho padrão para os arquivos de log	
	define('PATH_LOG',PATH_MILES . "log/");		

	// Caminho padrão para os arquivos de debug
	define('PATH_DEBUG',PATH_MILES . "debug/");
	
	// Caminho padrão para os arquivos de cada projeto
	define('PATH_PROJECT',PATH_MILES . FOLDER_PROJECT . '/');

	// Caminho padrão para arquivos do E-Commerce
	define('PATH_ECOMMERCE', PATH_MVC_CONTROLLER . "ecommerce/");

	// Caminho padrão para os arquivos de classe Interface
	define('PATH_CLASS_INTERFACE',PATH_CLASS.'interface/');

	// Caminho padrão para os arquivos de classe do E-Commerce
	define('PATH_CLASS_ECOMMERCE',PATH_CLASS.'ecommerce/');

	// Caminho padrão para os arquivos de classe do Sistema
	define('PATH_CLASS_SYSTEM',PATH_CLASS.'system/');

	// Caminho da Páginas
	define("PATH_SYSTEM_PAGE",PATH_MILES_LIBRARY . "page/");

	// Caminho dos Componentes
	define("PATH_SYSTEM_COMPONENT",PATH_MILES_LIBRARY . "component/");

	// Caminho de padrão para os arquivos de instalação
	define('PATH_INSTALL', PATH_MILES_LIBRARY . 'install/');

	// Caminho dos arquivos de instalação de pacotes ( módulos )
	define('PATH_PACKAGE', PATH_INSTALL . 'package/');

	// Caminho dos arquivos de registros padrão
	define('PATH_REGISTRO', PATH_INSTALL . 'registro/');

	// * 
	// * Caminhos estáticos do projeto
	// *

	// Caminho para o tema
	define("PATH_THEME",'tema/' . CURRENT_THEME . '/');
	define('PATH_THEME_SYSTEM', PATH_MILES.PATH_THEME);

	// Caminho do projeto atual
	define('PATH_CURRENT_PROJECT' , PATH_PROJECT);

	// Caminho padrão para o diretório do tema do projeto
	define('PATH_CURRENT_PROJECT_THEME', PATH_CURRENT_PROJECT . PATH_THEME);

	// Tema do Sistema
	define('PATH_SYSTEM_THEME', PATH_THEME_SYSTEM);

	// Caminho atual para configuração
	define('PATH_CURRENT_CONFIG_PROJECT',PATH_CURRENT_PROJECT . "config/");

	// Caminho padrão para o armazenamento de arquivos
	define("PATH_CURRENT_FILE",PATH_CURRENT_PROJECT. "arquivos/");

	// Caminho padrão para o armazenamento de arquivos tempor?rios
	define("PATH_CURRENT_FILE_TEMP", PATH_CURRENT_FILE . "temp/");
	
	// Caminho padrão para o armazenamento das imagens
	define("PATH_CURRENT_IMG", PATH_CURRENT_PROJECT . "images/");

	// Caminho padrão para as classes personalizadas
	define("PATH_CURRENT_CLASS_PROJECT", PATH_CURRENT_PROJECT . "classes/");
		
	// Caminho padrão para os controllers personalizados
	define("PATH_CURRENT_CONTROLLER", PATH_CURRENT_PROJECT  . "controller/");
	
	// Caminho padrão para as views personalizadas
	define("PATH_CURRENT_VIEW", PATH_CURRENT_PROJECT . "view/");

	// Caminho padrão para as páginas personalizadas
	define("PATH_CURRENT_PAGE", PATH_CURRENT_PROJECT . "page/");

	// Caminho padrão para os componentes personalizadas
	define("PATH_CURRENT_COMPONENT", PATH_CURRENT_PROJECT . "component/");
	
	// Caminho padrão para os arquivos de log
	define("PATH_CURRENT_LOG", PATH_CURRENT_PROJECT . "log/");		

	// Caminho padrão para os arquivos do WebSite
	define("PATH_CURRENT_WEBSITE",PATH_CURRENT_PROJECT . "website/");

	// Caminho padrão para os arquivos da páginas de cadastro
	define('PATH_FILES_PAGE', PATH_CURRENT_PROJECT . 'files/cadastro/');

	// Caminho padrão para os arquivos da páginas de cadastro
	define('PATH_FILES_CADASTRO', PATH_CURRENT_PROJECT . 'files/cadastro/');

	// Caminho padrão para os arquivos da páginas de consulta
	define('PATH_FILES_CONSULTA', PATH_CURRENT_PROJECT . 'files/consulta/');

	// Caminho padrão para os arquivos da páginas de relatório
	define('PATH_FILES_RELATORIO', PATH_CURRENT_PROJECT . 'files/relatorio/');

	// Caminho padrão para os arquivos da páginas de movimentação
	define('PATH_FILES_MOVIMENTACAO', PATH_CURRENT_PROJECT . 'files/movimentacao/');

	// Caminho dos arquivos de instalação de pacotes ( módulos )
	define('PATH_CURRENT_PACKAGE', PATH_CURRENT_PROJECT . 'install/package/');
	
	// Caminho padrão para os arquivos do build do projeto
	define("PATH_CURRENT_BUILD", PATH_CURRENT_PROJECT . FOLDER_BUILD . "/");

	define ('PATH_MDM', PATH_MILES_LIBRARY . 'mdm/');

	define("PATH_MDM_JS_COMPILE", PATH_CURRENT_PROJECT  . FOLDER_BUILD . "/js/");

	define('PATH_MDM_CONTROLLER' , PATH_MVC_CONTROLLER . 'mdm/');

	define('PATH_MVC_CONTROLLER_ECOMMERCE', PATH_MVC_CONTROLLER . 'ecommerce/');

	// Caminho das classes do Website
	define('PATH_CLASS_WEBSITE', PATH_CLASS . "website/");
	

<?php

	// Classe de Configuração do sistema
	require PATH_CORE . 'classes/system/config.class.php';

	// Variável global do projeto atual
	$currentProject = Config::currentProject();
	$_phpversion 	= explode('.',phpversion());
	$phpversion 	= (int)$_phpversion[0];
	$phpbuild 		= (int)$_phpversion[1];
	$phpcompilation	= isset($_phpversion[2])?(int)$_phpversion[2]:0;

	$_session_isactive = false;
	if ($phpversion >= 5 && $phpbuild > 3){
		if (session_status() === PHP_SESSION_ACTIVE) {
			$_session_isactive = true;
		}
	}else{
		if(session_id() != '') {
			$_session_isactive = true;
		}
	}

	// Verificar se a sessão não já está aberta.
	if (!$_session_isactive) {

		// Sessão do Sistema
		$sessionName = "miles_" . AMBIENTE . "_" . $currentProject;
		session_name($sessionName);
		session_start();

	}

	// Seta o ID do projeto atual
	$_SESSION["currentproject"] = $currentProject;

	// Sessão ONLINE [ 0 - False : 1 - True ]
	$_SESSON["ISONLINE"] = Config::isOnline();

	// Projeto configurado via .htaccess
	$isHtaccess = isset($_GET["ishtaccess"]) ? (bool)$_GET["ishtaccess"] : false;

	// Desliga o registro global
	ini_set("register_globals","off");

	// Configurando timezone
	date_default_timezone_set('America/Sao_Paulo');

	// Consumo especíco a um controller do sistema
	$consumoespecifico  = isset($_GET["key"]) == '' ? false : true;

	// Verifica se o usuário está logado
	$logged = false;
    if (isset($_SESSION["autenticado"])){
        if ($_SESSION["autenticado"]){
            $logged = true;
        }
    }

	// Se a raiz não for definida
	if (!defined('RAIZ')) define('RAIZ',PATH_MILES);

	// Arquivos de configuração
	if (AMBIENTE == "SISTEMA" || AMBIENTE == 'BIBLIOTECA'){		
		$currentConfigFile = PATH_MILES . 'projects/'.$currentProject.'/config/current_config.inc';
		if (!file_exists($currentConfigFile)){
			echo 'Arquivo de Configuração do projeto '.$currentProject.' não encontrado.<br/>';
			echo '<a href="index.php?controller=logout">[ Logout ]</a>';
			exit;
		}
	}else if (AMBIENTE == "WEBSERVICE" || AMBIENTE == "WEBSITE"){
		$currentConfigFile = PATH_MILES .'projects/'.PROJETO_CONSUMIDOR.'/config/current_config.inc';
		if (!file_exists($currentConfigFile)){
			echo 'Arquivo Atual de Configuração do Projeto [ '.PROJETO_CONSUMIDOR.' ] não encontrato.';
			exit;
		}
		$_SESSION["currentproject"] = PROJETO_CONSUMIDOR;
	}

	// Abrindo arquivo de configuração
	define("CURRENT_PATH_CONFIG",$currentConfigFile);

	// Current File Config
	$config = parse_ini_file($currentConfigFile);

	// Constantes de inicialização do sistema
	switch(AMBIENTE){
		case 'SISTEMA': define ("PROJETO_FOLDER",$config["PROJETO_FOLDER"]."/sistema"); break;
		case 'WEBSERVICE': define ("PROJETO_FOLDER",$config["PROJETO_FOLDER"]."/webservice"); break;
		case 'WEBSITE': define ("PROJETO_FOLDER",$config["PROJETO_FOLDER"].'/site'); break;
	}
	
	// Pega o PREFIXO
	if (file_exists($currentConfigFile)){
		define("PREFIXO",$config["PREFIXO"]);
	}else{
		define("PREFIXO",$mjc->system->prefix);
	}
	
	// Projeto atual
	define('CURRENT_PROJECT_ID',(isset($_SESSION["currentproject"])?$_SESSION["currentproject"]:$config["CURRENT_PROJECT"]));
	
	// Define se o usuário está logado ou não
	define("LOGGED",$logged);

	// Diretório padrão para os arquivos do sistema
	define("PATH_SYSTEM",PATH_CORE . "system/");
	
	// Caminho padrão para o arquivo de configuração do sistema
	define('PATH_CONFIG',PATH_MILES . 'config/');
	
	// Caminho padrão para as classes
	define('PATH_CLASS',PATH_CORE . 'classes/');

	// Caminho padrão para as blibiotecas
	define('PATH_LIB',RAIZ.'lib/');

	// Caminho padrão para as imagens
	define('PATH_IMG',RAIZ.'imagens/');
	
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

	// Caminho padrão para a classes de models do MVC ( MODELOS )
	define('PATH_MVC_MODEL',PATH_SYSTEM.'model/');

	// Caminho padrão para a classes de views do MVC ( VIS?O )
	define('PATH_MVC_VIEW',PATH_CORE.'view/');

	// Caminho padrão para a classes de controllers do MVC ( CONTROLADOR )
	define('PATH_MVC_CONTROLLER', PATH_CORE . 'controller/');

	// Caminho padrão para os componentes do BOOTSTRAP
	define('PATH_BOOTSTRAP',PATH_CLASS.'widgets/bootstrap/');

	// Caminho para o tema
	define("CURRENT_THEME",isset($config["THEME"])?$config["THEME"]:'padrao');
	define("PATH_THEME","tema/" . CURRENT_THEME . '/');	
	define('PATH_THEME_SYSTEM', PATH_CORE.PATH_THEME);	
	
	// Caminho padrão para os arquivos de log	
	define('PATH_LOG',RAIZ . "log/");
	
	// Caminho padrão para os arquivos de debug
	define('PATH_DEBUG',RAIZ . "debug/");	
	
	// Caminho padrão para os arquivos da classe das Bibliotecas Externas
	define('PATH_CLASS_LIB',PATH_CLASS.'biblioteca/');

	// Caminho padrão para os arquivos de cada projeto
	define('PATH_PROJECT',PATH_MILES . FOLDER_PROJECT . '/');

	// Caminho padrão para os arquivos de classe do projeto atual
	define('PATH_CURRENT_CLASS_PROJECT',PATH_PROJECT  . CURRENT_PROJECT_ID . "/classe/");		
	
	// Caminho padr?para arquivos do E-Commerce
	define('PATH_CURRENT_FILES_ECOMMERCE', PATH_MVC_CONTROLLER . "website/ecommerce/");
	
	// Caminho padrão para os arquivos de classe Interface
	define('PATH_CLASS_INTERFACE',PATH_CLASS.'interface/');
	
	// Caminho padrão para os arquivos de classe do E-Commerce
	define('PATH_CLASS_ECOMMERCE',PATH_CLASS.'ecommerce/');
	
	// Caminho padrão para os arquivos de classe do Sistema
	define('PATH_CLASS_SYSTEM',PATH_CLASS.'system/');	

	// REQUEST PROTOCOLO
	define("REQUEST_PROTOCOL",$mjc->system->request_protocol."://");

	// Caminho da Páginas
	define("PATH_SYSTEM_PAGE",PATH_SYSTEM . "page/");

	// Caminho do Favicon do projeto
	define("FILE_CURRENT_FAVICON",isset($config["FAVICON"])?$config["FAVICON"]:'');

	// Logo Padrão
	define("FILE_LOGO", isset($config["LOGO"]) ? $config["LOGO"] : 'logo.png');

	// Logo Padrão do Rodapé
	define("FILE_LOGO_RODAPE", isset($config["LOGO_RODAPE"]) ? $config["LOGO_RODAPE"] : 'logo.png');
	
	// Exibir mensagem de erro
	define("IS_SHOW_ERROR_MESSAGE",false);
	
	// Inclui os arquivos de funções do sistema
	require 'funcoes.php';

	// Inclui a classe AutoLoad	
	require PATH_TDC . 'AutoLoad.class.php';	

	$AutoLoad = new AutoLoad();	

	// Carrega o arquivo da classe quando o objeto for invocado	
	spl_autoload_register(array($AutoLoad, "load"));	

	// Operador E
	define("E",'AND ');

	// Operador OU
	define("OU",'OR ');

	// Carrega as classes que não são instânciadas
	include PATH_BD . 'transacao.class.php';	
	include PATH_TDC . 'tdclass.class.php';
	include PATH_TDC . 'campos.class.php';
	include PATH_TDC . 'debug.class.php';
	include PATH_TDC . 'session.class.php';
	include PATH_TDC . 'tdfile.class.php';
	include PATH_CLASS_INSTALL . 'install.class.php';

	// Dados de Sessão do Projeto
	Session::setName($sessionName);
	Session::append("currenttypedatabase",isset($_SESSION["currenttypedatabase"])?$_SESSION["currenttypedatabase"]:$config["CURRENT_DATABASE"]);
	Session::append("projeto",isset($_SESSION["projeto"])?$_SESSION["projeto"]:$config["CURRENT_PROJECT"]); 
	Session::append("currentprojectname",isset($_SESSION["currentprojectname"])?$_SESSION["currentprojectname"]:(isset($config["PROJETO_DESC"])?$config["PROJETO_DESC"]:'Teia'));
	
	// Código do Cliente
	define ("CODIGOCLIENTE",isset($config["CODIGOCLIENTE"])?$config["CODIGOCLIENTE"]:0);
		
	// URL ROOT
	Session::append("URL_ROOT",REQUEST_PROTOCOL . HTTP_HOST . "/" . $config["PROJETO_FOLDER"] . "/");

	// URL ALIAS
	if (isset($_GET["alias"])) Session::append("URL_ALIAS",REQUES_PROTOCOL . HTTP_HOST . "/" . $_GET["alias"]);
	
	// URL CORE
	Session::append("URL_CORE",Session::Get('URL_MILES') . FOLDER_CORE . '/');

	// URL MILES
	Session::append("URL_MILES",REQUEST_PROTOCOL . HTTP_HOST . "/" . FOLDER_MILES );

	// Caminho da raiz do sitema
	Session::append("PATH_ROOT",RAIZ);
	
	// URL SYSTEM
	Session::append("URL_SYSTEM",Session::Get('URL_CORE') . FOLDER_SYSTEM . '/');

	// Caminho do projeto atual		
	Session::append("PATH_CURRENT_PROJECT",PATH_PROJECT . CURRENT_PROJECT_ID . "/");
	Session::append("URL_CURRENT_PROJECT",Session::Get("URL_MILES") . FOLDER_PROJECT . '/' . CURRENT_PROJECT_ID . "/");
	define('URL_PROJECT',Session::Get("URL_MILES") . FOLDER_PROJECT . '/');
	
	// URL CLASSES	
	Session::append('URL_CLASS',Session::Get('URL_CORE') . FOLDER_CLASSES . '/');

	// URL MDM
	Session::append('URL_MDM',Session::Get('URL_CORE') . 'mdm/');

	// URL INSTALL
	Session::append('URL_INSTALL',Session::Get('URL_CORE') . 'install/');

	// CLASSE TDC
	Session::append('URL_CLASS_TDC',Session::Get('URL_CLASS') . 'tdc/');

	// Caminho padrão para o diretório do tema do projeto
	Session::append("PATH_CURRENT_PROJECT_THEME",Session::Get("PATH_CURRENT_PROJECT") . PATH_THEME);
	Session::append("URL_CURRENT_PROJECT_THEME",Session::Get("URL_CURRENT_PROJECT") . PATH_THEME);
	
	Session::append("PATH_SYSTEM_THEME",PATH_THEME_SYSTEM);
	Session::append("URL_SYSTEM_THEME",Session::Get('URL_CORE') . PATH_THEME);	

	// Logo padrão do sistema
	Session::append("PATH_CURRENT_LOGO_PADRAO",Session::Get("PATH_CURRENT_PROJECT_THEME") . FILE_LOGO);
	Session::append("URL_CURRENT_LOGO_PADRAO",Session::Get("URL_CURRENT_PROJECT_THEME") . FILE_LOGO );

	// Imagem de rodapé padrão do sistema
	Session::append("PATH_IMG_RODAPE_PADRAO",Session::Get("PATH_CURRENT_PROJECT_THEME") . FILE_LOGO_RODAPE);

	Session::append("PATH_CURRENT_FAVICON",Session::Get("PATH_CURRENT_PROJECT_THEME") . FILE_CURRENT_FAVICON);
	Session::append("URL_CURRENT_FAVICON",Session::Get("URL_CURRENT_PROJECT_THEME") . FILE_CURRENT_FAVICON);

	// Caminho atual para configuração
	Session::append("PATH_CURRENT_CONFIG_PROJECT",Session::Get("PATH_CURRENT_PROJECT"). "config/");
	Session::append("URL_CURRENT_CONFIG_PROJECT",Session::Get("URL_CURRENT_PROJECT"). FOLDER_CONFIG . "/");

	// Arquivo atual de configuração 
	Session::append("FILE_CURRENT_CONFIG_PROJECT",Session::Get("PATH_CURRENT_CONFIG_PROJECT"). "current_config.inc");

	// Caminho padrão para o armazenamento de arquivos
	Session::append("PATH_CURRENT_FILE",Session::Get("PATH_CURRENT_PROJECT") . "arquivos/");
	Session::append("URL_CURRENT_FILE",Session::Get("URL_CURRENT_PROJECT") . "arquivos/");

	// Caminho padrão para o armazenamento de arquivos tempor?rios
	Session::append("PATH_CURRENT_FILE_TEMP",Session::Get("PATH_CURRENT_FILE") . "temp/");
	Session::append("URL_CURRENT_FILE_TEMP",Session::Get("URL_CURRENT_FILE") . "temp/");
	
	
	// Caminho padrão para o armazenamento das imagens
	Session::append("PATH_CURRENT_IMG",Session::Get("PATH_CURRENT_PROJECT") . "imagens/");

	// Caminho padrão para as classes personalizadas
	Session::append("PATH_CURRENT_CLASS_PROJECT",Session::Get("PATH_CURRENT_PROJECT") . "classe/");
	
	// PATH TDC CLASSE
	Session::append('PATH_TDC', PATH_CLASS . 'tdc/');	
	
	// Caminho padrão para os controllers personalizados
	Session::append("PATH_CURRENT_CONTROLLER",Session::Get("PATH_CURRENT_PROJECT") . "controller/");
	
	// Caminho padrão para as views personalizadas
	Session::append("PATH_CURRENT_VIEW",Session::Get("PATH_CURRENT_PROJECT") . "view/");

	// Caminho padrão para os arquivos do WebSite
	Session::append("PATH_CURRENT_WEBSITE",Session::Get("PATH_CURRENT_PROJECT") . "website/");

	// Nome do diretório dos arquivos compilados
	define("FOLDER_PROJECT_FILES", FOLDER_PROJECT . "/" . CURRENT_PROJECT_ID . "/");

	// URL da Biblioteca
	define('URL_LIB',$mjc->system->url->lib);
	Session::append("URL_LIB",$mjc->system->url->lib);

	// Caminho padrão para os arquivos da páginas de cadastro
	define('PATH_FILES_PAGE',Session::Get("PATH_CURRENT_PROJECT").'files/cadastro/');

	// Caminho padrão para os arquivos da páginas de cadastro
	define('PATH_FILES_CADASTRO',Session::Get("PATH_CURRENT_PROJECT").'files/cadastro/');

	define('FOLDER_FILES_CADASTRO',FOLDER_PROJECT_FILES . 'files/cadastro/');
	define('URL_FILES_CADASTRO', Session::Get('URL_CURRENT_PROJECT') . 'files/cadastro/');
	
	// Caminho padrão para os arquivos da páginas de consulta
	define('PATH_FILES_CONSULTA',Session::Get("PATH_CURRENT_PROJECT").'files/consulta/');

	// Caminho padrão para os arquivos da páginas de relatório
	define('PATH_FILES_RELATORIO',Session::Get("PATH_CURRENT_PROJECT").'files/relatorio/');

	// Caminho padrão para os arquivos da páginas de movimentação
	define('PATH_FILES_MOVIMENTACAO',Session::Get("PATH_CURRENT_PROJECT").'files/movimentacao/');
	define('URL_FILES_MOVIMENTACAO', Session::Get('URL_CURRENT_PROJECT') . 'files/movimentacao/');

	// URL padrão para as classes de WIDGETS
	define('URL_CLASS_WIDGETS', Session::Get('URL_CLASS') . 'widgets/');
	
	//Session::append('FOLDER_FILES_CADASTRO', FOLDER_PROJECT . "/" . Session::Get('projeto') . "/files/cadastro/$entidade/");
	
	// Constantes de entidade padr?
	define("ENTIDADE",PREFIXO . "_entidade");
	define("ATRIBUTO",PREFIXO . "_atributo");
	define("MENU",PREFIXO . "_menu");
	define("USUARIO",PREFIXO . "_usuario");
	define("EMPRESA",PREFIXO . "_empresa");
	define("PROJETO",PREFIXO . "_projeto");
	define("ABAS",PREFIXO . "_abas");
	define("RELACIONAMENTO",PREFIXO . "_relacionamento");
	define("COLUNA_ENTIDADE",PREFIXO ."_entidade");
	define("LISTA",PREFIXO ."_lista");
	define("CONFIG",PREFIXO ."_config");
	define("PERMISSOES",PREFIXO ."_entidadepermissoes");
	define("FILTROATRIBUTO",PREFIXO ."_atributofiltro");
	define("CONSULTA",PREFIXO ."_consulta");
	define("FILTROCONSULTA",PREFIXO ."_consultafiltro");
	define("RELATORIO",PREFIXO ."_relatorio");
	define("FILTRORELATORIO",PREFIXO ."_relatoriofiltro");
	define("MOVIMENTACAO",PREFIXO ."_movimentacao");
	define("HISTORICOMOVIMENTACAO",PREFIXO ."_movimentacaohistorico");
	define("ALTERARMOVIMENTACAO",PREFIXO ."_movimentacaoalterar");

	// Loading 
	Session::append("URL_LOADING", Session::Get("URL_SYSTEM_THEME") . 'loading.gif');
	Session::append("URL_LOADING2", Session::Get("URL_SYSTEM_THEME") . 'loading2.gif');

	// Define a imagem de loader de contexto
	define("LOADERCONTEXTO",'<img class="loadercontexto" width="32" align="middle" src="'.Session::Get("URL_LOADING2").'">');

	// Título da página do projeto
	define ("PROJETO_DESC",utf8_encode(Session::Get("currentprojectname")));

	// For?ar a exibição dos erros para Super Usuário
	if (isset(Session::Get()->userid)){
		if (Session::Get()->userid == 1){
			ini_set('display_errors',1);
			ini_set('display_startup_erros',1);
			error_reporting(E_ALL);		
		}
	}

	// Indice do componente Collapse
	$_SESSION["icollapse"] = 0;

	// Database Connection do Projeto
	if (!defined("DATABASECONNECTION")) define("DATABASECONNECTION",(isset($_SESSION["currenttypedatabase"])?$_SESSION["currenttypedatabase"]:$config["CURRENT_DATABASE"]));
	
	// Abre a transação atual do banco de dados do projeto
	Transacao::abrir("current");

	// Variavel Global da conexão ative com banco de dados
	$conn = Transacao::Get();	

	// Abre a conexão com o banco de dados MILES
	#$connMILES = Conexao::abrir("miles");	
	$connMILES = null;

	// Seta Indice dos obejtos criados
	Session::append("IDOBJECTHTML",0);

	// Seta o valor padr?para charset ISO
	define("CHARSET_ISO","ISO-8859-1");

	// Seta o valor padr?para charset UTF 8
	define("CHARSET_UTF8","UTF-8");

	// MDM Javascript File Compile
	define("FILE_MDM_JS_COMPILE",FOLDER_BUILD . "/js/mdm.js");
	define("PATH_MDM_JS_COMPILE",Session::Get("PATH_CURRENT_PROJECT") . FILE_MDM_JS_COMPILE);
	define("URL_MDM_JS_COMPILE",Session::Get("URL_CURRENT_PROJECT") . FILE_MDM_JS_COMPILE);

	// ENTIDADES PERSONALIZADAS
	define('TD_PRODUTO',PREFIXO . '_' . $mjc->system->package . '_produto');
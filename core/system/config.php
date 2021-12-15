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

	// Sessão do Sistema
	$sessionName = "miles_" . AMBIENTE . "_" . $currentProject;

	// Verificar se a sessão não já está aberta.
	if (!$_session_isactive){
		// Cria uma nova sessão
		session_name($sessionName);
		session_start();
	}
	if (!defined('SCHEMA')){
		if (isset($_SESSION["db_base"])){
			define('SCHEMA',$_SESSION["db_base"]);
		}
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

	// Caminho dos arquivos de configuração
	if (AMBIENTE == "SISTEMA" || AMBIENTE == 'BIBLIOTECA'){		
		$currentConfigFile = PATH_MILES . 'projects/'.$currentProject.'/config/current_config.inc';
	}else if (AMBIENTE == "WEBSERVICE" || AMBIENTE == "WEBSITE"){
		$currentConfigFile = PATH_MILES .'projects/'.PROJETO_CONSUMIDOR.'/config/current_config.inc';
	}

	if (file_exists($currentConfigFile)){
		// Current File Config
		$config = parse_ini_file($currentConfigFile);

		// Folder do Projeto
		define ("PROJETO_FOLDER",$config["PROJETO_FOLDER"]);

		// Pega o PREFIXO
		define("PREFIXO",$config["PREFIXO"]);

		// Tema Atual
		define("CURRENT_THEME",isset($config["THEME"])?$config["THEME"]:'padrao');
	}else{
		// Folder da Projeto
		define ("PROJETO_FOLDER",$mjc->folder);
		
		// Pega o PREFIXO		
		define("PREFIXO",$mjc->prefix);

		define("CURRENT_THEME",$mjc->theme);
	}

	// Projeto atual
	define('CURRENT_PROJECT_ID',$currentProject);

	// Define se o usuário está logado ou não
	define("LOGGED",$logged);

	// REQUEST PROTOCOLO
	define("REQUEST_PROTOCOL",$mjc->system->request_protocol."://");
	
	// Exibir mensagem de erro
	define("IS_SHOW_ERROR_MESSAGE",$mjc->is_show_error_message);

	// Arquivos que fazem parte da estrutura do sistema
	$strutuct = json_decode(file_get_contents(PATH_CONFIG . 'estrutura.json'));

	// Carrega Composer
	require 'vendor/autoload.php';

	// Inclui a classe AutoLoad
	require PATH_MILES . $strutuct->auto_load_class;	
	$AutoLoad = new AutoLoad();

	// Carrega o arquivo da classe quando o objeto for invocado	
	spl_autoload_register(array($AutoLoad, "load"));	

	// Inclui os arquivos de funções do sistema
	require PATH_SYSTEM . 'funcoes.php';	
	require PATH_SYSTEM . 'path.php';
	require PATH_SYSTEM . 'file.php';
	require PATH_SYSTEM . 'estaticas.php';

	// Dados de Sessão do Projeto
	Session::setName($sessionName);
	Session::append("currenttypedatabase",isset($_SESSION["currenttypedatabase"])?$_SESSION["currenttypedatabase"]:(isset($config["CURRENT_DATABASE"])?$config["CURRENT_DATABASE"]:'desenv'));
	Session::append("projeto",$currentProject);
	Session::append("currentprojectname",isset($_SESSION["currentprojectname"])?$_SESSION["currentprojectname"]:(isset($config["PROJETO_DESC"])?$config["PROJETO_DESC"]:'Teia'));
	
	// Código do Cliente
	define ("CODIGOCLIENTE",isset($config["CODIGOCLIENTE"])?$config["CODIGOCLIENTE"]:0);

	require PATH_SYSTEM . 'url.php';	
	require PATH_SYSTEM . 'entidade.php';

	// Define a imagem de loader de contexto
	define("LOADERCONTEXTO",'<img class="loadercontexto" width="32" align="middle" src="'.Session::Get("URL_LOADING2").'">');

	// Título da página do projeto
	define ("PROJETO_DESC",utf8_encode(Session::Get("currentprojectname")));

	// Forçar a exibição dos erros para Super Usuário
	if (isset(Session::Get()->userid)){
		if (Session::Get()->userid == 1){
			ini_set('display_errors',1);
			ini_set('display_startup_erros',1);
			error_reporting(E_ALL);		
		}
	}

	// Indice do componente Collapse
	$_SESSION["icollapse"] = 0;

	// Seta Indice dos obejtos criados
	Session::append("IDOBJECTHTML",0);

	// Seta o valor padr?para charset ISO
	define("CHARSET_ISO","ISO-8859-1");

	// Seta o valor padr?para charset UTF 8
	define("CHARSET_UTF8","UTF-8");

	// Database Connection do Projeto
	if (!defined("DATABASECONNECTION")) define("DATABASECONNECTION",(isset($_SESSION["currenttypedatabase"])?$_SESSION["currenttypedatabase"]:(isset($config["CURRENT_DATABASE"])?$config["CURRENT_DATABASE"]:'desenv')));

	// Abre a transação atual do banco de dados do projeto
	if (!Transacao::abrir("current") && (tdc::r('controller') == '' || tdc::r('controller') == 'install') && AMBIENTE == 'SISTEMA'){

		// Redireciona o sistema para instalação do sistema
		include PATH_MVC_CONTROLLER . 'install.php';
		exit;
	}

	// Variavel Global da conexão ative com banco de dados
	$conn = Transacao::Get();

	// Abre a conexão com o banco de dados MILES
	$connMILES = null; # Descontinuado
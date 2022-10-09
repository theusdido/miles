<?php

	// Porta para chamadas via URL
	switch($mjc->port){
		case 'hidden': 
			$_port = ''; 
		break;
		case 'auto': 
			$_port = $_SERVER['SERVER_PORT']; 
		break;
		default:
			$_port = $mjc->port;
	}

	// Define a porta para requisições via API
	define('PORT', $_port);

	// Classe de Configuração do sistema
	require $_path_core . '/classes/system/config.class.php';

	// Seta se o requisição está com HTTPs
	define('_IS_HTTP',isset($_SERVER['HTTPS']) ? true : false);
	
	// Variável global apenas para o HTTP
	$_http_request = isset($_env->system->request_protocol) ? $_env->system->request_protocol :  Config::getEnvirommentVariable('system->request_protocol','http');

	// REQUEST PROTOCOLO
	define("REQUEST_PROTOCOL",$_http_request."://");

	// Força redirecionar para HTTPs
	$force_https = isset($_env->system->force_https) ? $_env->system->force_https : false;

	// Encerrar o programa se a requisição não estiver com HTTPs 
	// e o a configuração estiver setada para HTTPs
	if ($_http_request == 'https' && !_IS_HTTP)
	{
		$link_https = str_replace('http://','https://',$_SERVER['SCRIPT_URI']);
		if (AMBIENTE == 'SISTEMA' && !$force_https){
			showMessage('HTTPs está ativo na configuração!');
			echo '<br><a href="'.$link_https.'">Entrar com URL segura.</a>';
			exit;
		}else{
			header('Location: ' . $link_https);
		}
	}
	
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
		@session_start();
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


	// Folder da Projeto
	define ("PROJETO_FOLDER",$mjc->folder);

	// Pega o PREFIXO
	define("PREFIXO",$mjc->prefix);

	define("CURRENT_THEME",$mjc->theme);

	// Projeto atual
	define('CURRENT_PROJECT_ID',$currentProject); # Não utilizar, obsoleto
	define('PROJECT_ID',$currentProject);

	// Define se o usuário está logado ou não
	define("LOGGED",$logged);

	// Exibir mensagem de erro
	define("IS_SHOW_ERROR_MESSAGE",$mjc->is_show_error_message);

	// Exibir mensagem de erro
	define("IS_TRANSACTION_LOG",$mjc->is_transaction_log);	

	// Arquivos que fazem parte da estrutura do sistema
	$strutuct = json_decode(file_get_contents($_path_config . 'estrutura.json'));

	// Carrega Composer
	$path_composer = 'vendor/autoload.php';
	if (file_exists($path_composer)){
		require $path_composer;
	}

	// Inclui os arquivos de funções do sistema
	require $_path_system . 'path.php';
	require $_path_system . 'funcoes.php';
	require $_path_system . 'estaticas.php';
	require $_path_system . 'url.php';
	require $_path_system . 'file.php';
	require $_path_system . 'entidade.php';
	require $_path_system . 'atributo.php';

	// Inclui a classe AutoLoad
	require PATH_MILES . $strutuct->auto_load_class;	
	$AutoLoad = new AutoLoad();	

	// Carrega o arquivo da classe quando o objeto for invocado	
	spl_autoload_register(array($AutoLoad, "load"));

	// Dados de Sessão do Projeto
	Session::setName($sessionName);

	// Código do Cliente
	define ("CODIGOCLIENTE",0); # Ainda não implementado

	// Define a imagem de loader de contexto
	define("LOADERCONTEXTO",'<img class="loadercontexto" width="32" align="middle" src="'.URL_LOADING2.'">');

	// Título da página do projeto
	define ("PROJETO_DESC",$mjc->project->name);

	$_userid = isset(Session::Get()->userid) ? Session::Get()->userid : 0;

	// Forçar a exibição dos erros para Super Usuário
	if ($mjc->is_show_error_message || $_userid == 1){
		ini_set('display_errors',1);
		ini_set('display_startup_erros',1);
		error_reporting(E_ALL);
	}

	// Indice do componente Collapse
	$_SESSION["icollapse"] = 0;

	// Seta Indice dos obejtos criados
	Session::append("IDOBJECTHTML",0);

	// Seta o valor padr?para charset ISO
	define("CHARSET_ISO","ISO-8859-1");

	// Seta o valor padr?para charset UTF 8
	define("CHARSET_UTF8","UTF-8");

	// Navegador
	define('BROWSER', getNavegador());

	// Database Connection do Projeto
	if (!defined("DATABASECONNECTION")) define("DATABASECONNECTION",$mjc->database_current);

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

	// Aumenta o tamanho máximo para upload em 200MB
	ini_set('upload_max_filesize', '200M');

	// Seta o tempo limite para 5 minutos
	set_time_limit(300);

	// Permite recuperar abrir pela URL
	ini_set('allow_url_fopen','On');	

	// Padrão de envios via request
    $_op 			= tdc::r("op") == '' ? (tdc::r("_op") == '' ? '' : tdc::r("_op") ) : tdc::r("op");
	$_id			= tdc::r("id") == '' ? (tdc::r("_id")==''?0:tdc::r("_id")) : tdc::r("id");
	switch(gettype(tdc::r('dados'))){
		case 'array':
			$_dados = (object)tdc::r('dados');
		break;
		default:
			$_dados	= json_decode(tdc::r('dados') == '' ? (tdc::r('_dados')==''?'{}':tdc::r('_dados')) : tdc::r('dados'));		
	}
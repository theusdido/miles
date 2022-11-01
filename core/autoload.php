<?php
	// Rodando no composer ...

	// Define o caminho absoluto da raiz do MILES
	define('PATH_MILES',dirname(__DIR__) . '/');

	// Seta as constantes
	require PATH_MILES . 'core/system/constantes.php';

	// Diretório de sistema para carregar as principais funcionaliades
	$_path_core 	= PATH_MILES . '/' . FOLDER_CORE . '/';
	$_path_system	= $_path_core . FOLDER_SYSTEM . '/';
	$_path_config	= $_path_core . FOLDER_CONFIG . '/';
	$_path_classes	= $_path_core . FOLDER_CLASSES . '/';
	
	// Carrega biblioteca de funções independentes
	require $_path_system . 'functions.php';
	
	// Carrega as configurações do arquivo miles.json
	require PATH_MILES . 'core/system/miles.json.php';

	// Carrega os arquivos de configuração do sistema	
	require $_path_system . 'config.php';
	
	// Arquivo de compatibilidade entre versões
	include $_path_system . 'compatibilidade.php';
	
	// Rotas
	require $_path_system . 'rota.php';

	if (AMBIENTE == 'SISTEMA'){
		// Fecha a transação com o banco de dados
		Transacao::Commit();
	}

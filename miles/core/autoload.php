<?php
	// Define o caminho absoluto da raiz do MILES
	define('PATH_MILES',dirname(__DIR__) . '/');

	// Carrega as configurações do arquivo miles.json
	require PATH_MILES . 'core/system/miles.json.php';

	// Carrega os arquivos de configuração do sistema
	require PATH_MILES . 'core/system/globais.php';

	// Carrega os arquivos de configuração do sistema	
	require PATH_MILES . 'core/system/config.php';
	
	// Arquivo de compatibilidade entre versões
	include PATH_MILES . 'core/system/compatibilidade.php';

	// Rotas
	require PATH_MILES .'core/system/rota.php';

	// Fecha a transação com o banco de dados
	Transacao::Fechar();
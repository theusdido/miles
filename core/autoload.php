<?php
	// Define o caminho absoluto da raiz do MILES
	define('PATH_MILES',dirname(__DIR__) . '/');

	// Seta as constantes
	require PATH_MILES . 'core/system/constantes.php';

	// Carrega as configurações do arquivo miles.json
	require PATH_SYSTEM . 'miles.json.php';
	
	// Carrega os arquivos de configuração do sistema	
	require PATH_SYSTEM . 'config.php';
	
	// Arquivo de compatibilidade entre versões
	include PATH_SYSTEM . 'compatibilidade.php';

	// Rotas
	require PATH_SYSTEM .'rota.php';

	if (AMBIENTE == 'SISTEMA'){
		// Fecha a transação com o banco de dados
		Transacao::Fechar();
	}

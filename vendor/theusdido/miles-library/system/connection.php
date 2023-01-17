<?php
    // Abre a transação atual do banco de dados do projeto
	Transacao::abrir("current");

	if (
		($_controller == '' || $_controller == 'install') && 
		AMBIENTE == 'SISTEMA' &&
		!tdInstall::isInstalled()
	){
		// Redireciona o sistema para instalação do sistema
		include PATH_MVC_CONTROLLER . 'install.php';
		exit;
	}

	// Variavel Global da conexão ative com banco de dados
	$conn = Transacao::Get();

	if ($conn == null){
		showMessage('Não há conexão ativa com o banco de dados.');
		exit;
	}

	// Abre a conexão com o banco de dados MILES
	$connMILES = null; # Descontinuado
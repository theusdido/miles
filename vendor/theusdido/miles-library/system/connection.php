<?php
	#var_dump(Transacao::abrir("current"));
	#var_dump($_controller);
	#var_dump(AMBIENTE);
	#var_dump(tdInstall::isInstalled());

    // Abre a transação atual do banco de dados do projeto
	Transacao::abrir("current");

	if (		
		($_controller == '' || $_controller == 'install') && 
		AMBIENTE == 'SISTEMA' &&
		!tdInstall::isInstalled()
	){
		//echo '#### install ####';
		//!tdInstall::isInstalled()
		// Redireciona o sistema para instalação do sistema
		include PATH_MVC_CONTROLLER . 'install.php';
		exit;
	}

	// Variavel Global da conexão ative com banco de dados
	$conn = Transacao::Get();

	// Abre a conexão com o banco de dados MILES
	$connMILES = null; # Descontinuado

<?php

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
	// Verificar se a sessão não já está aberta
	if (!$_session_isactive){
		try{
			// Cria uma nova sessão
			session_name($sessionName);
			session_start();
		}catch(Throwable $t){
			if (IS_SHOW_ERROR_MESSAGE){
				var_dump($sessionName);
				var_dump(session_id());	
				echo $t->getMessage();
			}
			exit;
		}

	}
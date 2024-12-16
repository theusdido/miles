<?php
	switch(DATABASECONNECTION){
		case 'producao':
			$texto = 'Produção';
		break;
		case 'desenv':
			$texto = 'Desenvolvimento';
		break;
		default:
		$texto = '';	
	}

	$idprojetocurrent 				= CURRENT_PROJECT_ID;
	$dadosconfigprojeto 			= tdClass::Criar("div");
	$dadosconfigprojeto->class 		= "col-md-12 dados-config-projeto-" . DATABASECONNECTION;
	$dadosconfigprojeto->id 		= "dados-config-projeto";
	$dadosconfigprojeto->add("ID: [ <b>{$idprojetocurrent}</b> ] - Instância: <b>".$texto."</b>");

	$ocutarDadosConfigProjeto 			= tdClass::Criar("hyperlink");
	$ocutarDadosConfigProjeto->href		= "#";
	$ocutarDadosConfigProjeto->onclick 	= "$('#dados-config-projeto').hide(500);";
	$ocutarDadosConfigProjeto->id 		= "ocutarDadosConfigProjeto";
	$ocutarDadosConfigProjeto->add("[ Ocultar ]");

	$dadosconfigprojeto->add($ocutarDadosConfigProjeto);
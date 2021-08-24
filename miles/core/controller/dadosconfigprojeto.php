<?php
	if (Session::Get()->currenttypedatabase == "producao"){
		$texto = 'Produção';
	}else if(Session::Get()->currenttypedatabase == "desenv"){
		$texto = 'Desenvolvimento';
	}else{
		$texto = '';
	}
	$idprojetocurrent = Session::Get()->projeto;
	$dadosconfigprojeto = tdClass::Criar("div");
	$dadosconfigprojeto->class = "col-md-12 dados-config-projeto-" . Session::Get()->currenttypedatabase;
	$dadosconfigprojeto->id = "dados-config-projeto";
	$dadosconfigprojeto->add("ID: [ <b>{$idprojetocurrent}</b> ] - Instância: <b>".$texto."</b>");

	$ocutarDadosConfigProjeto = tdClass::Criar("hyperlink");
	$ocutarDadosConfigProjeto->href="#";
	$ocutarDadosConfigProjeto->onclick = "$('#dados-config-projeto').hide(500);";
	$ocutarDadosConfigProjeto->add("[ Ocultar ]");	
	$ocutarDadosConfigProjeto->id = "ocutarDadosConfigProjeto";

	$dadosconfigprojeto->add($ocutarDadosConfigProjeto);
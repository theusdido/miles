<?php

	$_file_mdm_js_compile = Asset::path('FILE_MDM_JS_COMPILE');

	if (!file_exists($_file_mdm_js_compile)){
		$_file_mdm_js_compile = $_path_project_install . FOLDER_BUILD_JS . FILE_MDM_JS_COMPILE;
	}else{
		$_file_mdm_js_compile = $_path_root_project . FOLDER_BUILD_JS . FILE_MDM_JS_COMPILE;
	}
	
	$mdmJSCompile = fopen($_file_mdm_js_compile,"w");

	// Entidades do Sistema
	fwrite($mdmJSCompile,'
		var td_entidade 		= [];
		var td_atributo 		= [];
		var td_relacionamento 	= [];
		var td_permissoes 		= [];
		var td_filtroatributo 	= [];
		var td_consulta 		= [];
		var td_relatorio 		= [];
		var td_status 			= [];
		var td_movimentacao 	= [];
		var formulario          = [];
	');

	$dataset 		= tdClass::Criar("repositorio",array(ENTIDADE))->carregar();
	if ($dataset){
		foreach ($dataset as $entidade){
			fwrite($mdmJSCompile,"
				td_entidade[{$entidade->id}] = ".Entity::getJSON($entidade->id).";
			");
		}
	}

	// Atributos do Sistema	
	$dataset = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar();	
	if ($dataset){
		foreach ($dataset as $atributo){
			fwrite($mdmJSCompile,"
				td_atributo[{$atributo->id}] =".Field::getJSON($atributo->id).";
			");
		}
	}

	// Relacionamentos do Sistema	
	$dataset = tdClass::Criar("repositorio",array(RELACIONAMENTO))->carregar();		
	if ($dataset){
		foreach ($dataset as $relacionamento){
			fwrite($mdmJSCompile,"
				td_relacionamento[{$relacionamento->id}] = ".Relationship::getJSON($relacionamento->id).";
			");
		}
	}

	$sqlPermissoes = tdClass::Criar("sqlcriterio");
	$sqlPermissoes->addFiltro(USUARIO,"=",(isset(Session::Get()->userid)?Session::Get()->userid:0));
	$dataset = tdClass::Criar("repositorio",array(PERMISSOES))->carregar();
	if ($dataset){
		foreach ($dataset as $permissoes){
			fwrite($mdmJSCompile,"
				td_permissoes[{$permissoes->id}] = ".Permission::getJSON($permissoes->id).";
			");
		}
	}

	$dataset = tdClass::Criar("repositorio",array(FILTROATRIBUTO))->carregar();		
	if ($dataset){
		foreach ($dataset as $filtroatributo){
			fwrite($mdmJSCompile,"
				td_filtroatributo[{$filtroatributo->id}] = ".FilterAttribute::getJSON($filtroatributo->id).";
			");
		}
	}

	// Consultas 
	$dataset = tdClass::Criar("repositorio",array(CONSULTA))->carregar();		
	if ($dataset){
		foreach ($dataset as $consulta){
			fwrite($mdmJSCompile,"
				td_consulta[{$consulta->id}] = ".Query::getJSON($consulta->id).";
			");
		}
	}

	// Relatório
	$dataset = tdClass::Criar("repositorio",array(RELATORIO))->carregar();		
	if ($dataset){
		foreach ($dataset as $relatorio){
			fwrite($mdmJSCompile,"
				td_relatorio[{$relatorio->id}] = ".Reporty::getJSON($relatorio->id).";
			");
		}
	}

	// Status
	$dataset = tdClass::Criar("repositorio",array("td_status"))->carregar();
	if ($dataset){
		foreach ($dataset as $status){
			fwrite($mdmJSCompile,"
				td_status[{$status->id}] = ".Status::getJSON($status->id).";
			");
		}
	}

	// Movimentação
	$dataset = tdClass::Criar("repositorio",array("td_movimentacao"))->carregar();
	if ($dataset){
		foreach ($dataset as $movimentacao){
			fwrite($mdmJSCompile,"
				td_movimentacao[{$movimentacao->id}] = ".Movimentation::getJSON($movimentacao->id).";
			");
		}
	}

	// Fecha o arquivo
	fclose($mdmJSCompile);
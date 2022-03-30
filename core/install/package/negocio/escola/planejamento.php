<?php
	
	// Setando variáveis
	$entidadeNome = "erp_escola_planejamento";
	$entidadeDescricao = "Planejamento";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);
	
	// Criando Atributos
	$numero				= criarAtributo($conn,$entidadeID,"numero","Número","tinyint",0,0,25,1);
	$cabecalho 			= criarAtributo($conn,$entidadeID,"cabecalho","Cabeçalho","text",0,1,21);
	$periodicidade		= criarAtributo($conn,$entidadeID,"periodicidade","Periodicidade","int",0,1,4,0,installDependencia("periodicidade","geral"));
	$data_inicial		= criarAtributo($conn,$entidadeID,"data_inicial","Data Inicial","date",0,1,11,0);
	$data_final			= criarAtributo($conn,$entidadeID,"data_final","Data Final","date",0,1,11,0);
	$professor			= criarAtributo($conn,$entidadeID,"professor","Professor","int",0,0,22,0,installDependencia("erp_escola_professor","package/sistema"));
	$unidadecurricular 	= criarAtributo($conn,$entidadeID,"unidadecurricular","Unidade Curricular","int",0,0,22,0,installDependencia("erp_escola_unidadecurricular","package/sistema"));
	$ano				= criarAtributo($conn,$entidadeID,"anoletivo","Ano Letivo","int",0,1,4,0,installDependencia("ano","geral"));
	
	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$numero,true);

	// Criando Acesso
	$menu = addMenu($conn,'Escola','#','',0,0,'escola');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');
<?php
	
	// Setando variáveis
	$entidadeNome 		= "erp_escola_avaliacao";
	$entidadeDescricao 	= "Avaliação";

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
	$titulo				= criarAtributo($conn,$entidadeID,"titulo","Título","varchar",200,0,3,1,0,0,"");
	$unidadecurricular 	= criarAtributo($conn,$entidadeID,"unidadecurricular","Unidade Curricular","int",0,1,22,1,installDependencia("erp_escola_unidadecurricular","package/sistema"));
	$professor 			= criarAtributo($conn,$entidadeID,"professor","Professor","int",0,1,22,1,installDependencia("erp_escola_professor","package/sistema"));
	$descricao          = criarAtributo($conn,$entidadeID,"descricao","Descrição","text",0,1,21,0);
	$peso				= criarAtributo($conn,$entidadeID,"peso","Peso","float",0,0,26);

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$titulo,true);
	
	// Criando Acesso
	$menu = addMenu($conn,'Escola','#','',0,0,'escola');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');

	// Critérios de Avaliação - Relacionamento
	criarRelacionamento($conn,11,$entidadeID,installDependencia("erp_escola_criterioavaliacao",'package/negocio/escola/pedagogico/criterioavaliacao'),"Critérios de Avaliação");
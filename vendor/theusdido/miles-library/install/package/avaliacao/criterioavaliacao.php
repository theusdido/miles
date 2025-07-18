<?php
	
	// Setando variáveis
	$entidadeNome       = "criterioavaliacao";
	$entidadeDescricao  = "Critério de Avaliação";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
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

	$unidadecurricular_entidade_id   	= installDependencia("erp_escola_unidadecurricular",'package/negocio/escola/itinerarioinformativo/unidadecurricular');
	$avaliacao_entidade_id   			= installDependencia("erp_escola_avaliacao",'package/negocio/escola/avaliacao/avaliacao');
	$objetivoespecifico_entidade_id   	= installDependencia("erp_escola_objetivoespecifico",'package/negocio/escola/itinerarioinformativo/objetivoespecifico');

	// Criando Atributos
	$avaliacao          	= criarAtributo($conn,$entidadeID,"avaliacao","Avaliação","int",0,1,22,0,$avaliacao_entidade_id);
	$titulo					= criarAtributo($conn,$entidadeID,"titulo","Título","varchar",200,0,3,1,0,0,"");
	$descricao				= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",1000,1,14,0,0,0,"");
	$peso					= criarAtributo($conn,$entidadeID,"peso","Peso","float",0,1,26);
	$objetivoespecifico 	= criarAtributo($conn,$entidadeID,"objetivoespecifico","Objetivo Específico","int",0,1,22,1,$objetivoespecifico_entidade_id);
	$unidadecurricular 		= criarAtributo($conn,$entidadeID,"unidadecurricular","Unidade Curricular","int",0,1,22,1,$unidadecurricular_entidade_id);	
	
	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$titulo,true);
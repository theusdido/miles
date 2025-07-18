<?php
	// Setando variáveis
	$entidadeNome 		= "anoletivo";
	$entidadeDescricao 	= "Ano Letivo";

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

	// Criando Atributos
	$ano 				= criarAtributo($conn,$entidadeID,"ano","Ano","smallint",0,0,25,1);

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$ano,true);
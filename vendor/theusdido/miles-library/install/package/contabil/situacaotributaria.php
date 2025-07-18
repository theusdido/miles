<?php
	// Setando variáveis
	$entidadeNome 			= "situacaotributaria";
	$entidadeDescricao 		= "Situação Tributária";
	
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
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$descricao 		= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");
    $sigla 		    = criarAtributo($conn,$entidadeID,"sigla","Sigla","varchar",10,1,3,1,0,0,"");

	Entity::setDescriptionField($conn,$entidadeID,$descricao,true);
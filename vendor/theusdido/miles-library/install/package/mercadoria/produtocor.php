<?php
	// Setando variáveis
	$entidadeNome 		= "produtocor";
	$entidadeDescricao 	= "Cor do Produto";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$descricao 	= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar","50",0,3,1,0,0,"");
	Entity::setDescriptionField($conn,$entidadeID,$descricao,false);

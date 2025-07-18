<?php
	
	// Setando variáveis
	$entidadeNome = "praticapedagogica";
	$entidadeDescricao = "Prática Pedagógica";

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
	$descricao			= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",500,0,3,1,0,0,"");

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$descricao,true);
<?php
	// Setando variáveis
	$entidadeNome = "database";
	$entidadeDescricao = "Banco de Dados";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 1,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$nome 		= criarAtributo($conn,$entidadeID,"nome","Nome","varchar",15,0,3,1,0,0,"");
	$descricao 	= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");
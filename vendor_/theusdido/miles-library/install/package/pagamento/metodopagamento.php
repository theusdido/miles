<?php

	// Setando variáveis
	$entidadeNome 		= "metodopagamento";
	$entidadeDescricao 	= "Método de Pagamento";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$email = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3,1);
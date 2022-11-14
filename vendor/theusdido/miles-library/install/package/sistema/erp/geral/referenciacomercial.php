<?php
	// Setando variáveis
	$entidadeNome = "erp_geral_referenciacomercial";
	$entidadeDescricao = "Referencia Comercial";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
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
	$descricao  = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,1,3);
<?php
	// Setando variáveis
	$entidadeNome = "tipochave";
	$entidadeDescricao = "Tipo de Chave";
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
	$descricaochave = criarAtributo($conn,$entidadeID,"descricaochave","Descrição da Chave","varchar",200,0,3);
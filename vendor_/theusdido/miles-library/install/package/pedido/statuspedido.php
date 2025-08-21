<?php

	// Setando variáveis
	$entidadeNome 		= "statuspedido";
	$entidadeDescricao 	= "Status do Pedido";

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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$descricao 			= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3,1);
	$operacaoestoque 	= criarAtributo($conn,$entidadeID,"operacaoestoque","Operação de Estoque","int",0,1,4,0,installDependencia("ecommerce_tipooperacaoestoque","package/website/ecommerce/estoque/tipooperacaoestoque"));
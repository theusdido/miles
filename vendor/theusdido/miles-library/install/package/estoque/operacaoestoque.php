<?php
	// Setando variáveis
	$entidadeNome 		= "operacaoestoque";
	$entidadeDescricao 	= "Operação de Estoque";

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
		$registrounico = 0,
		$carregarlibjavascript = 1,
		$criarinativo = false
	);
	
	// Criando Atributos
	
	$produto 			= criarAtributo($conn,$entidadeID,"produto","Produto","int",0,0,22,1,installDependencia("ecommerce_produto","package/website/ecommerce/mercadoria/produto"));
	$quantidade 		= criarAtributo($conn,$entidadeID,"quantidade","Quantidade","int",0,1,25,1);
	$operacaoestoque 	= criarAtributo($conn,$entidadeID,"operacaoestoque","Operação","int",0,0,4,1,installDependencia("ecommerce_tipooperacaoestoque","package/website/ecommerce/estoque/tipooperacaoestoque"));
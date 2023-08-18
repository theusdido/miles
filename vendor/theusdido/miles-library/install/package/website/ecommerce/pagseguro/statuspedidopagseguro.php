<?php

	// Setando variáveis
	$entidadeNome 		= "ecommerce_pagseguro_statuspedido";
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
	$significado		= criarAtributo($conn,$entidadeID,"significado","Significado","text",0,1,21,0);
	$operacaoestoque 	= criarAtributo($conn,$entidadeID,"operacaoestoque","Operação de Estoque","int",0,1,4,0,installDependencia("ecommerce_tipooperacaoestoque","package/website/ecommerce/estoque/tipooperacaoestoque"));

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
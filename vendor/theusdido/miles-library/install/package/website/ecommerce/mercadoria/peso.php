<?php

	// Setando variáveis
    $entidadeNome 		= "ecommerce_peso";
	$entidadeDescricao 	= "Peso";

	// Categoria
    $entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0,
		1,
		true
    );

	$peso 		= criarAtributo($conn,$entidadeID,"peso","Peso","float",0,0,26);
	$produto 	= criarAtributo($conn,$entidadeID,"produto","Produto","int",0,1,16,installDependencia("ecommerce_produto","package/website/ecommerce/mercadoria/produto"));
	Entity::setDescriptionField($conn,$entidadeID,$peso,false);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
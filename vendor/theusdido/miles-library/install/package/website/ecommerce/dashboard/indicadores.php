<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_indicadores";
	$entidadeDescricao 	= "Indicadores";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 1,
		$carregarlibjavascript = 1,
		$criarinativo = false
	);

	// Criando Atributos
	$visitanteonline 		= criarAtributo($conn,$entidadeID,"visitanteonline","Visitantes Online","int",0,1,25);
	$totalvisitas 	        = criarAtributo($conn,$entidadeID,"totalvisitas","Total Visitas","int",0,1,25);
	$carrinhosativos		= criarAtributo($conn,$entidadeID,"carrinhosativos","Carrinhos Ativos","int",0,1,25);
	$carrinhoabandonados	= criarAtributo($conn,$entidadeID,"carrinhoabandonados","Carrinhos Abandonados","int",0,1,25);
	$devolucoesetrocas		= criarAtributo($conn,$entidadeID,"devolucoesetrocas","Devoluções e Trocas","int",0,1,25);
	$produtosesgotados 		= criarAtributo($conn,$entidadeID,"produtosesgotados","Produtos Esgotados","int",0,1,25);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
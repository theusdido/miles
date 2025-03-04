<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_transportadora";
	$entidadeDescricao 	= "Transportadora";

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
	$nome 	= criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,0,3,1,0,0,"");	
	$logo   = criarAtributo($conn,$entidadeID,"logo","Logo","varchar",200,1,19,0);
	Entity::setDescriptionField($conn,$entidadeID ,$nome,true);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
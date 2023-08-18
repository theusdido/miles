<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_unidademedida";
	$entidadeDescricao 	= "Unidade de Medida";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
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
	$descricao  = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar","200",1,3,1,0,0,"");
	$sigla      = criarAtributo($conn,$entidadeID,"sigla","Sigla","varchar",3,1,3,1);
	Entity::setDescriptionField($conn,$entidadeID,$descricao,false);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
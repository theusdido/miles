<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_especificacaotecnica";
	$entidadeDescricao 	= "Especificação Técnica";
	
	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

    // Criando Atributos
    $descricao  = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar","1000",0,3,1,0,0,"");
    $icone      = criarAtributo($conn,$entidadeID,"icone","Ícone","text","",1,19,0,0,0,"");
	$ordem      = criarAtributo($conn,$entidadeID,"ordem","Ordem","int",1,1,25,0,0,0,"");

   	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	Entity::setDescriptionField($conn,$entidadeID,$descricao,true);

	// Adicionando Menu
    addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
<?php
	// Setando variáveis
    $entidadeNome = "ecommerce_marca";
	$entidadeDescricao = "Marca";

	// Categoria
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
		$registrounico = 0
    );

	$descricao = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','','','','ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
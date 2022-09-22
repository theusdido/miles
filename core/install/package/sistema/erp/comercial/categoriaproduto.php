<?php
	// Setando variáveis
	$entidadeNome = "erp_comercial_categoriaproduto";
	$entidadeDescricao = "Categoria de Produto";

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
	$descricao = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar","200",1,3,1,0,0,"");

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Categoria de Produto','#','',0,0,'comercial');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'comercial-' . $entidadeNome,$entidadeID, 'cadastro');
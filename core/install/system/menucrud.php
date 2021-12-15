<?php
	// Setando variáveis
	$entidadeNome = "menucrud";
	$entidadeDescricao = "Menu ( CRUD )";
	
	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 1,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$descricao = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");

	// Criando Entidade
	$menucruditensID = criarEntidade(
		$conn,
		"menucruditens",
		"Menu CRUD Itens",
		$ncolunas=3,
		$exibirmenuadministracao = 1,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$link = criarAtributo($conn,$menucruditensID,"link","Link","varchar",500,0,3,1,0,0,"");
	$menu = criarAtributo($conn,$menucruditensID,"menu","Menu","int",0,0,4,0,0,0,"");
	$descricao = criarAtributo($conn,$menucruditensID,"descricao","Descrição","varchar",50,0,3,1,0,0,"");
	# Tipo =>  1 - Cadastro; 2 - Consulta; 3 - Movimentação; 4 - Relatório;
	$tipo = criarAtributo($conn,$menucruditensID,"tipo","Tipo","int",0,0,4,0,0,0,"");
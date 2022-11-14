<?php

	// Setando variáveis
	$entidadeNome 		= "menu";
	$entidadeDescricao 	= "Menu";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 1,
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
	$descricao 	= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",120,0,3,1,0,0,"");
	$link 		= criarAtributo($conn,$entidadeID,"link","Link","varchar",200,0,3,1,0,0,"");
	$target 	= criarAtributo($conn,$entidadeID,"target","Target","varchar",15,1,3,1,0,0,"");
	$pai 		= criarAtributo($conn,$entidadeID,"pai","Pai","int",0,0,4,0,$entidadeID,0,"");
	$ordem 		= criarAtributo($conn,$entidadeID,"ordem","Ordem","smallint",0,0,3,0,0,0,"");
	$fixo 		= criarAtributo($conn,$entidadeID,"fixo","Fixo","varchar",200,0,3,0,0,0,"");
	$tipomenu 	= criarAtributo($conn,$entidadeID,"tipomenu","Tipo de Menu","varchar",35,0,3,0,0,0,"");
	$entidade 	= criarAtributo($conn,$entidadeID,"entidade","Entidade","int",0,0,3,0,0,0,"");
	$path 		= criarAtributo($conn,$entidadeID,"path","Path","varchar",250,1,3);
	$icon 		= criarAtributo($conn,$entidadeID,"icon","Icon","varchar",50,1,3);
	$coluna		= criarAtributo($conn,$entidadeID,"coluna","Coluna","smallint",0,1,4);
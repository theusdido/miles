<?php
	// Setando variáveis
	$entidadeNome = "relacionamento";
	$entidadeDescricao = "Relacionamento";
	
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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$pai 				= criarAtributo($conn,$entidadeID,"pai","Pai","int",0,0,4,0,0,0,"");
	$tipo 				= criarAtributo($conn,$entidadeID,"tipo","Tipo","int",0,0,4,0,0,0,"");
	$filho 				= criarAtributo($conn,$entidadeID,"filho","Filho","int",0,0,4,0,0,0,"");
	$td_atributo 		= criarAtributo($conn,$entidadeID,"atributo","Atributo","int",0,1,4,1,0,0,"");
	$descricao 			= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");
	$controller 		= criarAtributo($conn,$entidadeID,"controller","Controller","text",0,1,3,1,0,0,"");
	$cardinalidade 		= criarAtributo($conn,$entidadeID,"cardinalidade","Cadinalidade","char",2,1,4);
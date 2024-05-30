<?php
	// Setando variáveis
	$entidadeNome = "abas";
	$entidadeDescricao = "Abas";
	
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
	$entidade 	= criarAtributo($conn,$entidadeID,'entidade',"Entidade","int",0,0,4,0);
	$descricao 	= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",120,0,3,1,0,0,"");
	$atributos 	= criarAtributo($conn,$entidadeID,"atributos","Atributos","varchar",200,0,3,1,0,0,"");
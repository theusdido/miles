<?php
	// Setando variáveis
	$entidadeNome = "projeto";
	$entidadeDescricao = "Projeto";

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
	$nomefantasia 		= criarAtributo($conn,$entidadeID,"nome","Nome","varchar",120,0,3,1,0,0,"");
	$projectdiretorio 	= criarAtributo($conn,$entidadeID,"projectdiretorio","Diretório Projeto","varchar",50,1,3);

	// Criando Aba
	criarAba($conn,$entidadeID,"Capa",$nomefantasia.",".$projectdiretorio);
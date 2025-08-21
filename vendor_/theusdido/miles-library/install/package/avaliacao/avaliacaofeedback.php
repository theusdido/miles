<?php

	// Setando variáveis
	$entidadeNome 		= "avaliacaofeedback";
	$entidadeDescricao 	= "Feedback da Avaliação";

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
	$avaliacao 			= criarAtributo($conn,$entidadeID,"avaliacao","Avaliação","int",0,1,22,1,installDependencia("erp_escola_avaliacao","package/sistema"));
	$aluno	 			= criarAtributo($conn,$entidadeID,"aluno","Aluno","int",0,1,22,1,installDependencia("erp_escola_aluno","package/sistema"));	
	$nota				= criarAtributo($conn,$entidadeID,"nota","Nota","float",0,0,26);
	$feedback			= criarAtributo($conn,$entidadeID,"feedback","Feedback","text",0,1,21);
<?php

	// Setando variáveis
	$entidadeNome 		= "avaliacaoaluno";
	$entidadeDescricao 	= "Avaliação do Aluno";

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
	$avaliacao 			= criarAtributo($conn,$entidadeID,"avaliacao","Avaliação","int",0,1,16,1,installDependencia("erp_escola_avaliacao","package/sistema"));
	$aluno	 			= criarAtributo($conn,$entidadeID,"aluno","Aluno","int",0,1,16,1,installDependencia("erp_escola_aluno","package/sistema"));
	$is_feedback		= criarAtributo($conn,$entidadeID,"is_feedback","Feedback ?","boolean",0,1,7);
	
	// Relacionamento
	criarRelacionamento(
		$conn , #1
		$tipo = 6, #2
		$entidadePai = getEntidadeId("erp_escola_aluno"), #3
		$entidadeFilho = $entidadeID,#4
		$descricao = "Avaliações" ,#5
		$atributo = $avaliacao #6
	);
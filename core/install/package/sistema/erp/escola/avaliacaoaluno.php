<?php

	// Setando variáveis
	$entidadeNome = "erp_escola_avaliacaoaluno";
	$entidadeDescricao = "Avaliação do Aluno";

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
	$avaliacao 			= criarAtributo($conn,$entidadeID,"avaliacao","Avaliação","int",0,1,16,1,installDependencia("erp_escola_avaliacaoturma","package/sistema"));
	$aluno	 			= criarAtributo($conn,$entidadeID,"aluno","Aluno","int",0,1,16,1,installDependencia("erp_escola_aluno","package/sistema"));
	$nota				= criarAtributo($conn,$entidadeID,"nota","Nota","float",0,0,11);

	// Criando Acesso
	$menu = addMenu($conn,'Escola','#','',0,0,'escola');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');
	
	// Relacionamento
	criarRelacionamento(
		$conn , #1
		$tipo = 6, #2
		$entidadePai = getEntidadeId("erp_escola_aluno"), #3
		$entidadeFilho = $entidadeID,#4
		$descricao = "Avaliações" ,#5
		$atributo = $avaliacao #6
	);
<?php

	// Setando variáveis
	$entidadeNome = "erp_escola_turmaunidadecurricular";
	$entidadeDescricao = "Unidade Curricular da Turma";

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
	$turma 				= criarAtributo($conn,$entidadeID,"turma","Turma","int",0,1,16,1,installDependencia("erp_escola_turma","package/sistema"));
	$unidadecurricular 	= criarAtributo($conn,$entidadeID,"unidadecurricular","Unidade Curricular","int",0,0,22,1,installDependencia("erp_escola_unidadecurricular","package/sistema"));
	$aulassemanais		= criarAtributo($conn,$entidadeID,"aulassemanais","Aulas Semanais","tinyint",0,1,25);

	// Criando Acesso
	$menu = addMenu($conn,'Escola','#','',0,0,'escola');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');
	
	// Relacionamento
	criarRelacionamento(
		$conn , #1
		$tipo = 6, #2
		$entidadePai = getEntidadeId("erp_escola_turma"), #3
		$entidadeFilho = $entidadeID,#4
		$descricao = "Unidade Curricular" ,#5
		$atributo = $turma #6
	);
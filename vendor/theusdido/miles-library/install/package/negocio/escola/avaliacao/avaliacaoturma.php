<?php

	// Setando variáveis
	$entidadeNome 		= "erp_escola_avaliacaoturma";
	$entidadeDescricao 	= "Avaliação da Turma";

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
	$turma 				= criarAtributo($conn,$entidadeID,"turma","Turma","int",0,1,22,1,installDependencia("erp_escola_turma","package/sistema"));
	$avaliacao 			= criarAtributo($conn,$entidadeID,"avaliacao","Avaliação","int",0,1,22,1,installDependencia("erp_escola_avaliacao","package/sistema"));
	$tipo	 			= criarAtributo($conn,$entidadeID,"tipo","Tipo","int",0,1,4,1,installDependencia("erp_escola_avaliacaotipo","package/sistema"));
	$descricao          = criarAtributo($conn,$entidadeID,"descricao","Descrição","text",0,1,21,0);
	$data				= criarAtributo($conn,$entidadeID,"data","Data","date",0,0,11);

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$descricao,true);

	// Criando Acesso
	$menu = addMenu($conn,'Pedagógico','#','',0,0,'escola-pedagogico-avaliacao');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');
	
	// Relacionamento
	criarRelacionamento(
		$conn , #1
		$tipo = 6, #2
		$entidadePai = getEntidadeId("erp_escola_avaliacao"), #3
		$entidadeFilho = $entidadeID,#4
		$descricao = "Turmas" ,#5
		$atributo = $avaliacao #6
	);
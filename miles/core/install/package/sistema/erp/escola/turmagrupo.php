<?php
	// Setando variáveis
	$entidadeNome = "erp_escola_turmagrupo";
	$entidadeDescricao = "Grupo da Turma";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
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
	$descricao 	= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar","200",0,3,1,0,0,"");

	// Criando Acesso
	$menu = addMenu($conn,'Escola','#','',0,0,'escola');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".PREFIXO.$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');
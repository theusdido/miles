<?php
	
	// Setando variáveis
	$entidadeNome = "erp_escola_aluno";
	$entidadeDescricao = "Aluno";

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
	$nome 		= criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,0,3,1,0,0,"");
	$matricula 	= criarAtributo($conn,$entidadeID,"matricula","Matricula","varchar",15,1,3,1,0,0,"");
	$turma    	= criarAtributo($conn,$entidadeID,"turma","Turma","int",0,1,22,1,installDependencia("erp_escola_turma","package/sistema"));
	$turmagrupo	= criarAtributo($conn,$entidadeID,"turmagrupo","Grupo da Turma","int",0,1,22,1,installDependencia("erp_escola_turmagrupo","package/sistema"));

	// Criando Acesso
	$menu = addMenu($conn,'Escola','#','',0,0,'escola');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');
<?php

	// Setando variáveis
	$entidadeNome = "erp_escola_atendimento";
	$entidadeDescricao = "Atendimento";

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
	$aluno           	= criarAtributo($conn,$entidadeID,"aluno","Aluno","int",0,1,22,1,installDependencia("erp_escola_aluno","package/sistema"));
	$professor 			= criarAtributo($conn,$entidadeID,"professor","Professor","int",0,1,22,1,installDependencia("erp_escola_professor","package/sistema"));
	$unidadecurricular  = criarAtributo($conn,$entidadeID,"unidadecurricular","Unidade Curricular","int",0,1,22,1,installDependencia("erp_escola_unidadecurricular","package/sistema"));
    $descricao          = criarAtributo($conn,$entidadeID,"descricao","Descrição","text",0,1,21,0);
    $data				= criarAtributo($conn,$entidadeID,"data","Data","date",0,0,11,0);
    $hora               = criarAtributo($conn,$entidadeID,"hora","Hora","time",0,0,28,0);

	// Criando Acesso
	$menu = addMenu($conn,'Escola','#','',0,0,'escola');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');
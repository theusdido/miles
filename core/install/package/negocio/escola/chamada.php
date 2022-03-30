<?php

	// Setando variáveis
	$entidadeNome = "erp_escola_chamada";
	$entidadeDescricao = "Chamada";

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
	$aula           	= criarAtributo($conn,$entidadeID,"aula","Aula","int",0,1,16,1,installDependencia("erp_escola_aula","package/sistema"));
    $aluno              = criarAtributo($conn,$entidadeID,"aluno","Aluno","int",0,1,22,1,installDependencia("erp_escola_aluno","package/sistema"));
    $descricao          = criarAtributo($conn,$entidadeID,"descricao","Descrição","text",0,1,21,0);
    $is_presente		= criarAtributo($conn,$entidadeID,"is_presente","Presente ?","boolean",0,0,7);

	// Criando Acesso
	$menu = addMenu($conn,'Escola','#','',0,0,'escola');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');
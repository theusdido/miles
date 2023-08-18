<?php
	
	// Setando variáveis
	$entidadeNome = "erp_escola_unidadecurricular";
	$entidadeDescricao = "Unidade Curricular";

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
	$nome			= criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,0,3,1,0,0,"");
	$curso    		= criarAtributo($conn,$entidadeID,"curso","Curso","int",0,1,22,1,installDependencia("erp_escola_curso","package/sistema"));
	$objetivogeral	= criarAtributo($conn,$entidadeID,"objetivogeral","Objetivo Geral","text",0,1,21);

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$nome,true);
	
	// Criando Acesso
	$menu = addMenu($conn,'Escola','#','',0,0,'escola');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');
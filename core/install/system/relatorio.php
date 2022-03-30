<?php
	// Setando variáveis
	$entidadeNome = "relatorio";
	$entidadeDescricao = "Relatório";
	
	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 1,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$descricao = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");
	$entidade = criarAtributo($conn,$entidadeID,"entidade","Entidade","int",0,0,4,0,0,0,"");
	$urlpersonalizada = criarAtributo($conn,$entidadeID,"urlpersonalizada","URL Personalizada","varchar",200,0,3,0,0,0,"");

	// Criando Entidade
	$filtrosID = criarEntidade(
		$conn,
		"relatoriofiltro",
		"Relatório Filtros",
		$ncolunas=3,
		$exibirmenuadministracao = 1,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$relatorio = criarAtributo($conn,$filtrosID,"relatorio","Relatório","int",0,0,4,0,0,0,"");
	$atributo = criarAtributo($conn,$filtrosID,"atributo","Atributo","int",0,0,4,0,0,0,"");
	$operador = criarAtributo($conn,$filtrosID,"operador","Operador","varchar",5,0,3,1,0,0,"");
	$legenda = criarAtributo($conn,$filtrosID,"legenda","Legenda","varchar",50,0,3,1,0,0,"");
	
	// Status Grade
	$statusGradeID = criarEntidade(
		$conn,
		"relatoriostatus",
		"Relatório Status",
		$ncolunas=3,
		$exibirmenuadministracao = 1,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);
	
	$relatorio = criarAtributo($conn,$statusGradeID,"relatorio","Relatório","int",0,0,4,0,0,0,"");
	$atributo = criarAtributo($conn,$statusGradeID,"atributo","Atributo","int",0,0,4,0,0,0,"");
	$operador = criarAtributo($conn,$statusGradeID,"operador","Operador","varchar",5,0,3,1,0,0,"");
	$valor = criarAtributo($conn,$statusGradeID,"valor","Valor","varchar",200,0,3,1,0,0,"");
	$status = criarAtributo($conn,$statusGradeID,"status","Status","int",0,1,4,0,getEntidadeId("status",$conn),0,"",1,0);
<?php
	// Setando variáveis
	$entidadeNome 		= "relatorio";
	$entidadeDescricao 	= "Relatório";
	
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
	$descricao 			= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");
	$entidade 			= criarAtributo($conn,$entidadeID,"entidade","Entidade","int",0,0,4,0,0,0,"");
	$urlpersonalizada 	= criarAtributo($conn,$entidadeID,"urlpersonalizada","URL Personalizada","varchar",200,0,3,0,0,0,"");
	$fixo 				= criarAtributo($conn,$entidadeID,"fixo","Fixo","varchar",50,1,3);

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
	$relatorio 	= criarAtributo($conn,$filtrosID,"relatorio","Relatório","int",0,0,4,0,0,0,"");
	$atributo 	= criarAtributo($conn,$filtrosID,"atributo","Atributo","int",0,0,4,0,0,0,"");
	$operador 	= criarAtributo($conn,$filtrosID,"operador","Operador","varchar",5,0,3,1,0,0,"");
	$legenda 	= criarAtributo($conn,$filtrosID,"legenda","Legenda","varchar",50,0,3,1,0,0,"");
	$ordem 		= criarAtributo($conn,$filtrosID,"ordem","Ordem","tinyint",0,1,3);

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


	// Criando Entidade
	$filtrosIniciaisID = criarEntidade(
		$conn,
		"relatoriorestricao",
		"Restrição do Relatório",
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
	$relatorio 	= criarAtributo($conn,$filtrosIniciaisID,"relatorio","Relatório","int",0,0,4,0,0,0,"");
	$atributo 	= criarAtributo($conn,$filtrosIniciaisID,"atributo","Atributo","int",0,0,4,0,0,0,"");
	$operador 	= criarAtributo($conn,$filtrosIniciaisID,"operador","Operador","varchar",5,0,3,1,0,0,"");
	$valor 		= criarAtributo($conn,$filtrosIniciaisID,"valor","Valor","varchar",200,0,3,1,0,0,"");
	$legenda 	= criarAtributo($conn,$filtrosIniciaisID,"legenda","Legenda","varchar",50,0,3,1,0,0,"");

	// Relatório Colunas
	$colunasID = criarEntidade(
		$conn,
		"relatoriocoluna",
		"Coluna Relatório",
		$ncolunas=3,
		$exibirmenuadministracao = 1,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$relatorio 		= criarAtributo($conn,$colunasID,"relatorio","Relatório","int",0,0,4,0,0,0,"");
	$atributo 		= criarAtributo($conn,$colunasID,"atributo","Atributo","int",0,0,4,0,0,0,"");
	$alinhamento 	= criarAtributo($conn,$colunasID,"alinhamento","alinhamento","varchar",25,1,3);
	$ordem 			= criarAtributo($conn,$colunasID,"ordem","Ordem","tinyint",0,1,3);
	$descricao 		= criarAtributo($conn,$colunasID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");
	$is_somatorio	= criarAtributo($conn,$colunasID,"is_somatorio","Realizar Somatório","boolean",0,1,7);
	$exibirid 		= criarAtributo($conn,$colunasID,"exibirid","Exibir ID","boolean",0,1,7);
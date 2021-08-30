<?php
	// Setando variáveis
	$entidadeNome = "consulta";
	$entidadeDescricao = "Consulta";

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
	$descricao 		= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");
	$entidade 		= criarAtributo($conn,$entidadeID,"td_entidade","Entidade","int",0,0,4,0,0,0,"");
	$movimentacao 	= criarAtributo($conn,$entidadeID,"td_movimentacao","Movimentação","int",0,0,4,0,0,0,"");

	// Criando Entidade
	$filtrosID = criarEntidade(
		$conn,
		"consultafiltro",
		"Consulta Filtros",
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
	$consulta 	= criarAtributo($conn,$filtrosID,"td_consulta","Consulta","int",0,0,4,0,0,0,"");
	$atributo 	= criarAtributo($conn,$filtrosID,"td_atributo","Atributo","int",0,0,4,0,0,0,"");
	$operador 	= criarAtributo($conn,$filtrosID,"operador","Operador","varchar",5,0,3,1,0,0,"");
	$legenda 	= criarAtributo($conn,$filtrosID,"legenda","Legenda","varchar",50,0,3,1,0,0,"");
	$ordem	 	= criarAtributo($conn,$filtrosID,"ordem","Ordem","tinyint",0,1,25,0);

	// Status Grade
	$statusGradeID = criarEntidade(
		$conn,
		"consultastatus",
		"Consulta Status",
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

	$consulta = criarAtributo($conn,$statusGradeID,"td_consulta","Consulta","int",0,0,4,0,0,0,"");
	$atributo = criarAtributo($conn,$statusGradeID,"td_atributo","Atributo","int",0,0,4,0,0,0,"");
	$operador = criarAtributo($conn,$statusGradeID,"operador","Operador","varchar",5,0,3,1,0,0,"");
	$valor = criarAtributo($conn,$statusGradeID,"valor","Valor","varchar",200,0,3,1,0,0,"");
	$status = criarAtributo($conn,$statusGradeID,"status","Status","int",0,1,4,0,getEntidadeId("status",$conn),0,"",1,0);

	// Criando Entidade
	$filtrosIniciaisID = criarEntidade(
		$conn,
		"consultafiltroinicial",
		"Consulta Filtros Iniciais",
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
	$consulta = criarAtributo($conn,$filtrosIniciaisID,"td_consulta","Consulta","int",0,0,4,0,0,0,"");
	$atributo = criarAtributo($conn,$filtrosIniciaisID,"td_atributo","Atributo","int",0,0,4,0,0,0,"");
	$operador = criarAtributo($conn,$filtrosIniciaisID,"operador","Operador","varchar",5,0,3,1,0,0,"");
	$valor = criarAtributo($conn,$filtrosIniciaisID,"valor","Valor","varchar",200,0,3,1,0,0,"");
	$legenda = criarAtributo($conn,$filtrosIniciaisID,"legenda","Legenda","varchar",50,0,3,1,0,0,"");
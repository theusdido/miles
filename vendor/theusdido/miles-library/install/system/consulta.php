<?php
	// Setando variáveis
	$entidadeNome 		= "consulta";
	$entidadeDescricao 	= "Consulta";

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
	$movimentacao 		= criarAtributo($conn,$entidadeID,"movimentacao","Movimentação","int",0,0,4,0,0,0,"");
	$exibirbotaoeditar	= criarAtributo($conn,$entidadeID,"exibirbotaoeditar","Exibir Botão Editar","boolean",0,1,7);
	$exibirbotaoexcluir	= criarAtributo($conn,$entidadeID,"exibirbotaoexcluir","Exibir Botão Excluir","boolean",0,1,7);
	$exibirbotaoemmassa	= criarAtributo($conn,$entidadeID,"exibirbotaoemmassa","Exibir Botão Em Massa","boolean",0,1,7);
	$exibircolunaid		= criarAtributo($conn,$entidadeID,"exibircolunaid","Exibir Coluna ID","boolean",0,1,7);
	$adicionaridfiltro	= criarAtributo($conn,$entidadeID,"adicionaridfiltro","Adicionar campo ID no filtro","boolean",0,1,7);
	
	
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
	$consulta 	= criarAtributo($conn,$filtrosID,"consulta","Consulta","int",0,0,4,0,0,0,"");
	$atributo 	= criarAtributo($conn,$filtrosID,"atributo","Atributo","int",0,0,4,0,0,0,"");
	$operador 	= criarAtributo($conn,$filtrosID,"operador","Operador","varchar",5,0,3,1,0,0,"");
	$legenda 	= criarAtributo($conn,$filtrosID,"legenda","Legenda","varchar",50,0,3,1,0,0,"");
	$ordem 		= criarAtributo($conn,$filtrosID,"ordem","Ordem","tinyint",0,1,3);

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

	$consulta 	= criarAtributo($conn,$statusGradeID,"consulta","Consulta","int",0,0,4,0,0,0,"");
	$atributo 	= criarAtributo($conn,$statusGradeID,"atributo","Atributo","int",0,0,4,0,0,0,"");
	$operador 	= criarAtributo($conn,$statusGradeID,"operador","Operador","varchar",5,0,3,1,0,0,"");
	$valor 		= criarAtributo($conn,$statusGradeID,"valor","Valor","varchar",200,0,3,1,0,0,"");
	$status 	= criarAtributo($conn,$statusGradeID,"status","Status","int",0,1,4,0,getEntidadeId("status",$conn),0,"",1,0);

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
	$consulta 	= criarAtributo($conn,$filtrosIniciaisID,"consulta","Consulta","int",0,0,4,0,0,0,"");
	$atributo 	= criarAtributo($conn,$filtrosIniciaisID,"atributo","Atributo","int",0,0,4,0,0,0,"");
	$operador 	= criarAtributo($conn,$filtrosIniciaisID,"operador","Operador","varchar",5,0,3,1,0,0,"");
	$valor 		= criarAtributo($conn,$filtrosIniciaisID,"valor","Valor","varchar",200,0,3,1,0,0,"");
	$legenda 	= criarAtributo($conn,$filtrosIniciaisID,"legenda","Legenda","varchar",50,0,3,1,0,0,"");

	// Consulta Colunas
	$colunasID = criarEntidade(
		$conn,
		"consultacoluna",
		"Coluna Colunas",
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
	$consulta 		= criarAtributo($conn,$colunasID,"consulta","Consulta","int",0,0,4,0,0,0,"");
	$atributo 		= criarAtributo($conn,$colunasID,"atributo","Atributo","int",0,0,4,0,0,0,"");
	$exibirid 		= criarAtributo($conn,$colunasID,"exibirid","Exibir ID","boolean",0,1,7);
	$alinhamento 	= criarAtributo($conn,$colunasID,"alinhamento","alinhamento","varchar",25,1,3);
	$ordem 			= criarAtributo($conn,$colunasID,"ordem","Ordem","tinyint",0,1,3);
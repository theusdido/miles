<?php
	// Setando variáveis
	$entidadeNome = "movimentacao";
	$entidadeDescricao = "Movimentação";
	
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

	// Criando Atributos da entidade Movimentação
	$descricao 				= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");
	$entidade	 			= criarAtributo($conn,$entidadeID,"entidade","Entidade","int",0,0,4,0,0,0,"");	
	$motivo 				= criarAtributo($conn,$entidadeID,"motivo","Motivo","int",0,0,4,0,0,0,"");
	$exigirobrigatorio 		= criarAtributo($conn,$entidadeID,"exigirobrigatorio","Exigir Obrigátorio","tinyint",0,1,7,0,0,0,"",1,0);
	$exibirtitulo 			= criarAtributo($conn,$entidadeID,"exibirtitulo","Exigir Título","tinyint",0,1,7,0,0,0,"",1,0);	
	$exibirvaloresantigos 	= criarAtributo($conn,$entidadeID,"exibirvaloresantigos","Exigir Valores Antigos","boolean",0,1,7);	

	// Criando Entidade
	$alterarmovimentoID = criarEntidade(
		$conn,
		"movimentacaoalterar",
		"Movimentação Alterar",
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
	$movimentacao 	= criarAtributo($conn,$alterarmovimentoID,"movimentacao","Movimentação","int",0,0,4,0,0,0,"");
	$atributo 		= criarAtributo($conn,$alterarmovimentoID,"atributo","Atributo","int",0,0,4,0,0,0,"");
	$legenda 		= criarAtributo($conn,$alterarmovimentoID,"legenda","Legenda","varchar",50,0,3,1,0,0,"");

	// Status Grade
	$statusMovimentacaoID = criarEntidade(
		$conn,
		"movimentacaostatus",
		"Movimentação Status",
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

	$movimentacao 	= criarAtributo($conn,$statusMovimentacaoID,"movimentacao","Movimentação","int",0,0,4,0,0,0,"");
	$atributo 		= criarAtributo($conn,$statusMovimentacaoID,"atributo","Atributo","int",0,0,4,0,0,0,"");
	$operador 		= criarAtributo($conn,$statusMovimentacaoID,"operador","Operador","varchar",5,0,3,1,0,0,"");
	$valor 			= criarAtributo($conn,$statusMovimentacaoID,"valor","Valor","varchar",200,0,3,1,0,0,"");
	
	// Histórico Movimentação
	$historicoMovimentacaoID = criarEntidade(
		$conn,
		"movimentacaohistorico",
		"Movimentação Histórico",
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

	$movimentacao 		= criarAtributo($conn,$historicoMovimentacaoID,"movimentacao","Movimentação","int",0,0,4,0,0,0,"");
	$entidade 			= criarAtributo($conn,$historicoMovimentacaoID,"entidade","Entidade","int",0,0,4,0,0,0,"");
	$atributo 			= criarAtributo($conn,$historicoMovimentacaoID,"atributo","Atributo","int",0,0,4,0,0,0,"");
	$legenda 			= criarAtributo($conn,$historicoMovimentacaoID,"legenda","Legenda","varchar",50,0,3,1,0,0,"");
	
	// Histórico de Aletração de Movimentação
	$historicoAlteracaoMovimentacaoID = criarEntidade(
		$conn,
		"movimentacaohistoricoalteracao",
		"Movimentação Histórico Alteração",
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
	
	$movimentacao 		= criarAtributo($conn,$historicoAlteracaoMovimentacaoID,"movimentacao","Movimentação","int",0,0,4,0,0,0,"");
	$usuario 			= criarAtributo($conn,$historicoAlteracaoMovimentacaoID,"usuario","Usuário","int",0,0,4,1,getEntidadeId("usuario",$conn),0,"",1,0);
	$datahora 			= criarAtributo($conn,$historicoAlteracaoMovimentacaoID,"datahora","Data/Hora","datetime",0,0,23,1,0,0,"",1,0);
	$atributo 			= criarAtributo($conn,$historicoAlteracaoMovimentacaoID,"atributo","Atributo","int",0,0,4,1,0,0,"",1,0);
	$valor 				= criarAtributo($conn,$historicoAlteracaoMovimentacaoID,"valor","Valor","varchar",200,0,3,1,0,0,"",1,0);
	$valorold 			= criarAtributo($conn,$historicoAlteracaoMovimentacaoID,"valorold","Valor Antigo","varchar",200,0,3,1,0,0,"",1,0);
	$entidade 			= criarAtributo($conn,$historicoAlteracaoMovimentacaoID,"entidade","Entidade","int",0,0,4,1,0,0,"",1,0);
	$motivo 			= criarAtributo($conn,$historicoAlteracaoMovimentacaoID,"motivo","Motivo","int",0,0,4,1,0,0,"",1,0);
	$entidademotivo 	= criarAtributo($conn,$historicoAlteracaoMovimentacaoID,"entidademotivo","Entidade Motivo","int",0,0,4,1,0,0,"",1,0);
	$observacao 		= criarAtributo($conn,$historicoAlteracaoMovimentacaoID,"observacao","Observação","text",0,1,14,0,0,0,"",1,0);

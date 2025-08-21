<?php

	// Setando variáveis
	$entidadeNome = "historicoacao";
	$entidadeDescricao = "Ação  no Histórico";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 1
	);
	
	// Criando Atributos
	$descricao = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3,1,0,0,"",1,0);
	
	// Setando variáveis
	$entidadeNome = "historicoatividade";
	$entidadeDescricao = "Histórico de Atividade";

	// Criando Entidade
	$entidadeIDHistorico = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 1
	);
	
	// Criando Atributos
	$usuario 	= criarAtributo($conn,$entidadeIDHistorico,"usuario","Usuário","int",0,0,22,1,getEntidadeId("usuario",$conn),0,"",1,0);
	$datahora 	= criarAtributo($conn,$entidadeIDHistorico,"datahora","Data e Hora","date",0,0,23,1,0,0,"",1,0);
	$entidade 	= criarAtributo($conn,$entidadeIDHistorico,"entidade","Entidade","int",0,1,22,1,0,0,"",1,0);
	$atributo 	= criarAtributo($conn,$entidadeIDHistorico,"atributo","Atributo","int",0,1,22,1,0,0,"",1,0);
	$valorold 	= criarAtributo($conn,$entidadeIDHistorico,"valorold","Valor Antigo","varchar",200,0,3,1,0,0,"",1,0);
	$valornew 	= criarAtributo($conn,$entidadeIDHistorico,"valornew","Valor Novo","varchar",200,0,3,1,0,0,"",1,0);
	$registro	= criarAtributo($conn,$entidadeIDHistorico,"registro","Registro","int",0,0,3,1,0,0,"",1,0);
	$acao 		= criarAtributo($conn,$entidadeIDHistorico,"acao","Ação","int",0,0,22,1,$entidadeID,0,"",1,0);
	$observacao = criarAtributo($conn,$entidadeIDHistorico,"observacao","Observação","text",0,1,21,0,0,0,"",1,0);	
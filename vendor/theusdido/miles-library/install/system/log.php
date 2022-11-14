<?php
	// Setando variáveis
	$entidadeNome = "log";
	$entidadeDescricao = "Log";
	
	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 1,
		$exibircabecalho = 1,
		$campodescchave = "''",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$usuario 	= criarAtributo($conn,$entidadeID,"usuario","Usuário","int",0,0,4,0,getEntidadeId("usuario",$conn),0,'',1,0);
	$projeto 	= criarAtributo($conn,$entidadeID,"projeto","Projeto","int",0,0,4,0,getEntidadeId("projeto",$conn),0,"");
	$entidade 	= criarAtributo($conn,$entidadeID,"entidade","Entidade","int",0,0,3,0,0,0,"");
	$atributo 	= criarAtributo($conn,$entidadeID,"atributo","Atributo","int",0,0,3,1,getEntidadeId("atributo",$conn),0,'',1,0);
	$valorid 	= criarAtributo($conn,$entidadeID,"valorid","Valor ID","varchar",200,0,3,0,0,0,'',1,0);
	$valornew 	= criarAtributo($conn,$entidadeID,"valornew","Valor New","varchar",200,1,3,0,0,0,'',1,0);
	$datahora 	= criarAtributo($conn,$entidadeID,"datahora","Data e Hora","datetime",0,0,3,0,0,0,'',1,0);

	/// 1 - Inserção ; 2 - Atualização ; 3 - Exclusão ; - 4 - Acesso
	$acao		= criarAtributo($conn,$entidadeID,"acao","Ação","int",0,0,3,0,0,0,'',1,0);
	$registro 	= criarAtributo($conn,$entidadeID,"registro","Registro","text",0,1,3,0,0,0,'',1,0);
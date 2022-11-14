<?php
	$entidadeNome 		= "ticket";
	$entidadeDescricao 	= "Ticket";
	
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
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);
	
	$tipo 					= criarAtributo($conn,$entidadeID,"tipo","Tipo","int",0,0,4,1,getEntidadeId("tickettipo",$conn),0,"");
	$prioridade 			= criarAtributo($conn,$entidadeID,"prioridade","Prioridade","int",0,0,4,1,getEntidadeId("ticketprioridade",$conn),0,"");
	$usuario 				= criarAtributo($conn,$entidadeID,"usuario","Usuário","int",0,0,16,0,getEntidadeId("usuario",$conn),0,"session.userid",1);
	$responsavel 			= criarAtributo($conn,$entidadeID,"responsavel","Responsável","int",0,0,16,0,getEntidadeId("usuario",$conn),0,"session.userid",1);
	$titulo 				= criarAtributo($conn,$entidadeID,"titulo","Título","varchar",200,0,3,1,0,0,"");
	$descricao 				= criarAtributo($conn,$entidadeID,"descricao","Descrição","text",0,0,21,0,0,0,"");
	$previsaoentrega 		= criarAtributo($conn,$entidadeID,"previsaoentrega","Previsão de Entrega","datetime",0,1,23,1,0,0,"");
	$datacriacao 			= criarAtributo($conn,$entidadeID,"datacriacao","Data de Criação","datetime",0,0,23,0,0,0,"",1,1);
	$anexo 					= criarAtributo($conn,$entidadeID,"anexo","Anexo","varchar",200,1,19,0,0,0,"",1,0);
	$status 				= criarAtributo($conn,$entidadeID,"status",array("Status","Aberto","Finalizado"),"tinyint",0,1,7,0);

	$entidadeNome 			= "ticketinteraction";
	$entidadeDescricao 		= "Ticket Interação";
	
	$ticketinteractionID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	$descricao 	= criarAtributo($conn,$ticketinteractionID,"descricao","Descrição","text",0,0,21,1,0,0,"");
	$data 		= criarAtributo($conn,$ticketinteractionID,"data","Data","datetime",0,1,23,1,0,0,"",1,0);
	$anexo 		= criarAtributo($conn,$ticketinteractionID,"anexo","Anexo","varchar",200,1,19,0,0,0,"",1,0);
	$ticket 	= criarAtributo($conn,$ticketinteractionID,"ticket","Ticket","int",0,1,16,0,getEntidadeId("ticket",$conn),0,"",0,0);
	$usuario	= criarAtributo($conn,$ticketinteractionID,"usuario","Usuário","int",0,0,16,0,getEntidadeId("usuario",$conn),0,"session.userid",1);

	// Cria Relacionamento
	criarRelacionamento($conn,6,$entidadeID,getEntidadeId("ticketinteraction",$conn),"Interação",$ticket);
	
	// Cria Aba
	criarAba($conn,$entidadeID,'Capa',array($tipo,$prioridade,$usuario,$responsavel,$titulo,$descricao,$previsaoentrega,$datacriacao,$anexo,$status));
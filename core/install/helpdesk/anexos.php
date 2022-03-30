<?php
	$entidadeNome = "ticketanexo";
	$entidadeDescricao = "Anexos";
	
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	$ticket 			= criarAtributo($conn,$entidadeID,"ticket","Ticket","int",0,0,16,0,getEntidadeId("ticket",$conn),0,"",1);
	$ticketinteraction 	= criarAtributo($conn,$entidadeID,"ticketinteraction","Ticket Interação","int",0,0,16,0,getEntidadeId("ticketinteraction",$conn),0,"",1);
	$arquivo 			= criarAtributo($conn,$entidadeID,"arquivo","Arquivo","varchar",200,1,3,0,0,0,"",1,0);
<?php
	$entidadeNome = "ticketseguidores";
	$entidadeDescricao = "Seguidores";
	
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	criarAtributo(
		$conn,
		$entidade = $entidadeID,
		$nome = "usuario",
		$descricao = "Usuário",
		$tipo = "int",
		$tamanho = 0,
		$nulo = 0,
		$tipohtml = 22,
		$exibirgradededados = 0,
		$chaveestrangeira = getEntidadeId("usuario",$conn),
		$dataretroativa = 0,
		$inicializacao = ""
	);

	criarAtributo(
		$conn,
		$entidade = $entidadeID,
		$nome = "ticket",
		$descricao = "Ticket",
		$tipo = "int",
		$tamanho = 0,
		$nulo = 0,
		$tipohtml = 22,
		$exibirgradededados = 0,
		$chaveestrangeira = getEntidadeId("ticket",$conn),
		$dataretroativa = 0,
		$inicializacao = ""
	);
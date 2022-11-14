<?php
	$entidadeNome = "website_redesocial_match";
	$entidadeDescricao = "Match";

	// 1º PASSO
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$descricao = $entidadeDescricao,
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
	
	criarAtributo(
		$conn,
		$entidadeID,
		$nome = "datahora",
		$descricao = "Data/Hora",
		$tipo = "datetime",
		$tamanho = 0,
		$nulo = 0,
		$tipohtml = 23,
		$exibirgradededados = 1,
		$chaveestrangeira = 0,
		$dataretroativa = 0,
		$inicializacao = ""
	);
	criarAtributo(
		$conn,
		$entidadeID,
		$nome = "usuario",
		$descricao = "Usuário",
		$tipo = "int",
		$tamanho = 0,
		$nulo = 1,
		$tipohtml = 16,
		$exibirgradededados = 0,
		$chaveestrangeira = 1,
		$dataretroativa = 0,
		$inicializacao = ""
	);
	criarAtributo(
		$conn,
		$entidadeID,
		$nome = "perfil",
		$descricao = "Perfil",
		$tipo = "int",
		$tamanho = 0,
		$nulo = 1,
		$tipohtml = 16,
		$exibirgradededados = 0,
		$chaveestrangeira = getEntidadeId("website_redesocial_usuario",$conn),
		$dataretroativa = 0,
		$inicializacao = ""
	);
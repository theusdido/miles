<?php
	$entidadeNome 		= "website_blog_tagsocorrencia";
	$entidadeDescricao 	= "Tags Ocorrência";
	
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
	
	// 2º PASSO	
	criarAtributo(
		$conn,
		$entidadeID,
		$nome = "tag",
		$descricao = "Tag",
		$tipo = "varchar",
		$tamanho = 200,
		$nulo = 0,
		$tipohtml = 3,
		$exibirgradededados = 1,
		$chaveestrangeira = 0,
		$dataretroativa = 0,
		$inicializacao = ""
	);
	criarAtributo(
		$conn,
		$entidadeID,
		$nome = "qtde",
		$descricao = "Quantidade",
		$tipo = "int",
		$tamanho = 0,
		$nulo = 1,
		$tipohtml = 16,
		$exibirgradededados = 0,
		$chaveestrangeira = 0,
		$dataretroativa = 0,
		$inicializacao = ""
	);
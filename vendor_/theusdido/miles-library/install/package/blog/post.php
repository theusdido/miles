<?php
	$entidadeNome 		= "post";
	$entidadeDescricao 	= "Post";
	
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
		$nome = "titulo",
		$descricao = "Título",
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
		$nome = "imagem",
		$descricao = "Imagem",
		$tipo = "text",
		$tamanho = 0,
		$nulo = 1,
		$tipohtml = 19,
		$exibirgradededados = 0,
		$chaveestrangeira = 0,
		$dataretroativa = 0,
		$inicializacao = ""
	);
	criarAtributo(
		$conn,
		$entidadeID,
		$nome = "texto",
		$descricao = "Texto",
		$tipo = "text",
		$tamanho = 0,
		$nulo = 1,
		$tipohtml = 21,
		$exibirgradededados = 0,
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
		$nome = "tags",
		$descricao = "Tags",
		$tipo = "varchar",
		$tamanho = 200,
		$nulo = 1,
		$tipohtml = 3,
		$exibirgradededados = 0,
		$chaveestrangeira = 0,
		$dataretroativa = 0,
		$inicializacao = ""
	);

	$colunista 			= criarAtributo($conn,$entidadeID,"colunista","Colunista","int",0,1,22,1,installDependencia("website_blog_colunista","website/blog/editorial/colunista"));
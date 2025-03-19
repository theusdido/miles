<?php
	$entidadeNome 		= "website_blog_formemail";
	$entidadeDescricao 	= "Fale Conosco";
	
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
		$nome = "nome",
		$descricao = "Nome",
		$tipo = "varchar",
		$tamanho = "200",
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
		$nome = "email",
		$descricao = "E-Mail",
		$tipo = "varchar",
		$tamanho = "200",
		$nulo = 0,
		$tipohtml = 12,
		$exibirgradededados = 1,
		$chaveestrangeira = 0,
		$dataretroativa = 0,
		$inicializacao = ""
	);
	criarAtributo(
		$conn,
		$entidadeID,
		$nome = "datahora",
		$descricao = "Data",
		$tipo = "datetime",
		$tamanho = 0,
		$nulo = 1,
		$tipohtml = 23,
		$exibirgradededados = 1,
		$chaveestrangeira = 0,
		$dataretroativa = 0,
		$inicializacao = ""
	);
	criarAtributo(
		$conn,
		$entidadeID,
		$nome = "mensagem",
		$descricao = "Mensagem",
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
		$nome = "resposta",
		$descricao = "Resposta",
		$tipo = "text",
		$tamanho = 0,
		$nulo = 0,
		$tipohtml = 21,
		$exibirgradededados = 0,
		$chaveestrangeira = 0,
		$dataretroativa = 0,
		$inicializacao = ""
	);	
	// 3º PASSO
	$menu_blog = addMenu($conn,'Blog','#','',0,0,'blog');
	
	// 4º PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',".$menu_blog.",3,'website-'.$entidadeNome,$entidadeID, 'cadastro');
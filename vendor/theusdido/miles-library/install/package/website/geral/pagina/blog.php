<?php
	$entidadeNome 		= "website_geral_blog";
	$entidadeDescricao 	= "Blog";
	
	// 1 PASSO
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
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// 2 PASSO
	criarAtributo($conn,$entidadeID,"titulo","Titulo"	,"text","",0,3	,1,0,0,"");
	criarAtributo($conn,$entidadeID,"arquivo","Arquivo"	,"text","",1,19	,0,0,0,"");
	criarAtributo($conn,$entidadeID,"texto"	,"Texto"	,"text","",1,21	,0,0,0,"");
	criarAtributo($conn,$entidadeID,"datahora"	,"Data e Hora"	,"datetime","",1,23,1);
	criarAtributo($conn,$entidadeID,"chamada","Chamada"	,"varchar",1000,1,3	,0,0,0,"");
	criarAtributo($conn,$entidadeID,"produto","Produto"	,"int",0,1,22);
	criarAtributo($conn,$entidadeID,"youtube"	,"Youtube ( LINK )"	,"varchar",500,1,3	,0,0,0,"");

	// 3 PASSO
	$menu_webiste = addMenu($conn,'WebSite','#','',0,0,'website');

	// 4 PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,7,'website-'.$entidadeNome,$entidadeID,'cadastro');
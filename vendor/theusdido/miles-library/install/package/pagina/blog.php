<?php
	$entidadeNome 		= "blog";
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
	criarAtributo($conn,$entidadeID,"titulo","Titulo"	,"varchar",250,0,3	,1,0,0,"");
	criarAtributo($conn,$entidadeID,"subtitulo","Sub Titulo"	,"varchar",500,1,3	,0,0,0,"");
	criarAtributo($conn,$entidadeID,"arquivo","Arquivo"	,"text","",1,19	,0,0,0,"");
	criarAtributo($conn,$entidadeID,"texto"	,"Texto"	,"text","",1,21	,0,0,0,"");
	criarAtributo($conn,$entidadeID,"datahora"	,"Data e Hora"	,"datetime","",1,23,0);
	criarAtributo($conn,$entidadeID,"data"	,"Data"	,"date","",1,11,1);
	criarAtributo($conn,$entidadeID,"hora"	,"Hora"	,"time","",1,28,1);	
	criarAtributo($conn,$entidadeID,"chamada","Chamada"	,"varchar",1000,1,3	,0,0,0,"");	
	criarAtributo($conn,$entidadeID,"youtube"	,"Youtube ( LINK )"	,"varchar",500,1,3	,0,0,0,"");
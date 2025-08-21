<?php
	$entidadeNome 		= "website_geral_slider";
	$entidadeDescricao 	= "Slider";
	
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
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);
	
	// 2º PASSO	
	criarAtributo($conn,$entidadeID,"imagem","Imagem"	,"text","",1,19	,0,0,0,"");
	criarAtributo($conn,$entidadeID,"html"	,"HTML"		,"text","",1,21	,0,0,0,"");
	criarAtributo($conn,$entidadeID,"exibir","Exibir"	,"tinyint","",1,7);
	criarAtributo($conn,$entidadeID,"ordem" ,"Ordem"	,"tinyint","",1,25);
	
	// 3º PASSO
	$menu_webiste = addMenu($conn,'WebSite','#','',0,0,'website');
	
	// 4º PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,6,'website-'.$entidadeNome,$entidadeID,'cadastro');

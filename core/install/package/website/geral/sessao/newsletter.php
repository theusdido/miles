<?php
	$entidadeNome 		= "website_geral_newsletter";
	$entidadeDescricao 	= "Newsletter";
	
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
	criarAtributo($conn,$entidadeID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$entidadeID,"email","E-Mail","text","",1,12,1,0,0,"");
	
	// 3º PASSO
	$menu_webiste = addMenu($conn,'WebSite','#','',0,0,'website');
	
	// 4º PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'website-'.$entidadeNome,$entidadeID,'cadastro');
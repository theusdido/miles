<?php
	$entidadeNome 		= "website_geral_menutopo";
	$entidadeDescricao 	= "Menu ( Topo )";
	
	// 1º PASSO
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
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
	criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$entidadeID,"link","Link","text","",1,3,0,0,0,"");
	criarAtributo($conn,$entidadeID,"target","Target","varchar","15",1,3,0,0,0,"");
	criarAtributo($conn,$entidadeID,"pai","pai","int","",1,4,1,$entidadeID,0,"");
	criarAtributo($conn,$entidadeID,"ordem","Ordem","int","",1,3,0,0,0,"");
	criarAtributo($conn,$entidadeID,"fixo","Fixo","varchar","35",1,3,0,0,0,"");
	
	// 3º PASSO
	$menu_webiste = addMenu($conn,'WebSite','#','',0,0,'website');
	
	// 4º PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,3,'website-'.$entidadeNome,$entidadeID,'cadastro');

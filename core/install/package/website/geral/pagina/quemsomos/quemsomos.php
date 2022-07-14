<?php
	$entidadeNome 		= "website_geral_quemsomos";
	$entidadeDescricao 	= "Quem Somos";

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
		$registrounico = 1
	);

	$texto 	= criarAtributo($conn,$entidadeID,"texto"	,"Texto"	,"text",0,1,21	,0,0,0,"");
	$imagem	= criarAtributo($conn,$entidadeID,"imagem","Imagem"	,"text",0,1,19,1);
	
	$menu_webiste = addMenu($conn,'WebSite','#','',0,0,'website');

	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,5,'website-'.$entidadeNome,$entidadeID,'cadastro');

	criarAba($conn,$entidadeID,"Capa", array($texto,$imagem));	

<?php
	$entidadeNome 		= "website_geral_googlemaps";
	$entidadeDescricao 	= "Google Maps";

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

	// 2 PASSO
	criarAtributo($conn,$entidadeID,"iframe"	,"IFrame"	,"text","",1,21	,0,0,0,"");

	// 3 PASSO
	$menu_webiste = addMenu($conn,'WebSite','#','',0,0,'website');

	// 4 PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,5,'website-'.$entidadeNome,$entidadeID,'cadastro');
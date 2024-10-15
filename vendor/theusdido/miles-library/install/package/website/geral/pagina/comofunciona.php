<?php
	$entidadeNome 		= "website_geral_comofunciona";
	$entidadeDescricao 	= "Como funciona ?";
	
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
    criarAtributo($conn,$entidadeID,"ordem","Ordem"	,"INT",0,1,25	,1);
	criarAtributo($conn,$entidadeID,"titulo","Titulo"	,"text","",0,3	,1);
	criarAtributo($conn,$entidadeID,"texto"	,"Texto"	,"text","",1,21	,0);
    criarAtributo($conn,$entidadeID,"imagem","Imagem"	,"text","",1,19	,0);

	// 3 PASSO
	$menu_webiste = addMenu($conn,'WebSite','#','',0,0,'website');

	// 4 PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,7,'website-'.$entidadeNome,$entidadeID,'cadastro');
<?php
	$entidadeNome 		= "website_geral_redessociais";
	$entidadeDescricao 	= "Redes Sociais";

	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$descricao = $entidadeDescricao,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// 2 PASSO
	$redesocial = criarAtributo($conn,$entidadeID,"redesocial"	,"Rede Social"	,"int",0,0,4,1,installDependencia('website_geral_redesocial','package/website/geral/sessao/redesocial'),0,"");
    $link 		= criarAtributo($conn,$entidadeID,"link"	,"Link"	,"varchar",500,0,3,0,0,0,"");

	Entity::setDescriptionField($conn,$entidadeID,$link);

	// 3 PASSO
	$menu_webiste = addMenu($conn,'WebSite','#','',0,0,'website');

	// 4 PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,5,'website-'.$entidadeNome,$entidadeID,'cadastro');
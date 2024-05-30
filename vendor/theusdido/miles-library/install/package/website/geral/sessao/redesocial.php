<?php
	$entidadeNome 		= "website_geral_redesocial";
	$entidadeDescricao 	= "Rede Social";

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

	$descricao 	= criarAtributo($conn,$entidadeID,"descricao"	,"Descrição"	,"varchar",50,0,3,1,0,0,"");
    $icone 		= criarAtributo($conn,$entidadeID,"icone"	,"Ícone"	,"varchar",50,1,3,0,0,0,"");

	Entity::setDescriptionField($conn,$entidadeID,$descricao);

	$menu_webiste = addMenu($conn,'WebSite','#','',0,0,'website');

	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,5,'website-'.$entidadeNome,$entidadeID,'cadastro');
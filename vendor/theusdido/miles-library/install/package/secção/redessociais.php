<?php
	$entidadeNome 		= "redessociais";
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
    $link 		= criarAtributo($conn,$entidadeID,"link"	,"Link"	,"varchar",500,0,3,1,0,0,"");

	Entity::setDescriptionField($conn,$entidadeID,$link);
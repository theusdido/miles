<?php
	// Setando variáveis
    $entidadeNome 		= "categoria";
	$entidadeDescricao 	= "Categoria";

	// Categoria
    $entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
    );

	$descricao 		= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");
	$imagem 		= criarAtributo($conn,$entidadeID,"imagem","Imagem","text",0,1,19,0,0,0,"");
	$icon 			= criarAtributo($conn,$entidadeID,"icon","Icon","text",0,1,19,0,0,0,"");
	$modalidade  	= criarAtributo($conn, $entidadeID,"modalidade","Modalidade","int",0,1,4,0,installDependencia("ecommerce_modalidade","package/website/ecommerce/geral/modalidade"));

	Entity::setDescriptionField($conn,$entidadeID,$descricao,false);
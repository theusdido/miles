<?php

	// Cidade
	$entidadeNome 		= "ecommerce_cidade";
	$entidadeDescricao 	= "Cidade ( Localidade )";

	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	$nome   = criarAtributo($conn,$entidadeID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	$bairro = criarAtributo($conn,$entidadeID,"bairromapeado","Bairro Mapeado","tinyint",0,1,7,0,0,0,"");
	$uf     = criarAtributo($conn,$entidadeID,"uf","UF","int",0,0,4,1,installDependencia('ecommerce_uf','package/website/ecommerce/endereco/uf'),0,"");
    Entity::setDescriptionField($conn,$entidadeID ,$nome,true);
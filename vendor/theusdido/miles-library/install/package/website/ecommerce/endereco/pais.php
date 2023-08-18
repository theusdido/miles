<?php
	$entidadeNome       = "ecommerce_pais";
    $entidadeDescricao  = "País";

	// País
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

	$nome   = criarAtributo($conn,$entidadeID,"nome","Nome","varchar",200,0,3,1,0,0,"");
	$sigla  = criarAtributo($conn,$entidadeID,"sigla","Sigla","varchar",5,0,3,1,0,0,"");
    Entity::setDescriptionField($conn,$entidadeID ,$nome,true);
<?php

    // Estado
	$entidadeNomeUF 		= "ecommerce_uf";
	$entidadeDescricaoUF 	= "Estado ( UF )";
	$ufID = criarEntidade(
		$conn,
		$entidadeNomeUF,
		$entidadeDescricaoUF,
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

	$uf_nome 	= criarAtributo($conn,$ufID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	$uf_sigla 	= criarAtributo($conn,$ufID,"sigla","Sigla","char","2",0,3,1,0,0,"");
	$uf_pais 	= criarAtributo($conn,$ufID,"pais","País","int",0,1,4,1,installDependencia('ecommerce_pais','package/website/ecommerce/endereco/pais'),0,"");
	Entity::setDescriptionField($conn,$ufID ,$uf_nome,true);

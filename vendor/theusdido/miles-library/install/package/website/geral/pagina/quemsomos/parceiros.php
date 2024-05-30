<?php
	$entidadeNome 		= "website_geral_quemsomosparceiros";
	$entidadeDescricao 	= "Parceiros ( Quem Somos )";

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
		$registrounico = 0
	);

    $quemsomos_entidade_id  = getEntidadeId("website_geral_quemsomos");
    $quemsomos              = criarAtributo($conn,$entidadeID,"quemsomos","Quem Somos","int",0,1,16,0,$quemsomos_entidade_id,0,"");
	$nome                   = criarAtributo($conn,$entidadeID,"nome"	,"Nome"	,"varchar",200,1,3,1);
    $logo                   = criarAtributo($conn,$entidadeID,"logo","Logo"	,"text",0,1,19,1);
	$site                   = criarAtributo($conn,$entidadeID,"site"	,"Site"	,"varchar",500,1,3,0);

    criarRelacionamento($conn,2,$quemsomos_entidade_id,$entidadeID,"Parceiros",$quemsomos);
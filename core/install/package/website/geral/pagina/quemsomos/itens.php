<?php
	$entidadeNome 		= "website_geral_quemsomositens";
	$entidadeDescricao 	= "Itens ( Quem Somos )";

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
	$titulo                 = criarAtributo($conn,$entidadeID,"titulo"	,"Título"	,"varchar",100,0,3,1);
    $texto                  = criarAtributo($conn,$entidadeID,"texto"	,"Texto"	,"varchar",1000,1,3,1);

    criarRelacionamento($conn,2,$quemsomos_entidade_id,$entidadeID,"Itens",$quemsomos);
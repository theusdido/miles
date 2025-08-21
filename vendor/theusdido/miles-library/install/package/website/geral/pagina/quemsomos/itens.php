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
	$itens_sessao			= installDependencia('website_geral_quemsomositenssessao','package/website/geral/pagina/quemsomos/itenssessao');
    $sessao              	= criarAtributo($conn,$entidadeID,"sessao","Sessão","int",0,1,4,0,$itens_sessao,0,"");
	$quemsomos              = criarAtributo($conn,$entidadeID,"quemsomos","Quem Somos","int",0,1,16,0,$quemsomos_entidade_id,0,"");
	$titulo                 = criarAtributo($conn,$entidadeID,"titulo"	,"Título"	,"varchar",100,0,3,1);
    $texto                  = criarAtributo($conn,$entidadeID,"texto"	,"Texto"	,"text",0,1,21,1);
	$imagem 				= criarAtributo($conn,$entidadeID,"imagem","Imagem","text",0,1,19,0,0,0,"");

    criarRelacionamento($conn,2,$quemsomos_entidade_id,$entidadeID,"Itens",$quemsomos);
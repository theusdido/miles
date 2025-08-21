<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_propaganda";
	$entidadeDescricao 	= "Propaganda";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas = 1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
    $datahorainicial 		= criarAtributo($conn,$entidadeID,"datahorainicial","Data/Hora Inicial","datetime",0,0,23,1,0,0,"");
    $datahorafinal 		    = criarAtributo($conn,$entidadeID,"datahorafinal","Data/Hora Final","datetime",0,0,23,1,0,0,"");
    $descricao              = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",150,1,3);
    $linkexterno            = criarAtributo($conn,$entidadeID,"linkexterno","Link Externo","varchar",300,1,3);
    $loja 			        = criarAtributo($conn,$entidadeID,"loja","Loja","int",0,1,22,0,installDependencia("ecommerce_loja","package/website/ecommerce/geral/loja"));
    $banner              	= criarAtributo($conn,$entidadeID,"banner","Banner","text",0,1,19);
	$is_encerrado			= criarAtributo($conn,$entidadeID,"is_encerrado","Encerrado ?","boolean",0,1,7);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
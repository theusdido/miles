<?php
	$entidadeNome 		= "website_geral_portifolio";
	$entidadeDescricao 	= "Portifólio";
	
	// 1 PASSO
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
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// 2 PASSO
	$titulo = criarAtributo($conn,$entidadeID,"titulo","Titulo"	,"text","",0,3	,1,0,0,"");
	$imagem = criarAtributo($conn,$entidadeID,"imagem","Imagem"	,"text","",1,19	,0,0,0,"");
	$texto  = criarAtributo($conn,$entidadeID,"texto"	,"Texto"	,"text","",1,21	,0,0,0,"");

	// Adiciando categoria em produto
	$produto_categoria = criarAtributo($conn,$entidadeID,"categoria","Categoria","int",0,1,4,1,installDependencia("ecommerce_categoria","package/website/ecommerce/mercadoria/categoria"),0,"");
    
    Entity::setDescriptionField($conn,$entidadeID,$titulo,true);

	// 3 PASSO
	$menu_webiste = addMenu($conn,'WebSite','#','',0,0,'website');

	// 4 PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,7,'website-'.$entidadeNome,$entidadeID,'cadastro');
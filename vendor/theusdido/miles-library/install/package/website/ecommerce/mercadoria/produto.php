<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_produto";
	$entidadeDescricao 	= "Produto";
	
	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=2,
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
	
	// Criando Atributos
	$produto_nome 						= criarAtributo($conn,$entidadeID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	$produto_preco 						= criarAtributo($conn,$entidadeID,"preco","Preço","float","",1,13,0,0,0,"");	
	$produto_exibirpreco 				= criarAtributo($conn,$entidadeID,"exibirpreco","Exibir Preço","tinyint",0,1,7,0,0,0,"");
	$produto_exibirhome 				= criarAtributo($conn,$entidadeID,"exibirhome","Exibir na Home","tinyint",0,1,7,0,0,0,"");
	$produto_inativo 					= criarAtributo($conn,$entidadeID,"inativo","Inativo","tinyint","",1,7,0,0,0,"");
	$produto_descricao 					= criarAtributo($conn,$entidadeID,"descricao","Descrição","text","",1,21,0,0,0,"");
	$produto_imagemPrincipal 			= criarAtributo($conn,$entidadeID,"imagemprincipal","Imagem ( Principal )","text","",1,19,0,0,0,"");
	$produto_imagemExtra1 				= criarAtributo($conn,$entidadeID,"imagemextra1","Imagem ( Extra )","text","",1,19,0,0,0,"");
	$produto_imagemExtra2 				= criarAtributo($conn,$entidadeID,"imagemextra2","Imagem ( Extra )","text","",1,19,0,0,0,"");
	$produto_imagemExtra3 				= criarAtributo($conn,$entidadeID,"imagemextra3","Imagem ( Extra )","text","",1,19,0,0,0,"");
	$produto_peso 						= criarAtributo($conn,$entidadeID,"peso","Peso","float",0,1,26,0,0,0,"");
	$produto_comprimento 				= criarAtributo($conn,$entidadeID,"comprimento","Comprimento","float",0,1,26,0,0,0,"");
	$produto_altura 					= criarAtributo($conn,$entidadeID,"altura","Altura","float",0,1,26,0,0,0,"");
	$produto_largura 					= criarAtributo($conn,$entidadeID,"largura","Largura","float",0,1,26,0,0,0,"");
	$produto_diametro 					= criarAtributo($conn,$entidadeID,"diametro","Diametro","float",0,1,26,0,0,0,"");
	$produto_referencia 				= criarAtributo($conn,$entidadeID,"referencia","Referência","varchar",50,1,3);
	$produto_destaque 					= criarAtributo($conn,$entidadeID,"destaque","Destaque","boolean",0,1,7,0,0,0,"");
	$produto_sku 						= criarAtributo($conn,$entidadeID,"sku","SKU","varchar",50,1,3);
	$produto_is_permite_trocar_pontos	= criarAtributo($conn,$entidadeID,"is_permite_trocar_pontos","Permite Trocar por Pontos","tinyint","",1,7,0,0,0,"");
	$produto_pontos 					= criarAtributo($conn,$entidadeID,"pontos","Pontos","float",0,1,26,0,0,0,"");

	Entity::setDescriptionField($conn,$entidadeID,$produto_nome,false);

	// Adiciando categoria em produto
	$produto_categoria = criarAtributo($conn,$entidadeID,"categoria","Categoria","int",0,1,4,1,installDependencia("ecommerce_categoria","package/website/ecommerce/mercadoria/categoria"),0,"");

	// Adicionando Subcategoria em produto
    $produto_subcategoria = criarAtributo($conn,$entidadeID,"subcategoria","Subcategoria","int",0,1,4,0,installDependencia("ecommerce_subcategoria","package/website/ecommerce/mercadoria/categoria"),0,"");

	// Adiciando tipo de produto
	$produto_tipo = criarAtributo($conn,$entidadeID,"tipo","Tipo","int",0,1,4,0,installDependencia("ecommerce_tipoproduto","package/website/ecommerce/mercadoria/tipoproduto"),0,"");

    // Adiciando Unidade de Medida
    $produto_unidademedida = criarAtributo($conn,$entidadeID,"unidademedida","Unidade de Medida","int",0,1,4,0,installDependencia("ecommerce_unidademedida","package/website/ecommerce/mercadoria/unidademedida"));
	
    // Adiciando Marca
    $produto_marca = criarAtributo($conn,$entidadeID,"marca","Marca","int",0,1,4,0,installDependencia("ecommerce_marca","package/website/ecommerce/mercadoria/marca"));	

	// Adiciando categoria em produto
	$produto_grupo = criarAtributo($conn,$entidadeID,"grupo","Grupo","int",0,1,4,0,installDependencia("ecommerce_grupoproduto","package/website/ecommerce/mercadoria/grupoproduto"),0,"");

	// Adiciando opções de cores no produto
	$produto_cores 		= criarAtributo($conn,$entidadeID,"cores","Cores","varchar",50,1,5,0,installDependencia("ecommerce_produtocor","package/website/ecommerce/mercadoria/produtocor"),0,"");

	// Adiciando opções de tamanho no produto
	$produto_tamanhos 	= criarAtributo($conn,$entidadeID,"tamanhos","Tamanhos","varchar",50,1,5,0,installDependencia("ecommerce_produtotamanho","package/website/ecommerce/mercadoria/produtotamanho"),0,"");

	// Criando Acesso
	$menu_webiste 		= addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,6,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');

	// Abas
	criarAba($conn,$entidadeID,'Capa',array($produto_nome,$produto_sku,$produto_referencia,$produto_categoria,$produto_subcategoria,$produto_tipo,$produto_unidademedida,$produto_marca,$produto_grupo,$produto_preco,$produto_exibirpreco,$produto_pontos,$produto_exibirhome,$produto_is_permite_trocar_pontos,$produto_inativo));
	criarAba($conn,$entidadeID,'Caracteristicas',array($produto_descricao,$produto_cores,$produto_tamanhos));
	criarAba($conn,$entidadeID,'Imagens',array($produto_imagemPrincipal,$produto_imagemExtra1,$produto_imagemExtra2,$produto_imagemExtra3));
	criarAba($conn,$entidadeID,'Embalagem',array($produto_peso,$produto_comprimento,$produto_altura,$produto_largura,$produto_diametro));
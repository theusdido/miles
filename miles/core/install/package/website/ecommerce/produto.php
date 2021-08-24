<?php
	// Setando variáveis
	$entidadeNome = "ecommerce_produto";
	$entidadeDescricao = "Produto";
	
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
	$produto_nome 				= criarAtributo($conn,$entidadeID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	$produto_preco 				= criarAtributo($conn,$entidadeID,"preco","Preço","float","",1,13,0,0,0,"");	
	$produto_exibirpreco 		= criarAtributo($conn,$entidadeID,"exibirpreco","Exibir Preço","tinyint","",0,7,0,0,0,"");
	$produto_exibirhome 		= criarAtributo($conn,$entidadeID,"exibirhome","Exibir na Home","tinyint","",0,7,0,0,0,"");
	$produto_inativo 			= criarAtributo($conn,$entidadeID,"inativo","Inativo","tinyint","",0,7,0,0,0,"");
	$produto_descricao 			= criarAtributo($conn,$entidadeID,"descricao","Descrição","text","",1,21,0,0,0,"");
	$produto_imagemPrincipal 	= criarAtributo($conn,$entidadeID,"imagemprincipal","Imagem ( Principal )","text","",1,19,0,0,0,"");
	$produto_imagemExtra1 		= criarAtributo($conn,$entidadeID,"imagemextra1","Imagem ( Extra )","text","",1,19,0,0,0,"");
	$produto_imagemExtra2 		= criarAtributo($conn,$entidadeID,"imagemextra2","Imagem ( Extra )","text","",1,19,0,0,0,"");
	$produto_imagemExtra3 		= criarAtributo($conn,$entidadeID,"imagemextra3","Imagem ( Extra )","text","",1,19,0,0,0,"");
	$produto_peso 				= criarAtributo($conn,$entidadeID,"peso","Peso","float",0,1,26,0,0,0,"");
	$produto_comprimento 		= criarAtributo($conn,$entidadeID,"comprimento","Comprimento","float",0,1,26,0,0,0,"");
	$produto_altura 			= criarAtributo($conn,$entidadeID,"altura","Altura","float",0,1,26,0,0,0,"");
	$produto_largura 			= criarAtributo($conn,$entidadeID,"largura","Largura","float",0,1,26,0,0,0,"");
	$produto_diametro 			= criarAtributo($conn,$entidadeID,"diametro","Diametro","float",0,1,26,0,0,0,"");
	$produto_referencia 		= criarAtributo($conn,$entidadeID,"referencia","Referência","varchar","50",0,3);
	$produto_destaque 			= criarAtributo($conn,$entidadeID,"destaque","Destaque","boolean","",0,7,0,0,0,"");

	// Adiciando categoria em produto
	$produto_categoria = criarAtributo($conn,$entidadeID,"categoria","Categoria","int",0,1,4,1,installDependencia($conn,"ecommerce_categoria","package/website/"),0,"");

	// Adicionando Subcategoria em produto
    $produto_subcategoria = criarAtributo($conn,$entidadeID,"subcategoria","Subcategoria","int",0,1,4,0,installDependencia($conn,"ecommerce_subcategoria","package/website/"),0,"");

	// Adiciando tipo de produto
	$produto_tipo = criarAtributo($conn,$entidadeID,"tipo","Tipo","int",0,1,4,0,installDependencia($conn,"ecommerce_tipoproduto","package/website/"),0,"");

    // Adiciando Unidade de Medida
    $produto_unidademedida = criarAtributo($conn,$entidadeID,"unidademedida","Unidade de Medida","int","",1,4,0,installDependencia($conn,"ecommerce_unidademedida","package/website/"));
	
    // Adiciando Marca
    $produto_marca = criarAtributo($conn,$entidadeID,"marca","Marca","int",1,1,4,0,installDependencia($conn,"ecommerce_marca","package/website/"));	

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','','','','ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".PREFIXO.$entidadeNome.".html",'',$menu_webiste,6,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');

	// Abas
	criarAba($conn,$entidadeID,'Capa',array($produto_nome,$produto_referencia,$produto_categoria,$produto_subcategoria,$produto_tipo,$produto_marca,$produto_preco,$produto_exibirpreco,$produto_exibirhome,$produto_inativo));
	criarAba($conn,$entidadeID,'Caracteristicas',array($produto_descricao));
	criarAba($conn,$entidadeID,'Imagens',array($produto_imagemPrincipal,$produto_imagemExtra1,$produto_imagemExtra2,$produto_imagemExtra3));
	criarAba($conn,$entidadeID,'Embalagem',array($produto_peso,$produto_comprimento,$produto_altura,$produto_largura,$produto_diametro));
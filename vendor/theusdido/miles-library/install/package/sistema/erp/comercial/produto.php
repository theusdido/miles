<?php
	// Setando variáveis
	$entidadeNome = "erp_comercial_produto";
	$entidadeDescricao = "Produto";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
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
	
	// Criando Atributos
	$nome = criarAtributo($conn,$entidadeID,"nome","Nome","varchar","200",1,3,1,0,0,"");
	$descricao = criarAtributo($conn,$entidadeID,"descricao","Descrição","text",0,1,21,0,0,0,"",1,0);
	$referencia = criarAtributo($conn,$entidadeID,"referencia","Referencia","varchar","30",1,3,0,0,0,"",1,0);
	$categoria = criarAtributo($conn,$entidadeID,"categoria","Categoria","int",0,1,4,0,installDependencia($conn,"erp_comercial_categoriaproduto"),0,"",1,0);
	$marca = criarAtributo($conn,$entidadeID,"marca","Marca","int",0,1,4,22,installDependencia($conn,"erp_geral_marca"),0,'',1,0);
	$unidademedida = criarAtributo($conn,$entidadeID,"unidademedida","Unidade de Medida","int",0,1,22,0,installDependencia($conn,"erp_geral_unidademedida"));	
	$peso = criarAtributo($conn,$entidadeID,"peso","Peso","float",0,1,26);
	$altura = criarAtributo($conn,$entidadeID,"altura","Altura","float",0,1,26);
	$largura = criarAtributo($conn,$entidadeID,"largura","Largura","float",0,1,26);
	$comprimento = criarAtributo($conn,$entidadeID,"comprimento","Comprimento","float",0,1,26);	
	$precounitario = criarAtributo($conn,$entidadeID,"precounitario","Preço Unitário","float",0,0,13,1,0);
	$precocusto = criarAtributo($conn,$entidadeID,"precocusto","Preço de Custo","float",0,0,13,1,0);
	$precovenda = criarAtributo($conn,$entidadeID,"precovenda","Preço Venda","float",0,0,13,1,0);


	// Criando Acesso
	$menu_webiste = addMenu($conn,'Comercial','#','',0,0,'comercial');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'comercial-' . $entidadeNome,$entidadeID, 'cadastro');

	// Relacionamentos
	criarRelacionamento($conn,5,$entidadeID,installDependencia($conn,"erp_geral_foto"),"Fotos",0);
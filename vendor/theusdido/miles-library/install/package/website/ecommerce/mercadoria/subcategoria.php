<?php
    // Setando variáveis
    $entidadeNome       = "ecommerce_subcategoria";
    $entidadeDescricao  = "Subcategoria";

    // Categoria
    $entidadeID = criarEntidade(
        $conn,
        $entidadeNome,
        $entidadeDescricao,
        $ncolunas = 1,
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

    $descricao  = criarAtributo($conn, $entidadeID, "descricao", "Descrição", "varchar", 200, 0, 3, 1, 0, 0, "");
    $categoria  = criarAtributo($conn, $entidadeID,"categoria","Categoria","int",0,0,4,1,installDependencia("ecommerce_categoria","package/website/ecommerce/mercadoria/categoria"));
    $imagem 	= criarAtributo($conn, $entidadeID,"imagem","Imagem","text",0,1,19,0,0,0,"");    

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
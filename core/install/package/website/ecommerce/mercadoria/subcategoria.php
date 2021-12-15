<?php
    // Setando variсveis
    $entidadeNome = "ecommerce_subcategoria";
    $entidadeDescricao = "Subcategoria";

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

    $descricao = criarAtributo($conn, $entidadeID, "descricao", "Descriчуo", "varchar", 200, 0, 3, 1, 0, 0, "");
    $categoria = criarAtributo($conn, $entidadeID,"categoria","Categoria","int",0,0,4,1,installDependencia($conn,"ecommerce_categoria","package/website/ecommerce/mercadoria/categoria"));

    // Criando Acesso
    $menu_webiste = addMenu($conn, 'E-Commerce', '#', '', '', '', 'ecommerce');

    // Adicionando Menu
    addMenu($conn, $entidadeDescricao, "files/cadastro/" . $entidadeID . "/" . PREFIXO . $entidadeNome . ".html", '', $menu_webiste, 0, 'ecommerce-' . $entidadeNome, $entidadeID, 'cadastro');
<?php
    // Setando variáveis
    $entidadeNome       = "geral_mes";
    $entidadeDescricao  = "Mês";

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

    $nome = criarAtributo($conn, $entidadeID, "nome", "Nome", "varchar", 15, 0, 3);

    // Criando Acesso
    $menu_webiste = addMenu($conn, 'Geral', '#', '', 0, 0, 'geral');

    // Adicionando Menu
    addMenu($conn, $entidadeDescricao, "files/cadastro/" . $entidadeID . "/" . PREFIXO . $entidadeNome . ".html", '', $menu_webiste, 0, 'geral-' . $entidadeNome, $entidadeID, 'cadastro');
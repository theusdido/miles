<?php
    // Setando variáveis
    $entidadeNome = "diasemana";
    $entidadeDescricao = "Dia Semana";

    // Categoria
    $entidadeID = criarEntidade(
        $conn,
        $entidadeNome,
        $entidadeDescricao,
        $ncolunas = 3,
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

    $dia = criarAtributo($conn, $entidadeID, "nome", "Nome", "varchar", 15, 1, 3);

    // Criando Acesso
    $menu_webiste = addMenu($conn, 'Geral', '#', '', '', '', 'geral');

    // Adicionando Menu
    addMenu($conn, $entidadeDescricao, "files/cadastro/" . $entidadeID . "/" . PREFIXO . $entidadeNome . ".html", '', $menu_webiste, 0, 'geral-' . $entidadeNome, $entidadeID, 'cadastro');
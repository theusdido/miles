<?php
    // Setando variáveis
    $entidadeNome       = "ano";
    $entidadeDescricao  = "Ano";

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

    $descricao      = criarAtributo($conn, $entidadeID, "descricao", "Descrição", "varchar", 50, 0, 3);
    $ano_inicial    = criarAtributo($conn, $entidadeID, "ano_inicial", "Ano Inicial", "tinyint", 0, 1, 25, 1);
    $ano_final      = criarAtributo($conn, $entidadeID, "ano_final", "Ano Final", "tinyint", 0, 1, 25, 1);

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$descricao,true);

    // Criando Acesso
    $menu_webiste = addMenu($conn, 'Geral', '#', '', 0, 0, 'geral');

    // Adicionando Menu
    addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,1,'geral-' . $entidadeNome,$entidadeID,'cadastro');
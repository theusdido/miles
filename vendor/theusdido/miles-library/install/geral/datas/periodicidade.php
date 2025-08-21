<?php
    // Setando variáveis
    $entidadeNome = "periodicidade";
    $entidadeDescricao = "Periodicidade";

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

    $descricao = criarAtributo($conn, $entidadeID, "descricao", "Descrição", "varchar", 50, 1, 3);

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$descricao,true);

    // Criando Acesso
    $menu_webiste = addMenu($conn, 'Geral', '#', '', 0, 0, 'geral');

    // Adicionando Menu
    addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,1,'geral-' . $entidadeNome,$entidadeID,'cadastro');
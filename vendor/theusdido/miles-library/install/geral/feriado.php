<?php
    // Setando variáveis
    $entidadeNome = "geral_feriado";
    $entidadeDescricao = "Feriado";

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
        $registrounico = 0,
		1,
		true
    );

    $dia 			= criarAtributo($conn, $entidadeID,"dia","Dia","int",0,0,25,1);
	$mes		 	= criarAtributo($conn, $entidadeID,"mes","Mês","int",0,0,4,1,installDependencia($conn,"mes","geral/"));
	$nome			= criarAtributo($conn, $entidadeID,"nome","Nome","varchar",50,0,3);

    // Criando Acesso
    $menu_webiste = addMenu($conn, 'E-Commerce', '#', '', '', '', 'ecommerce');

    // Adicionando Menu
    addMenu($conn, $entidadeDescricao, "files/cadastro/" . $entidadeID . "/" . PREFIXO . $entidadeNome . ".html", '', $menu_webiste, 0, 'ecommerce-' . $entidadeNome, $entidadeID, 'cadastro');
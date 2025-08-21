<?php

    // Setando variáveis
    $entidadeNome       = "feriado";
    $entidadeDescricao  = "Feriado";

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
	$mes		 	= criarAtributo($conn, $entidadeID,"mes","Mês","int",0,0,4,1,Entity::install("geral_datas_mes"));
	$nome			= criarAtributo($conn, $entidadeID,"nome","Nome","varchar",50,0,3);
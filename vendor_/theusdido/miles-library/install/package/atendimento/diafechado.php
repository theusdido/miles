<?php
    // Setando variáveis
    $entidadeNome       = "diafechado";
    $entidadeDescricao  = "Dia Fechado";

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
	
    $data 			= criarAtributo($conn, $entidadeID,"data","Data","date",0,0,11,1);
	$motivo			= criarAtributo($conn, $entidadeID,"motivo","Motivo","text",0,1,21,1);
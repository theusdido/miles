<?php

    // Limpa a tabela destino
    $conn->exec("TRUNCATE TABLE {$para_entidade};");

    // Seleciona os dados da tabela de origem
    $sql        = "SELECT * FROM {$de_entidade};";   
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
        // Adiciona registros na tabela de destino        
        $para                       = tdc::p($para_entidade);
        
$para->id= $de[''];
$para->nome` varchar(120) NOT NULL,
$para->descricao` varchar(120) NOT NULL,
$para->exibirmenuadministracao` tinyint(4) NOT NULL,
$para->exibircabecalho` tinyint(4) NOT NULL,
$para->pai= $de[''];
$para->ncolunas` tinyint(4) NOT NULL,
$para->campodescchave= $de[''];
$para->atributogeneralizacao= $de[''];
$para->exibirlegenda` smallint(6) NOT NULL,
$para->onloadjs` text,
$para->beforesavejs` text,
$para->tipopesquisa=$de[''];
$para->htmlpagefile` blob,
$para->registrounico` tinyint(4) DEFAULT NULL,
$para->carregarlibjavascript` tinyint(4) DEFAULT NULL,
$para->pacote= $de[''];
        
        $para->armazenar();
    }
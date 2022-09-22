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
$para->td_produto= $de[''];
$para->descricao=$de[''];
$para->valor` float NOT NULL,
$para->inativo` tinyint(1) DEFAULT NULL,
$para->peso` float NOT NULL,
        
        $para->armazenar();
    }
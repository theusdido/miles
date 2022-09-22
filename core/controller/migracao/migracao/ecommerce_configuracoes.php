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
$para->valorminimopedido` float DEFAULT NULL,
$para->permitecomprasemestoque` tinyint(1) DEFAULT NULL,
$para->usarvariacaoproduto` tinyint(1) DEFAULT NULL,
$para->ispaginacaoprodutohome` tinyint(1) DEFAULT NULL,
$para->qtdadeprodutohome= $de[''];
$para->valorminimoentrega` float DEFAULT NULL,
$para->prazominimoentrega= $de[''];
$para->qtdademaximaitenspedido= $de[''];
        
        $para->armazenar();
    }
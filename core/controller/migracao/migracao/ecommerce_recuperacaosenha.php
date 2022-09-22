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
$para->td_projeto=$de[''];
$para->td_empresa=$de[''];
$para->token=$de[''];
$para->td_cliente= $de[''];
$para->datahoraenvio` datetime DEFAULT NULL,
$para->datahoravalidade` datetime NOT NULL,
$para->email` varchar(200) DEFAULT NULL,
$para->ip` varchar(64) DEFAULT NULL,
$para->dadosnavegacao` text,
        
        $para->armazenar();
    }
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
$para->inativo` tinyint(1) DEFAULT NULL,
$para->email=$de[''];
$para->token=$de[''];
$para->producao` tinyint(4) DEFAULT NULL,
$para->notificacaourl` varchar(1000) DEFAULT NULL,
        
        $para->armazenar();
    }
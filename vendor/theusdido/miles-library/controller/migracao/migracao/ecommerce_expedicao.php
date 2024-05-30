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
$para->datahoraenvio` datetime NOT NULL,
$para->datahorarecebimento` datetime NOT NULL,
$para->entregue` tinyint(1) NOT NULL,
$para->valorfrete` float DEFAULT NULL,
$para->pesototal` float DEFAULT NULL,
$para->td_transportadora= $de[''];
$para->codigorastreamento=$de[''];
$para->td_pedido= $de[''];
        
        $para->armazenar();
    }
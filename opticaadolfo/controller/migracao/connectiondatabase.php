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
        
        $para->id                   = $de['id'];
        $para->host                 = $de['host'];
        $para->base                 = $de['base'];
        $para->port                 = $de['port'];
        $para->user                 = $de['user'];
        $para->password             = $de['password'];
        $para->type                 = $de['td_type'];
        $para->projeto              = $de['td_projeto'];
        $para->sgdb                 = $de['td_sgdb'];
        $para->armazenar();
    }
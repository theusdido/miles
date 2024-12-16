<?php

    // Limpa a tabela destino
    $conn->exec("TRUNCATE TABLE {$para_entidade};");
    
    // Seleciona os dados da tabela de origem
    $sql        = "SELECT * FROM {$de_entidade};";
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
        // Adiciona registros na tabela de destino
        $para               = tdc::p($para_entidade);
        $para->inativo      = 0;
        $para->entidade     = $de['td_entidade'];
        $para->descricao    = $de['descricao'];
        $para->atributos    = $de['atributos'];
        $para->armazenar();
    }
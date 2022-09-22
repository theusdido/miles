<?php

    // Limpa a tabela destino
    $conn->exec("TRUNCATE TABLE {$para_entidade};");

    // Seleciona os dados da tabela de origem
    $sql        = "SELECT * FROM {$de_entidade};";   
    $query      = $connProd->query($sql);
    while ($de  = $query->fetch())
    {
        // Adiciona registros na tabela de destino        
        $para                               = tdc::p($para_entidade);
        $para->projeto                      = $de['td_projeto'];
        $para->empresa                      = $de['td_empresa'];
        $para->inativo                      = $de['inativo'];
        $para->nome                         = $de['nome'];
        $para->cidade                       = $de['td_cidade'];

        $para->armazenar();
        
    }
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
$para->descricao=$de[''];
$para->td_entidade= $de[''];
$para->td_motivo= $de[''];
$para->exigirobrigatorio` tinyint(4) DEFAULT NULL,
$para->exibirtitulo` tinyint(4) DEFAULT NULL,
        
        $para->armazenar();
    }
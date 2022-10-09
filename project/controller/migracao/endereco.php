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
$para->td_bairro` varchar(35) NOT NULL,
$para->logradouro=$de[''];
$para->numero=$de[''];
$para->complemento=$de[''];
$para->cep` varchar(10) NOT NULL,
        
        $para->armazenar();
    }
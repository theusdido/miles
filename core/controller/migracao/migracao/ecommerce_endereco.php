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
$para->logradouro=$de[''];
$para->numero= $de[''];
$para->complemento` varchar(200) DEFAULT NULL,
$para->cep` varchar(10) NOT NULL,
$para->bairro` varchar(200) DEFAULT NULL,
$para->cidade` varchar(200) DEFAULT NULL,
$para->td_uf=$de[''];
$para->td_bairro= $de[''];
$para->td_cidade= $de[''];
        
        $para->armazenar();
    }
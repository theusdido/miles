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
$para->codigo= $de[''];
$para->nomefantasia` varchar(120) NOT NULL,
$para->razaosocial` varchar(200) DEFAULT NULL,
$para->cnpj= $de[''];
$para->inscricaoestadual` varchar(40) DEFAULT NULL,
$para->inscricaomunicipal` varchar(40) DEFAULT NULL,
$para->inativo` tinyint(1) DEFAULT NULL,
$para->telefone= $de[''];
$para->email` varchar(200) DEFAULT NULL,
      
        
        $para->armazenar();
    }
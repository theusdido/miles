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
$para->td_projeto` smallint(6) NOT NULL,
$para->td_empresa` smallint(6) NOT NULL,
$para->pai= $de[''];
$para->tipo= $de[''];
$para->filho= $de[''];
$para->td_atributo= $de[''];
$para->descricao=$de[''];
$para->controller` text,
$para->cardinalidade` char(2) DEFAULT NULL,

        $para->armazenar();
    }
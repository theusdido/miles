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
        
$para->id` int(11) NOT NULL AUTO_INCREMENT,
$para->inativo` tinyint(1) DEFAULT NULL,
$para->td_cliente= $de[''];
$para->data` date NOT NULL,
$para->hora` time NOT NULL,
$para->ip` varchar(25) NOT NULL,
$para->navegador` varchar(20) NOT NULL,
$para->sessao` varchar(32) DEFAULT NULL,
      
        
        $para->armazenar();
    }
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
$para->td_projeto= $de[''];
$para->td_empresa=$de[''];
$para->descricao` varchar(120) NOT NULL,
$para->link=$de[''];
$para->target` varchar(15) DEFAULT NULL,
$para->td_pai= $de[''];
$para->ordem` smallint(6) NOT NULL,
$para->fixo=$de[''];
$para->tipomenu` varchar(35) NOT NULL,
$para->td_entidade= $de[''];
        
        $para->armazenar();
    }